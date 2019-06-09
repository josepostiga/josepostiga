---
extends: _layouts.article
section: content
title: Running a smooth Docker stack for Web Development
date: 2019-06-09
cover_image: https://images.unsplash.com/photo-1508404999913-79a3a2e75437?ixlib=rb-1.2.1&auto=format&fit=crop&w=967&q=80
photo_credits: https://unsplash.com/photos/M3yYOCob6kE
description: "Let's skip the introductions and get down to business, baby!"
---

Docker is the best way to build, share and run applications in the cloud. There're no doubts about that! You literally only have to configure your infrastructure once, programmatically, and can run on every cloud provider. It's amazingly fast, too. No wonder that everyone is crazy about this technology and are using it to support their most critical business services.

Surely you've also heard, and read, about teams using Docker in their development process, right? That you can get up and running, on any project, without messing around with your computer and not being worried about installing different (or specific) versions of a software language or any other dependency you might need. You simply `docker-compose up` and all services/dependencies are spawn and ready to use. Everything gets to run on an isolated container. Pretty cool, and it doesn't sound too complicated, right?

Well... As you'll see on this article, there're some details that make using Docker for development a real PITA! I've been using Docker for development for a little more than two years, at the time of writing this article, and I'd like to share how I've handled those details and got to a point of having a very smooth environment set up! My main goal is to enable you to acquire "hands-on" experience running Docker on your development workflow.

But before I dump all the knowledge to you, we'll start slow, by breaking my path to the final implementation into small parts, so you can understand where my decisions came from. Let's get to it, shall we?

_Note: All examples will be oriented with web development in mind, but I think you can extrapolate to your situation, specifically._

---

## Part 1: With great Dockerfiles, comes great responsibility 

This is not an introduction to Docker or Dockerfiles. You have the official [documentation](https://docs.docker.com/engine/reference/builder/) that has in-depth information about it. However, I'll tell you this: Dockerfiles contains additional commands (steps) that are called to assemble a Docker image with configurations, dependencies and other things you might need to run your container. However, you should avoid using them for as long as you possibly can. 

It's not that these files are complicated because they're not, it's just that, as soon as you create a Dockerfile, you're taking responsibility about managing that specific image definition. It's not a decision you should take lightly. Dockerfiles, like any other system of its kind, need to be revisited from time to time and updated because, for example, a library you added has a new version available.

To avoid having this responsibility, you should first check for an official image on [Docker Hub](https://hub.docker.com/)! Let others have to worry about dependency management and update. You'll want to use your time to generate value, not worrying about dependencies versions being outdated, or even worrying about vulnerabilities on those dependencies. Docker Hub has a lot of images for you to work with. Not only has many images created by community members, but also has official images, supported by the very companies and groups that develop the underlying dependencies. For example, you can find official images for NGINX, PHP, Composer, Yarn, NodeJS, and a lot more!

However, for those cases when you really have to create one, try to use it as little as possible. The next sections will show you how.

### Choose a base image wisely

A Dockerfile requires that you define a base image, which to apply your changes upon. This can be an O.S. version (Ubuntu 19.10, Debian 9, Fedora, Mint, etc.) or you can even use an image that already has a dependency installed (PHP 7.3, NGINX, MySQL, etc.) which already have an underlying O.S. definition and all configurations needed to run that dependency.

For example, when creating a PHP Dockerfile, I'll try to use the most recent, stable, version needed for my situation. So, supposing I need to use PHP 7.2 (CLI), I'll start my Dockerfile with:

```dockerfile
FROM php:7.2-cli
```

Every command I add next will be run on top of this PHP 7.2 image, and have all dependencies needed to have it running without problems.

By using a base image that already has almost everything I need, I'm also limiting my responsibility. I don't have to worry about every command it takes to install PHP and I don't have to worry about managing or updating it. If by any chance, there's a new version of that base image, all I have to do is run the docker build command to fetch all updates available, directly related to the base image.

### Condense as much commands as possible

To execute a command, after defining the base image, you use the `RUN` directive. This runs the given command during the image build process, while the final image is being compiled, and corresponds to a layer. Each layer, upon being executed, is cached. This prevents the `docker build` process to run every command, every time you execute it, saving you (sometimes a lot of) computation time.

So, if every `RUN` directive can correspond to a cached layer, it should make sense to use as few as possible, speeding up the compiling time. Well, not so fast! You need to use it with care, because every time you change the statement, the build process will re-execute the entire modified `RUN` directive, even if the only thing you changed was the order of the arguments. So, if you have a "big" `RUN` command, and change it, the cache is invalidated and you'll have to wait that it finishes executing all work defined on that modified `RUN` directive and every one after.

To avoid having this problem, often, you have to find a sweet spot between the number of `RUN` directives and the amount of work done on each. I tend to separate mines in three sections: package installs, package configurations and packages activations.

### Keep your images on a diet

The whole point of using Docker is to keep the dependencies at a minimum, and decoupled from your host computer. However, this line of thinking is not only applicable to the containers. You should start applying, at least the "keep dependencies at a minimum" part, with your Dockerfiles. Images are a very important part of your Docker setup: without images, you can't have containers! They are two parts of a whole: the bigger your image's size, the bigger the container size.

Remember that the only thing that your host and your containers share, is the kernel. Everything that you install or copy onto your image will add up to its final size. Every `RUN` directive that has a package install command (`apt-get install` or `apk install`) will be saved on the image. If you're not careful, your image can become very heavy, very quickly. You might not even install that many things and still see your images reach one gigabyte of size, or even more.

One of the main enablers of that situation is the package manager's cache system. You see, when you have to install software, a general step required is to run `apt-get update` (supposing you're running Ubuntu as a base image) before running the command to install the software you need. This is because that command is responsible to download all package's information list, available on every repository registered on the O.S. That list contains the repositories where the software is so that the package manager can download it and install it. The "real" problem is that after downloading that information, it's cached.

That behavior is very welcome if you're running that on your host machine, avoiding having to download that information every time you try to update your system, but in the context of a Docker image, that's useless! After you install all the software you need, that cache is only occupying precious space as it's not needed to run the, already installed, software. By removing that cache information, you can save a lot of space.

---

## Part 2. Containers and networks

Again, this is not an introduction to Docker networks. You have the official [documentation](https://docs.docker.com/network/) for in-depth information about it. Networking can be tricky, but it's essential that you have a general understanding of how containers do networking.

When you spawn a container via `docker run` you can define a network by adding `--network={your-network-name-here}` (or the shortcut `-n`) and this will configure access, to this new container, to that network. This means that this container will have access to all other containers that also may be connected to the same network. But what about if you spawn a container without defining an explicit network? Well, one might think that this container won't have access to anything, that'll run in complete isolation. But that's not always the case.

All containers that are spawn without a network definition are connected, automatically, to the default `bridge` network. So, in fact, if you start two containers without defining a network, those containers will be able to communicate with each other through that default `bridge` network. However, contrary to user-generated networks, which supports service discovery (calling other containers by their defined name), those two containers can only communicate only via their IP address. 

This type of nuance can be very handy and it's even more interesting if you think about using it for connecting to database instances! As long as your exposed ports don't collide, you can then use the bounded, default, localhost port (0.0.0.0 or 127.0.0.1), specifying the exposed, mapped to host, port and connect to your database via a DBMS (pgAdmin, DBeaver, MySQL Workbench or any other of your liking). Pretty neat, I think! You can spin up two containers, one with your database instance and another with your app, and get them working together without even thinking about creating a user-defined network, without any `docker-compose` files and any other type of orchestration method being needed.

But when you do use a `docker-compose` file, there are a lot more interesting things you can do. You can define many networks, their drivers, force IP addresses, group different services into different networks and even define aliases for the same instances on different networks. So, an NGINX container can be called `load-balancer` on a network, and be called `nginx-lb` on another. It's the exact same instance, but with different names on each of those networks.

---

## Part 3. Permissions and user/group mappings

There's something really inconvenient in running commands through containers: any file created gets associated, by default, with the user and group 0 (the `root` user/group mapping). This means that you can't edit or delete them, on your host computer, without using `sudo` or running a `chown` command to remap the file's permissions to your own user. This might not sound very important but since I develop a lot with PHP, using `composer` can get messy as the `vendor` directory gets totally owned by `root`.

You might think it's fine because, after all, `composer` is a package manager and external, third-party, packages are not to be edited locally. Ok, that's fair, but imagine you use a script that generates PHP classes. Let's say, like me, you work with Laravel, which has the `artisan` command line tool, that helps me generate not only classes but a lot more resource files to speed up your work. As soon as I run `php artisan make:controller SomeController` I won't be able to edit it without, first, do a `sudo chown {your username}: SomeController` to give write access to that file. Pretty inconvenient, right? Luckily this can be avoided pretty easily!

Docker supports the mapping of personalized users/groups to which the container will run as! We can use it as a command parameter when running `docker run`. The parameter's name signature is `--user {user}:{group}` and, by adding it, we're explicitly saying that the command is being run by that user, under that group. With this, if we map to our own user/group values, it'll associate the files to our account, enabling us to fully work with them as if they were generated directly on our host machine. 

However, there's a catch. Or two, in this case:
1. It only takes numbers for both user and group so you have to know, in advance, what's yours;
2. It has no validation, whatsoever, for those numbers so, technically, you can use any number combination for user:group and still get the container to work. However, this has the same result as running as `root`, since you wouldn't be able to edit without elevated access...

Don't worry! There's an easy way of using this properly without ever think about it. If you inspect the `id` command, available on your machine, you'll notice you have two options to get the ID and GROUP of the currently, logged in, user account. You can use that to programmatically get the correct values.

Since bash commands can take sub-commands and evaluates them first, you can use `id -u` to get the user and `id -g` to get the group, before the main `docker run` command gets executed. So, you can run any docker container with your user:group mapping with the following partial: `docker run --user $(id -u):$(id -g) {the rest of your command here}`.

Now, you can run `composer`, `artisan` or any other command and have all the generated files correctly associated with your user/group. You don't ever have to `chown` your way through those files, again, to be able to edit them on your IDE or code editor of choice.

---

## Let's play!

Did you think I've forgotten about why you're reading this article? Of course not! Let's see how we can get a smooth docker stack installed and configured on our computer.

### Starting with infrastructure architecture

Like every new (tech) project, specifically infrastructure-related projects, we need to think, first, about how should we structure things. When I started to use Docker, I thought that I'd use a simple docker-compose file on the root of my project and be done with it. The infrastructure is project bounded, it's related and tightly coupled to it, so it made sense to manage the infrastructure that way. It seemed simple, too. 

However, I was not thinking about what to do with the Dockerfiles, with service-related configuration files (e.g.: NGINX conf files or PHP's modules' ini files). To handle this noise, I started using a `.docker` folder with everything inside, except the docker-compose file, which remained on the project root. This sounded liked a nice way to gently organize all my docker related files away from my project's files, but it could still be versioned with the latter. Although there were many more files to manage, I would only have to worry about it once, so it still was "simple enough".

Everything was fine until I started deploying things. All my infrastructure related files were deployed attached to my code. Some sort of inception was going on, where I was creating a stack that'll deploy an application that had all infrastructure related files inside, too. I thought that, although having the infrastructure definition on the project repository looked like I was helping others deploying and testing my work faster, the truth is that I was assuming that they'd use Docker, too. 

They could be using other development environments, like virtual machines or even have all the dependencies installed on the host machine, but they would still have to download all my infrastructure files. It didn't felt clean, so I decided to separate my infrastructure files from my project's, completely, and started to think about how could I manage it in a simple, eloquent, way. I needed to find a solution that would be easy to maintain, to version and to be simple enough to be rebuilt on another computer, if necessary.

I found myself creating a dedicated folder for this on my system account's home directory (in Linux is `/home/{username}`) named `Infrastructure`. Inside this folder I have:
 
 1. A folder for each different, personalized, Docker Image (for PHP, NGINX and other I needed to tailor to my needs);
 2. A folder called `Stacks` containing dedicated `docker-compose` files, each corresponding to a project;
 3. A folder called `Volumes` containing all data that I need to persist from my running containers (like the database container's data);
 4. A folder called `Scripts` containing utility scripts for running containers' commands through the console (for example, running Composer or PHP).

Here's a tree description of the folder:

```txt
├── Nginx
│   ├── certificates
│   ├── conf
│   ├── docker-compose.yml
│   └── nginx.conf
├── PHP
│   └── 7.2
│       ├── cli
│       │   ├── conf
│       │   │   └── xdebug.ini
│       │   └── Dockerfile
│       └── fpm
│           ├── conf
│           │   └── xdebug.ini
│           └── Dockerfile
├── Scripts
│   ├── composer
│   ├── dep
│   ├── mkdocs
│   ├── mysql
│   ├── php
│   ├── php5.6
│   ├── phpcs
│   ├── php-cs-fixer
│   ├── phpinsights
│   ├── psql
│   ├── redis-cli
│   └── yarn
├── Stacks
├── Volumes
└── install.sh
```

This structure allows me to version the whole folder, except the `Volumes` folder (database data should not be versioned) so I can quickly check out it on another computer, run the `install.sh` script and have the exact same structure and scripts available in a very short time. That file has five execution steps:

1. Creates all Docker networks that my stacks use;
2. Pulls all latest images versions that my containers use from Docker HUB;
3. Copies the Scripts folder's scripts to my `/usr/local/bin` folder, allowing me to use them globally on my computer;
4. Installs globally required composer packages, like PHPUnit, PHP-CS-Fixer and others alike;
5. Boots the NGINX container, that acts as the reverse-proxy.

### One NGINX container to rule them all

Another detail on my infrastructure configuration is that I only use one NGINX container to serve all my (web) projects. If you've read any tutorial, on the web, about using Docker for development you'll remember the service definition for a HTTP server (either NGINX, Apache or Caddy) declared on your project's docker-compose file. There's no need for that, really.

The reality is that if you follow those tutorials recommendations, you'll be repeating yourself over and over, again, by copying and pasting the same server configuration, on every project you have. Unless you have a very specific need for that, you'll most lickly use the same HTTP server, with the same configuration for every project you work on. 

Here's a better way: use one, persistent, container. What do I mean with a "persistent" is a container that has the `restart` directive property to `unless-stopped`. This marks the container to be permanently up, even if some error occurs or the computer gets powered down. The Docker daemon will always try to reboot the container as fast as possible after it stops unintentionally.

Take, for example, the following NGINX docker-compose definition:

```yaml
version: "3.7"
services:
    nginx-lb:
        container_name: nginx
        image: nginx
        ports:
            - 80:80
            - 443:443
            - 8080:8080
            - 8082:8082
        volumes:
            - ./nginx.conf:/etc/nginx/nginx.conf:ro
            - ./certificates:/etc/nginx/certificates:ro
            - ./conf:/etc/nginx/conf.d:ro
            - ~/Code:/var/www/html
        restart: unless-stopped
        networks:
            web:
networks:
    web:
        external: true
```

With this configuration, all I need to do to serve a new project is a new configuration file in the `conf` folder (which is mapped on the volumes section) and restart the container, besides having the source code on the `Code` folder. After this, the NGINX will have all subsequent requests to my new project routed to the proper project container, to be handled.

_If you noted the volume mapping of my entire `Code` folder, is because of, unfortunately, a limitation in the NGINX itself, that requires the reading the file, first, to interpret the configuration rules and calculate which server/location blocks to route the request to._

### Spinning up a project's container

Having our reverse-proxy setup is nice, but we're still missing the project container instance, so that the requests are processed. Here's an example docker-compose file for spinning a project's container:

```yaml
version: "3.7"
services:
    services:
        awesome-project:
            build: ../../PHP/7.2/fpm
            image: josepostiga/php:7.2-fpm
            user: "1000:1000"
            expose:
                - 9000
            volumes:
                - ~/Code:/var/www/html
            restart: unless-stopped
            networks:
                - web
                - app
networks:
    app:
    web:
        external: true
```

Contrary to what you may find on various online articles, there's no HTTP server service definition. Like I said, there's no need, since we already have it prepared to handle many projects.

But let's break this file, shall we? This file defines that we'll have a PHP 7.2 container, that'll be executing under a mapped user within system's ID/GROUP 1000 (remember these section, from before?), that exposes port 9000 to the host. Also, it has the host's `Code` folder mapped to the container's `/var/www/html` folder (that's where your code will exist), will always restart unless we issue a command to stop it (voluntarily) and has access to two networks: the `web` and `app`. 

Take a moment to sink this information in...

Have you wondered why it has two networks defined? Allow me to explain: it's to separate and isolate access to services that only concerns this stack. For example, the database container should not be on the `web` network, because there's another container, the NGINX one, that also has access to that network. Does it makes sense to have them both on the same layer? No. We're simply talking about development environment, here, but it's not difficult to imagine a similar stack deployed on production. Having unrelated services accessing the same network is a bad habit, because anyone with access to your NGINX container can, too, have access to your database container.

It's a good habit to think about proper boundaries on your services and limit access where it's not needed. So, the `web` network allows the NGINX container communicate with this PHP container and the `app` network allows this PHP container to access other, more reserved, services (like a database). You can add as many networks as you may see fit. Also, since the `app` network is not marked as `external`, Docker will have it namespaced to this stack, only, and even if you have the same name on any other project, they will not be able to access each other's services. It's a neat security feature, built-in with Docker itself.

---

## All together, now!

Ok, now that we have both the reverse proxy and a project's container to serve, we need to understand how that all fits together and enable communications between the two services.

When a request comes to NGINX, it'll scan it to determine which domain it needs to route the request to the correct container. This happens on, what NGINX calls it, the server blocks. That's why I've mapped a `conf` folder on the NGINX container: that's the folder that will contain all the dedicated projects configuration files. Here's an example of such file:

```
server {
    server_name awesome-project.test;
    listen 80;

    root /var/www/html/awesome-project/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { log_not_found off; access_log off; }
    location = /robots.txt { log_not_found off; access_log off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        set $upstream awesome-project:9000;

        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass $upstream;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

As you can see, this server block configuration file is set to handle requests for an `awesome-project.test` domain. So, when a request comes from that domain, the NGINX container will look on the defined root folder for any file that matches the first `location` definition. Assuming that it finds one that matches one of the patterns set, it'll then scan all of the remaining `location` definitions (on this example file, there's one more) and if it can match the pattern (which is looking for any file ending in `.php`), then it executes the corresponding block instructions. And is in this second `location` block that all the magic happen.

First and foremost, we're setting a `container:port` mapping in a variable. This is a very important part of the configuration, which avoids NGINX to malfunction, and enter a restart loop, if the container happens to not been started yet. If that would happen, all other projects that it may be responsible to handle wouldn't be processed! 

After calcuating, and successfully resolving the project's container, NGINX compiles the path info and request, passing it to the destination container, through the defined port. After this, the PHP container will pick up the call, execute the code and return the response back to NGINX to be outputted to the user.

## Extra points: utility scripts for daily usage

Remember the `Scripts` folder? As I've said before, that folder contains several scripts I use to perform a lot of common tasks on my day-to-day work. By copying them to a `bin` folder, I can simulate the behaviour of any program as if it was installed on my computer, but, in fact, I'm running them in isolation, through a Docker container.

For example, the command to run Composer, which is a dependency manager for PHP, is the following:

```sh
#!/bin/sh
docker run --rm -ti --user $(id -u):$(id -g) \
    --volume ~/.config/composer:/tmp \
    --volume $SSH_AUTH_SOCK:/ssh-auth.sock \
    --volume /etc/passwd:/etc/passwd:ro \
    --volume /etc/group:/etc/group:ro \
    --volume $(pwd):/app \
    --env SSH_AUTH_SOCK=/ssh-auth.sock \
    composer $@
```

With this, I can run any composer command exactly as I would if I installed it on my computer, but without needing to install PHP and all it's required dependencies. I just do `composer install` or `composer update` and be done with the task. This script can even access my SSH keys to authenticate the requests on private repositories.

Here's another example, this time for running Yarn, a dependency manager for Javascript based projects:

```sh
#!/bin/sh
docker run --rm -ti --user $(id -u):$(id -g) \
    --volume $(pwd):/usr/src/app \
    -w /usr/src/app \
    node yarn $@
```

And I run it with a simple `yarn {my command here}`.

This works absolutely perfect and without any hassle whatsoever. If I end up not need a script, anymore, I simply remove it from my computer with a simple `rm {script file path}` and that's it! No unnecessary dependencies lying around on my computer.

## That's it!

Well done! You now have the necessary information to be able to create a smooth infrastructure, just like me! You've learned how to put different containers to communicate with each other, how to use a single NGINX container to reverse-proxy, and serve, as many projects as you need and, on top of that, you learned that you can use containers to run your everyday scripts and not needing to worry if its installed on your current working computer.

Hope you've enjoyed the article. If you have any question, feel free to contact me on [Twitter](https://twitter.com/josepostiga). I'm more than happy to help you with any difficulty you may have while applying the knowledge available on this article.
