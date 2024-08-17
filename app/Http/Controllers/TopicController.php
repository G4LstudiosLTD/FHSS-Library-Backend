<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\User;
use App\Models\Subject;
use App\Models\Activity;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class TopicController extends Controller
{
    // Fetch all topics
    public function index($id)
    {
        $topics = Topic::where('subject_id', $id)->get()
        ->map(function ($item) {
            return collect($item->getAttributes())
                ->mapWithKeys(function ($value, $key) {
                    return [$key => (string) $value];
                });
        });
    
    return response()->json($topics);
    }
    
    public function sindex(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            // 'term_id' => 'nullable|string',
        ]);
        
        $topics = Topic::select('id', 'week', 'title', 'introduction', 'file', 'video')->where('subject_id', $request->input('subject_id'))->where('grade_id', $request->input('grade_id'))->get();
        return response()->json($topics);
    }


    // Store a new topic
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grade_id' => 'required|exists:grades,id',
            'term_id' => 'nullable|string',
            'week' => 'required|integer',
            'title' => 'required|string|max:255',
            'introduction' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:102400', // Max 100MB
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt|max:10240', // Max 10MB
        ]);
    
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            $videoName = 'video_' . Carbon::now()->timestamp . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('videos', $videoName, 'public');
            $validated['video'] = Storage::disk('public')->url($path);
        }
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = 'file_' . Carbon::now()->timestamp . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('files', $fileName, 'public');
            $validated['file'] = Storage::disk('public')->url($path);
        }
    
        $topic = Topic::create($validated);
        
        $subject = Subject::where('id', $validated['subject_id'])->first();
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
        $activity_log = 'Created a Topic "'.$validated['title'].'" under '.$subject->subject_name;
        $activity = Activity::create([
            'user_id' => $userId,
            'school_id' => $school_id,
            'name' => 'Topic Creation',
            'description' => $activity_log,
            'type' => 'Topic',
        ]);
        
        //dd($activity);
    
        return response()->json($topic, 201);
    }

    // Show a specific topic
    public function show($id)
    {
        $topic = Topic::findOrFail($id);
        return response()->json($topic);
    }

    // Update a specific topic
    public function update(Request $request, $id)
    {
        $topic = Topic::findOrFail($id);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'week' => 'required|integer',
            'title' => 'required|string|max:255',
            'introduction' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480', // Max 20MB
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('video')) {
            if ($topic->video) {
                Storage::disk('public')->delete($topic->video);
            }
            $videoFile = $request->file('video');
            $videoName = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Carbon::now()->timestamp . '.' . $videoFile->getClientOriginalExtension();
            $path = $videoFile->storeAs('videos', $videoName, 'public');
            $validated['video'] = Storage::disk('public')->url($path);
        }else{
            $validated['video'] = null;
        }

        if ($request->hasFile('file')) {
            if ($topic->file) {
                Storage::disk('public')->delete($topic->file);
            }
            $file = $request->file('file');
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . Carbon::now()->timestamp . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('files', $fileName, 'public');
            $validated['file'] = Storage::disk('public')->url($path);
        }else{
            $validated['file'] = null;
        }

        $topic->update($validated);

        return response()->json($topic);
    }

    // Delete a specific topic
    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);

        if ($topic->video) {
            Storage::disk('public')->delete($topic->video);
        }

        if ($topic->file) {
            Storage::disk('public')->delete($topic->file);
        }

        $topic->delete();

        return response()->json(['message' => 'Topic deleted successfully']);
    }
}