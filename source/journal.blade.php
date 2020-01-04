---
pagination:
    collection: journal
    perPage: 4
---
@extends('_layouts.master')

@push('meta')
    <meta property="og:title" content="{{ $page->siteName }} Journal" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $page->getUrl() }}"/>
    <meta property="og:description" content="The journal for {{ $page->siteName }}" />
@endpush

@section('body')
    <div class="flex flex-wrap">
        @foreach ($pagination->items as $journal)
            @include('_components.entry-preview-inline')
        @endforeach
    </div>

    @if ($pagination->pages->count() > 1)
        <nav class="flex text-base my-8">
            @if ($previous = $pagination->previous)
                <a href="{{ $previous }}" title="Previous Page" class="bg-grey-lighter hover:bg-grey-light rounded mr-3 px-5 py-3">&LeftArrow;</a>
            @endif

            @foreach ($pagination->pages as $pageNumber => $path)
                <a href="{{ $path }}" title="Go to Page {{ $pageNumber }}" class="bg-grey-lighter hover:bg-grey-light text-blue-darker rounded mr-3 px-5 py-3 {{ $pagination->currentPage == $pageNumber ? 'text-blue-dark' : '' }}">
                    {{ $pageNumber }}
                </a>
            @endforeach

            @if ($next = $pagination->next)
                <a href="{{ $next }}" title="Next Page" class="bg-grey-lighter hover:bg-grey-light rounded mr-3 px-5 py-3">&RightArrow;</a>
            @endif
        </nav>
    @endif
@stop
