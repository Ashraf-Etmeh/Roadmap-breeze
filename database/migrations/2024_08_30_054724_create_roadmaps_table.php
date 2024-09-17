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
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('start_node_id')->constrained('nodes');//->onDelete('cascade');
            $table->foreignId('constructor_id')->constrained(); //I don't want it to be cascade on delete
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->text('prerequirements');
            $table->text('recommendations');
            $table->integer('rate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
