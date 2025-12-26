@extends('layouts.site')

@section('content')
    @foreach($page->config['blocks'] ?? [] as $block)
        <div id="{{ $block['id'] }}">
            @includeIf('components.site.' . str_replace('_', '-', $block['type']), ['data' => $block['data']])
        </div>
    @endforeach
@endsection