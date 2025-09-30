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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('table_number'); // instead of user_id
        $table->json('items'); // store product ids & qty as JSON
        $table->decimal('total_price', 10, 2);
        $table->string('status')->default('pending'); // pending, paid, completed
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
