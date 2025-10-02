<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('city');
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['product_id','city']); // ensure one stock per product+city
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
