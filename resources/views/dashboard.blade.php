@extends('layouts.app')

@section('title', 'Dashboard - RealyHack')

@section('content')
<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Recruiter Dashboard</h1>
        <div class="flex gap-3">
            <a href="{{ route('jobs.create') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-semibold shadow-md hover:shadow-lg">
                + New Job
            </a>
            <a href="{{ route('candidates.create') }}" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 transition font-semibold shadow-md hover:shadow-lg">
                + New Candidate
            </a>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-gray-600 text-sm font-semibold uppercase mb-2">Open Jobs</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_jobs'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-gray-600 text-sm font-semibold uppercase mb-2">Active Candidates</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_candidates'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-gray-600 text-sm font-semibold uppercase mb-2">Filled Positions</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['filled_positions'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="text-gray-600 text-sm font-semibold uppercase mb-2">Pending Reviews</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $stats['pending_reviews'] }}</p>
        </div>
    </div>
    
    <!-- Recent Jobs -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Recent Jobs</h2>
            <a href="/jobs" class="text-blue-600 hover:text-blue-800 font-semibold transition">View All →</a>
        </div>
        
        @if($stats['recent_jobs']->count() > 0)
            <div class="space-y-3">
                @foreach($stats['recent_jobs'] as $job)
                    <div class="border-l-4 border-blue-500 pl-4 py-2 hover:bg-gray-50 transition rounded">
                        <a href="/jobs/{{ $job->job_id }}" class="text-lg font-semibold text-gray-800 hover:text-blue-600 transition">
                            {{ $job->job_title }}
                        </a>
                        <p class="text-sm text-gray-600">{{ $job->preferred_location ?? 'Remote' }}</p>
                        <p class="text-xs text-gray-500">Posted {{ $job->posted_date ? \Carbon\Carbon::parse($job->posted_date)->diffForHumans() : 'recently' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No recent jobs found.</p>
        @endif
    </div>
</div>
@endsection
