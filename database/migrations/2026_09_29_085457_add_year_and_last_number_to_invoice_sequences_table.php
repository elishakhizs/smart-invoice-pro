<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoice_sequences', function (Blueprint $table) {
            $table->unsignedInteger('year')->after('company_id');
            $table->unsignedBigInteger('last_number')->default(0)->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('invoice_sequences', function (Blueprint $table) {
            $table->dropColumn(['year', 'last_number']);
        });
    }
};