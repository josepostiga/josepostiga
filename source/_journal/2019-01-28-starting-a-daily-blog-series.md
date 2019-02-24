---
extends: _layouts.journal
section: content
title: Starting a daily blog series
date: 2019-01-28
cover_image: https://images.unsplash.com/photo-1483546363825-7ebf25fb7513?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80
description: 
---

First of all, this is going to be a big challenge for me, to expose myself this way, but I think it'll be a great experience! I'm going to start creating a daily post about my professional experience.

So, today was testing day! Since we've finished our Sprint last Friday, we've been testing our software's golden paths (we call golden paths to the minimum functionality that must be totally functional at every release) to prepare for another release. So far, so good. We catch minor bugs but those were resolved quickly.

Since all the team had their own set of QA tasks, and after I've done mine, I put my focus on my goal of creating the necessary documentation to allow my teammates to configure a normalised set of coding style rules (PSR-2) on their text editor/IDE. We don't force anyone to use specific software, so some use Sublime Text, others (especially the frontend devs) use Visual Studio Code and others, like me, use PHPstorm. Yeah, that's going to be a challenge since my main objective is to make some kind of automatic style check and correction using our Docker containers. That means that PHP CS Fixer should run within a Docker container, fix the files in sync with the host system. So far, especially with the text editors, that seems almost impossible (it's probably a lack of knowledge).

I hope tomorrow I can pass that difficulty and finish the documentation in time for the new sprint planning.