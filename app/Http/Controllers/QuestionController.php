<?php

namespace App\Http\Controllers;
use App\Imports\QuestionsImport;
use App\Models\OptionManual;
use App\Models\QuizManual;
use Illuminate\Support\Str;
use App\Models\TestFile;
use App\Models\TestForm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceResponse;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    public function import(Request $request){
        $request->validate([
            'excel' => 'required|mimes:xlsx,xls,csv',
        ]);

        //Get file size,type and extension
        $file = $request->file('excel');
        $file_size = $file->getSize();
        $file_type = $file->getMimeType();
        $file_extension = $file->getClientOriginalExtension();

        // Check file size and if file size equals zero return error
        if ($file_size==0) {
            return ["error"=>["message"=>"file bo'sh bo'lmasligi lozim!"]];
        }

        // Create file name with current date and random 5 string
        $date = now()->format('Y_m_d');
        $randomString = Str::random(5);

        // Create file information to database
        $test_file = TestFile::create(
            [
                "name"=>$date . '_' . $randomString,
                "type"=>$file_type,
                "size"=>$file_size,
                "extension"=>$file_extension,
            ]
        );

        // Save data in the file to the database
        Excel::import(new QuestionsImport($test_file->id),$request->file('excel'));
        return ["success"=>true];
    }

    public function addManual(Request $request){
        //first create TestForm data
        $testForm = TestForm::create([
            'user_id'=>1,
            'name'=>Str::random(10),
            'type'=>'private'
        ]);
        //second foreach every data from $request
        foreach($request->all() as $data){

            //declareted various variables
            $question = $data['editorContent'];
            $options = $data['options'];
            $switchType = $data['switchType'];

            //Check answer type and set type 1 or 2
            if ($switchType=='one answer') {
                $switchType=1;
            }else{
                $switchType=2;
                $selectedOptions = $data['selectedOptions'];
            }

            //Create new question in database
            $quiz = QuizManual::create([
                'test_form_id'=>$testForm->id,
                'question'=>$question,
                'type'=>$switchType
            ]);


            //Check more options or not
            if (isset($selectedOptions)) {
                foreach($options as $option){
                    if (in_array($option,$selectedOptions)) {
                        $isCorrect = true;
                    }else{
                        $isCorrect = false;
                    }
                    //Create option
                    OptionManual::create([
                        'quiz_manual_id'=>$quiz->id,
                        'option'=>$option['value'],
                        'is_correct'=>$isCorrect
                       ]);
                }
            }else{
                foreach($options as $option)
                    OptionManual::create([
                    'quiz_manual_id'=>$quiz->id,
                    'option'=>$option['value'],
                    'is_correct'=>$option['isCorrect']
                    ]);
            }
        }
        return ['success'=>true];
    }

}
