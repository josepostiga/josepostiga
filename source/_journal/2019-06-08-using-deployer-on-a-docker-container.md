---
extends: _layouts.journal
section: content
title: Using Deployer on a Docker container
date: 2019-06-08
cover_image: https://images.unsplash.com/photo-1515718287792-cccf004e3c20?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=967&q=80
photo_credits: https://unsplash.com/photos/onk0Hn5iY6M
description: "Deployer is a deployment tool for PHP. Now is containerized!"
---

A few days back I had the need to run [Deployer](https://deployer.org/), a deployment tool for PHP, on an isolated container. This was to avoiding keeping it, as a dependency, on a project that needed to be constantly tested on a PHP version not supported by a version of the tool that needed to be run.

I couldn't simply remove the dependency, because it's used to deploy the project to production, and I didn't want to have it installed directly, and globally (using composer), on my computer. So, I simply created a Docker image that would have that tool pre-installed and configured, and simply execute a script that would spin up a container, run the desired command, and then self-remove after the work is done.

After I got it working as expected, I thought it would be useful for others, too, so I published it on Docker Hub. You can get to the page, [here](https://hub.docker.com/r/josepostiga/deployer).

Now you too can deploy your projects without having to install Deployer either globally on your computer or as a project dependency. The only thing you need is to run the container on the same folder where your `deploy.php` file is and everything should work as expected.

If you have any questions of feedback, feel free to contact me.
