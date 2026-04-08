<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE token_transactions
            MODIFY COLUMN type ENUM('credit', 'debit', 'subscription_reset') NOT NULL
        ");
    }

    public function down(): void
    {
        // Önce mevcut subscription_reset kayıtları varsa rollback patlar,
        // bu yüzden önce temizle (dev ortamında güvenli)
        DB::statement("
            UPDATE token_transactions
            SET type = 'debit'
            WHERE type = 'subscription_reset'
        ");

        DB::statement("
            ALTER TABLE token_transactions
            MODIFY COLUMN type ENUM('credit', 'debit') NOT NULL
        ");
    }
};
