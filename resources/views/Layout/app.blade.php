<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="night">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="صدى — منصة لمشاركة الاقتباسات. كلمات يبقى صداها.">
    <title>@yield('title', 'كلمات يبقى صداها') · صدى</title>

    <script>
        try {
            document.documentElement.dataset.theme = localStorage.getItem('sada-theme') || 'night';
        } catch (e) {}
    </script>

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'><circle cx='16' cy='16' r='5' fill='%234FD1C5'/><circle cx='16' cy='16' r='10' fill='none' stroke='%234FD1C5' stroke-width='2' opacity='.6'/><circle cx='16' cy='16' r='15' fill='none' stroke='%234FD1C5' stroke-width='1.5' opacity='.3'/></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Reem+Kufi:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">
    <div class="ambient" aria-hidden="true"></div>

    <x-navbar :overlay="$overlayNav ?? false" />

    <div class="toast-stack" id="toasts" aria-live="polite">
        @if (session('status'))
            <div class="toast" data-auto-dismiss>✨ {{ session('status') }}</div>
        @endif
    </div>

    <main class="flex-1">
        @yield('content')
    </main>

    <x-footer />

    @auth
        <button type="button" class="fab" data-open-modal="share-modal" aria-label="شارك اقتباساً" title="شارك اقتباساً">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
        </button>
        <x-share-modal />
    @endauth

    <x-image-modal />
</body>

</html>
