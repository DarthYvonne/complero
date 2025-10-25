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
        Schema::table('mailing_lists', function (Blueprint $table) {
            $table->string('organization_name')->nullable();
            $table->string('website')->nullable();
            $table->string('responsible_person')->nullable();
            $table->string('support_email')->nullable();
            $table->string('primary_color')->default('#be185d');
            $table->string('secondary_color')->default('#9d174d');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mailing_lists', function (Blueprint $table) {
            $table->dropColumn(['organization_name', 'website', 'responsible_person', 'support_email', 'primary_color', 'secondary_color']);
        });
    }
};
