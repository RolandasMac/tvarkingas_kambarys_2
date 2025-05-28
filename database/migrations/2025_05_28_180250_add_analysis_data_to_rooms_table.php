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
        Schema::table('rooms', function (Blueprint $table) {
            // JSON stulpelis, skirtas apibendrintai analizei
            // Galima naudoti json() stulpelio tipą MySQL 5.7+ arba PostgreSQL
            $table->json('analysis_summary')->nullable();

            // LONGTEXT stulpelis, skirtas visiems neapdorotiems API duomenims
            // longText() tinka, nes JSON gali būti didelis
            $table->longText('raw_analysis_data')->nullable();

            // Analizės data ir laikas
            $table->timestamp('analyzed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('analysis_summary');
            $table->dropColumn('raw_analysis_data');
            $table->dropColumn('analyzed_at');
        });
    }
};
