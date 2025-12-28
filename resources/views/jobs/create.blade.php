@extends('layouts.app')

@section('title', 'Create Job - RealyHack')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Create New Job</h1>
        <p class="text-gray-600">Fill in the details below to post a new job opening.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 lg:p-8 max-w-4xl">
        <form method="POST" action="{{ route('jobs.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Job Title -->
                <div class="md:col-span-2">
                    <label for="job_title" class="block text-sm font-semibold mb-2 text-gray-700">Job Title <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="job_title" 
                        name="job_title" 
                        value="{{ old('job_title') }}"
                        required
                        class="w-full border {{ $errors->has('job_title') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="e.g., Senior Software Engineer"
                    >
                    @error('job_title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Experience Years -->
                <div>
                    <label for="min_experience_years" class="block text-sm font-semibold mb-2 text-gray-700">Minimum Experience (years) <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        id="min_experience_years" 
                        name="min_experience_years" 
                        value="{{ old('min_experience_years', 0) }}"
                        required
                        min="0"
                        class="w-full border {{ $errors->has('min_experience_years') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                    @error('min_experience_years')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Availability Type -->
                <div>
                    <label for="availability_type" class="block text-sm font-semibold mb-2 text-gray-700">Employment Type <span class="text-red-500">*</span></label>
                    <select 
                        id="availability_type" 
                        name="availability_type" 
                        required
                        class="w-full border {{ $errors->has('availability_type') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                        <option value="">Select Type</option>
                        <option value="full_time" {{ old('availability_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('availability_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                        <option value="contract" {{ old('availability_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="temporary" {{ old('availability_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                    @error('availability_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred Location -->
                <div>
                    <label for="preferred_location" class="block text-sm font-semibold mb-2 text-gray-700">Preferred Location</label>
                    <input 
                        type="text" 
                        id="preferred_location" 
                        name="preferred_location" 
                        value="{{ old('preferred_location') }}"
                        class="w-full border {{ $errors->has('preferred_location') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="e.g., New York, NY or Remote"
                    >
                    @error('preferred_location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Results -->
                <div>
                    <label for="max_results" class="block text-sm font-semibold mb-2 text-gray-700">Max Ranking Results</label>
                    <input 
                        type="number" 
                        id="max_results" 
                        name="max_results" 
                        value="{{ old('max_results', 10) }}"
                        min="1"
                        max="50"
                        class="w-full border {{ $errors->has('max_results') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                    @error('max_results')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Posted Date -->
                <div>
                    <label for="posted_date" class="block text-sm font-semibold mb-2 text-gray-700">Posted Date</label>
                    <input 
                        type="date" 
                        id="posted_date" 
                        name="posted_date" 
                        value="{{ old('posted_date', date('Y-m-d')) }}"
                        class="w-full border {{ $errors->has('posted_date') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                    @error('posted_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                    <select 
                        id="status" 
                        name="status"
                        class="w-full border {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                        <option value="open" {{ old('status', 'open') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="reviewing" {{ old('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                        <option value="filled" {{ old('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Description -->
                <div class="md:col-span-2">
                    <label for="job_description" class="block text-sm font-semibold mb-2 text-gray-700">Job Description</label>
                    <textarea 
                        id="job_description" 
                        name="job_description" 
                        rows="6"
                        class="w-full border {{ $errors->has('job_description') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Describe the role, responsibilities, required skills, and qualifications..."
                    >{{ old('job_description') }}</textarea>
                    @error('job_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t">
                <button 
                    type="submit"
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    Create Job
                </button>
                <a 
                    href="{{ route('home') }}"
                    class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-semibold"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
