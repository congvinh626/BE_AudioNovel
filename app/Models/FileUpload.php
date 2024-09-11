<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    protected $table = 'file_uploads';

    protected $fillable = [
        'name',
        'type',
        'name_table',
        'lesson_id'
    ];

    // public function lesson(){
    //     return $this->belongsTo(Lesson::class);
    // }

}
