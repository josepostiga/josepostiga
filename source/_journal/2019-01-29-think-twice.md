---
extends: _layouts.journal
section: content
title: Think twice
date: 2019-01-29
cover_image: https://cdn-images-1.medium.com/max/800/0*s7b9hxxH3mvMQcyR
description: "Two major things happened today: I did overpass the difficulty I talked about, on yesterday's daily blog post, and the Team discussed the strategy for the next 3 weeks."
---

Two major things happened today: I did overpass the difficulty I talked about, on yesterday's daily blog post, and the Team discussed the strategy for the next 3 weeks.

Regarding yesterday's problem, one of my team leaders, Gonçalo, helped me find a possible solution to execute PHP CodeSniffer on a Docker container, within Sublime Text. He found this pretty quickly, which made me a little mad at myself since I, practically, wasted almost an afternoon trying to do, basically, the same thing he showed me, but I was not using the absolute path to the script. 🤦‍♂

Anyway, I was relieved that we had found a solution in time to document the implementation to the rest of the Team before the start of Sprint #51. Speaking of which...

Today was the first of two days that the Team at Infraspeak dedicates to designing the next sprint. I don't know if I've already told you that but a Sprint is a period of time (in our case, three weeks) when we create new features and improvements to our maintenance management software. The main goal for this Sprint is to create a new type of report, regarding technical interventions in our customers' buildings. Bogas, our designer, did a wonderful job at designing the mockups that we'll use. Our team leaders, João and Gonçalo, presented the Epics to the Team and we began planning and classifying the issues. It was pretty neat to see how much everyone cares for the product and the UX that'll be implemented to the customer to use.

On another topic, remember that I talked about starting the development of a small pet project, a few articles back? Yep, I've started it! I called it HUB and it's going to be my interpretation of an Enterprise Social Network software. It'll handle the users and departments management, projects and respective tasks, allow co-workers to share updates on different newsfeeds, have a few specific reports to gather data for analysis and have minor notifications about new developments on projects and departments.

I won't get in too much detail, right now, and it's not the project that matters the most. The main reason for this pet project is improving my skills as a developer. So, I set some ground rules for the development approach I'd take: TDD. Yep, Test-Driven Development! I want to be more familiar with TDD and nothing beats experience. I'm not that unfamiliar with it, because I've been practising it for the last year but, as they say: "Practice makes perfect" and I know that I need to be better at this.

Right now, I've done most of the unit test of the first module I'm developing: Co-Workers and Departments Directory. Here's a print with the main Epics for this module:

![](https://c10.patreonusercontent.com/3/eyJ3IjoxMjQwfQ%3D%3D/patreon-media/p/post/24312911/fd37d50ab8e9464796c9cc94e59821d7/1?token-time=1552176000&token-hash=LDn31N6JQhiBnpB6ZODFrROQ-Row_W0Q_5abSdQuhMs%3D)

So far, so good. I've already made like 8 tests and 21 assertions. Seeing everything pass is an awesome sensation and I've already learned a lot! All this are "only" unit tests, testing how a user account behaves, which required properties are needed and making sure they really exist within the object. Here's one of those tests:

![](https://c10.patreonusercontent.com/3/eyJ3IjoxMjQwfQ%3D%3D/patreon-media/p/post/24312911/bcf21c101c6440c08337fe5780da282a/1?token-time=1552176000&token-hash=bO2nQR-NB4Z257eo9tLpyt9DW0B2-15hlgIc-elhbhc%3D)

With this method, I can ensure that the User object has the required basic info I defined when designing the whole co-worker management system. If any of these properties behave differently or cease to exist, this test will error out and warn me that I may be breaking a lot of functionality, unintentionally. Amazing!

I'll try to continue to advance on this on a daily basis, too, so it's probably safe to say that'll be one of the topics on these daily blog posts. 
