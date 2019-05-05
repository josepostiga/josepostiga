<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="{{ $page->meta_description ?? $page->siteDescription }}">

        <meta property="og:title" content="{{ $page->title ?  $page->title . ' | ' : '' }}{{ $page->siteName }}"/>
        <meta property="og:type" content="website" />
        <meta property="og:url" content="{{ $page->getUrl() }}"/>
        <meta property="og:description" content="{{ $page->siteDescription }}" />

        <title>{{ $page->siteName }}{{ $page->title ? ' | ' . $page->title : '' }}</title>

        <link rel="home" href="{{ $page->baseUrl }}">
        <link rel="icon" href="/favicon.ico">
        <link href="/blog/feed.atom" type="application/atom+xml" rel="alternate" title="{{ $page->siteName }} Atom Feed">

        @stack('meta')

        <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,300i,400,400i,700,700i,800,800i" rel="stylesheet">
        <link rel="stylesheet" href="{{ mix('css/main.css', 'assets/build') }}">
    </head>

    <body class="flex flex-col justify-between min-h-screen bg-grey-lightest text-grey-darkest leading-normal font-sans">
        <header class="flex items-center shadow bg-white border-b h-24 py-4" role="banner">
            <div class="container flex items-center max-w-xl mx-auto px-6">
                <nav class="lg:flex items-center justify-end text-sm md:text-lg">
                    <a title="{{ $page->siteName }}" href="/" class="mr-5 text-grey-darker hover:text-blue-dark {{ $page->isActive('/') ? 'active text-blue-dark' : '' }}">
                        About
                    </a>

                    <a title="{{ $page->siteName }} Journal" href="/journal" class="mr-5 text-grey-darker hover:text-blue-dark {{ $page->isActive('/journal') ? 'active text-blue-dark' : '' }}">
                        Journal
                    </a>

                    <a title="{{ $page->siteName }} Articles" href="/articles" class="mr-5 text-grey-darker hover:text-blue-dark {{ $page->isActive('/articles') ? 'active text-blue-dark' : '' }}">
                        Articles
                    </a>
                </nav>

                <div id="vue-search" class="flex flex-1 justify-end items-center">
                    <search></search>
                </div>
            </div>
        </header>

        <main role="main" class="flex-auto w-full container max-w-xl mx-auto mt-5 py-6 px-6">
            @yield('body')
        </main>

        <footer class="bg-white text-center text-sm mt-5 py-4" role="contentinfo">
            <ul class="flex flex-col md:flex-row justify-center list-reset">
                <li class="md:mr-2">
                    &copy; José Postiga {{ date('Y') }}.
                </li>

                <li>
                    Built with <a href="http://jigsaw.tighten.co" title="Jigsaw by Tighten">Jigsaw</a>
                    and <a href="https://tailwindcss.com" title="Tailwind CSS, a utility-first CSS framework">Tailwind CSS</a>.
                    Theme &copy; <a href="https://tighten.co" title="Tighten website">Tighten</a>
                </li>
            </ul>
        </footer>

        <script src="{{ mix('js/main.js', 'assets/build') }}"></script>

        @stack('scripts')
    </body>
</html>
