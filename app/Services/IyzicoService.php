<?php

namespace App\Services;

use App\Services\Interfaces\IyzicoServiceInterface;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\ThreedsInitialize;
use Iyzipay\Model\ThreedsPayment;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Request\CreateThreedsPaymentRequest;

class IyzicoService implements IyzicoServiceInterface
{
    protected Options $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('iyzico.api_key'));
        $this->options->setSecretKey(config('iyzico.secret_key'));
        $this->options->setBaseUrl(config('iyzico.base_url'));
    }

    /**
     * 3D Secure ödeme başlat
     */
    public function initiate3DSecurePayment(array $orderData, array $cardData): array
    {
        try {
            $request = new CreatePaymentRequest();
            $request->setLocale(Locale::TR);
            $request->setConversationId($orderData['conversation_id']);
            $request->setPrice($orderData['price']);
            $request->setPaidPrice($orderData['paid_price']);
            $request->setCurrency(\Iyzipay\Model\Currency::TL);
            $request->setInstallment(1);
            $request->setBasketId($orderData['basket_id']);
            $request->setPaymentChannel(PaymentChannel::WEB);
            $request->setPaymentGroup(PaymentGroup::PRODUCT);
            $request->setCallbackUrl($orderData['callback_url']);

            // Kart bilgileri
            $paymentCard = new PaymentCard();
            $paymentCard->setCardHolderName($cardData['card_holder_name']);
            $paymentCard->setCardNumber($cardData['card_number']);
            $paymentCard->setExpireMonth($cardData['expire_month']);
            $paymentCard->setExpireYear($cardData['expire_year']);
            $paymentCard->setCvc($cardData['cvc']);
            $paymentCard->setRegisterCard(0);
            $request->setPaymentCard($paymentCard);

            // Alıcı bilgileri
            $buyer = new Buyer();
            $buyer->setId($orderData['buyer']['id']);
            $buyer->setName($orderData['buyer']['name']);
            $buyer->setSurname($orderData['buyer']['surname']);
            $buyer->setEmail($orderData['buyer']['email']);
            $buyer->setIdentityNumber($orderData['buyer']['identity_number'] ?? '11111111111');
            $buyer->setRegistrationAddress($orderData['buyer']['address'] ?? 'Adres Bilgisi');
            $buyer->setCity($orderData['buyer']['city'] ?? 'İstanbul');
            $buyer->setCountry($orderData['buyer']['country'] ?? 'Turkey');
            $buyer->setZipCode($orderData['buyer']['zip_code'] ?? '34000');
            $buyer->setIp($orderData['buyer']['ip']);
            $request->setBuyer($buyer);

            // Adres bilgileri
            $shippingAddress = new Address();
            $shippingAddress->setContactName($orderData['buyer']['name'] . ' ' . $orderData['buyer']['surname']);
            $shippingAddress->setCity($orderData['buyer']['city'] ?? 'İstanbul');
            $shippingAddress->setCountry($orderData['buyer']['country'] ?? 'Turkey');
            $shippingAddress->setAddress($orderData['buyer']['address'] ?? 'Adres Bilgisi');
            $shippingAddress->setZipCode($orderData['buyer']['zip_code'] ?? '34000');
            $request->setShippingAddress($shippingAddress);

            $billingAddress = new Address();
            $billingAddress->setContactName($orderData['buyer']['name'] . ' ' . $orderData['buyer']['surname']);
            $billingAddress->setCity($orderData['buyer']['city'] ?? 'İstanbul');
            $billingAddress->setCountry($orderData['buyer']['country'] ?? 'Turkey');
            $billingAddress->setAddress($orderData['buyer']['address'] ?? 'Adres Bilgisi');
            $billingAddress->setZipCode($orderData['buyer']['zip_code'] ?? '34000');
            $request->setBillingAddress($billingAddress);

            // Sepet öğeleri
            $basketItems = [];
            foreach ($orderData['items'] as $item) {
                $basketItem = new BasketItem();
                $basketItem->setId($item['id']);
                $basketItem->setName($item['name']);
                $basketItem->setCategory1($item['category']);
                $basketItem->setItemType(BasketItemType::VIRTUAL);
                $basketItem->setPrice($item['price']);
                $basketItems[] = $basketItem;
            }
            $request->setBasketItems($basketItems);

            // 3D Secure başlat
            $threedsInitialize = ThreedsInitialize::create($request, $this->options);

            if ($threedsInitialize->getStatus() === 'success') {
                \Log::info('İyzico 3D Secure başlatıldı: ' . $orderData['conversation_id']);

                return [
                    'status' => 'success',
                    'html_content' => $threedsInitialize->getHtmlContent(),
                    'payment_id' => $threedsInitialize->getPaymentId(),
                    'conversation_id' => $threedsInitialize->getConversationId(),
                ];
            }

            // Hata durumunda detaylı log
            \Log::error('İyzico 3D Secure başlatma hatası');
            \Log::error('Conversation ID: ' . $orderData['conversation_id']);
            \Log::error('Error Message: ' . $threedsInitialize->getErrorMessage());
            \Log::error('Error Code: ' . $threedsInitialize->getErrorCode());
            \Log::error('Raw Response: ' . $threedsInitialize->getRawResult());

            return [
                'status' => 'error',
                'error_message' => $threedsInitialize->getErrorMessage(),
                'error_code' => $threedsInitialize->getErrorCode(),
            ];

        } catch (\Exception $e) {
            \Log::error('İyzico 3D Secure exception: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return [
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ];
        }
    }

    /**
     * 3D Secure callback işle
     */
    public function handle3DSecureCallback(array $callbackData): array
    {
        try {
            \Log::info('İyzico callback alındı', $callbackData);

            $request = new CreateThreedsPaymentRequest();
            $request->setLocale(Locale::TR);
            $request->setConversationId($callbackData['conversationId'] ?? '');
            $request->setPaymentId($callbackData['paymentId'] ?? '');
            $request->setConversationData($callbackData['conversationData'] ?? '');

            $threedsPayment = ThreedsPayment::create($request, $this->options);

            if ($threedsPayment->getStatus() === 'success') {
                \Log::info('İyzico callback başarılı: ' . ($callbackData['conversationId'] ?? 'N/A'));

                return [
                    'status' => 'success',
                    'payment_id' => $threedsPayment->getPaymentId(),
                    'conversation_id' => $threedsPayment->getConversationId(),
                    'price' => $threedsPayment->getPrice(),
                    'paid_price' => $threedsPayment->getPaidPrice(),
                ];
            }

            // Callback hatası
            \Log::error('İyzico callback hatası');
            \Log::error('Error Message: ' . $threedsPayment->getErrorMessage());
            \Log::error('Error Code: ' . $threedsPayment->getErrorCode());
            \Log::error('Raw Response: ' . $threedsPayment->getRawResult());

            return [
                'status' => 'error',
                'error_message' => $threedsPayment->getErrorMessage(),
                'error_code' => $threedsPayment->getErrorCode(),
            ];

        } catch (\Exception $e) {
            \Log::error('İyzico callback exception: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return [
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ];
        }
    }
}
