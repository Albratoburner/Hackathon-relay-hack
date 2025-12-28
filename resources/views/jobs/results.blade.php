@extends('layouts.app')

@section('title', 'Ranking Results - ' . $job->job_title)

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">Ranking Results</h1>
        <p class="text-gray-600">
            Job: <a href="/jobs/{{ $job->job_id }}" class="text-blue-600 hover:underline">{{ $job->job_title }}</a> 
            (Max Results: {{ $maxResults }})
        </p>
    </div>
    
    @if(count($results) > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                <span class="font-semibold text-gray-800">{{ count($results) }} candidates ranked</span>
                <button 
                    onclick="exportToCSV()"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold transition"
                >
                    Export CSV
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="results-table">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">#</th>
                            <th class="px-4 py-3 text-left font-semibold">Candidate</th>
                            <th class="px-4 py-3 text-right font-semibold">Total Score</th>
                            <th class="px-4 py-3 text-right font-semibold">Skills</th>
                            <th class="px-4 py-3 text-right font-semibold">Experience</th>
                            <th class="px-4 py-3 text-right font-semibold">Availability</th>
                            <th class="px-4 py-3 text-right font-semibold">Location</th>
                            <th class="px-4 py-3 text-right font-semibold">Cultural Fit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $index => $candidate)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-semibold text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <a href="/candidates/{{ $candidate->candidate_id }}" class="text-blue-600 hover:underline font-medium">
                                        {{ $candidate->full_name }}
                                    </a>
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-gray-800">{{ number_format($candidate->total_score, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($candidate->skill_score, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($candidate->experience_score, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($candidate->availability_score, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($candidate->location_score, 2) }}</td>
                                <td class="px-4 py-3 text-right text-gray-700">{{ number_format($candidate->cultural_fit_score, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="flex gap-4">
            <a href="/jobs/{{ $job->job_id }}" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold transition">
                ← Back to Job
            </a>
        </div>
        
        <script>
        function exportToCSV() {
            const table = document.getElementById('results-table');
            const rows = Array.from(table.querySelectorAll('tr'));
            const csv = rows.map(row => {
                const cells = Array.from(row.querySelectorAll('th, td'));
                return cells.map(cell => `"${cell.textContent.trim()}"`).join(',');
            }).join('\n');
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'ranking_results_{{ $job->job_id }}_{{ date("Y-m-d") }}.csv';
            a.click();
            URL.revokeObjectURL(url);
        }
        </script>
    @else
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded" role="alert">
            <p class="font-semibold">No candidates matched the ranking criteria.</p>
            <p class="text-sm mt-2">Try adjusting the required skills or increasing max results.</p>
        </div>
        <a href="/jobs/{{ $job->job_id }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 font-semibold transition">
            ← Try Again
        </a>
    @endif
</div>
@endsection
