<?php

use App\Models\Inventory;
use App\Models\Supplier;
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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignIdFor(Inventory::class)->constrained(app(Inventory::class)->getTable())->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('price')->nullable();
            $table->string('unit')->nullable();
            $table->float('quantity')->nullable();
            $table->float('subtotal')->nullable();
            $table->foreignIdFor(Supplier::class)->constrained(app(Supplier::class)->getTable())->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('supplied_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
