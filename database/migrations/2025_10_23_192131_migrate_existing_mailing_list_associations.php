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
        // Migrate existing mailing_list_id data to the pivot table
        \DB::table('resources')
            ->whereNotNull('mailing_list_id')
            ->get()
            ->each(function ($resource) {
                \DB::table('mailing_list_resource')->insert([
                    'mailing_list_id' => $resource->mailing_list_id,
                    'resource_id' => $resource->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the pivot table
        \DB::table('mailing_list_resource')->truncate();
    }
};
