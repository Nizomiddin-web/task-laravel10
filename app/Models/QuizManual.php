<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizManual extends Model
{
    use HasFactory;

    protected $fillable = ['test_form_id','question','type'];

    public function test_form() : BelongsTo {
        return $this->belongsTo(TestForm::class);
    }

    public function options() : HasMany {
        return $this->hasMany(OptionManual::class);
    }
}
