@if (file_exists(public_path('images/farmago-logo.png')))
    <img src="{{ asset('images/farmago-logo.png') }}" alt="{{ config('app.name', 'FarmaGo') }}" {{ $attributes->merge(['class' => trim(($attributes->get('class') ?: '') . ' farmago-app-logo object-contain')]) }} />
@else
    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        <rect x="8" y="8" width="48" height="48" rx="14" fill="currentColor" opacity="0.12"/>
        <path fill="currentColor" d="M39.9 14.8c5.2 3 7 9.7 4 14.9l-8.2 14.2c-3 5.2-9.7 7-14.9 4s-7-9.7-4-14.9L25 18.8c3-5.2 9.7-7 14.9-4Zm-9.2 8.1-8.2 14.2c-1.5 2.6-.6 6 2 7.5 2.6 1.5 6 .6 7.5-2l2.7-4.7-9.5-5.5 3.5-6.1 9.5 5.5 2-3.5c1.5-2.6.6-6-2-7.5-2.6-1.5-6-.6-7.5 2.1Z"/>
        <path fill="currentColor" d="M47 41h-5v-5h-5v5h-5v5h5v5h5v-5h5v-5Z"/>
    </svg>
@endif
