@extends('layouts.app')

@section('title', 'Candidates - RealyHack')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Candidates</h1>
        <form method="GET" action="/candidates" class="flex items-center space-x-2">
            <input type="text" name="search" placeholder="Search candidates..." value="{{ request('search') }}" class="border rounded px-3 py-2" />
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
        </form>
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
