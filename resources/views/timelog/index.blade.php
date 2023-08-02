<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Time Track') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <div class="max-w-screen-md mx-auto bg-gray-200 p-6 flex items-center">
                    <h1 class="text-3xl font-semibold">Time Log</h1>
                    <a href="{{ route('timelogs.create') }}" class="ml-3 right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Add') }}
                    </a>
                  </div>
                    <table class="w-full table-fixed hover:border-collapse border-separate border-spacing-16 border border-slate-500">
                        <thead>
                          <tr>
                            <th class="border border-slate-600">User</th>
                            <th class="border border-slate-600">Project</th>
                            <th class="border border-slate-600">Start Time</th>
                            <th class="border border-slate-600">End Time</th>
                            <th class="border border-slate-600">Description</th>
                            <th class="border border-slate-600">Created</th>
                            <th class="border border-slate-600">Updated</th>
                            <th class="border border-slate-600">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($timelogs as $timelog)
                            <tr>
                              <td class="border border-slate-700">{{ $timelog->user->name }}</td>
                              <td class="border border-slate-700">{{ $timelog->project->name }}</td>
                              <td class="border border-slate-700">{{ $timelog->formatted_start_time }}</td>
                              <td class="border border-slate-700">{{ $timelog->formatted_end_time }}</td>
                              <td class="border border-slate-700">{{ $timelog->description }}</td>
                              <td class="border border-slate-700">{{ $timelog->created_at }}</td>
                              <td class="border border-slate-700">{{ $timelog->updated_at }}</td>
                              <td class="border border-slate-700">
                                <div>
                                  <a href="{{ route('timelogs.edit', $timelog->id) }}" class="ml-3 right-0 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Edit') }}
                                  </a>
                                </div>
                                <div>
                                  <form action="{{ route('timelogs.destroy', $timelog->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Delete
                                    </button>
                                  </form>
                                </div>

                              </td>
                            </tr>
                          @endforeach
                          
                        </tbody>
                      </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
