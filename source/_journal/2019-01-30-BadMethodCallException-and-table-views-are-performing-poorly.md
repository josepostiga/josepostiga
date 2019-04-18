---
extends: _layouts.journal
section: content
title: BadMethodCallException and table views are performing poorly
date: 2019-01-30
cover_image: https://cdn-images-1.medium.com/max/800/0*D75cx9-F9YxRLQmO
description: "Yesterday, after I published the daily blog post, I received a few e-mails regarding a pull request I made."
---

Yesterday, after I published the daily blog post, I received a few e-mails regarding a pull request I made. The specific commit removed a call to Auth::login() since my tests were throwing an exception about method "login" not being found through the call to the Auth façade. We all make mistakes and I trusted that my test was right and, thus, removed the call without checking further the reason why. Stupid mistake, rightly caught by Gonçalo, which I selected as one of two reviewers, which broke the authentication with OAuth tokens.

It was late, and since it was not yet on production (was on a dev branch), it could wait for the next day. So, the day came and the first thing I did after reaching the office was figuring out why that was happening. I checked everything! Which guard Laravel was configured to load, the data that was passing to the methods. Somehow, the logic was breaking, according to the stack trace, when the Macroable trait was handling the call to the login method (which, when using a façade, is called using the magic method `__call`). I was not getting why, so I called Gonçalo to help me understand since I've never gone this deep on the framework internals.

Even he was not understanding why it was not working, until he remembered to search for every login method call, on every project's classes. While analysing the search results, he remembered that we had forced the injection of the login driver to use to "api" every time we call "$this->actingAs()" to mock an authentication as a specific user. 

Laravel has, by default, two main authentication mechanisms: Session and API. However, when using the Laravel Passport package, to support OAuth authentications, we get another driver: "passport". So, when the Auth façade was called, when using oauth tokens, Laravel will need to use the "passport" driver but, since we forced the "api" driver to be used, everything got messed up! Removing this forcing fixed the problem, tests got back to green and I even checked the authentication methods manually.

Everyone messes up, sometimes. 🤷‍♂️

In between this debug session, I attend the second phase of the sprint #51 planning. While discussing the issues we were planning, a colleague of mine, Gedielson (we call him Jedi), was talking about poor performance in our tables views and that we should create the SQL statements directly on our code. I'll be honest by saying that I don't know much about table's views, but the feedback I had, from other professionals, was that it would never perform worst than creating the queries within our codebase. In fact, using views, we would be transferring complexity to the DBMS layer and, thus, simplifying the access to that views' data because, then, we could use basic select statements.

With that in mind, I questioned him about his statement and he explained to me that the problem was not the access of the data (the select part) but the use of views with joins to other tables and sub-queries and that was the culprid, making the database work with unnecessary effort and should be optimized using raw queries. [1]

That made complete sense to me. Always learning! 😏

We finish the day making the last tests against the features and improvements made during sprint #50 and deployed to production! 🚀 Sprint #50 finished with success. Now, let's go to sprint #51!

***

[1] I was called to attention, by my colleague (and CTO) Luis, that update operations, on Table Views, don't exist. I misunderstood the conversation I had with Jedi, when he talked about making operations on the views, when he was refering to Joins and not updates (duh). Always learning! Thank you, Luis. (edited on Feb 5 2019).
