@extends('layouts.app')

@section('title', $job->job_title . ' - RealyHack')

@section('content')
<div>
    <!-- Breadcrumb -->
    <div class="mb-4 text-sm text-gray-600">
        <a href="/jobs" class="hover:text-blue-600 transition">Jobs</a> / {{ $job->job_title }}
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Job Details (left 2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h1 class="text-3xl font-bold mb-2 text-gray-800">{{ $job->job_title }}</h1>
                <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                    <span>📍 {{ $job->location ?? 'Remote' }}</span>
                    <span>🏢 {{ $job->client_name ?? 'N/A' }}</span>
                    <span>📅 Posted {{ $job->posted_date ? \Carbon\Carbon::parse($job->posted_date)->format('M d, Y') : 'N/A' }}</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        {{ $job->status == 'open' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $job->status == 'filled' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $job->status == 'reviewing' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    ">{{ ucfirst($job->status) }}</span>
                </div>
                
                <h2 class="text-xl font-semibold mb-2 text-gray-800">Description</h2>
                <div class="prose max-w-none mb-6 text-gray-700 whitespace-pre-line">
                    {{ $job->job_description ?? 'No description available.' }}
                </div>
                
                @if($job->required_skills_description)
                    <h2 class="text-xl font-semibold mb-2 text-gray-800">Required Skills</h2>
                    <p class="text-gray-700 whitespace-pre-line">{{ $job->required_skills_description }}</p>
                @endif
            </div>
        </div>
        
        <!-- Ranking Panel (right 1/3) -->
        <div class="lg:col-span-1">
            <div class="bg-blue-50 border border-blue-200 rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Rank Candidates</h2>
                
                @if($job->status !== 'open')
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4 text-sm" role="alert">
                        This job is {{ $job->status }}. Ranking is only available for open jobs.
                    </div>
                @else
                    <form method="POST" action="/jobs/{{ $job->job_id }}/rank">
                        @csrf
                        
                        <!-- Required Skills Multi-select -->
                        <div class="mb-4">
                            <label for="required_skills" class="block text-sm font-semibold mb-2 text-gray-700">Required Skills (Optional)</label>
                            <select 
                                id="required_skills"
                                name="required_skills[]" 
                                multiple 
                                size="8"
                                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            >
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->skill_id }}">
                                        {{ $skill->skill_name }} ({{ $skill->category->category_name ?? 'Other' }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-600 mt-1">Hold Ctrl/Cmd to select multiple. Leave empty to rank by all skills.</p>
                        </div>
                        
                        <!-- Max Results -->
                        <div class="mb-4">
                            <label for="max_results" class="block text-sm font-semibold mb-2 text-gray-700">Max Results</label>
                            <input 
                                id="max_results"
                                type="number" 
                                name="max_results" 
                                value="20" 
                                min="1" 
                                max="50"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                            >
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition"
                        >
                            Rank Candidates
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
