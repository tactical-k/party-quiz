<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'text',
        'type',
        'created_at',
    ];

    protected $casts = [
        'is_submitted' => 'boolean',
    ];

    // リレーション: 一つの質問は多くの選択肢を持つ
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'uuid');
    }

    public function respondentsAnswers(): HasMany
    {
        return $this->hasMany(RespondentsAnswer::class);
    }
}
