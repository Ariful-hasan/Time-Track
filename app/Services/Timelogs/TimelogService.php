<?php

namespace App\Services\Timelogs;

use App\Models\Project;
use App\Models\TimeLog;
use App\Services\AbstractServices;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class TimelogService extends AbstractServices
{
    use TimelogTrait;
    
    public function getAllProjects()
    {
        return Project::select( Project::ID, Project::NAME)->get();
    }

    public function index()
    {
        return TimeLog::with(['project', 'user'])->paginate(10);
    }

    public function store(array $data)
    {
        try {
            $data['start_time'] = $this->setDateTime($data['start_time'], Carbon::today());
            $data['end_time'] = $this->setDateTime($data['end_time'], Carbon::today());
            
            TimeLog::create(array_merge($data, ['user_id' => auth()->user()->id]));

            return redirect()->route('timelogs.index')->with('success', 'Successfully created.');
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Failed to create.']);
        }
    }

    public function update(array $data, string $id)
    {
        try {
            $timelog = TimeLog::find($id);

            $timelog->start_time = $this->setDateTime($data['start_time'], Carbon::parse($timelog->created_at));
            $timelog->end_time = $this->setDateTime($data['end_time'], Carbon::parse($timelog->created_at));
            $timelog->project_id = $data['project_id'];
            $timelog->description = $data['description'];
            $timelog->save();

            return redirect()->route('timelogs.index')->with('success', 'Successfully updated.');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->withErrors(['error' => 'Failed to update data.']);
        }
    }

    public function destroy(string $id)
    {
        try {
            TimeLog::destroy($id);

            return redirect()->route('timelogs.index')->with('success', 'Successfully deleted.');
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Failed to delete data.']);
        }
    }

    public function getTimelogDetailsById(string $id)
    {
        try {
            return TimeLog::with(['project'])->findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'No data found.']);
        }
    }
}