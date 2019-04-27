@extends('_layouts.master')

@section('body')
    <div class="block overflow-hidden">
        <h1>Hello!</h1>

        <img src="https://gravatar.com/avatar/{{ md5('josepostiga1990@gmail.com') }}?s=250" alt="José Postiga" class="flex rounded-full h-64 w-64 bg-contain mx-auto md:float-right my-2 md:ml-10">

        <p class="mb-6">I'm <a href="https://twitter.com/josepostiga" target="_blank">@josepostiga</a>, a Senior Backend Developer at <a href="https://twitter.com/infraspeak" target="_blank">@infraspeak</a> by day, co-host on <a href="https://twitter.com/LaravelPortugal" target="_blank">@LaravelPortugal</a> Podcast and a contributor on various <a href="https://github.com/josepostiga" target="_blank">Open Source Software projects</a> by night!</p>

        <p class="mb-6">I've been working with web related technologies since 2008 and I'm experient with Symfony, Laravel and CodeIgniter (PHP), Bootstrap, TailwindCSS, Bulma, jQuery, Vue (HTML/CSS, JS) and AdonisJS (NodeJS). You can see my career history at <a href="https://www.linkedin.com/in/josepostiga/" target="_blank">LinkedIn</a>. </p>

        <p class="mb-6">Besides that, I like to write about <a href="/articles">tech/web/programming</a> topics, I talk a lot about my daily work on my <a href="/journal">journal</a>. Besides, I occasionally share pictures on my <a href="https://instagram.com/jose.postiga" target="_blank">Instagram</a>.</p>

        <p class="mb-6">You can contact me, directly, via <a href="https://t.me/josepostiga" target="_blank" rel="nofollow">Telegram</a>.</p>
    </div>

    <hr>

    <div class="flex flex-col md:flex-row">
        <div class="flex-1 col-6 pr-0 md:pr-3">
            <h3>Latest article</h3>

            <div class="flex flex-col mb-6">
                @if ($articles->first()->cover_image)
                    <img src="{{ $articles->first()->cover_image }}" alt="{{ $articles->first()->title }} cover image" class="mb-2 w-full">
                @endif

                <h3 class="text-xl mt-1 mb-2 flex flex-row">
                    <span class="text-sm text-grey-darker pt-1 pr-4">{{ $articles->first()->getDate()->format('d/m/Y') }}</span>

                    <a href="{{ $articles->first()->getUrl() }}" title="Read more - {{ $articles->first()->title }}" class="text-black font-extrabold">
                        {{ $articles->first()->title }}
                    </a>
                </h3>
            </div>
        </div>

        <div class="flex-1 col-6 pl-0 md:pl-3">
            <h3>Latest journal entry</h3>

            <div class="flex flex-col mb-6">
                @if ($journal->first()->cover_image)
                    <img src="{{ $journal->first()->cover_image }}" alt="{{ $journal->first()->title }} cover image" class="mb-2 w-full">
                @endif

                <h3 class="text-xl mt-1 mb-2 flex flex-row">
                    <span class="text-sm text-grey-darker pt-1 pr-4">{{ $journal->first()->getDate()->format('d/m/Y') }}</span>

                    <a href="{{ $journal->first()->getUrl() }}" title="Read more - {{ $journal->first()->title }}" class="text-black font-extrabold">
                        {{ $journal->first()->title }}
                    </a>
                </h3>
            </div>
        </div>
    </div>
@stop
