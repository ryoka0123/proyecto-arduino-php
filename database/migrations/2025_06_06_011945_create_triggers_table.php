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
        Schema::create('triggers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arduino_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->string('contexto');
            $table->timestamps();
        });
    }
};
