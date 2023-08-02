<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Time Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Time Log') }}
                        </h2>
                
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Edit Log your time.") }}
                        </p>
                    </header>
                

                    <form method="post" action="{{ route('timelogs.update', $timelog->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')
                
                        <div>
                            <select id="project_id" name="project_id">
                                @foreach ($projects as $project)
                                    <option 
                                    value="{{ $project->id }}" 
                                    {{$project->id == $timelog->project_id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                                @endforeach
                              </select>
                        </div>
                        <div>
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" class="rounded-md p-3 py-2" type="text" name="start_time" :value="old('start_time', $timelog->formatted_start_time)" placeholder="00:00"/>
                            <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" class="rounded-md p-3 py-2" type="text" name="end_time" :value="old('end_time', $timelog->formatted_end_time)" placeholder="00:00"/>
                            <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea name="description" class="resize-y rounded-md p-3 py-2" :value="old('description', $timelog->description)" placeholder="Description"></textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
