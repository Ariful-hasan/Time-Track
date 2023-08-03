<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Track Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                  <div class="max-w-screen-md mx-auto bg-gray-200 p-6 flex items-center">
                    <h1 class="text-3xl font-semibold">Report Type</h1>

                    <form action="{{ route('report.generate') }}" method="POST" class="inline">
                        @csrf
                        @method('post')
                        <div>
                            <select id="type" name="type" class="items-center px-4 py-2">
                                @foreach ($types as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                              </select>
                              <x-input-error class="mt-2" :messages="$errors->get('type')" />
                        </div>
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Search
                        </button>
                    </form>
                    
                  </div>
                    <table class="w-full table-fixed hover:border-collapse border-separate border-spacing-16 border border-slate-500">
                        <thead>
                          <tr>
                            <th class="border border-slate-600">Project</th>
                            <th class="border border-slate-600">Total</th>
                          </tr>
                        </thead>
                        <tbody>
                            @isset($reports)
                                @foreach ($reports as $report)
                                    <tr>
                                        <td class="border border-slate-700">{{ $report->project->name }}</td>
                                        <td class="border border-slate-700">{{ $report->total }}</td>
                                    </tr>
                                @endforeach
                            @endisset
                        </tbody>
                      </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
