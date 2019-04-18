---
extends: _layouts.journal
section: content
title: Small, firm, steps
date: 2019-02-09
cover_image: https://cdn-images-1.medium.com/max/800/0*RPVATmE2nc-thu9e
description: "I passed all day working on a report class..."
---

Hey there! What have you been up to?

I passed all day working on a report class, which will be responsible to fetch, and process, all maintenance work done in a building (or a set of buildings). It has been a challenge, not for the task, itself, but the domain logic I need to know before even start to make the queries. 

The most part of my day was about understanding all the dependencies associated with an entity, how their operator permissions influence the model scopes and how to associate them. I identified a few factories that needed to be created and got to the end of the day with a working first feature test: checking that the route was reachable. It looks simples but, to that simple test to work, all the "world" needed to be mapped and set up before the assertion runs and that was the bulk of the work.

Monday I'll be solely focused on the queries and JSON response to pass the task back to my teammate Ricardo, which will be responsible to connect it to the view that'll be used to generate the PDF.

I could have gone much faster if I skipped all the testing part, and some would say it was ok to do that, but it was not! I understand that shipping features is very important, but I can't jeopardise the quality of the code, especially when I still don’t master all the domain logic, or I risk injecting bugs or not catch other people's bugs before being pushed to production. It's best to be a little slow, at first, but then get a quality solution that works as designed and is tested.

On another topic, I read a very good article about MVP (Minimum Viable Product), where the author explained why he hated MVP, as well as customers hates it, too! He showed some very good points and I think you should read it, too. You can access the article here: https://blog.asmartbear.com/slc.html
