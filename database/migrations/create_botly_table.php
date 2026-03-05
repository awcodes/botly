<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('botly', static function (Blueprint $table) {
            $table->id();

            $table->longText('rules')->nullable();
            $table->longText('sitemaps')->nullable();
            $table->longText('ai_crawlers')->nullable();

            $table->timestamps();
        });
    }
};
