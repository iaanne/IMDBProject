<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('watchlists', function (Blueprint $table) {
        $table->id();
        // Use unsignedBigInteger to match the 'id' from users table
        $table->unsignedBigInteger('user_id'); 

        // Standard string for the movie ID
        $table->string('tconst');

        $table->timestamps();

        // Constraint to prevent duplicate watchlist entries
        $table->unique(['user_id', 'tconst']);

        // Optional: Add foreign key constraint if you want strict integrity
        // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
