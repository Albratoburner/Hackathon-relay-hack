@extends('layouts.app')

@section('title', $candidate->full_name . ' - Candidate Profile')

@section('content')
<div x-data="{ showContact: false }">
    <div class="mb-4 text-sm text-gray-600">
        <a href="/jobs" class="hover:text-blue-600 transition">Jobs</a> / Candidate Profile
    </div>
    
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2 text-gray-800">{{ $candidate->full_name }}</h1>
                <p class="text-lg text-gray-600 mb-2">{{ $candidate->headline ?? 'Candidate' }}</p>
                <div class="flex flex-wrap gap-3 text-sm">
                    <span class="text-gray-600">📍 {{ $candidate->location ?? 'N/A' }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $candidate->availability_status == 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $candidate->availability_status == 'employed' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $candidate->availability_status == 'unavailable' ? 'bg-red-100 text-red-800' : '' }}
                    ">{{ ucfirst($candidate->availability_status ?? 'Unknown') }}</span>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <button 
                    @click="showContact = !showContact"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold transition"
                >
                    <span x-show="!showContact">Show Contact</span>
                    <span x-show="showContact" x-cloak>Hide Contact</span>
                </button>
            </div>
        </div>
        
        <!-- Contact Info (toggle) -->
        <div x-show="showContact" x-cloak class="mt-4 pt-4 border-t">
            <p class="text-sm text-gray-700"><strong>Email:</strong> {{ $candidate->email ?? 'N/A' }}</p>
            <p class="text-sm text-gray-700"><strong>Phone:</strong> {{ $candidate->phone_number ?? 'N/A' }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Skills -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Skills</h2>
            @if($candidate->skills->count() > 0)
                <div class="space-y-4">
                    @foreach($candidate->skills as $candidateSkill)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-semibold text-gray-800">{{ $candidateSkill->skill->skill_name ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-600">
                                    {{ $candidateSkill->level->level_name ?? 'N/A' }}
                                    ({{ $candidateSkill->years_of_experience ?? 0 }} yrs)
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div 
                                    class="bg-blue-600 h-2 rounded-full transition-all"
                                    style="width: {{ min(($candidateSkill->proficiency_rating ?? 50), 100) }}%"
                                ></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $candidateSkill->skill->category->category_name ?? 'Other' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No skills listed.</p>
            @endif
        </div>
        
        <!-- Experience Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Recent Assignments</h2>
            @if($candidate->assignments->count() > 0)
                <div class="space-y-4">
                    @foreach($candidate->assignments as $assignment)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <p class="font-semibold text-gray-800">{{ $assignment->job->job_title ?? 'Assignment' }}</p>
                            <p class="text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($assignment->start_date)->format('M Y') }} - 
                                {{ $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date)->format('M Y') : 'Present' }}
                            </p>
                            @if($assignment->performance_rating)
                                <p class="text-xs text-gray-500">Rating: {{ $assignment->performance_rating }}/5</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No assignment history.</p>
            @endif
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
