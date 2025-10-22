<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('mailing_list_id')->constrained()->onDelete('cascade');
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'unsubscribed'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'mailing_list_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_memberships');
    }
};
