<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestForm extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','type'];

    public function quizsManual() : HasMany {
        return $this->hasMany(QuizManual::class);
    }
}
