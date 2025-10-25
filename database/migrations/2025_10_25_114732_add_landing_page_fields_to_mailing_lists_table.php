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
            $table->string('landing_hero_title')->nullable();
            $table->string('landing_hero_subtitle')->nullable();
            $table->string('landing_hero_image')->nullable();
            $table->text('landing_feature_1')->nullable();
            $table->text('landing_feature_2')->nullable();
            $table->text('landing_feature_3')->nullable();
            $table->string('landing_cta_text')->default('Tilmeld nu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mailing_lists', function (Blueprint $table) {
            $table->dropColumn([
                'landing_hero_title',
                'landing_hero_subtitle',
                'landing_hero_image',
                'landing_feature_1',
                'landing_feature_2',
                'landing_feature_3',
                'landing_cta_text',
            ]);
        });
    }
};
