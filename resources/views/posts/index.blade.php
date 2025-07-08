@extends('layouts.blog')

@section('title', 'Laravel Blog Tutorial')
@section('description', 'Welcome to our Laravel blog tutorial. Learn Laravel and Livewire step by step.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            Welcome to Laravel Blog Tutorial
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Learn how to build a complete blog application with Laravel 11 and Livewire 3.
            Follow our step-by-step tutorials and master modern web development.
        </p>
    </div>

   <!-- Posts Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($posts as $post)
        @livewire('blog-post-card', ['post' => $post], key($post->id))
    @empty
        <div class="col-span-3 text-center py-12">
            <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
            <p class="text-gray-600">Check back later for new content!</p>
        </div>
    @endforelse
</div>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection