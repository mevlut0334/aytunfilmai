<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaddleWebhookController extends Controller
{
    /**
     * Paddle'dan gelen tüm webhook'ları karşıla.
     */
    public function handle(Request $request): JsonResponse
    {
        // 1. İmza doğrula
        if (!$this->verifySignature($request)) {
            Log::warning('Paddle webhook: geçersiz imza', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload   = $request->json()->all();
        $eventType = $payload['event_type'] ?? null;

        Log::info('Paddle webhook alındı', ['event_type' => $eventType]);

        return match ($eventType) {
            'transaction.completed'   => $this->handleTransactionCompleted($payload),
            'subscription.canceled'   => $this->handleSubscriptionCanceled($payload),
            'subscription.expired'    => $this->handleSubscriptionExpired($payload),
            default                   => response()->json(['status' => 'ignored']),
        };
    }

    // -------------------------------------------------------------------------
    // İmza doğrulama
    // -------------------------------------------------------------------------

    private function verifySignature(Request $request): bool
    {
        $secret = config('cashier.webhook_secret');

        if (!$secret) {
            Log::error('Paddle webhook: PADDLE_WEBHOOK_SECRET tanımlı değil');
            return false;
        }

        $signatureHeader = $request->header('Paddle-Signature');

        if (!$signatureHeader) {
            Log::warning('Paddle webhook: Paddle-Signature header yok');
            return false;
        }

        // "ts=xxx;h1=yyy" parse et
        $parts = [];
        foreach (explode(';', $signatureHeader) as $part) {
            $segments = explode('=', $part, 2);
            if (count($segments) === 2) {
                $parts[$segments[0]] = $segments[1];
            }
        }

        if (!isset($parts['ts'], $parts['h1'])) {
            return false;
        }

        $signedPayload     = $parts['ts'] . ':' . $request->getContent();
        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($expectedSignature, $parts['h1']);
    }

    // -------------------------------------------------------------------------
    // transaction.completed → bakiyeyi sıfırla, yeni token yükle
    // -------------------------------------------------------------------------

    private function handleTransactionCompleted(array $payload): JsonResponse
    {
        $data           = $payload['data']         ?? [];
        $customData     = $data['custom_data']     ?? [];
        $transactionId  = $data['id']              ?? null;
        $subscriptionId = $data['subscription_id'] ?? null;

        $userId    = isset($customData['user_id'])    ? (int) $customData['user_id']    : null;
        $packageId = isset($customData['package_id']) ? (int) $customData['package_id'] : null;

        // Zorunlu alanlar kontrolü
        if (!$userId || !$packageId || !$transactionId) {
            Log::error('Paddle webhook: customData eksik', [
                'user_id'         => $userId,
                'package_id'      => $packageId,
                'transaction_id'  => $transactionId,
                'subscription_id' => $subscriptionId,
            ]);
            return response()->json(['error' => 'Missing custom data'], 400);
        }

        // Duplicate kontrolü — aynı transaction tekrar işlenmesin
        if (Order::where('transaction_id', $transactionId)->exists()) {
            Log::info('Paddle webhook: transaction zaten işlendi', [
                'transaction_id' => $transactionId,
            ]);
            return response()->json(['status' => 'already_processed']);
        }

        $user    = User::find($userId);
        $package = Package::where('id', $packageId)->where('is_active', true)->first();

        if (!$user) {
            Log::error('Paddle webhook: kullanıcı bulunamadı', ['user_id' => $userId]);
            return response()->json(['error' => 'User not found'], 400);
        }

        if (!$package) {
            Log::error('Paddle webhook: paket bulunamadı veya pasif', ['package_id' => $packageId]);
            return response()->json(['error' => 'Package not found'], 400);
        }

        // Ödeme tutarı (Paddle kuruş gönderir → 100'e böl)
        $totals      = $data['details']['totals'] ?? [];
        $totalAmount = isset($totals['total']) ? (int) $totals['total'] / 100 : 0;

        try {
            DB::beginTransaction();

            // Sipariş oluştur
            $order = Order::create([
                'user_id'         => $user->id,
                'total_amount'    => $totalAmount,
                'discount_amount' => 0,
                'final_amount'    => $totalAmount,
                'status'          => 'completed',
                'payment_date'    => now(),
                'transaction_id'  => $transactionId,
            ]);

            // Sipariş kalemi oluştur
            OrderItem::create([
                'order_id'   => $order->id,
                'package_id' => $package->id,
                'quantity'   => 1,
                'unit_price' => $totalAmount,
                'subtotal'   => $totalAmount,
            ]);

            $oldBalance = (float) $user->token_balance;

            // Abonelik yenilemesi: bakiyeyi sıfırla, paketteki miktarı yükle (Spotify mantığı)
            // İlk abonelikte de aynı davranıyoruz — sıfırdan başla
            $newBalance = $package->token_amount;
            $user->update(['token_balance' => $newBalance]);

            // Eğer önceki bakiye varsa sıfırlama kaydı ekle
            if ($oldBalance > 0) {
                TokenTransaction::create([
                    'user_id'       => $user->id,
                    'amount'        => $oldBalance,
                    'type'          => 'subscription_reset',
                    'description'   => 'Abonelik yenilendi — önceki bakiye sıfırlandı',
                    'order_id'      => $order->id,
                    'balance_after' => 0,
                ]);
            }

            // Yeni token yükleme kaydı
            TokenTransaction::create([
                'user_id'       => $user->id,
                'amount'        => $package->token_amount,
                'type'          => 'credit',
                'description'   => $package->name . ' aboneliği — ' . now()->format('F Y'),
                'order_id'      => $order->id,
                'balance_after' => $newBalance,
            ]);

            DB::commit();

            Log::info('Paddle webhook: abonelik token yüklendi ✓', [
                'user_id'         => $user->id,
                'package'         => $package->name,
                'old_balance'     => $oldBalance,
                'new_balance'     => $newBalance,
                'order_id'        => $order->id,
                'transaction_id'  => $transactionId,
                'subscription_id' => $subscriptionId,
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Paddle webhook: işlem hatası', [
                'error'          => $e->getMessage(),
                'transaction_id' => $transactionId,
            ]);

            // Paddle 500 alırsa 3 kez daha dener — bu kasıtlı
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // subscription.canceled → Spotify gibi: token'a dokunma, sadece logla.
    // Kullanıcı dönem sonuna kadar token'larını kullanabilir.
    // Dönem bitince Paddle subscription.expired atar → o zaman sıfırlarız.
    // -------------------------------------------------------------------------

    private function handleSubscriptionCanceled(array $payload): JsonResponse
    {
        $data           = $payload['data']         ?? [];
        $subscriptionId = $data['id']              ?? null;
        $customData     = $data['custom_data']     ?? [];
        $userId         = $customData['user_id']   ?? null;

        Log::info('Paddle webhook: abonelik iptal edildi (dönem sonuna kadar aktif)', [
            'subscription_id' => $subscriptionId,
            'user_id'         => $userId,
        ]);

        // Token'a dokunmuyoruz.
        // subscription.expired gelince token sıfırlanacak.

        return response()->json(['status' => 'canceled_noted']);
    }

    // -------------------------------------------------------------------------
    // subscription.expired → Dönem bitti, token bakiyesini sıfırla
    // -------------------------------------------------------------------------

    private function handleSubscriptionExpired(array $payload): JsonResponse
    {
        $data           = $payload['data']         ?? [];
        $subscriptionId = $data['id']              ?? null;
        $customData     = $data['custom_data']     ?? [];
        $userId         = isset($customData['user_id']) ? (int) $customData['user_id'] : null;

        if (!$userId) {
            Log::error('Paddle webhook: subscription.expired — user_id yok', [
                'subscription_id' => $subscriptionId,
            ]);
            return response()->json(['error' => 'Missing user_id'], 400);
        }

        $user = User::find($userId);

        if (!$user) {
            Log::error('Paddle webhook: subscription.expired — kullanıcı bulunamadı', [
                'user_id' => $userId,
            ]);
            return response()->json(['error' => 'User not found'], 400);
        }

        try {
            DB::beginTransaction();

            $oldBalance = (float) $user->token_balance;

            // Token bakiyesini sıfırla
            $user->update(['token_balance' => 0]);

            // Sıfırlama kaydı
            if ($oldBalance > 0) {
                TokenTransaction::create([
                    'user_id'       => $user->id,
                    'amount'        => $oldBalance,
                    'type'          => 'subscription_reset',
                    'description'   => 'Abonelik sona erdi — bakiye sıfırlandı',
                    'order_id'      => null,
                    'balance_after' => 0,
                ]);
            }

            DB::commit();

            Log::info('Paddle webhook: abonelik sona erdi, bakiye sıfırlandı', [
                'user_id'         => $user->id,
                'old_balance'     => $oldBalance,
                'subscription_id' => $subscriptionId,
            ]);

            return response()->json(['status' => 'expired_processed']);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Paddle webhook: subscription.expired işlem hatası', [
                'error'   => $e->getMessage(),
                'user_id' => $userId,
            ]);

            return response()->json(['error' => 'Processing failed'], 500);
        }
    }
}
