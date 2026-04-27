<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $bt) {
            $bt->id();
            $bt->string('key')->unique();
            $bt->boolean('enabled')->default(false);
            $bt->json('value')->nullable();
            $bt->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
