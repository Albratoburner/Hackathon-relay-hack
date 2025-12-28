<nav class="bg-white shadow-lg" x-data="{ open: false }">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-2">
                <a href="/" class="text-2xl font-bold text-blue-600">RealyHack</a>
            </div>
            
            <!-- Desktop menu -->
            <div class="hidden md:flex space-x-6">
                <a href="/" class="text-gray-700 hover:text-blue-600 transition {{ request()->is('/') ? 'font-bold text-blue-600' : '' }}">Dashboard</a>
                <a href="/jobs" class="text-gray-700 hover:text-blue-600 transition {{ request()->is('jobs*') ? 'font-bold text-blue-600' : '' }}">Jobs</a>
                <a href="/candidates" class="text-gray-700 hover:text-blue-600 transition {{ request()->is('candidates*') ? 'font-bold text-blue-600' : '' }}">Candidates</a>
            </div>
            
            <!-- Mobile toggle -->
            <button @click="open = !open" class="md:hidden focus:outline-none focus:ring-2 focus:ring-blue-600 rounded p-2" aria-label="Toggle menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
        
        <!-- Mobile menu -->
        <div x-show="open" x-cloak class="md:hidden pb-4">
            <a href="/" class="block py-2 text-gray-700 hover:text-blue-600 transition {{ request()->is('/') ? 'font-bold text-blue-600' : '' }}">Dashboard</a>
            <a href="/jobs" class="block py-2 text-gray-700 hover:text-blue-600 transition {{ request()->is('jobs*') ? 'font-bold text-blue-600' : '' }}">Jobs</a>
            <a href="/candidates" class="block py-2 text-gray-700 hover:text-blue-600 transition {{ request()->is('candidates*') ? 'font-bold text-blue-600' : '' }}">Candidates</a>
        </div>
    </div>
</nav>

<style>
[x-cloak] { display: none !important; }
</style>
