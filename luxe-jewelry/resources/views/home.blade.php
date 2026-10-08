@extends('layouts.app')

@section('content')
    @include('partials.hero')
    @include('partials.press')
    @include('partials.categories')
    @include('partials.intention')
    @include('partials.carousel', [
        'eyebrow' => 'Shop the edit',
        'title' => 'Essentials',
        'items' => $essentials,
        'id' => 'essentials',
        'viewAll' => route('shop.index'),
    ])
    @include('partials.collections')
    @include('partials.carousel', [
        'eyebrow' => 'Just landed',
        'title' => 'New In',
        'items' => $newIn,
        'id' => 'new-in',
        'viewAll' => route('shop.index', ['sort' => 'newest']),
    ])
    @include('partials.materials')
    @include('partials.reviews')
    @include('partials.story')
@endsection
