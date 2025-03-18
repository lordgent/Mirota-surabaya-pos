<div {{ $attributes->merge(['class' => 'flex items-center space-x-2 text-blue-600']) }}>
    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
         viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8v8H4z"></path>
    </svg>
    <span>{{ $slot ?? 'Loading...' }}</span>
</div>
