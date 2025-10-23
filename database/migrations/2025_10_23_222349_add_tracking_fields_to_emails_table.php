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
        Schema::table('emails', function (Blueprint $table) {
            $table->string('brevo_message_id')->nullable()->after('sent_at');
            $table->integer('total_opens')->default(0)->after('brevo_message_id');
            $table->integer('unique_opens')->default(0)->after('total_opens');
            $table->integer('total_clicks')->default(0)->after('unique_opens');
            $table->integer('unique_clicks')->default(0)->after('total_clicks');
            $table->timestamp('last_opened_at')->nullable()->after('unique_clicks');
            $table->timestamp('last_clicked_at')->nullable()->after('last_opened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn([
                'brevo_message_id',
                'total_opens',
                'unique_opens',
                'total_clicks',
                'unique_clicks',
                'last_opened_at',
                'last_clicked_at'
            ]);
        });
    }
};
