@if (session('success') || session('error') || session('warning') || session('info'))
    <div x-data="{ show: true }"
         x-show="show"
         x-transition
         x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-6 right-6 z-50 flex items-center p-6 rounded-lg shadow-lg min-w-[320px]
         {{-- Dynamic background colors --}}
         {{ session('success') ? 'bg-secondary/10 text-secondary-dark dark:text-secondary-light border border-secondary/20' : '' }}
         {{ session('error') ? 'bg-primary/10 text-primary-dark dark:text-primary-light border border-primary/20' : '' }}
         {{ session('warning') ? 'bg-yellow-100/80 text-yellow-800 dark:text-yellow-200 border border-yellow-200' : '' }}
         {{ session('info') ? 'bg-blue-100/80 text-blue-800 dark:text-blue-200 border border-blue-200' : '' }}"
         role="alert">
        <div class="flex items-center gap-3">
            {{-- Success Icon --}}
            @if(session('success'))
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @endif

            {{-- Error Icon --}}
            @if(session('error'))
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @endif

            {{-- Warning Icon --}}
            @if(session('warning'))
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            @endif

            {{-- Info Icon --}}
            @if(session('info'))
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @endif

            <div class="text-lg font-medium">
                {{ session('success') ?? session('error') ?? session('warning') ?? session('info') }}
            </div>
        </div>

        <button type="button"
            @click="show = false"
            class="ms-auto -mx-2 -my-2 rounded-lg focus:ring-2 p-2 inline-flex items-center justify-center h-10 w-10
            {{ session('success') ? 'focus:ring-secondary hover:bg-secondary/20' : '' }}
            {{ session('error') ? 'focus:ring-primary hover:bg-primary/20' : '' }}
            {{ session('warning') ? 'focus:ring-yellow-400 hover:bg-yellow-200/50' : '' }}
            {{ session('info') ? 'focus:ring-blue-400 hover:bg-blue-200/50' : '' }}"
            aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    </div>
@endif
