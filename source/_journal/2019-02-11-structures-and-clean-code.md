---
extends: _layouts.journal
section: content
title: Structures and clean code
date: 2019-02-11
cover_image: https://c10.patreonusercontent.com/3/eyJ3IjoxNjAwfQ%3D%3D/patreon-media/p/post/24634449/b0e9e612192d4bd5ac6d476e4f4765a7/4?token-time=1552176000&token-hash=bZdn8gqyfAXVPmv-bh8jBy8mu2wJ6Lg7LVF4Bdc8j_g%3D
description: 
---
Hey there! How was your weekend? Ready for another week full of opportunities?

Today I started my day reading the powerful, insightful and intriguing book "Clean Code", by Robert C. "Uncle Bob" Martin. I'm still a couple chapters in but I'm already hooked on the premise of analysing code and understanding the common pitfalls of programming. The level of details and narrative shows a lot of commitment to trying to make us (programmers) more thoughtful on our daily work. I sure am learning something new at each paragraph.

With all this inspiration. as soon as I entered the office, I went deep on that reporting class I've been working since last week. Now that I have the foundations (the "world", as I referred on my last post), I rapidly added the tests necessary to validate the structure of that report. However, something were to keep me occupied for the most part of the morning: configurations.

I had been having trouble configuring PHPstorm to run my test suite directly on it. If you have PHPstorm and have a test class opened, you can click on the green arrow, on the left side of the test function's name, and let the IDE run the test through PHPUnit and output the result. After the first run (which should fail) you can rapidly re-execute it by clicking ctrl+F5 (on Windows/Linux). This workflow is better than going to the console, accessing the history to show past executed commands and run the command to test a function/entire class. And so, I spent the next hour and a half configuring a remote PHP interpreter with Docker, configuring PHPUnit to use a custom configuration file (under the tests folder) and going back and forth with little mistakes, along the way.

After I got it to work properly, I started creating the different tests for each important part of the report's response structure. I knew I could use two special functions, for this: "assertJsonStructure()" and "assertJsonFragment()". I've never used them much, before, but the work I was doing was a perfect use case for these functions.

By using the "assertJsonStructure()", I can validate that a given response has the required structure, by checking if the defined keys are present.  On the other hand, "assertJsonFragment()" validates that the response contains the given information, by checking not only the keys but also its values. Here's an example of how I used the two to test for a valid response:

![](https://c10.patreonusercontent.com/3/eyJ3IjoxMjQwfQ%3D%3D/patreon-media/p/post/24634449/d41e4f5f3a92466db06ffa8c9a478df7/1?token-time=1552176000&token-hash=xnwuQJGC_-TRg7bq5ROf7V0gIG0aa5F4n7KEd642tkM%3D)

As you can see, the "AssertJsonStructure()" is checking the response of the "getJson()" for the existence of the keys I defined as required. Now note that I'm not checking the keys' values, but only that the structure is as defined. The values are checked on the "assertJsonFragment()" where it's checked that the response has the given fragment somewhere. Alone, these assertions are prone to false positives but together they make a pretty good enforcing of the necessary structure of the response. Pretty neat, I think!

I’m yet to finish this, but I can say I'm halfway through it. The best of all, I've been refactoring my solution along the way, and the tests let me do it with confidence that I won’t break anything. Awesome!

But let's talk about another thing, now: HUB. I've worked on it, this weekend. I finally finished the feature tests for the user's profile update and also finished the create and update part of the CRUD functionality. I'm on the delete part, the last one before finishing this module. Another thing I've done, too, is to change the project, a little bit. I will be creating this as an API first software (or headless, one term you might already hear of). This decision will remove the need to think of a design to ship with the product when I release it to the World (MIT licensed). This way, anyone can create the frontend on their preferred technology. Also, as a bonus, when I finish the API part, I can practice the frontend part myself, as a separate project.