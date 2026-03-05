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
        Schema::create('night_closings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->decimal('opening_quantity', 12, 2);
            $table->decimal('closing_quantity', 12, 2);
            $table->decimal('consumed_quantity', 12, 2);
            $table->date('entry_date');
            $table->timestamps();

            $table->unique(['item_id', 'entry_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('night_closings');
    }
};
