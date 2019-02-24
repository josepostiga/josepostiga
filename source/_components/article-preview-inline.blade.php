<div class="flex flex-col mb-4">
    <p class="text-grey-darker font-medium my-2">
        {{ $article->getDate()->format('F j, Y') }}
    </p>

    <h2 class="text-3xl mt-0">
        <a href="{{ $article->getUrl() }}" title="Read more - {{ $article->title }}" class="text-black font-extrabold">
            {{ $article->title }}
        </a>
    </h2>

    <p class="mb-4 mt-0">{!! $article->getExcerpt(200) !!}</p>

    <a href="{{ $article->getUrl() }}" title="Read more - {{ $article->title }}" class="uppercase font-semibold tracking-wide mb-2">
        Read
    </a>
</div>
