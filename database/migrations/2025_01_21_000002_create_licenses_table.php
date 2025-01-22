<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->uuid('license_key')->unique();
            $table->string('purchase_code')->unique();
            $table->string('buyer_username')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('domain')->nullable();
            $table->boolean('activated')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
