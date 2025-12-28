@extends('layouts.app')

@section('title', 'Confirm Hire - ' . $job->job_title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Confirm Hire</h1>
        <p class="text-gray-600">Review the details before finalizing this hire.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Job Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-blue-600">Job Position</h2>
            
            <div class="mb-4">
                <h3 class="text-xl font-semibold">{{ $job->job_title }}</h3>
                <div class="flex flex-wrap gap-3 text-sm text-gray-600 mt-2">
                    <span>📍 {{ $job->preferred_location ?? 'Remote' }}</span>
                    <span>📅 Posted {{ $job->posted_date ? \Carbon\Carbon::parse($job->posted_date)->format('M d, Y') : 'N/A' }}</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-700 mb-2">Job Description</h4>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {{ $job->job_description ?? 'No description available.' }}
                </div>
            </div>

            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold text-gray-700 mb-2">Requirements</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Minimum Experience: {{ $job->min_experience_years }} years</li>
                    <li>Availability: {{ ucfirst($job->availability_type) }}</li>
                    @if($job->preferred_location)
                        <li>Location: {{ $job->preferred_location }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Candidate Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-green-600">Selected Candidate</h2>
            
            <div class="mb-4">
                <h3 class="text-xl font-semibold">{{ $candidate->full_name }}</h3>
                <p class="text-sm text-gray-600">{{ $candidate->email }}</p>
                @if($candidate->phone)
                    <p class="text-sm text-gray-600">{{ $candidate->phone }}</p>
                @endif
            </div>

            <div class="border-t pt-4">
                <h4 class="font-semibold text-gray-700 mb-2">Profile Summary</h4>
                <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
                    <li>Location: {{ $candidate->location ?? 'Not specified' }}</li>
                    <li>Total Experience: {{ $candidate->total_experience_years ?? 0 }} years</li>
                    <li>Availability: {{ ucfirst($candidate->availability_type ?? 'Unknown') }}</li>
                    <li>Status: {{ $candidate->is_active ? 'Active' : 'Inactive' }}</li>
                </ul>
            </div>

            @if($candidate->skills && $candidate->skills->count() > 0)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Skills (Top {{ min($candidate->skills->count(), 10) }})</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($candidate->skills->take(10) as $candidateSkill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                {{ $candidateSkill->skill->skill_name ?? 'N/A' }}
                                @if($candidateSkill->level)
                                    - {{ $candidateSkill->level->level_name }}
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($candidate->bio)
                <div class="border-t pt-4 mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Bio</h4>
                    <p class="text-sm text-gray-600">{{ $candidate->bio }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Ranking Score (if available) -->
    @if(isset($score))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-bold mb-3">Ranking Score</h3>
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 text-center">
                <div>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($score->total_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Total</p>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ number_format($score->skill_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Skills</p>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ number_format($score->experience_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Experience</p>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ number_format($score->availability_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Availability</p>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ number_format($score->location_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Location</p>
                </div>
                <div>
                    <p class="text-lg font-semibold">{{ number_format($score->cultural_fit_score, 1) }}</p>
                    <p class="text-xs text-gray-600">Cultural Fit</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Form -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-bold mb-4">Additional Notes (Optional)</h3>
        
        <form method="POST" action="{{ route('jobs.assign', ['id' => $job->job_id]) }}">
            @csrf
            <input type="hidden" name="candidate_id" value="{{ $candidate->candidate_id }}">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2">Start Date</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea 
                    name="notes" 
                    rows="3" 
                    placeholder="Add any notes about this hire..."
                    class="w-full border rounded px-3 py-2"
                ></textarea>
            </div>

            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="flex-1 bg-green-600 text-white font-semibold py-3 rounded hover:bg-green-700 transition"
                >
                    ✓ Confirm Hire &amp; Mark Job as Filled
                </button>
                <a 
                    href="{{ route('jobs.show', ['id' => $job->job_id]) }}"
                    class="flex-1 bg-gray-300 text-gray-700 font-semibold py-3 rounded hover:bg-gray-400 transition text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
