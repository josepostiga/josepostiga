---
extends: _layouts.journal
section: content
title: It's Friday and I'm thinking about repositories!
date: 2019-02-02
cover_image: https://c10.patreonusercontent.com/3/eyJ3IjoxNjAwfQ%3D%3D/patreon-media/p/post/24400117/742cd4f95b3b4aa8a66f007a462f9877/2?token-time=1552176000&token-hash=8yVESTz8WMeFK1P2xxmNEJVa0-gkmE2n4sHhfQ6gJMg%3D
description: 
---

Everybody loves Friday, right? I've no special reaction to Friday, really. I love what I do, so I'm always enjoying my days. However, weekends are when I spend a little more time studying and training. I think that continue improving and challenging ourselves is very important to keep being relevant in our work. Especially if you want to work your way up on your career. But I digress...

Since today I needed to leave the office in the afternoon, I don't have many updates to share. I finally get to see my colleagues really try to change their work process and use work branches and pull requests to privilege the sharing of knowledge. Already saw a lot of exchange happening between them. I really hope that they get used to this new process because the big results will only come a few months from now when they realize that their domain knowledge improved.

Changing subjects, and on a more personal level, I'm still working on HUB, although I haven't touched the subject in about two days. I was doubting that the unit tests were not being well done because I was creating some tests to guarantee that required relations to other models exist. In my mind, that was a violation of testing in isolation. However, after exposing my doubts to the Laravel Portugal Community, they quickly told me I was overreacting. So this project is still under heavy development. I'm about to start the first feature tests for the Users model.

Talking about models, I've been recently thinking about Models and Repositories. If you don't know, Repositories (from the Repositories Pattern) are classes that the main objective is to abstract the persistent layer. This way, you can exchange the persistent layer drivers without needing to change anything else on your application. Just swap the concrete implementation injected as a dependency to the related repositories and you're golden. It's a pretty awesome pattern to follow.

So, without much surprise, HUB will have the repository pattern implemented. Besides abstraction, I can control the data flowing from the application to the persistent layer, and vice-versa, which will help me keep my models clean. Also, if needed, I can apply other types of logic against the data, without polluting the models, like a cache system.

Let's see how this weekend goes, in terms of more excited news about this project.