<div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
    <h3 class="text-xl font-bold mb-2">
        <a href="/candidates/{{ $candidate->candidate_id }}" class="text-gray-800 hover:text-blue-600 transition">
            {{ $candidate->full_name }}
        </a>
    </h3>
    <p class="text-sm text-gray-600 mb-2">{{ $candidate->headline ?? 'Candidate' }}</p>
    <div class="flex flex-wrap gap-2 text-xs">
        <span class="text-gray-600">📍 {{ $candidate->location ?? 'N/A' }}</span>
        <span class="px-2 py-1 rounded-full
            {{ $candidate->availability_status == 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}
        ">{{ ucfirst($candidate->availability_status ?? 'Unknown') }}</span>
    </div>
</div>
