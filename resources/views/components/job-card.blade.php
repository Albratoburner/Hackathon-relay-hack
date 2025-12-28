<div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
    <div class="mb-2">
        <span class="px-2 py-1 rounded text-xs font-semibold
            {{ $job->status == 'open' ? 'bg-green-100 text-green-800' : '' }}
            {{ $job->status == 'filled' ? 'bg-gray-100 text-gray-800' : '' }}
            {{ $job->status == 'reviewing' ? 'bg-yellow-100 text-yellow-800' : '' }}
        ">{{ ucfirst($job->status) }}</span>
    </div>
    <h3 class="text-xl font-bold mb-2">
        <a href="/jobs/{{ $job->job_id }}" class="text-gray-800 hover:text-blue-600 transition">
            {{ $job->job_title }}
        </a>
    </h3>
    <p class="text-sm text-gray-600 mb-3">
        📍 {{ $job->preferred_location ?? 'Remote' }}
    </p>
    <p class="text-xs text-gray-500">
        Posted {{ $job->posted_date ? \Carbon\Carbon::parse($job->posted_date)->diffForHumans() : 'N/A' }}
    </p>
</div>
