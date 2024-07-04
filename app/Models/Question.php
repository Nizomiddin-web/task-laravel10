<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['test_file_id','question'];


    public function test_file() : BelongsTo {
        return $this->belongsTo(TestFile::class);
    }

    public function options() : HasMany {
        return $this->hasMany(Option::class);
    }
}
