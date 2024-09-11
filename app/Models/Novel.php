<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novel extends Model
{
    use HasFactory;

    protected $table = 'novels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'thumbnail',
        'title',
        'slug',
        'excerpt',
        'author_id',
        'chapter',
    ];

    protected $hidden = [
        'pivot'
    ];

    public function TypeNovels(){
        return $this->belongsToMany(TypeNovel::class, 'map_novel_type');
    }

    public function chapteres(){
        return $this->hasMany(Chapter::class);
    }
}
