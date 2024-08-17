<?php

namespace App\Http\Controllers;

use App\Models\Subject_Routing;
use Illuminate\Http\Request;

class SubjectRoutingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index(Request $request)
    {

        $request->user();

        $request->validate([
            'school_id' => 'required'
        ]);

        $subject = Subject_Routing::join('subjects', 'subject_routings.subject_id', '=', 'subjects.id')
                ->where('subject_routings.school_id', $request->input('school_id'))
                ->where('subject_routings.grade_id', $request->input('grade_id'))
                ->select('subjects.subject_name', 'subjects.subject_description')
                ->get();
                //
        return $subject;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject_Routing  $subject_Routing
     * @return \Illuminate\Http\Response
     */
    public function show(Subject_Routing $subject_Routing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject_Routing  $subject_Routing
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject_Routing $subject_Routing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject_Routing  $subject_Routing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject_Routing $subject_Routing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject_Routing  $subject_Routing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject_Routing $subject_Routing)
    {
        //
    }
}
