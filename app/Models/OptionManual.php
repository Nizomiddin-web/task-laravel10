<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionManual extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_manual_id','option','is_correct'];

    public function quizManual() : BelongsTo {
        return $this->belongsTo(QuizManual::class);
    }
}
