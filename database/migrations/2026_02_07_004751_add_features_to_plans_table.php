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
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('channels')->nullable();
            $table->integer('movies')->nullable();
            $table->integer('series')->nullable();
            $table->json('extras')->nullable();
            $table->string('max_resolution')->nullable();
            $table->integer('simultaneous_devices')->nullable();
            $table->boolean('match_recording')->default(false)->nullable();
            $table->boolean('multi_audio_subtitles')->default(false)->nullable();
            $table->boolean('support_24_7')->default(false)->nullable();
            $table->boolean('instant_activation')->default(false)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'channels',
                'movies',
                'series',
                'extras',
                'max_resolution',
                'simultaneous_devices',
                'match_recording',
                'multi_audio_subtitles',
                'support_24_7',
                'instant_activation',
            ]);
        });
    }
};
