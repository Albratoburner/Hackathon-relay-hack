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
                    <span>📍 {{ $job->preferred_location ?? 'Remote' }}</span>
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
            <div class="bg-blue-50 border border-blue-200 rounded-lg shadow p-6 md:sticky md:top-28">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Rank Candidates</h2>
                
                @if($job->status !== 'open')
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-4 text-sm" role="alert">
                        This job is {{ $job->status }}. Ranking is only available for open jobs.
                    </div>
                @else
                    <form method="POST" action="/jobs/{{ $job->job_id }}/rank">
                        @csrf
                        
                        <!-- Required Skills Searchable Multi-select (Alpine.js) -->
                        <div class="mb-4" x-data="skillsSelector()">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Required Skills (Optional)</label>

                            <div class="mb-2">
                                <input
                                    x-model="query"
                                    @input="highlightIndex = 0"
                                    type="search"
                                    placeholder="Search skills (type to filter)"
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                                />
                            </div>

                            <div class="border rounded bg-white max-h-52 overflow-auto">
                                <template x-for="(skill, idx) in filteredSkills" :key="skill.skill_id">
                                    <label
                                        class="flex items-center justify-between px-3 py-2 hover:bg-gray-50 cursor-pointer"
                                    >
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" :value="skill.skill_id" :id="'skill-'+skill.skill_id" x-model="selected" class="h-4 w-4" />
                                            <div class="text-sm">
                                                <div x-text="skill.skill_name" class="font-medium text-gray-800"></div>
                                                <div x-text="skill.category_name" class="text-xs text-gray-500"></div>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500" x-text="skill.tier ? '(' + skill.tier + ')' : ''"></div>
                                    </label>
                                </template>
                                <div x-show="filteredSkills.length === 0" class="px-3 py-2 text-sm text-gray-500">No skills found.</div>
                            </div>

                            <p class="text-xs text-gray-600 mt-2">Type to filter, click to select. Selected: <span x-text="selected.length"></span></p>

                            <!-- Hidden inputs to submit selected skill IDs -->
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="required_skills[]" :value="id">
                            </template>
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
@php
    $skills_for_js = $skills->map(function($s){
        return [
            'skill_id' => $s->skill_id,
            'skill_name' => $s->skill_name,
            'category_name' => $s->category->category_name ?? 'Other',
            'tier' => null,
        ];
    })->toArray();
    $skillsJson = json_encode($skills_for_js, JSON_UNESCAPED_UNICODE);
@endphp

<script>
function skillsSelector(){
    return {
        query: '',
        selected: [],
        skills: {!! $skillsJson !!},
        get filteredSkills(){
            if(!this.query) return this.skills;
            const q = this.query.toLowerCase();
            return this.skills.filter(s => s.skill_name.toLowerCase().includes(q) || (s.category_name && s.category_name.toLowerCase().includes(q)));
        }
    }
}
</script>
