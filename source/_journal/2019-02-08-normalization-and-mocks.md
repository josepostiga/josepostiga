---
extends: _layouts.journal
section: content
title: Normalization and mocks
date: 2019-02-08
cover_image: https://c10.patreonusercontent.com/3/eyJ3IjoxNjAwfQ%3D%3D/patreon-media/p/post/24547191/dd0571cd7b524c74851e921b27f7acf9/2?token-time=1552176000&token-hash=_VXE-mcGNEJifBM3fOk36e_WWflqn4U4uADLB6KsZXA%3D
description: 
---

I finally finished all my tasks regarding the implementation of the code sniffers and fixers on all my teammates' computers and advanced to other, more challenging tasks. The one I'm currently working on is related to reports generation.

Since reports are a very sensitive part of our software, I started unit testing a specific report class, making sure that it behaves as expected, returning the required, basic, information (like the report's name, configuration) and got to the part of ensuring the right methods are called to generate a PDF. I stopped... The report classes use a third-party library to handle the generation of the PDF file and, to generate a file, at the very least, one method needed to be called. How could I ensure that that method was, in fact, called, without testing the third party code, itself?

Like I said before, I'm not an expert on TDD, but I know enough to answer this: we Mock that behaviour, creating the expectation required to ensure that a certain method(s) is(are) called. On this particular case, I needed to ensure that some options were set and that the method to load a view, to be generated as a PDF, was called. So I started mocking the behaviour, setting the required expectations.

When I tried to run the test, it broke... But, why? Going through the output of the error, I got the feedback that the class was being mocked more than once. Ok, but how? I went finding more information about the error message returned, on our lord and saviour Google. It turned out that Laravel Façades already have it activated when running in a test environment. Always learning, hein? I thought it's pretty cool. After removing the mocking definition, the "Mockery::mock(Foo::class)" part, tests went green! Right on time to commit the work in progress and leave for the day.

On our beloved HUB project, as I promised, I have advanced to the next part of the development of the users' module. I switched from unit tests to feature tests, where I began testing the creation of a user account. While doing this, I started implementing the repository class, too.

This is the Tests\Feature\UsersTest class, right now:

![](https://c10.patreonusercontent.com/3/eyJ3IjoxMjQwfQ%3D%3D/patreon-media/p/post/24547191/c35c0974fc434ddea20d57bcec0f2317/1?token-time=1552176000&token-hash=9OeiGwmEwabY5ryeZyWHJBYsf-bljQPnKURTUHY1CYA%3D)

It's incredible how little code can test so many parts of the software: the existence of the correct endpoint, the request validated input, the lack of errors on session, the correct redirection to the users' index page, that it only may be done by someone logged in and that the record is, in fact, saved to the persistent layer (thus, it tests that the repository class does communicate to this layer). Perfect!

Are you interested in seeing the repository? Here:

![](https://c10.patreonusercontent.com/3/eyJ3IjoxMjQwfQ%3D%3D/patreon-media/p/post/24547191/778a07133f6b41efb3934f5385961c48/1?token-time=1552176000&token-hash=XS8Kym_sGz0zQY24qWlCJq07EotOs74uOMYAzdXBIEs%3D)

Of course, it's incomplete. It's a work in progress and, so far, I only implemented the save method. Laravel's Service Container makes dependency injection so easy it's hard to belive that just by type-hinting the correct model class, in the constructor, is enough to connect it to the repository class and use the underlying behaviour.

Tomorrow I'll work through the rest of this users' module. 🤓