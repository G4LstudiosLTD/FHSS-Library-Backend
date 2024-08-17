<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Subject;
use App\Models\Subject_Routing;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\School;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test storing a new subject with subject routing.
     *
     * @return void
     */
    public function testStoreSubjectWithRouting()
    {
        // Create a school, teacher, and grades
        $school = School::factory()->create();
        $teacher = Teacher::factory()->create();
        $grades = Grade::factory()->count(3)->create(['school_id' => $school->id]);

        $data = [
            'school_id' => $school->id,
            'subject_name' => $this->faker->unique()->word,
            'subject_description' => $this->faker->sentence,
            'grade_ids' => $grades->pluck('id')->toArray(),
            'teacher_id' => $teacher->id,
        ];

        $response = $this->postJson(route('subjects.store'), $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('subjects', [
            'school_id' => $school->id,
            'subject_name' => $data['subject_name'],
        ]);
        foreach ($data['grade_ids'] as $grade_id) {
            $this->assertDatabaseHas('subject_routings', [
                'school_id' => $school->id,
                'grade_id' => $grade_id,
                'teacher_id' => $teacher->id,
            ]);
        }
    }

    /**
     * Test editing a subject routing record.
     *
     * @return void
     */
    public function testEditSubjectRouting()
    {
        // Create a subject routing record
        $subjectRouting = Subject_Routing::factory()->create();

        $response = $this->getJson(route('subject_routing.edit', $subjectRouting->id));

        $response->assertStatus(200)
                 ->assertJson([
                     'subjectRouting' => [
                         'id' => $subjectRouting->id,
                         'school_id' => $subjectRouting->school_id,
                         'grade_id' => $subjectRouting->grade_id,
                         'subject_id' => $subjectRouting->subject_id,
                         'teacher_id' => $subjectRouting->teacher_id,
                     ],
                 ]);
    }

    /**
     * Test updating a subject with subject routing.
     *
     * @return void
     */
    public function testUpdateSubjectWithRouting()
    {
        // Create a subject and associated subject routing records
        $subject = Subject::factory()->create();
        $currentGrades = Grade::factory()->count(3)->create(['school_id' => $subject->school_id]);
        foreach ($currentGrades as $grade) {
            Subject_Routing::factory()->create([
                'subject_id' => $subject->id,
                'grade_id' => $grade->id,
                'school_id' => $subject->school_id,
            ]);
        }

        // New grades and teacher
        $newGrades = Grade::factory()->count(2)->create(['school_id' => $subject->school_id]);
        $teacher = Teacher::factory()->create();

        $data = [
            'school_id' => $subject->school_id,
            'subject_name' => $this->faker->unique()->word,
            'subject_description' => $this->faker->sentence,
            'grade_ids' => $newGrades->pluck('id')->toArray(),
            'teacher_id' => $teacher->id,
        ];

        $response = $this->putJson(route('subjects.update', $subject->id), $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'subject_name' => $data['subject_name'],
        ]);
        foreach ($data['grade_ids'] as $grade_id) {
            $this->assertDatabaseHas('subject_routings', [
                'school_id' => $subject->school_id,
                'grade_id' => $grade_id,
                'teacher_id' => $teacher->id,
            ]);
        }
    }
}

