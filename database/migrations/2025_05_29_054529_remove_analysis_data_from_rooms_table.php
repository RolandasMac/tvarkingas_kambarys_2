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
            // Patikrinkite, ar stulpeliai egzistuoja prieš bandant juos pašalinti
            // Tai apsaugo nuo klaidų, jei stulpeliai jau būtų pašalinti rankiniu būdu
            if (Schema::hasColumn('rooms', 'analysis')) {
                $table->dropColumn('analysis');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * Šis metodas naudojamas, jei norėtumėte grąžinti pakeitimus
     * ir vėl pridėti stulpelius (pvz., paleidus `php artisan migrate:rollback`).
     * Turite nurodyti originalius stulpelių tipus.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->json('analysis')->nullable();
        });
    }
};
