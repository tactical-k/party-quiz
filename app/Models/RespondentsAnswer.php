<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class RespondentsAnswer extends Model
{
    use HasFactory;

    protected $table = 'respondents_answers';

    protected $fillable = [
        'event_id',
        'question_id',
        'name',
        'answer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'uuid');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
