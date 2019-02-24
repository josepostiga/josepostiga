@extends('_layouts.master')

@section('body')
    <div class="block overflow-hidden mb-12">
        <h1>Hello!</h1>

        <img src="https://gravatar.com/avatar/{{ md5('josepostiga1990@gmail.com') }}?s=250" alt="José Postiga" class="flex rounded-full h-64 w-64 bg-contain mx-auto md:float-right my-2 md:ml-10">

        <p class="mb-6">I'm <a href="https://twitter.com/josepostiga" target="_blank">@josepostiga</a>, a Senior Backend Developer at <a href="https://twitter.com/infraspeak" target="_blank">@infraspeak</a> by day, co-host on <a href="https://twitter.com/LaravelPortugal" target="_blank">@LaravelPortugal</a> Podcast and a contributor on various <a href="https://github.com/josepostiga" target="_blank">Open Source Software projects</a> by night!</p>

        <p class="mb-6">I've been working with web related technologies since 2008 and I'm experient with Symfony, Laravel and CodeIgniter (PHP), Bootstrap, TailwindCSS, Bulma, jQuery, Vue (HTML/CSS, JS) and AdonisJS (NodeJS).</p>

        <p class="mb-6">Besides that, I like to write about <a href="/articles">tech/web/programming</a> topics and I share a lot of my work day on my <a href="/journal">journal</a>.</p>
    </div>
@stop
