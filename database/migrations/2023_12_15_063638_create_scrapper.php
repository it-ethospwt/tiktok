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
        Schema::create('scrappers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telp');
            $table->text('alamat');
            $table->string('item');
            $table->string('qty');
            $table->string('resi');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrapper');
    }
};
