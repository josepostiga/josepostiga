<div class="flex w-full md:w-1/2 flex-col md:px-3 pb-6">
    @if ($journal->cover_image)
        <div>
            <img src="{{ $journal->cover_image }}" alt="{{ $journal->title }} cover image" class="object-cover w-full">
        </div>
    @endif

    <h2 class="text-xl mt-1 mb-1 flex flex-col">
        <span class="text-xs text-grey-darker pt-1 pb-1">{{ $journal->getDate()->format('d/m/Y') }}</span>

        <a href="{{ $journal->getUrl() }}" title="Read more - {{ $journal->title }}" class="text-black font-extrabold">
            {{ $journal->title }}
        </a>
    </h2>

    <p class="text-base text-grey-darker mt-0">{{ $journal->description }}</p>
</div>
