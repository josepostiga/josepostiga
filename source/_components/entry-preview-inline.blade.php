<div class="flex flex-col mb-6">
    @if ($journal->cover_image)
        <img src="{{ $journal->cover_image }}" alt="{{ $journal->title }} cover image" class="mb-2 w-full">
    @endif

    <h2 class="text-3xl mt-1 mb-2">
        <a href="{{ $journal->getUrl() }}" title="Read more - {{ $journal->title }}" class="text-black font-extrabold">
            {{ $journal->title }}
        </a>
    </h2>

    <p class="text-grey-darker font-medium my-2">
        {{ $journal->getDate()->format('d/m/Y') }}
    </p>

    <p class="mb-4 mt-0">{!! $journal->getExcerpt(200) !!}</p>

    <a href="{{ $journal->getUrl() }}" title="Read more - {{ $journal->title }}" class="uppercase font-semibold tracking-wide mb-2">
        Read
    </a>
</div>
