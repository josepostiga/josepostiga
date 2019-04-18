---
extends: _layouts.journal
section: content
title: I finally launched a proper blog!
date: 2019-03-03
cover_image: https://images.unsplash.com/1/irish-hands.jpg?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1000&q=80
description: "After so many years without a dedicated blog platform, I thought it was time to launch one."
---

After so many years without a dedicated blog platform, I thought it was time to launch one. Until now, I've relied on platforms like Wordpress, Medium, Patreon and alike, which have been useful so far. Using a third-party platform is both convenient and cheap. For instance, using the Wordpress or Medium platform allows for bigger exposure of my work, and is free to host.

However, lately, I've noticed some problems to continue with this approach, with some of these services changing their _modus operandis_, pushing for the readers to pay to continue reading of selected articles or, even worse, being fully censured and blocked from being accessible from readers of some countries.

This was something I was not to be a part of, and like many of some authors I follow did, so did I came to a plan to transfer my work to my own domain and keep full control of how it's distributed and accessed.

Since I have no eye for design, and I really liked how practical those platforms made publishing articles and keep my journal up-to-date, I started looking to self-hosted alternatives to those platforms. There were two main options:
1. **Wink**, by <a href="https://themsaid.com/" target="_blank" rel="nofollow">Mohamed Said</a>, which is an open-sourced blog platform based on the Laravel framework, which replicates the Medium blog platform in a simplistic, and beautiful, way.
2. **Wordpress**, which you all should know.

Of these two, I was more inclined to use **Wink**, for the simplicity and awesome editor (one of the things I liked most on Medium). However, Wink requires to be installed on a Laravel project so I needed to maintain a framework, update and find a server for basic support. That's a lot of work just for a simple, and low traffic, blog and I still needed to work on a design, which I know would take me a lot of time and effort to come up with. So I kept looking for good alternatives. In the middle of that investigation, I remembered that there were two other platforms that I've encountered in the past that I haven't considered before: **Statamic** and **Jigsaw**.

Statamic looked pretty slick, but it required a license, while Jigsaw was free. That's a point in favor of Jigsaw. Next, I looked at both platform's documentation. Statamic is more of a CMS and looked really nice but I wouldn't need a lot of the features it supports. That and the license fee made me look at Jigsaw with more detail, to see if it could be the one I needed to support my blog. I was in for a treat...

By analyzing the Jigsaw's platform, it had everything I needed and in the right dosage:
* Has a default template, which I could modify to my needs.
* Doesn't require to code any kind of admin area to manage the content, because it uses `MD` and `blade` files that compile to HTML.

It took me only 8 hours of work to go from zero to published! Really, it was that fast, and I'm also counting the time it took to read the documentation. It was a very good developer experience. And on top of everything, since it compiles to static files, it was a perfect candidate to be used with the GitHub pages feature, which is free! So, after setting up my domain to point to the Github's DNS servers, I had a full blog platform. I just needed to write a new post in the form of an `MD` file, run the compile command and push the compiled directory to the GH-pages branch of the repository I use for the blog. In no time I had a blog available to be accessed. Total costs per month: 0€. 

**But it wasn't over, yet.**

After I finished publishing my new blog, like a good developer I am, I went to publicize it to Twitter:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">After so many years without a personal <a href="https://twitter.com/hashtag/website?src=hash&amp;ref_src=twsrc%5Etfw">#website</a>, I&#39;ve just finished creating one! Thanks <a href="https://twitter.com/TightenCo?ref_src=twsrc%5Etfw">@TightenCo</a> for the awesome <a href="https://twitter.com/hashtag/jigsaw?src=hash&amp;ref_src=twsrc%5Etfw">#jigsaw</a> project. Really enjoyable dev experience, <a href="https://twitter.com/hashtag/markdown?src=hash&amp;ref_src=twsrc%5Etfw">#markdown</a> support is awesome and by generating static sites, I&#39;m able to deploy it to <a href="https://twitter.com/github?ref_src=twsrc%5Etfw">@github</a> <a href="https://twitter.com/hashtag/pages?src=hash&amp;ref_src=twsrc%5Etfw">#pages</a> in no time!🚀</p>&mdash; José Postiga (@josepostiga) <a href="https://twitter.com/josepostiga/status/1099507598003503104?ref_src=twsrc%5Etfw">February 24, 2019</a></blockquote>

Not long after posting it Wilbur Powery E., a fellow developer, shared this interesting information:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Tip: Use <a href="https://twitter.com/Netlify?ref_src=twsrc%5Etfw">@Netlify</a>, thank me later 🤠</p>&mdash; Wilbur Powery E. (@wilburpowery) <a href="https://twitter.com/wilburpowery/status/1099507994918875142?ref_src=twsrc%5Etfw">February 24, 2019</a></blockquote> 

I was intrigued. I had heard about Netlify before, but I never got to know what it was, exactly. But I was curious, so I access their site. Turns out it's an all-in-one platform that combined global deployment, continuous integration, and automatic HTTPS. I went even deeper and read the features page and the very first two items were enough to lead me to sign up: "Push your site live" and "Automate deployment". This made me realize I could avoid the extra work I had at the moment, where I needed to run the build command and then push the compiled pages to the correct repository branch.

So, I set up the account, updated the DNS to point to Netlify and watch as I saw the build process finish successfully. The big difference was the whole process of publishing: I just need to write a new article/journal entry and push it to the repository. Immediately after the push, Netlify workers start fetching the updates, run the necessary commands to compile the static files and the website is automatically updated without any more intervention of mine. And it's fast, too, as it only takes one minute to the whole process to finish!

Pretty neat! I'm pretty satisfied with this setup. If you want to try it yourself, check out <a href="https://netlify.com" target="_blank" rel="nofollow">Netlify</a> for more details. You're always welcome to <a href="https://twitter.com/josepostiga" target="_blank">tweet</a> me any doubts and/or questions about the whole stack described here. I'll be sure to respond as quickly as I can!
