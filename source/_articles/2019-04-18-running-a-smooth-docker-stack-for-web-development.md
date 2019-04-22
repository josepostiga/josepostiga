---
extends: _layouts.article
section: content
title: Running a smooth Docker stack for Web Development
date: 2019-04-18
cover_image: https://images.unsplash.com/photo-1511578194003-00c80e42dc9b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80
description: "Let's skip the introductions and get down to business, baby!"
---
Docker is the best way to build, share and run applications in the cloud. There're no doubts about that! You literally only have to configure your infrastructure once, programmatically, and can run on every cloud provider. It's amazingly fast, too. No wonder that everyone is crazy about this technology and are using it to support their most critical business services. Docker is the best way to build, share and run applications in the cloud. There're no doubts about that! You literally only have to configure your infrastructure once, programmatically, and can run on every cloud provider. It's amazingly fast, too. No wonder that everyone is crazy about this technology and are using it to support their most critical business services.

Surely you've also heard, and read, about teams using Docker in their development process. You read that you can get up and running without messing around with installing specific versions of a software language or any other dependency. You simply `docker-compose up` and all services/dependencies are spawn and ready to use. Pretty cool. It doesn't sound too complicated, right?

Well, that's not that simple! Using Docker for development can be a real PITA! I've been using Docker for development, for a little more than two years and I got to say I've had to handle a lot of problems to have a smooth environment set up!

That's what I'll share with you, in this article. This is not an "Introduction to Docker" type of article. This is "Put up your sleeves and let's get down to business" kind of article! My main goal is to enable you to acquire "hands-on" experience running Docker exclusively for your development workflow. All examples will be developed with PHP development in mind, but I think you can extrapolate to your situation, specifically.

Oh, and before dumping all the knowledge to you, I'll start by breaking my path to the final implementation into small parts, so you can understand where my decisions came from. So, let's get to it?

---

## Part 1: With great Dockerfiles, comes great responsibility 

This is not an introduction to Docker or Dockerfiles. You have the official [documentation](https://docs.docker.com/engine/reference/builder/) that has in-depth information about it. However, I'll tell you this: Dockerfiles contains additional commands that are called to assemble an image with the configurations, dependencies and other things you might need to run your containers!

Having said this, I want to call for your attention that you should avoid using Dockerfiles for as long as you possibly can. It's not that these files are complicated because they're not. It's just that, as soon as you create a Dockerfile, you're taking responsibility about managing that specific image definition. It's not a decision you should take lightly. Dockerfiles, like any other system, need to be revisited from time to time and updated because, for example, a library you added has a new version available.

To avoid having this responsibility, you should first check for an official image on [Docker Hub](https://hub.docker.com/)! Let others have to worry about dependency management and update. You'll want to use your time to generate value, not worrying that your PHP base version is outdated... Right?

However, for those cases when you really have to create one, this is my advice, to you: try to use it as little as possible. The next sections should help you with that!

### Use the most possible recent version of the base image you need.

A Dockerfile requires that you define a base image, which to apply your changes upon. This can be an O.S. version (Ubuntu 19.10, Debian 9, Fedora, Mint, etc.) or you can even use an image that already has a dependency installed (PHP 7.3, NGINX, MySQL, etc.) which already have an underlying O.S. definition and all dependencies and additional software needed to run what you want.

For example, when creating a PHP Dockerfile, I'll try to use the most recent, stable, version that I'll like to support. So, to use PHP 7.2 (CLI), I'll start my Dockerfile as:

```dockerfile
FROM php:7.2-cli
```

Every command I add, next, will be run on top of this PHP 7.2 image, that'll have PHP and all dependencies needed to have it running without problems. By using a base image that already has almost everything I need, I'm also limiting my responsibility. I don't have to worry about every command it takes to install PHP and I don't have to worry about managing or updating it.

### Condense as much commands as possible.

To execute a command, you define it with the `RUN` statement. This statement runs the given command on the terminal while the final image is being compiled and corresponds to a layer. Each layer, upon being executed, is cached. This prevents the `docker build` process to run every command every time you execute it, saving you (a lot of) computation time.

So, if every `RUN` statement corresponds to a cacheable layer, it should make sense to use as few as possible, speeding up the compiling time. Well, no. Use it with care because every time you change the statement, the build process will re-execute the entire modified `RUN` statement, even if the only thing you changed was the order of the arguments. So, if you have a "big" `RUN` command, and change it, the cache is invalidated and you'll have to wait that it finishes executing (and, then, it gets cached, again).

Try to find a sweet spot between the number of `RUN` statements and the amount of work done on each one. I tend to separate mine in three sections: package installs, package configurations and packages activations.

---

## Part 2. Containers and networks

Again, this is not an introduction to containers networks. You have the official [documentation](https://docs.docker.com/network/) for in-depth information about it. Networking can be tricky, but it's essential that you have a general understanding of how containers do networking.

When you spawn a container via `docker run` you can define a network by adding `--network={your-network-name-here}` (or the shortcut `-n`) and this will configure access, to this new container, to that network. This means that this container will have access to all other containers that also may be connected to the same network. But what about if you spawn a container without defining an explicit network? Well, one might think that this container won't have access to anything, that'll run in complete isolation. But that's not always the case.

All containers that are spawn without a network definition are connected, automatically, to the default `bridge` network. So, in fact, if you start two containers, you can make them communicate with each other. However, contrary to user-generated networks, which supports service discovery (calling other containers by their defined name), that communication can only be done via IP. 

This type of nuance can be very handy and it's even more interesting if you think about using it for connecting to database instances! As long as your exposed ports don't collide, you can then use the bound localhost port (0.0.0.0 or 127.0.0.1), specifying the exposed, mapped to host, port and connect to your database via a DBMS (pgAdmin, DBeaver, MySQL Workbench or any other of your liking). Pretty neat, I think! You can spin up two containers, one with your database instance and another with your app, and get them working together without even thinking about creating a user-defined network, without any `docker-compose` files and any other type of orchestration method.

But when you do use a `docker-compose` file, there are a lot more interesting things you can do. You can define many networks, their drivers, force IP addresses, group different services into different networks and even define aliases for the same instances on different networks. So, an NGINX container can be called `load-balancer` on a network, and be called `nginx-lb` on another. It's the exact same instance, but with different names on each of those networks. This will come at hand when you want to make a call (let's say, with `curl`) from one container to another, by a custom domain name, which has to pass through your reverse proxy container, so the call can be properly routed to the other container.

You might be confused, right now, to why that's a big deal, but be patient as we get closer to the climax of the article.

---

## Part 3. Permissions and user/group mappings

I'm not an expert on user/group permissions and namespacing on Linux (I'm assuming you're using Linux, here). However, there was something really inconvenient in running commands through containers: any file created gets associated with the user and group 0, which maps to `root`. Which means, you can't edit them, or delete them, without running a `chown` command to remap the file's permissions to your own user. And this might not be as important as it looks, but since I develop a lot with PHP, using `composer` can get messy, because the `vendor` directory gets totally owned by `root`. You might think it's fine because, after all, `composer` is a package manager and external, third-party, packages are not to be edited locally. Ok, that's fair, but let me give you one more example...

Imagine you use a script that generates PHP classes. Let's say, like me, you work with Laravel, which has the `artisan` command line tool, that helps you generate not only classes, but a lot more resource files to speed up your work. As soon as you run `php artisan make:controller SomeController` you'll not be capable of editing it without, first, do a `sudo chown <your-username>: SomeController` to give write access to that file. Pretty inconvenient, right? Well, this can be averted.

I recently learned that we can map users and groups, to which the container will run as, on-the-fly and, quite frankly, in a very easy way! You can use it as a command parameter when running `docker run`. The parameter's name signature is `--user {user}:{group}` and, by adding it, you're explicitly saying that the command is being run by that user, under that group. With this, if you map to your own user/group, will associate the files to your account enabling you to fully work with them as if they were generated directly on your host machine. 

However, there's a catch. Or two, in this case:
1. It only takes numbers for both user and group so you have to know, in advance, what's yours (you can use the `id` command to get that info);
2. It has no validation, whatsoever, for those numbers so, technically, you can use any number combination for user:group and still get the container to work. However, this has the same result as running as `root`, since you wouldn't be able to edit without elevated access...

Don't worry! There's an easy way of using this properly without ever think about it. If you inspect the `id` command, available on your machine, you'll notice you have two options to get the ID and GROUP of the currently, logged in, user account. You can use that to programmatically get the correct values. Since bash commands can take sub-commands and evaluates them first, you can use `id -u` to get the user and `id -g` to get the group, before the main `docker run` command gets executed. So, you can run any docker container with your user:group mapping with the following partial: `docker run --user $(id -u):$(id -g) <the rest of your command goes here>`.

Now, you can run `composer`, `artisan` or any other command and have all the generated files correctly associated with your user/group. You don't ever have to `chown` your way through those files, again, to be able to edit them on your IDE or code editor of choice.
