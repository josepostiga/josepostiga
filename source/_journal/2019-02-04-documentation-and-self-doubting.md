---
extends: _layouts.journal
section: content
title: Documentation and self-doubting
date: 2019-02-04
cover_image: https://c10.patreonusercontent.com/3/eyJ3IjoxNjAwfQ%3D%3D/patreon-media/p/post/24468930/b24434b6c53f4168974e63ac945fa81a/7?token-time=1552176000&token-hash=msIhYDp4pT0CUqIGX6tZaW1JFNIWSmCZ820h19c4E28%3D
description: 
---

The main tasks assigned to me are associated with documenting and helping my teammates implement code sniffers and fixers, to help keep our codebase in line with the current PSR-2 standard. So, as part of that work, I've been updating our internal documentation with the steps required to implement the necessary tools and processes so that everyone can configure them on their IDE/Code Editor.

The tools we'll be using are PHP CodeSniffer (phpcs) and PHP Code Beautifier and Fixer (phpcbf) and, since we use Docker in development, I needed to make sure those commands can be used both for every IDE/Code Editor, currently in use on our team, and through the terminal.

It was a fun work to do, because I had to learn about how Sublime Text's Phpcs plugin works, which order of arguments it uses and use shell scripts to run the command on a Docker container. The same for Visual Studio Code, although for this one I learnt that there's no "native" script that could be used to handle the shell scripts so I had to find a workaround with "Save and Run" plugin, which executes commands after a file is saved.

I learnt how to properly use the dirname script to fetch the file to handle and map, as a volume, to a PHP docker container with PHP CodeSniffer installed. I thought it would be much harder than actually was.

In the end, I manage to see it quickly implemented on, at least, my team mate's Ricardo dev environment. It worked flawlessly. And, now, is fully documented, which will benefit any other new team member that'd join us, in the future. He/She just needs to follow the steps and have it properly configured in no time!

You probably have heard of this, before, but documentation is very important. It consumes a lot of time, for sure, but it helps not only to consolidate our knowledge but can be used as a reference for other people. It makes all the difference, be it whatever type of project. Document everything!

On another topic, I'd like to update you all on the HUB project. I had a little discussion with myself, regarding how to properly unit test a class that relies on relations with other classes.  All because I had some input, from a few colleagues, telling me that I should not test what I did not own. They were talking about the screenshot I shared on a previous post, where I was almost testing the Collection class' behaviour (which was a little overkill) but I transported that feedback to the work I was doing (testing the existence of a certain set of relations) and was doubting myself. Would it be best to do it or should I trust that the relations were there and fully functional?

After thinking about it, I got to the conclusion that it makes perfect sense to test, even in unit testing, that the required relations do, in fact, exist, and even that they return the right instance of the objects (e.g.: a Collection).

You might think I was too involved in details but, as the saying goes: "The Devil is in the details".