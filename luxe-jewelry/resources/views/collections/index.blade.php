@extends('layouts.app')

@section('title', 'Collections — ' . $brandName)

@section('content')
    <section class="pt-14 md:pt-20 pb-12 text-center">
        <div class="shell">
            <p class="eyebrow mb-4">Iconic Collections</p>
            <h1 class="font-display text-5xl md:text-7xl font-medium mb-4">The House of {{ $brandName }}</h1>
            <p class="text-[var(--text-dim)] max-w-xl mx-auto">Each collection tells its own story — handcrafted in Mallorca, designed to be layered and lived in.</p>
        </div>
    </section>

    <div class="shell pb-24 space-y-4">
        @foreach ($collections as $col)
            <a href="{{ route('collections.show', $col) }}"
               class="group relative block h-[56vh] min-h-[380px] overflow-hidden reveal" data-reveal>
                <img src="{{ asset($col->feature_image) }}" alt="{{ $col->name }}" loading="lazy"
                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1100ms] ease-out group-hover:scale-105">
                <div class="absolute inset-0 bg-black/40 group-hover:bg-black/30 transition-colors"></div>
                <div class="relative z-10 h-full flex flex-col items-center justify-center text-center text-white px-6">
                    <p class="eyebrow !text-white/70 mb-3">{{ $col->products_count }} {{ Str::plural('piece', $col->products_count) }}</p>
                    <h2 class="font-display text-5xl md:text-7xl mb-3">{{ $col->name }}</h2>
                    <p class="font-display italic text-xl md:text-2xl text-white/80 mb-6">{{ $col->tagline }}</p>
                    <span class="btn !text-white !border-white/70 group-hover:!bg-white group-hover:!text-black">Discover</span>
                </div>
            </a>
        @endforeach
    </div>
@endsection
