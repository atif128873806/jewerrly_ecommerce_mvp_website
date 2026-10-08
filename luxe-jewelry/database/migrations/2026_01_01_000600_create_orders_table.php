<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_number')->unique();

            // Customer
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();

            // Shipping address
            $table->string('address');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('Spain');
            $table->text('notes')->nullable();

            // Payment
            $table->string('payment_method')->default('cod');
            $table->string('payment_status')->default('pending'); // pending | paid
            $table->string('status')->default('confirmed');       // confirmed | shipped | cancelled

            // Totals (cents)
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('shipping');
            $table->unsignedInteger('total');

            $table->timestamps();

            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
