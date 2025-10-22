<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default organization settings
        DB::table('settings')->insert([
            ['key' => 'organization_name', 'value' => 'Complero', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'organization_email', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'organization_website', 'value' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
