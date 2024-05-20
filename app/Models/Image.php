<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'title'];

    public function note():BelongsTo{
        return $this->belongsTo(Note::class);
    }

    public function todo():BelongsTo{
        return $this->belongsTo(Todo::class);
    }



}
