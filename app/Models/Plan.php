<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
        'duration',
        'description',
        'status',
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
    ];

    protected $casts = [
        'extras' => 'array',
        'match_recording' => 'boolean',
        'multi_audio_subtitles' => 'boolean',
        'support_24_7' => 'boolean',
        'instant_activation' => 'boolean',
    ];
}
