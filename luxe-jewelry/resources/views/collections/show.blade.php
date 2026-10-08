@extends('layouts.app')

@section('title', $collection->name . ' Collection — ' . $brandName)

@section('content')
    {{-- Hero --}}
    <section class="relative h-[58vh] min-h-[400px] overflow-hidden">
        <img src="{{ asset($collection->feature_image) }}" alt="{{ $collection->name }}" class="absolute inset-0 w-full h-full object-cover" data-hero-img>
        <div class="absolute inset-0 bg-black/45"></div>
        <div class="relative z-10 h-full flex flex-col items-center justify-center text-center text-white shell">
            <p class="eyebrow !text-white/70 mb-4 reveal" data-hero="1">Iconic Collection</p>
            <h1 class="font-display text-6xl md:text-8xl font-medium mb-4 reveal" data-hero="2">{{ $collection->name }}</h1>
            <p class="font-display italic text-2xl md:text-3xl text-white/85 max-w-2xl reveal" data-hero="3">{{ $collection->tagline }}</p>
        </div>
    </section>

    <div class="shell py-14 md:py-20">
        <nav class="text-[0.65rem] tracking-[0.2em] uppercase text-[var(--text-dim)] mb-10 text-center">
            <a href="{{ route('home') }}" class="link-underline">Home</a>
            <span class="mx-2 opacity-50">/</span>
            <a href="{{ route('collections.index') }}" class="link-underline">Collections</a>
            <span class="mx-2 opacity-50">/</span>
            <span class="text-[var(--text)]">{{ $collection->name }}</span>
        </nav>

        @if ($collection->description)
            <p class="text-center text-[var(--text-dim)] max-w-2xl mx-auto mb-14 leading-relaxed">{{ $collection->description }}</p>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-3 gap-y-10 md:gap-x-5 md:gap-y-14">
            @foreach ($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="mt-16">
            {{ $products->links('pagination.luxe') }}
        </div>
    </div>
@endsection
