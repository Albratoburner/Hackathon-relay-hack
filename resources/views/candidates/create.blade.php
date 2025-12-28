@extends('layouts.app')

@section('title', 'Create Candidate - RealyHack')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Add New Candidate</h1>
        <p class="text-gray-600">Enter candidate information to add them to the system.</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 lg:p-8 max-w-4xl">
        <form method="POST" action="{{ route('candidates.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label for="full_name" class="block text-sm font-semibold mb-2 text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        value="{{ old('full_name') }}"
                        required
                        class="w-full border {{ $errors->has('full_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="e.g., John Doe"
                    >
                    @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold mb-2 text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="john.doe@example.com"
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold mb-2 text-gray-700">Phone</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        value="{{ old('phone') }}"
                        class="w-full border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="+1 (555) 123-4567"
                    >
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-semibold mb-2 text-gray-700">Location <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="location" 
                        name="location" 
                        value="{{ old('location') }}"
                        required
                        class="w-full border {{ $errors->has('location') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="e.g., San Francisco, CA"
                    >
                    @error('location')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Availability Type -->
                <div>
                    <label for="availability_type" class="block text-sm font-semibold mb-2 text-gray-700">Availability Type <span class="text-red-500">*</span></label>
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

                <!-- Total Experience Years -->
                <div>
                    <label for="total_experience_years" class="block text-sm font-semibold mb-2 text-gray-700">Total Experience (years) <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        id="total_experience_years" 
                        name="total_experience_years" 
                        value="{{ old('total_experience_years', 0) }}"
                        required
                        min="0"
                        class="w-full border {{ $errors->has('total_experience_years') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                    @error('total_experience_years')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label for="is_active" class="block text-sm font-semibold mb-2 text-gray-700">Status</label>
                    <select 
                        id="is_active" 
                        name="is_active"
                        class="w-full border {{ $errors->has('is_active') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                    >
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="md:col-span-2">
                    <label for="bio" class="block text-sm font-semibold mb-2 text-gray-700">Bio / Summary</label>
                    <textarea 
                        id="bio" 
                        name="bio" 
                        rows="5"
                        class="w-full border {{ $errors->has('bio') ? 'border-red-500' : 'border-gray-300' }} rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"
                        placeholder="Brief professional summary, key skills, and experience highlights..."
                    >{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-700">
                    <strong>Note:</strong> After creating the candidate, you can add their skills and certifications from their profile page.
                </p>
            </div>

            <div class="flex gap-4 pt-4 border-t">
                <button 
                    type="submit"
                    class="bg-green-600 text-white px-8 py-3 rounded-lg hover:bg-green-700 transition font-semibold"
                >
                    Create Candidate
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
