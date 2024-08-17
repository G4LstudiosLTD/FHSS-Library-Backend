<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Topic;
use App\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $files = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('school_id', $id)->pluck('file');
    
        // Counting number of teachers
        $teacherCount = Teacher::where('school_id', $id)->count();
        
        // Assuming there is a Student model and it has a school_id column
        $studentCount = Student::where('school_id', $id)->count();
        
        // Counting number of subjects
        $subjectCount = Subject::where('school_id', $id)->count();
        
        // Counting number of subjects where files is not empty or null
        $subjectWithFilesCount = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('subjects.school_id', $id)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->count();
            
        // Count subjects where video column is not empty
        $subjectWithVideoCount = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('subjects.school_id', $id)
            ->whereNotNull('video')
            ->where('video', '!=', '')
            ->count();
    
        // Count files by type
        $pdfCount = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('subjects.school_id', $id)
            ->where('file', 'LIKE', '%.pdf')
            ->count();
            
        $audioCount = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('subjects.school_id', $id)
            ->where(function ($query) {
                $query->where('file', 'LIKE', '%.mp3')
                      ->orWhere('file', 'LIKE', '%.wav')
                      ->orWhere('file', 'LIKE', '%.aiff')
                      ->orWhere('file', 'LIKE', '%.flac')
                      ->orWhere('file', 'LIKE', '%.ogg')
                      ->orWhere('file', 'LIKE', '%.m4a');
            })
            ->count();
            
        $totalFileCount = Topic::join('subjects', 'subjects.id','=','topics.subject_id')->where('subjects.school_id', $id)
            ->whereNotNull('file')
            ->where('file', '!=', '')
            ->count();
    
        $otherFilesCount = $totalFileCount - ($pdfCount + $audioCount);
        
        $activity = Activity::join('users', 'users.id', '=', 'activities.user_id')
        ->where('activities.school_id', $id)
        ->select(
            \DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
            'activities.description as description',
            'activities.created_at as time'
        )
        ->take(2)
        ->get();
        
        return response()->json([
            'teacher_count' => $teacherCount,
            'student_count' => $studentCount,
            'subject_count' => $subjectCount,
            'files_count' => $subjectWithFilesCount,
            'chart' => [
                'video_count' => $subjectWithVideoCount,
                'pdf_count' => $pdfCount,
                'audio_count' => $audioCount,
                'other_count' => $otherFilesCount,
            ],
            'activities' => $activity
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function student()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'school_id' => 'required',
            'grade_name' => 'required',
            // Add any other validation rules as needed
        ]);

        // Create or update the record based on school_id
        Grade::create([
            'school_id' => $request->input('school_id'),
            'grade_name' => $request->input('grade_name'),
            // Add any other fields as needed
        ]);

        // Respond with a JSON message and HTTP status code
        return response()->json(['message' => 'Grade stored created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        //
    }
}
