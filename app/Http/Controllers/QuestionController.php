<?php

namespace App\Http\Controllers;
use App\Imports\QuestionsImport;
use Illuminate\Support\Str;
use App\Models\TestFile;
use Illuminate\Http\Request;
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
}
