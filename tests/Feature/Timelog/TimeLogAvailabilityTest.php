<?php

namespace Tests\Feature\Timelog;

use App\Models\Project;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeLogAvailabilityTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_timelog_can_be_created() : void
    {
        $currentTime = Carbon::now();
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create();

        $data = [
            'project_id' => $project->id,
            'start_time' => $currentTime->addHour(3)->format('H:i'),
            'end_time' => $currentTime->addHour(5)->format('H:i'),
            'description' => 'aaaa',
        ];
        $response = $this->post('/timelogs', $data);
        
        $response->assertStatus(302); // redirect status code.
        $response->assertRedirect('/timelogs');
        $this->assertDatabaseHas('time_logs', [
            'user_id' => $user->id,
        ]);
    }

    public function test_timelog_can_not_be_created_without_authenticate(): void
    {
        $response = $this->post('/timelogs', [
            'start_time' => '02:00',
            'end_time' => '03:00',
            'description' => '',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_timelog_can_not_be_created_with_same_start_end_time() : void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        $response = $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'start_time' => '10:00',
            'end_time' => '10:00',
            'description' => '',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['end_time']);
        $response->assertSessionHasErrors([
            'end_time' => 'The end time field must be a date after start time.',
        ]);
        
    }

    public function test_timelog_can_not_be_created_with_out_to_out_overlapping() : void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:00",
            'end_time' => "10:00",
            'description' => '',
        ]);

        $response = $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "08:00",
            'end_time' => "11:00",
            'description' => '',
        ]);
        
        $response->assertStatus(302);
        $this->assertEquals(TimeLog::where('user_id', $user->id)->count(), 1);
        $response->assertSessionHasErrors(['start_time']);
        $response->assertSessionHasErrors([
            'start_time' => 'Time exist.',
        ]);
    }

    function test_timelog_can_not_be_created_with_in_to_out_overlapping(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:00",
            'end_time' => "10:00",
            'description' => '',
        ]);

        $response = $this
        ->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:30",
            'end_time' => "10:30",
            'description' => '',
        ]);

        $response->assertStatus(302);
        $this->assertEquals(TimeLog::where('user_id', $user->id)->count(), 1);
        $response->assertSessionHasErrors(['start_time']);
        $response->assertSessionHasErrors([
            'start_time' => 'Time exist.',
        ]);
    }

    function test_timelog_can_not_be_created_with_in_to_in_overlapping(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:00",
            'end_time' => "10:00",
            'description' => '',
        ]);

        $response = $this
        ->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:10",
            'end_time' => "09:30",
            'description' => '',
        ]);
        $response->assertStatus(302);
        $this->assertEquals(TimeLog::where('user_id', $user->id)->count(), 1);
        $response->assertSessionHasErrors(['start_time']);
        $response->assertSessionHasErrors([
            'start_time' => 'Time exist.',
        ]);
    }

    function test_timelog_can_not_be_created_with_out_to_in_overlapping(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create();

        $this->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "09:00",
            'end_time' => "10:00",
            'description' => '',
        ]);

        $response = $this
        ->post('/timelogs', 
        [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'start_time' => "08:30",
            'end_time' => "09:30",
            'description' => '',
        ]);
        
        $response->assertStatus(302);
        $this->assertEquals(TimeLog::where('user_id', $user->id)->count(), 1);
        $response->assertSessionHasErrors(['start_time']);
        $response->assertSessionHasErrors([
            'start_time' => 'Time exist.',
        ]);
    }
}