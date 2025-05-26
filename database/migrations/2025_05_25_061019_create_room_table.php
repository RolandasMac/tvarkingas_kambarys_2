<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('time_of_day'); // pvz. 'rytą' arba 'vakarą'
            $table->text('comment')->nullable();
            $table->text('analysis')->nullable();
            $table->timestamps();
        });

    }
};
