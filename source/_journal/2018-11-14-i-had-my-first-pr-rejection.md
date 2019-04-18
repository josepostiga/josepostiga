---
extends: _layouts.journal
section: content
title: I had my first PR rejection
date: 2018-11-14
description: "Yeah, it sucks."
---

Yeah, it sucks.

Last week I tried to contribute to Mohammed Said's Wink Laravel package (https://github.com/writingink/wink) in order to make it use the Laravel's default auth system, since Wik uses its own guards, register and login workflow.

After a couple days of work, and a nice discussion on the PR, Mohammed though that it was best not to merge the PR because he wanted it to be a more flexible solution as my PR would force Wink to use Laravel's default database connection and would update the users table with Wink specific columns.

Although the feeling of rejection sucks, I thank Mohammed, and others that commented on my PR, as they allowed me to grow and learn that contributing with open source is not all about quantity of PRs merged, but the quality of the products afterwards.

But this isn't over, yet. We're still discussing a better approach to this situation. If you're interesting what we're planning, check the issue on github: https://github.com/writingink/wink/issues/13
