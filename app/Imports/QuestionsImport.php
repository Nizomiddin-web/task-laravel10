<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class QuestionsImport implements OnEachRow
{
    /**
     * @param $file_id is id in file information data
     * created in the QuestionController
     */
    protected $file_id;

    public function __construct($file_id)
    {
        $this->file_id = $file_id;
    }

    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $row = $row->toArray();
        $row_count = count($row)-1;

        //create Question where $row[0] not null
        if (isset($row[0])) {
            $question = Question::firstOrCreate(
                ['test_file_id' => $this->file_id, 'question' => $row[0]]
            );

            // Add question options where $row[$optionKey]  not null
            foreach (range(1,$row_count) as $optionKey) {
                if (isset($row[$optionKey])) {
                    if (str_starts_with($row[$optionKey],"#")) {
                        $question->options()->create(['option' => $row[$optionKey],'correct'=>true]);
                    }else{
                        $question->options()->create(['option' => $row[$optionKey]]);
                    }
                }
            }
        }
    }
}
