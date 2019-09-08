<div class="flex flex-col mb-6">
    @if ($article->cover_image)
        <img src="{{ $article->cover_image }}" alt="{{ $article->title }} cover image" class="mb-2 w-full">
    @endif

    <h2 class="text-3xl mt-1 mb-1 flex flex-row">
        <span class="text-2xl text-grey-darker pt-1 pr-4">{{ $article->getDate()->format('d/m/Y') }}</span>

        <a href="{{ $article->getUrl() }}" title="Read more - {{ $article->title }}" class="text-black font-extrabold">
            {{ $article->title }}
        </a>
    </h2>

    <p class="text-xl text-grey-darker mt-0">{{ $article->description }}</p>
</div>
