---
pagination:
    collection: journal
    perPage: 6
---
@extends('_layouts.master')

@section('body')
    <div class="block overflow-hidden">
        <h2 class="text-3xl mt-1 mb-1 flex flex-col md:flex-row md:items-center">
            Practical Docker For Developers
            <div class="flex items-center md:ml-2 mt-2 md:mt-0">
                <span class="inline-block bg-blue-light text-xs text-white mr-2 px-2 py-1 rounded-full font-semibold tracking-wide">
                    year/2020
                </span>
                <span class="inline-block bg-grey text-xs text-white mr-2 px-2 py-1 rounded-full font-semibold tracking-wide">
                    status/WIP
                </span>
            </div>
        </h2>

        <h4 class="leading-none text-2xl mt-5 mb-1">Abstract</h4>

        <p>
            Docker is a very well-known technology that enables developers to develop secure, containerized and shareable applications to any infrastructure provider, by abstracting away infrastructure-related configurations.
            However, configuring and maintaining a development environment is not as simple and straightforward as advertised, especially if the developers want to work with several projects (which normally they do).
        </p>

        <p>
            In this 30 minutes(-ish) talk, I share a lot of actionable advice on how to configure a local Docker setup that enables developers to work on as many projects as they need in a simple and practical way.
            Complex topics like using development domain names, automatic services' discovering and request routing configuration, amongst others, will be things of the past.
        </p>

        <h5 class="leading-none mt-5 mb-1">Conferences</h5>

        <ul>
            <li class="flex md:items-center mb-2 flex-col md:flex-row">
                <div>
                    <small class="mr-2">23/05/2020</small>
                    <a href="https://devday.io/" target="_blank" rel="nofollow">Faro DevDay</a>
                </div>
                <span class="inline-block bg-orange-light text-xs text-white ml-2 px-2 py-1 rounded-full font-semibold">
                    submitted
                </span>
            </li>
            <li class="flex md:items-center mb-2 flex-col md:flex-row">
                <div>
                    <small class="mr-2">06/06/2020</small>
                    <a href="https://commitporto.com/" target="_blank" rel="nofollow">CommitPorto</a>
                </div>
                <span class="inline-block bg-orange-light text-xs text-white ml-2 px-2 py-1 rounded-full font-semibold">
                    submitted
                </span>
            </li>
            <li class="flex md:items-center mb-2 flex-col md:flex-row">
                <div>
                    <small class="mr-2">03/10/2020</small>
                    <a href="#" target="_blank" rel="nofollow">SPEAKING ABOUT - WEB & MOBILE DEVELOPMENT</a>
                </div>
                <span class="inline-block bg-green-light text-xs text-white ml-2 px-2 py-1 rounded-full font-semibold">
                    accepted
                </span>
            </li>
            <li class="flex md:items-center mb-2 flex-col md:flex-row">
                <div>
                    <small class="mr-2">--/--/2020</small>
                    <a href="https://techinporto.com/" target="_blank" rel="nofollow">TechInPorto</a>
                </div>
                <span class="inline-block bg-grey text-xs text-white ml-2 px-2 py-1 rounded-full font-semibold">
                    waiting CFP
                </span>
            </li>
        </ul>
    </div>
@stop
