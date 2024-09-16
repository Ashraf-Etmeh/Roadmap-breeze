<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
    {{-- search bar --}}
    <form method="POST" action="#">
        @csrf
        <input name="search" id="search" type="search" placeholder="search" :value="old('search')">

    </form>
    {{-- suggested roadmaps --}}
    <div class="space-y-4">
        @foreach ($roadmaps as $roadmap)

            <a href="#" class="block px-4 py-6 border border-gray-200 rounded-lg">
                {{-- <div class="font-bold text-blue-500 text-sm">{{ $job->employer->name }}</div> --}}
                <div>
                    <strong>{{ $roadmap['title'] }} : </strong>  {{ $roadmap['description'] }} per month
                </div>
            </a>
        @endforeach
        {{-- <div>
            {{ $jobs->links() }}
        </div> --}}
    </div>
</x-app-layout>
