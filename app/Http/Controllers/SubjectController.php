<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject_Routing;
use App\Models\Activity;
use App\Models\User;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Exception;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        // Log the request data for debugging
        // \Log::info('Request Data:', $request->all());
    
        $request->validate([
            'school_id' => 'required',
        ]);
    
        $data = Subject::join('subject_routings', 'subjects.id', '=', 'subject_routings.subject_id')
            ->leftjoin('teachers', 'subject_routings.teacher_id', '=', 'teachers.id')
            ->join('grades', 'grades.id', '=', 'subject_routings.grade_id')
            ->select(
                'subjects.id as subject_id',
                'subjects.subject_name',
                'subjects.subject_description',
                'subjects.department',
                'subjects.cover',
                'grades.id as grade_id',
                'grades.grade_name',
                \DB::raw('CONCAT(teachers.first_name, " ", teachers.last_name) as teacher_name'),
                'teachers.id as teacher_id'
            )
            ->where('subjects.school_id', $request->input('school_id'))
            ->get();
            
            //dd($data);
    
        // Process data to group grades by subject
        $subjects = $data->groupBy('subject_id')->map(function ($subjectGroup) {
            $first = $subjectGroup->first();
            return [
                'subject_id' => $first->subject_id,
                'subject_name' => $first->subject_name,
                'subject_description' => $first->subject_description,
                'department' => $first->department,
                'cover' => $first->cover,
                'grades' => $subjectGroup->map(function ($item) {
                    return [
                        'grade_id' => $item->grade_id,
                        'grade_name' => $item->grade_name,
                    ];
                })->unique('grade_id')->values()->all(),
                'teacher_name' => $first->teacher_name,
                'teacher_id' => $first->teacher_id,
            ];
        })->values();
    
        return response()->json($subjects);
    }


    public function create($id) 
    {
        $teachers = Teacher::where('school_id', $id)->select(\DB::raw("id, CONCAT(first_name, ' ', last_name) as full_name"))->get();
        $grades = Grade::where('school_id', $id)->pluck('grade_name','id');
        $departments = Department::where('school_id', $id)->pluck('department_name');

        return response()->json([
            'teachers' => $teachers,
            'grades' => $grades,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'school_id' => 'required|integer',
                'subject_name' => [
                    'required',
                    Rule::unique('subjects')->where(function ($query) use ($request) {
                        return $query->where('school_id', $request->input('school_id'));
                    }),
                ],
                'grade_ids' => 'required|array',
                'grade_ids.*' => 'integer',
                'teacher_id' => 'required|integer',
            ]);
            
            $cover = null;
            
            if ($request->hasFile('cover')) {
            $videoFile = $request->file('cover');
            $videoName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Carbon::now()->timestamp . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('subject/cover', $videoName, 'public');
            $cover = Storage::disk('public')->url($path);
            }

            $subject = Subject::create([
                'school_id' => $request->input('school_id'),
                'subject_name' => $request->input('subject_name'),
                'subject_description' => $request->input('subject_description', ''),
                'department' => $request->input('department'),
                'cover' => $cover,
            ]);

            foreach ($request->input('grade_ids') as $grade_id) {
                Subject_Routing::create([
                    'school_id' => $validated['school_id'],
                    'grade_id' => $grade_id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $request->input('teacher_id'),
                ]);
            }
            
            $user = $request->user(); // Get the authenticated user
            $userId = $user->id;
            $role = $user->role;
            $school_id = 0;
            if($role == 'student'){
                $school_id = Student::where('user_id', $userId)->pluck('school_id')->first();
            } elseif($role == 'admin'){
                $school_id = Admin::where('user_id', $userId)->pluck('school_id')->first();
            } elseif($role == 'teacher'){
                $school_id = Teacher::where('user_id', $userId)->pluck('school_id')->first();
            }
            $user_name = User::where('id',$userId)->first();
            $activity_log = 'Created a Subject - '.$request->input('subject_name');
            $activity = Activity::create([
                'user_id' => $userId,
                'school_id' => $school_id,
                'name' => $request->input('subject_name'),
                'description' => $activity_log,
                'type' => 'Subject',
            ]);
            
            

            return response()->json(['message' => 'Subject and subject routing created successfully', 'subject' => $subject], 201);

        } catch (QueryException $exception) {
            return response()->json(['error' => 'Server error: ' . $exception->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'school_id' => 'required|integer',
                'subject_name' => [
                    'required',
                    Rule::unique('subjects')->where(function ($query) use ($request) {
                        return $query->where('school_id', $request->input('school_id'));
                    })->ignore($id),
                ],
                'grade_ids' => 'required|array',
                'grade_ids.*' => 'integer',
                'teacher_id' => 'required|integer',
            ]);

            $subject = Subject::findOrFail($id);

            $subject->update([
                'school_id' => $request->input('school_id'),
                'subject_name' => $request->input('subject_name'),
                'subject_description' => $request->input('subject_description', $subject->subject_description),
            ]);

            $currentGradeIds = Subject_Routing::where('subject_id', $id)->pluck('grade_id')->toArray();
            $newGradeIds = $request->input('grade_ids');
            $gradeIdsToAdd = array_diff($newGradeIds, $currentGradeIds);
            $gradeIdsToDelete = array_diff($currentGradeIds, $newGradeIds);

            Subject_Routing::where('subject_id', $id)->whereIn('grade_id', $gradeIdsToDelete)->delete();

            foreach ($gradeIdsToAdd as $grade_id) {
                Subject_Routing::create([
                    'school_id' => $request->input('school_id'),
                    'grade_id' => $grade_id,
                    'subject_id' => $id,
                    'teacher_id' => $request->input('teacher_id'),
                ]);
            }

            Subject_Routing::where('subject_id', $id)->whereIn('grade_id', $newGradeIds)
                ->update(['teacher_id' => $request->input('teacher_id')]);

            return response()->json(['message' => 'Subject and subject routing updated successfully'], 200);

        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Subject not found'], 404);

        } catch (QueryException $exception) {
            return response()->json(['error' => 'Server error: ' . $exception->getMessage()], 500);

        } catch (Exception $exception) {
            return response()->json(['error' => 'Server error: ' . $exception->getMessage()], 500);
        }
    }
    
    public function fetch($id)
    {
        // Log the request data for debugging

         $data = Subject::join('subject_routings', 'subjects.id', '=', 'subject_routings.subject_id')
        ->join('teachers', 'subject_routings.teacher_id', '=', 'teachers.id')
        ->join('grades', 'grades.id', '=', 'subject_routings.grade_id')
        ->select(
            'subjects.id as subject_id',
            'subjects.subject_name',
            'subjects.subject_description',
            'subjects.department',
            'subjects.cover',
            'grades.id as grade_id',
            'grades.grade_name',
            \DB::raw('CONCAT(teachers.first_name, " ", teachers.last_name) as teacher_name'),
            'teachers.id as teacher_id'
        )
        ->where('subjects.id', $id)
        ->get();

        // Process data to group grades by subject
        $subjects = $data->groupBy('subject_id')->map(function ($subject) {
            $first = $subject->first();
            return [
                'id' => $first->subject_id,
                'subject_name' => $first->subject_name,
                'subject_description' => $first->subject_description,
                'department' => $first->department,
                'cover' => $first->cover,
                'grades' => $subject->map(function ($item) {
                    return [
                        'grade_id' => $item->grade_id,
                        'grade_name' => $item->grade_name,
                    ];
                })->unique('grade_id')->values()->all(),
                'teacher_name' => $first->teacher_name,
                'teacher_id' => $first->teacher_id,
            ];
        })->values();
    
        return response()->json($subjects);
    }
    
    public function studentsubject(Request $request)
    {
        // Log the request data for debugging
        // \Log::info('Request Data:', $request->all());
    
        $request->validate([
            'school_id' => 'required',
            'grade_id' => 'required',
        ]);
    
        $data = Subject::join('subject_routings', 'subjects.id', '=', 'subject_routings.subject_id')
            ->leftjoin('teachers', 'subject_routings.teacher_id', '=', 'teachers.id')
            ->join('grades', 'grades.id', '=', 'subject_routings.grade_id')
            ->select(
                'subjects.id as subject_id',
                'subjects.subject_name',
                'subjects.subject_description',
                'subjects.department',
                'subjects.cover',
                \DB::raw('CONCAT(teachers.first_name, " ", teachers.last_name) as teacher_name'),
                'teachers.id as teacher_id'
            )
            ->where('subjects.school_id', $request->input('school_id'))
            ->where('subject_routings.grade_id', $request->input('school_id'))
            ->get();
            
            //dd($data);
    
        return response()->json($data);
    }

    public function destroy($subject_id)
    {
        // Your logic for deleting a subject
    }
}
