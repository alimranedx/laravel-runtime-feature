<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_rules', function (Blueprint $bt) {
            $bt->id();
            $bt->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $bt->string('type');
            $bt->json('value');
            $bt->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_rules');
    }
};
