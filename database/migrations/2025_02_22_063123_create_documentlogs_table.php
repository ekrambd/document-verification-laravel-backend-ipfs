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
        Schema::create('documentlogs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('document_id');
            $table->string('ipfs_hash');
            $table->string('transaction_hash')->unique();
            $table->date('date');
            $table->string('time');
            $table->enum('action', ['Add', 'Edit', 'Delete']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentlogs');
    }
};
