<?php

namespace Tests\Feature\Report;

use App\Models\Project;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportGenerateTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_can_not_be_generated_by_invalid_type() : void
    {
        $currentTime = Carbon::now();
        $user = User::factory()->create();
        
        $project = Project::create([
            'name' => 'test',
            'description' => 'test',
            'user_id' => $user->id,
        ]);
        $timelog = TimeLog::create([
            'project_id' => $project->id,
            'start_time' => $currentTime->addHour(3)->format('H:i'),
            'end_time' => $currentTime->addHour(5)->format('H:i'),
            'description' => 'aaaa',
            'user_id' =>  $user->id,
        ]);

        $this->actingAs($user);
        $response = $this->post('/reports/generate', ['type' => 'test']);
        
        $response->assertStatus(302); // redirect status code.
        $response->assertSessionHasErrors(['type' => 'The selected type is invalid.']);
    }
}