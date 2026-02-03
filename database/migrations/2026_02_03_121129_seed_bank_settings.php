// php artisan make:migration seed_bank_settings --table=site_settings

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Eğer yoksa varsayılan banka bilgileri ekle
        $defaults = [
            ['key' => 'bank_account_name', 'value' => 'Şirket Adı'],
            ['key' => 'bank_iban',         'value' => 'TR00 0000 0000 0000 0000 0000 00'],
        ];

        foreach ($defaults as $item) {
            DB::table('site_settings')
                ->where('key', $item['key'])
                ->when(
                    !DB::table('site_settings')->where('key', $item['key'])->exists(),
                    fn($q) => DB::table('site_settings')->insert([
                        'key'        => $item['key'],
                        'value'      => $item['value'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')
            ->whereIn('key', ['bank_account_name', 'bank_iban'])
            ->delete();
    }
};
