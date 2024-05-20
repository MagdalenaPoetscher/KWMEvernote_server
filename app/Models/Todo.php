<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due', 'note_id', 'catalogue_id', 'user_id'];

    public function tags():BelongsToMany{
        return $this->belongsToMany(Tag::class);
    }


    public function note():BelongsTo{
        return $this->belongsTo(Note::class);
    }

    public function catalogue():BelongsTo{
        return $this->belongsTo(Catalogue::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function images():HasMany{
        return $this->hasMany(Image::class);
    }
}
