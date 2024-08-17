<?php

namespace App\Http\Controllers;

use App\Models\arm;
use Illuminate\Http\Request;

class ArmController extends Controller
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
        ]);

        // Retrieve all grade_name values for the specified school_id
        $arms = Arm::where('school_id', $request->input('school_id'))
        ->where('grade_id', $request->input('grade_id'))
        ->select('id','arm_name')
        ->get();

        // Respond with the list of grade_names
        return response()->json(['arms' => $arms]);
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
        // Validate the request data
        $request->validate([
            'school_id' => 'required',
            'grade_id' => 'required',
            'arm_name' => 'required',
            // Add any other validation rules as needed
        ]);

        // Create or update the record based on school_id
        Arm::create([
            'school_id' => $request->input('school_id'),
            'grade_id' => $request->input('grade_id'),
            'arm_name' => $request->input('arm_name'),
        ]);

        // Respond with a JSON message and HTTP status code
        return response()->json(['message' => 'Arm created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\arm  $arm
     * @return \Illuminate\Http\Response
     */
    public function show(arm $arm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\arm  $arm
     * @return \Illuminate\Http\Response
     */
    public function edit(arm $arm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\arm  $arm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, arm $arm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\arm  $arm
     * @return \Illuminate\Http\Response
     */
    public function destroy(arm $arm)
    {
        //
    }
}
