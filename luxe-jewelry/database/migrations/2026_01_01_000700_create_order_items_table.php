<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot of the product at purchase time (survives catalog changes)
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('unit_price'); // cents
            $table->unsignedInteger('qty');
            $table->unsignedInteger('line_total');  // cents

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
