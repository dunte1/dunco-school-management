@extends('layouts.public')

@section('title', $module->seo_title ?: $module->name . ' - Dunco SMS')
@section('meta_description', $module->seo_description ?: $module->short_description)

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-24">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-300 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="flex-1 text-center lg:text-left">
                @if($module->icon)
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                        <i class="fas {{ $module->icon }} text-4xl text-blue-300"></i>
                    </div>
                @endif
                <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">
                    {{ $module->hero_title ?: $module->name }}
                </h1>
                @if($module->hero_subtitle)
                    <p class="text-xl text-blue-200 mb-8 max-w-xl mx-auto lg:mx-0">
                        {{ $module->hero_subtitle }}
                    </p>
                @endif
                <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                    <a href="{{ route('public.demo') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-900 font-bold rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-xl hover:shadow-2xl">
                        <i class="fas fa-play-circle me-2"></i> Request Demo
                    </a>
                    <a href="{{ route('public.contact') }}" class="inline-flex items-center px-8 py-4 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300">
                        <i class="fas fa-envelope me-2"></i> Contact Sales
                    </a>
                </div>
            </div>
            @if($module->screenshot_path)
                <div class="flex-1">
                    <img src="{{ asset($module->screenshot_path) }}" alt="{{ $module->name }}" class="rounded-2xl shadow-2xl w-full">
                </div>
            @endif
        </div>
    </div>
</section>

@if($module->description)
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">About {{ $module->name }}</h2>
            <p class="text-lg text-gray-600 leading-relaxed">{{ $module->description }}</p>
        </div>
    </div>
</section>
@endif

@if($module->features && count($module->features))
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-4">Features</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Everything You Need</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($module->features as $feature)
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                        <p class="text-gray-700 font-medium pt-1.5">{{ $feature }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($module->benefits && count($module->benefits))
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">Benefits</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Why Choose {{ $module->name }}</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($module->benefits as $benefit)
                <div class="flex items-start gap-4 p-6 bg-green-50 rounded-2xl">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-star text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-gray-800 font-semibold text-lg">{{ $benefit }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-20 bg-gradient-to-br from-blue-900 to-indigo-900 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Transform Your School?</h2>
        <p class="text-xl text-blue-200 mb-10 max-w-2xl mx-auto">
            Experience how the {{ $module->name }} module can streamline your school's operations. Schedule a personalized demo today.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ route('public.demo') }}" class="inline-flex items-center px-10 py-5 bg-white text-blue-900 font-bold rounded-xl hover:bg-blue-50 transition-all duration-300 shadow-xl hover:shadow-2xl text-lg">
                <i class="fas fa-play-circle me-3"></i> Request Free Demo
            </a>
            <a href="{{ route('public.contact') }}" class="inline-flex items-center px-10 py-5 border-2 border-white/30 text-white font-semibold rounded-xl hover:bg-white/10 transition-all duration-300 text-lg">
                <i class="fas fa-phone me-3"></i> Talk to Sales
            </a>
        </div>
    </div>
</section>
@endsection
