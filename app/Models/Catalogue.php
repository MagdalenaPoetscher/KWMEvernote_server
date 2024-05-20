<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Catalogue extends Model
{
    use HasFactory;

    protected $fillable =['name', 'user_id'];

    public function notes():HasMany{
        return $this->hasMany(Note::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function todo():HasMany{
        return $this->hasMany(Todo::class);
    }


}
