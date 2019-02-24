<div class="flex flex-col mb-6">
    @if ($article->cover_image)
        <img src="{{ $article->cover_image }}" alt="{{ $article->title }} cover image" class="mb-2 w-full">
    @endif

    <h2 class="text-3xl mt-1 mb-2">
        <a href="{{ $article->getUrl() }}" title="Read more - {{ $article->title }}" class="text-black font-extrabold">
            {{ $article->title }}
        </a>
    </h2>

    <p class="text-grey-darker font-medium my-2">
        {{ $article->getDate()->format('d/m/Y') }}
    </p>

    <p class="mb-4 mt-0">{!! $article->getExcerpt(200) !!}</p>

    <a href="{{ $article->getUrl() }}" title="Read more - {{ $article->title }}" class="uppercase font-semibold tracking-wide mb-2">
        Read
    </a>
</div>
