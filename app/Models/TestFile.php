<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestFile extends Model
{
    use HasFactory;

    protected $fillable = ['name','type','size','extension'];

    public function questions() : HasMany {
        return $this->hasMany(Question::class);
    }
}
