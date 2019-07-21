---
extends: _layouts.article
section: content
title: Continuous Integration with Bitbucket Pipelines
date: 2019-07-21
cover_image: https://images.unsplash.com/photo-1556075798-4825dfaaf498?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1355&q=80
photo_credits: https://unsplash.com/photos/842ofHC6MaI
description: "How we automated integration testing in a simple and effective way at Infraspeak."
---

> This article was originally submitted on the [Infraspeak tech blog](https://medium.com/infraspeak).

Infraspeak is a startup founded in 2015, focused on developing the best maintenance management software in the market. Built around simplicity and a user-friendly UI/UX, we have more than 25000 buildings being managed every day, with the help of our product.

You might think that due to this number, there's a big team of software engineers developing and maintaining the software. You'd be wrong. The Product Team is composed of 12 people, distributed between Backend, Frontend, Mobile, AI, and Integrations, and I'm also counting with the CTO.

Although we're actively recruiting new developers to the team, we're perfectly comfortable in maintaining such a user base and still making sure we keep improving the codebase with optimizations as well as deploying new features because we automate as much as possible. One automation we have in place is the Continuous Integration pipeline.

The need for a Continuous Integration pipeline came from the fact that we were quickly becoming a critical dependency for our customers daily operations and, because of that, we needed to focus on constant quality assurance of our work. We needed a way of automating the integration testing of the work that was merged to the master branch (which is then deployed to production) with as minimal human interference as possible.

When searching for options for a pipeline system, we were looking for something simple and quick to implement, but also easy to maintain. We had three ways of doing this:

* Invest in preparing and actively maintaining a dedicated infrastructure for a self-hosted system, like Jenkins or Drone CI.
* Pay a lot of money, recurrently, to use another external tool, like Travis CI or Circle CI.
* Use Bitbucket Pipelines, which is natively integrated with their software repository, which we were already using.

Since we have a relatively small team, allocating resources to configure a dedicated CI infrastructure,  and actively support it, was very hard to reason about, so this option was quickly disregarded. We looked at Travis CI and Circle CI with good eyes, because we didn't have to handle the maintenance ourselves, but even being a well-funded company, their pricing plans were very expensive for our first attempt at using a pipeline system. We needed to gather more information, and experience, before requesting more resources to be invested in this.

In the end, we went with Bitbucket Pipelines. Since we were already a paid customer, we had access to a 500 minutes pipeline execution plan without any additional costs. For what we wanted to do at the moment, it was good enough.

Activating Bitbucket Pipelines was as simple as clicking on the dedicated "Enable Pipelines" button, available in the repository settings page. The interesting and a little bit more complicated part was creating the configuration YAML file according to the project specification so that Bitbucket knew when to run and how to instantiate and automatically configure the pipeline's infrastructure. The main goal was to activate the pipeline in specific points on our branching strategy.

We have a pretty standard branching strategy: we have a master branch, always in sync with production, we have a development branch and we have n working branches corresponding to an active task. As soon as developers are finished with their task, they send a PR targeting the development branch, which then is peer-reviewed and, finally, merged. At the end of the sprint cycle, the development branch gets merged to master and deployed to production.

To be confident about the changes we continuously made to the project, and to minimize the need for manual testing between merges to the development and the master branch, without sacrificing speed and agility of development, we needed the pipeline needed to run on key events of our workflow:

* When a PR is opened targeting the master or the development branch.
* When a push is done directly to the development or the master branch.

Because we had a limited execution time, we couldn't include every push to the working branches but assumed that each developer would run the full test suite locally and only push the changes when they had them all passing. The pipeline would only continuously check the integration of all developers code into the development and the master branch, as we knew that those were the points in time were bugs, conflicts, and other problems could happen.

We decided that the first project which we were to have the pipeline configured to test was our main API layer. It's the center of our work and the main source of business logic and data to our satellite projects (like the mobile app and the frontend layer). If it failed, then all other tools we provide were going to fail, too. Having this in mind, we started to map all the steps required to automatically instantiate the project and run the full test suite (unit and feature tests):

1. Checkout the project from the repository.
2. Install all the dependencies and instantiate a PostgreSQL database.
3. Run the full test suite.

Digging through the Bitbucket Pipelines documentation, we learned that it's all run inside a dockerized environment and that the main base image they recommend using would check out the correct branch and make the code available to all other steps of the pipeline. That was exactly what we needed to handle the first step.

For step two, we needed to have a way to run Composer, to install the dependencies, and run PHP for the test suite. The Bitbucket Pipelines documentation states that each pipeline step can have a dedicated docker image running. That would support our initial idea for running Composer and the PHPUnit test suite in two different steps.

The documentation also refers to the fact that we can have shared containers (they call it services) running and accessible from all steps throughout the whole pipeline execution. Since we rely on PostgreSQL as our persistent layer, and we had a lot of feature tests that would use that layer, it was a very much appreciated functionality that we would definitely need.

After having the first draft of the pipeline configuration file, we created a test branch, pushed the file to the repository and watch the pipeline come to life. It started to run and we were watching happily, for about a minute, then watch it fail hard. The logs stated that the vendor folder, which is where Composer downloads and installs the dependencies, was nowhere to be found. It seemed that it was being removed at the end of the step (at the teardown phase).

We got to scratch our heads, a lot, about this. We were installing the dependencies in the previous step and we were declaring the composer cache strategy, which is natively supported by Bitbucket and is specifically built for keeping Composer dependencies for the next step. After a little investigation, and reading a lot of documentation, we found out that this was happening because the artifacts configuration key was missing. This configuration maps the Composer vendor folder, and all files it contains, to transition to the next step.

After updating the configuration file, we got it run successfully. The vendor folder was transitioning correctly to the next step and the test suite was running. Everything was green and we had our first successful pipeline execution!

Since it was running, it was time to optimize it. The first optimization was around the Composer dependencies install. It was slow, taking around three minutes to install all the dependencies. Was there something we could do to improve this? After a little more digging, we found out that we should use a caching strategy. Composer, when run locally in your machine, saves a reference to the remote dependencies repositories, allowing it to skip several steps when fetching those dependencies for each project you use it.

Since the Composer binary, on our pipeline, was run in a docker container, the references cache were compiled but it would never persist. Docker containers don't persist data after being destroyed, unless you add volumes to them. And that's what we needed to add: a cache volume to persist those references.

Doing that was not an easy task. The Bitbucket Pipelines documentation states that it has a predefined caching strategy for Composer, which is awesome and would save a lot of time by not requiring us to configure a personalized one, but it forgot to mention that if you use the default Composer docker image you need to also declare the /tmp folder as the one that needs to be cached.

But after we finished updating the configuration file, the time to install the dependencies went down to around fifteen seconds. That's fast! Considering that this was to be running several times, per developer, in a normal workday, it would stretch out our available execution time cap.

And it was pretty much it. We only needed to make it run on the predefined events (pull requests and pushes to master or development branch) and we had our pipeline fully working so we made the PR to the development branch. Our final configuration file was similar to the following:

```YAML
image: atlassian/default-image:latest

pipelines:
  pull-requests:
    '**':
      - step:
          name: Install dependencies
          image: composer
          caches:
            - composer
          artifacts:
            - vendor/**
          script:
            - composer install --ignore-platform-reqs
      - step:
          name: Run tests
          image: php:7.2-cli
          script:
            - ln -f -s .env.pipeline .env
            - php artisan key:generate
            - php artisan migrate
            - php artisan passport:keys
            - printf "\n" | php artisan passport:client --password
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Unit --no-coverage
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Feature --no-coverage
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Domain --no-coverage
          services:
            - postgres
  branches:
    '{master,dev-sprint-*}':
      - step:
          name: Install dependencies
          image: composer
          caches:
            - composer
          artifacts:
            - vendor/**
          script:
            - composer install --ignore-platform-reqs
      - step:
          name: Run tests
          image: php:7.2-cli
          script:
            - ln -f -s .env.pipeline .env
            - php artisan key:generate
            - php artisan migrate
            - php artisan passport:keys
            - printf "\n" | php artisan passport:client --password
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Unit --no-coverage
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Feature --no-coverage
            - vendor/bin/phpunit -c tests/phpunit.xml --testsuite Domain --no-coverage
          services:
            - postgres
definitions:
  caches:
    composer: /tmp
  services:
    postgres:
      image: postgres:10.5
      environment:
        POSTGRES_DB: database
        POSTGRES_USER: root
        POSTGRES_PASSWORD: root
```

With Bitbucket Pipelines handling our continuous integration testing workflow, every time some sneaky bug tried to enter our codebase, the pipeline would fail, informing the author of the commit via e-mail, warning to check the pipeline logs and apply the necessary correction to the code submitted.

As long as we continued to add tests for every new feature, bug fix, and improvement, the pipeline would take care of checking every test case for possible problems. This had a very positive impact in our team workflow and improved our code quality because lesser bugs were merged into the codebase. It helped the team shift a little bit more from a reactive to a more preventive position. It's better for the pipeline to catch the bugs, and break, than our customers' catch the bugs, lose work and then lose those clients!
