@extends('layouts.landing')

@php
    $metaTitle = $page->meta_title ?? $page->title;
    $metaDescription = $page->meta_description;
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
    @foreach($sections as $section)
        @if(view()->exists("landing.sections.{$section->type}"))
            @include("landing.sections.{$section->type}", ['section' => $section])
        @else
            <!-- Missing view for section type: {{ $section->type }} -->
            <div class="py-10 text-center text-slate-400 bg-slate-50 border-y border-slate-100">
                <p>Section type [{{ $section->type }}] is configured but view partial is missing.</p>
            </div>
        @endif
    @endforeach
@endsection