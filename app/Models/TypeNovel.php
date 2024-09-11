<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeNovel extends Model
{
    use HasFactory;

    protected $table = 'type_novels';

    protected $fillable = [
        'title',
        'slug',
    ];


    protected $hidden = [
        'pivot'
    ];
    
    public function novels(){
        return $this->belongsToMany(Novel::class, 'map_novel_type');
    }
}
