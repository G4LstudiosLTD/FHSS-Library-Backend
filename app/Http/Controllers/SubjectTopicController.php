<?php

namespace App\Http\Controllers;

use App\Models\Subject_Topic;
use Illuminate\Http\Request;

class SubjectTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validate the request data (optional)
        $request->validate([
            'school_id' => 'required',
            'grade_id' => 'required',
            'subject_id' => 'required',
        ]);

        // Retrieve the authenticated user
        $user = $request->user();

        // Check if the authenticated user has access to the specified school_id
        if ($user->getSchoolId() != $request->input('school_id')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Fetch all topics based on the provided criteria
        $topics = Subject_Topic::where('school_id', $user->getSchoolId())
            ->where('grade_id', $request->input('grade_id'))
            ->where('subject_id', $request->input('subject_id'))
            ->select('topic', 'description')
            ->get();

        // Return the fetched topics as a JSON response
        return response()->json(['topics' => $topics], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $user = $request->user();
        
        $request->validate([
            'subject_id' => 'required',
            'grade_id' => 'required',
            'school_id' => 'required',
            'topic' => 'required',
            'description' => 'sometimes',
            'file' => 'sometimes'
        ]);

        $topic = Subject_Topic::create([
            'school_id' => $user->getSchoolId(),
            'grade_id' => $request->input('grade_id'),
            'subject_id' => $request->input('subject_id'),
            'topic' => $request->input('topic'),
            'description' => $request->input('description'),
            'file' => $request->input('file'),
        ]);
        
        if ($topic) {
            // Topic created successfully
            return response()->json(['message' => 'Topic created successfully'], 201);
        } else {
            // Failed to create topic
            return response()->json(['error' => 'Failed to create topic'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject_Topic  $subject_Topic
     * @return \Illuminate\Http\Response
     */
    public function show(Subject_Topic $subject_Topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject_Topic  $subject_Topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject_Topic $subject_Topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject_Topic  $subject_Topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject_Topic $subject_Topic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject_Topic  $subject_Topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject_Topic $subject_Topic)
    {
        //
    }
}
