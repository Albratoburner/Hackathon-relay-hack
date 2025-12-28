@extends('layouts.app')

@section('title', 'Jobs - RealyHack')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Job Orders</h1>
    
    <!-- Search & Filter -->
    <form method="GET" action="/jobs" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input 
                type="text" 
                name="search" 
                placeholder="Search jobs..." 
                value="{{ request('search') }}"
                class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                aria-label="Search jobs"
            >
            <select name="status" class="border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" aria-label="Filter by status">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition font-semibold">Filter</button>
        </div>
    </form>
    
    <!-- Jobs Grid -->
    @if($jobs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($jobs as $job)
                @include('components.job-card', ['job' => $job])
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="bg-white p-8 rounded-lg shadow text-center">
            <p class="text-gray-500 text-lg">No jobs found matching your criteria.</p>
            @if(request('search') || request('status'))
                <a href="/jobs" class="inline-block mt-4 text-blue-600 hover:text-blue-800 font-semibold">Clear filters</a>
            @endif
        </div>
    @endif
</div>
@endsection
