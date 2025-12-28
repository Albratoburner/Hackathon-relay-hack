@extends('layouts.app')

@section('title', 'Manage Skills - ' . $candidate->full_name)

@section('content')
<div x-data="{ 
    skills: [{ skill_id: '', level_id: '', years_of_experience: 0, proficiency_rating: 3 }],
    addSkill() {
        this.skills.push({ skill_id: '', level_id: '', years_of_experience: 0, proficiency_rating: 3 });
    },
    removeSkill(index) {
        if (this.skills.length > 1) {
            this.skills.splice(index, 1);
        }
    }
}">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Manage Skills</h1>
        <p class="text-gray-600">
            <a href="{{ route('candidates.show', $candidate->candidate_id) }}" class="text-blue-600 hover:underline">{{ $candidate->full_name }}</a> / Add Skills
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('candidates.storeSkills', $candidate->candidate_id) }}">
            @csrf
            
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Skills</h2>
                    <button 
                        type="button" 
                        @click="addSkill()" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold transition"
                    >
                        + Add Skill
                    </button>
                </div>

                <template x-for="(skill, index) in skills" :key="index">
                    <div class="border border-gray-300 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-700" x-text="'Skill #' + (index + 1)"></h3>
                            <button 
                                type="button" 
                                @click="removeSkill(index)"
                                class="text-red-600 hover:text-red-800 text-sm"
                                x-show="skills.length > 1"
                            >
                                Remove
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">Skill *</label>
                                <select 
                                    :name="'skills[' + index + '][skill_id]'" 
                                    x-model="skill.skill_id"
                                    required
                                    class="w-full border rounded px-3 py-2"
                                >
                                    <option value="">Select a skill</option>
                                    @foreach($skills as $s)
                                        <option value="{{ $s->skill_id }}" {{ in_array($s->skill_id, $existingSkills) ? 'selected' : '' }}>
                                            {{ $s->skill_name }} ({{ $s->category->category_name ?? 'Other' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2">Level *</label>
                                <select 
                                    :name="'skills[' + index + '][level_id]'" 
                                    x-model="skill.level_id"
                                    required
                                    class="w-full border rounded px-3 py-2"
                                >
                                    <option value="">Select level</option>
                                    @foreach($skillLevels as $level)
                                        <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2">Years of Experience</label>
                                <input 
                                    type="number" 
                                    :name="'skills[' + index + '][years_of_experience]'"
                                    x-model="skill.years_of_experience"
                                    min="0"
                                    max="50"
                                    class="w-full border rounded px-3 py-2"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2">Proficiency Rating (1-5)</label>
                                <input 
                                    type="number" 
                                    :name="'skills[' + index + '][proficiency_rating]'"
                                    x-model="skill.proficiency_rating"
                                    min="1"
                                    max="5"
                                    class="w-full border rounded px-3 py-2"
                                >
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            @error('skills')
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $message }}
                </div>
            @enderror

            <div class="flex gap-4">
                <button 
                    type="submit"
                    class="flex-1 bg-blue-600 text-white font-semibold py-3 rounded hover:bg-blue-700 transition"
                >
                    Save Skills
                </button>
                <a 
                    href="{{ route('candidates.show', $candidate->candidate_id) }}"
                    class="flex-1 bg-gray-300 text-gray-700 font-semibold py-3 rounded hover:bg-gray-400 transition text-center"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
