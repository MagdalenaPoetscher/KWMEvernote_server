<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'catalogue_id', 'user_id', 'tag_id', 'todo_id'];

    public function tags():BelongsToMany{
        return $this->belongsToMany(Tag::class);
    }

    public function catalogue():BelongsTo{
        return $this->belongsTo(Catalogue::class);
    }

    public function todo():HasMany{
        return $this->hasMany(Todo::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function images():HasMany{
        return $this->hasMany(Image::class);
    }

}
