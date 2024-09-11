<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Test;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    protected $exam_id;

    public function  __construct(string $exam_id)
    {
        $this->exam_id = $exam_id;
    }


    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {


            if ($key > 1) {
                $question = new Question([
                    'title' => $row[1],
                    'level' => $row[7],
                    'exam_id' => $this->exam_id,
                    // 'file_upload_id' => $row[5]
                ]);
                $question->save();

                for ($i = 0; $i < 4; $i++) {
                    $answer = new Answer([
                        'title' => $row[$i + 2],
                        'result' => $i == 0 ? 1 : 0,
                        'question_id' => $question->id,
                        // 'file_upload_id' => $row[5]
                    ]);
                    $answer->save();
                }
            }
        }
        return 'ok';
        // return Xxx::all();
        // return new Test([
        //     'tl1'=> $row["STT"],
        //     // 'tl2'=> $row[1],
        // ]);
    }
}
