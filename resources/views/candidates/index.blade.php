@extends('layouts.app')

@section('title', 'Candidates - RealyHack')

@section('content')
<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <h1 class="text-3xl font-bold text-gray-800">Candidates</h1>
        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="/candidates" class="flex items-center gap-2">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search candidates..." 
                    value="{{ request('search') }}" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                />
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-semibold">Search</button>
            </form>
            <a href="{{ route('candidates.create') }}" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg text-center">
                + New Candidate
            </a>
        </div>
    </div>

    @if($candidates->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($candidates as $candidate)
                @include('components.candidate-card', ['candidate' => $candidate])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $candidates->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-white p-6 rounded shadow text-center">
            <p class="text-gray-500">No candidates found.</p>
        </div>
    @endif
</div>
@endsection
