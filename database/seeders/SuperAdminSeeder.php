<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Süper Admin hesabı oluştur
     */
    public function run(): void
    {
        // Süper Admin hesabı var mı kontrol et
        $superAdmin = User::where('email', 'admin@aytunfilmai.com')->first();

        if ($superAdmin) {
            $this->command->info('Süper Admin zaten mevcut!');
            return;
        }

        // Süper Admin oluştur
        User::create([
            'name' => 'Süper Admin',
            'email' => 'admin@aytunfilmai.com',
            'phone' => '05000000000',
            'password' => Hash::make('admin123'), // ÖNEMLİ: Production'da değiştirin!
            'is_admin' => true,
            'token_balance' => 0,
            'email_verified_at' => now(),
        ]);

        $this->command->info('✅ Süper Admin başarıyla oluşturuldu!');
        $this->command->info('📧 Email: admin@aytunfilmai.com');
        $this->command->info('🔑 Şifre: admin123');
        $this->command->warn('⚠️  Production ortamında mutlaka şifreyi değiştirin!');
    }
}
