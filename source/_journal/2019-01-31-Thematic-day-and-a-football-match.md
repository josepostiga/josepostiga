---
extends: _layouts.journal
section: content
title: Thematic day and a football match
date: 2019-01-31
cover_image: https://cdn-images-1.medium.com/max/800/0*lyfCn3WPnh3cAzsm
description: "Today was one of those nice and quiet days, where nothing really notorious happens." 
---

Today was one of those nice and quiet days, where nothing really notorious happens. It was a thematic day (like a hackathon), dedicated to resolving a type of issues that have not a significant impact on our customer's day-to-day work, but that we know that must be dealt with, sometime in the future.

Today's theme was translations. We were given a spreadsheet that had all the various inconsistencies and errors on our translations (keys missing, grammar errors, etc.) and we dedicated all day dealing with that. I decided to start a little late on that, and prioritized other tasks that were planned for me: clean dead branches from the repositories and create the new develop branch for this sprint, and run a sniffer (PHPCS) and a fixer (PHPCBF) to normalize and correct all violations of the PSR2 standard, on the main project's repository. There were a lot but mostly was blank lines where there shouldn't be other issues alike (like "else" statements on new lines instead of in front of the closing brace of the "if").

However, now, we can be assured that the entire project is PSR2 compliant and I can skip to the next phase: document how to integrate the sniffer and the fixer into every body's IDE/text editor to avoid that further violations enter our repository. It looks like an unimportant task, but it'll bring tremendous benefits to the team coding style and overall code quality. It's a nice step in the right direction and shows our commitment to the quality of our product.

After the workday, we head over to an indoor soccer field, where we enjoyed a nice match and developed a friendly game between peers. It was really cool to see the Team on a more casual scene. Not that we're much more serious, but it's a little different when we're not in a work environment.

Oh, before I forget, Infraspeak launched an awesome video, that the Team made to celebrate the closing of a round of investment. I think it came off as a really cool video. See for yourself, here: https://www.youtube.com/watch?v=-2_jpT_jqdM
