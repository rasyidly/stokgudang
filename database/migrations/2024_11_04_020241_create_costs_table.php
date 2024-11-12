<?php

use App\Models\Inventory;
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
        Schema::create('costs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignIdFor(Inventory::class)->constrained(app(Inventory::class)->getTable())->cascadeOnUpdate()->cascadeOnDelete();
            $table->float('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->timestamp('cost_at')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('costs');
    }
};
