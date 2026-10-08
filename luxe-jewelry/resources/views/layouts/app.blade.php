<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $brandName }} — iconic handcrafted jewelry. Necklaces, rings, earrings & bracelets in 18K gold and 925 silver.">
    <title>@yield('title', $brandName . ' — Iconic Jewelry')</title>

    {{-- Set theme before paint to avoid flash --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'light') document.documentElement.classList.add('light');
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>
