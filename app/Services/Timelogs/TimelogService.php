<?php

namespace App\Services\Timelogs;

use App\Models\Project;
use App\Models\TimeLog;
use App\Services\AbstractServices;
use App\Traits\TimelogTrait;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TimelogService extends AbstractServices
{
    use TimelogTrait;
        
    /**
     * all projects list
     *
     * @return Collection
     */
    public function getAllProjects(): Collection
    {
        return Project::select( Project::ID, Project::NAME)->get();
    }
    
    /**
     * pagination list of Timelog data
     *
     * @return LengthAwarePaginator or RedirectResponse
     */
    public function index(): LengthAwarePaginator|RedirectResponse
    {
        try {
            return TimeLog::with(['project', 'user'])->paginate(10);
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->withErrors(['error' => 'Woops!, Something went wrong!']);
        }
    }
    
    /**
     * store new record of Timelog
     *
     * @param  array $data valid input
     * @return RedirectResponse
     */
    public function store(array $data): RedirectResponse
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
    
    /**
     * update existing record of Timelog
     *
     * @param  array $data valid input
     * @param  string $id primary key of Timelog
     * @return RedirectResponse
     */
    public function update(array $data, string $id): RedirectResponse
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
    
    /**
     * destroy one record from Timelog
     *
     * @param  string $id
     * @return RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            TimeLog::destroy($id);

            return redirect()->route('timelogs.index')->with('success', 'Successfully deleted.');
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'Failed to delete data.']);
        }
    }
    
    /**
     * get one record of Timelog
     *
     * @param  string $id
     * @return TimeLog
     */
    public function getTimelogDetailsById(string $id): TimeLog|RedirectResponse
    {
        try {
            return TimeLog::with(['project'])->findOrFail($id);
        } catch (\Throwable $th) {
            Log::error($th);
            
            return back()->withErrors(['error' => 'No data found.']);
        }
    }
}