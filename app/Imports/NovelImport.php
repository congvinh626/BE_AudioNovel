<?php

namespace App\Imports;

use App\Models\Author;
use App\Models\Novel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NovelImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $rowCount = 0;

    public function model(array $row)
    {
        // $total = count($row);
        if(!$row[0]){
            return;
        }
        // \Log::info('sssssssssssssss22222' ,$row );
        // \Log::info($row[0] );

        $this->rowCount++;

        $author_id = null;
        if($row[3]){
            $author = Author::where('slug', $row[3])->first();
            
            if ($author) {
                // Nếu tìm thấy slug trùng khớp, trả về id của author đó
                $author_id = $author->id;
            } else {
                $newAuthor = new Author([
                    'slug' => $row[3],
                    'name' => $row[2],
                ]);
                $newAuthor->save();
                $author_id = $newAuthor->id;
            }
        }

        $checkNovel = Novel::where('slug', $row[1])->first();

        if($checkNovel){
            return;
        }
        
        $novel = new Novel([
            'slug' => $row[1],
            'title' => $row[0],
            'author_id' => $author_id,
        ]);
        $novel->save();
        $this->echoLog($this->rowCount .' --- ' .$row[0]);
    }

    public function echoLog($string) {
        \Log::info($string);
    }
}
