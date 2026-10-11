<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6A5AE0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=2">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <title>@yield('title', 'Beranda') · {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="@yield('body-class')">
    @yield('body')
    <x-flash />
    <x-confirm-modal />
    <script>
    // Cegah kirim ganda: formulir non-GET hanya boleh terkirim sekali; tombol dinonaktifkan sementara.
    (function () {
        const reset = (f) => { delete f.dataset.submitting; f.querySelectorAll('.is-busy').forEach((b) => { b.classList.remove('is-busy'); b.disabled = false; }); };
        document.addEventListener('submit', function (e) {
            const f = e.target;
            if (! (f instanceof HTMLFormElement) || (f.method || '').toLowerCase() === 'get' || e.defaultPrevented) return;
            if (f.dataset.submitting) { e.preventDefault(); return; }
            f.dataset.submitting = '1';
            setTimeout(() => {
                f.querySelectorAll('button:not([type=button]), input[type=submit]').forEach((b) => { b.classList.add('is-busy'); b.disabled = true; });
            }, 0);
            setTimeout(() => reset(f), 8000);   // pengaman bila respons tidak kunjung datang
        });
        window.addEventListener('pageshow', () => document.querySelectorAll('form').forEach(reset));
    })();
    </script>
    @stack('scripts')</body>
</html>
