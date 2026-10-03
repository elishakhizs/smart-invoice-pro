<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_sequences', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')
                ->after('id');

            $table->unique(['company_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_sequences', function (Blueprint $table) {
            $table->dropUnique([
                'invoice_sequences_company_id_year_unique',
            ]);

            $table->dropColumn('company_id');
        });
    }
};