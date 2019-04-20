---
extends: _layouts.article
section: content
title: How I managed to control chaos with Laravel
date: 2018-06-14
cover_image: https://cdn-images-1.medium.com/max/800/0*_nYV0zRg4leoLAvL
description: "Automatizing fetching, normalizing and storing information from different online sources without breaking a sweat."
---
> Originally posted on [Medium](https://medium.com/@josepostiga/how-i-managed-to-control-chaos-with-laravel-d47b9444a451)

## A little of context

At the time of writing this post, I’m a Full-Stack Web Developer @ TBFiles, a Portuguese company with operations also in Angola and Mozambique. Our core business is dematerialization, workflow design and optimization of documents and digital processes. We help our customers transferring offline information (documents, forms, processes in general). By using digital processes, instead of physical documents, our customers save time, space (because they don’t have to deal with physical archives) and keeps all information accessible through a single application, in a friendly, searchable and scalable way.

This kind of service, however, requires that we support data collection and extraction using different technologies (like FTP, e-mail and third-party web-services) with very distinctive nuances that comes with each customer having its own ways of organizing information.

## The Chaos

Up until now, whenever a new customer subscribed to our “Digital Archive” product, our team would have to create a specific PHP script that would connect to whichever systems this customer uses, import the required data to index, deal with the specific logic of extracting and saving in our infrastructure and then display that information in our application. This work could take some time, depending on the client’s specifications, number and type of systems we’d need to handle and if our team would need to coordinate with third-party tech teams, to number a few…

This was manageable but, as everything else, when we got to a serious number of customers using this service, this type of workflow began to backfire.

One might ask: “But didn’t anyone in that team thought about scaling when developing the service?”. To which I answer: “Of course! But there’s something called Management, it’s friend Delivery Date and their lawyer called Pressure”. Jokes aside, the first step taken was to create this fast, so it could be tested and proved to be a viable business.

Having proved that, in fact, it was a good product, our team gathered to discuss how we could manage the chaos that was starting to be. And we needed to move fast, because Management’s lawyer (The Pressure) never left the building, and we found out that Deliver Date had a twin…

## Getting The Party Started!

After a quick team brainstorming, we came to the conclusion that we had to develop a two step process: a Collector, for fetching information, and an Extractor, to handle data extraction and send it to our main application’s persistent layer, to be available to our customers.

I was put in charge of developing the Collector part, and another colleague was in charge of developing the Extractor. I was pretty excited! I was the newest member of the team and already got assigned this kind of responsibility. I immediately got to work.

The requirements for the Collector were:
* It must be capable of connecting to all the data sources that the company currently supports (e-mail inboxes, FTP file repositories and Shared Directories) and save all found information, structuring it in a normalized way and storing it for posterity.
* It must have a web UI to allow non tech users to access and configure data buckets (a repository of a type of data, e.g.: an e-mail bucket), set rules that would allow the system to know exactly what to import and a few post-import actions for clean-up work afterwards.
* It must have an API layer to allow other internal services to access data for specific processing and other business logic that may be needed.

That’s it. Those three bullet points were the main requisites for my part of the system. Deliver Date was set for two weeks after initial kick-off.

## Choosing The Tool For The Job

Two weeks was a challenge… I needed to get my part done, and then test the result alongside my colleague’s. To be able to deliver a good, robust application it immediately came to my mind the Laravel framework. I didn’t even think twice: I’ve had used it successfully on another project, before this one, and loved the experience, thanks to it’s fluent syntax, well documented interface and awesome community. And the benchmarks were impressive, too.

OK, I can do this!

## Laravel New Collector

Yep, let’s start talking about the implementation.

Before starting to code, I got myself thinking a little more about how to handle e-mails. I knew that for FTP and shared folders (we have other services that scans physical documents and makes them available in shared folders) I could simply use Flysystem, which comes out-of-the-box with Laravel. However, e-mail accessing and handling is not straightforward as one might think. I though: “There must be a package for that!”. So, like every other super dev, I went to GitHub and searched a bit about available projects that could handle some of the heavy lifting of handling IMAP connections.

I quickly found IMAP, by David de Boer. Exactly what I needed: a nice, tested library to handle IMAP connections, message search and retrieval and attachments download through a very straightforward API. The examples clearly showed me I could definitely use this without much work on my end. Awesome!

While Composer was doing it’s thing, I was thinking about how I could make everything configurable in a simple way. I needed to add a UI layer so that an authenticated user could configure the different connections, rules for data validation and some actions for cleaning up already handled data.

The term Buckets came to my mind, influenced by the AWS S3 Buckets. Indeed it’s a very simple term to describe a resource that holds contents. A Bucket would only have one type of data, would contain it’s rules to filter out data that would not conform to some business rules and would also contain the actions to be executed after the filtered data was imported to our systems.

## The UI Layer

I’m not a designer. I definitely can’t draw nothing useful, even if my life depended upon it… Luckily, projects like Bootstrap exists and can help anyone like me on that matter. Also, I found out a UI project called Tabler, which I could use to handle my “original design”.

So I created the normal index, edit and create panels for managing Buckets and associating them to our already existing customers’ table. In the next two days I created the necessary panels for managing the Buckets’ rules and actions. Also, I made the necessary listing and showing panels for the e-mails that would be imported, and a download link for each attachment they could possible have associated.

I’ve also added a button for starting and stopping the buckets process job. And that’s the interesting part of this article.

## Getting To The Fun Stuff

By the end of the first week of work, I’d have all my controllers, models and views prepared to receive the data from the sources defined in the Buckets.

One thing I made sure was done right was the encryption of all Bucket’s configuration, because it’ll be holding access information to our customers’ data. That’s a pretty sensible information to be holding in plain text, so I used the encrypt() and decrypt() methods that Laravel has at our disposal. I’d encrypt the data before saving it to the database and decrypt it only when accessing them.

After this, I stepped back and thought a little more about how the system was going to work. I definitely knew I’d need to use queuable classes to make the application as snappy as possible, by sending the real work to be handled on the background. But how to best approach this? How should my system handle that? To help me think, I picked up my notepad and came up with this workflow:
* A scheduled command should query for all the active Buckets that were eligible for processing.
* For each active Bucket found, a new Process Job should be dispatched to connect to the source defined.
* For each data (should it be a message, a file or other supported media) the respective Process Job should get the Bucket’s rules and only process the data that passed every rule.
* If one of the rules were not correctly validated, this data would be ignored by the Process Job.
* Finally, the Process Job would import a normalized version of the fetched data, check for attachments (in case of an email) and download them to the disk for archiving purposes. In case there were any PDF file we’ll need to extract the text and save it to a persistent layer to show on our application.
* At the very end, the Process Job would execute every action defined in the Bucket’s configuration.

After getting the workflow straighten up, I created a new Laravel command which would query, every minute, which Buckets were suppose to be processed and dispatch a job for each one, like this:

```php
<?php

namespace App\Console\Commands;

use App\Bucket;
use App\Jobs\ProcessEmailBuckets;
use App\Jobs\ProcessFtpBuckets;
use App\Jobs\ProcessNfsBuckets;
use Illuminate\Console\Command;

class Collector extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'collector';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initializes the processing of buckets';

    /**
     * Execute the console command.
     *
     * @param Bucket $buckets
     * @return mixed
     */
    public function handle(Bucket $buckets)
    {
        $buckets->freeForProcess()->get()->each(function ($bucket) {
            if ($bucket->type === 'email') {
                ProcessEmailBuckets::dispatch($bucket)->onQueue('collector');
            } elseif ($bucket->type === 'ftp') {
                ProcessFtpBuckets::dispatch($bucket)->onQueue('collector');
            } elseif ($bucket->type === 'nfs') {
                ProcessNfsBuckets::dispatch($bucket)->onQueue('collector');
            }
        });
    }
}
```

Nothing fancy. To avoid any overlaps I scheduled this Collector command using the withoutOverlapping() method chained, like this:

```php
/**
 * Define the application's command schedule.
 *
 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
 * @return void
 */
protected function schedule(Schedule $schedule)
{
    $schedule->command('collector')->everyMinute()->withoutOverlapping();
}
```

This takes care of the first two bullet points, previously listed above. So far, so good. I got the ProcessFtpBuckets and ProcessNfsBuckets Job classes working in little time, thanks to the awesome Flysystem support.

But, while I was coding the ProcessEmailBuckets Job class, I started thinking about the fact that it should not be aware of the concrete implementation of the IMAP library. To avoid falling in a common trap, and to keep me from messing my Future Self’s life, I created the repository class EmailServiceRepository to be used as a wrapper for the IMAP library, with a few simple methods that would enable the job class to deal with the mailbox connections. Here’s the interface that defined my implementation:

```php
<?php

namespace App\Repositories\Contracts;

interface EmailServiceInterface
{
  /**
   * Connects to server and authenticates.
   *
   * @param string $server
   * @param string $username
   * @param string $password
   * @return self $this
   */
  public function connect(string $server, string $username, string $password);
  
  /**
   * List all available mailboxes.
   *
   * @return array
   */
  public function listMailboxes();
  
  /**
   * Lists all messages.
   *
   * @param string $mailbox
   * @param array $filters
   * @return mixed
   */
  public function getEmails(string $mailbox, array $filters = []);
  
  /**
   * Creates normalized message metadata.
   *
   * @param Message $message
   * @return array
   */
  public function normalize(Message $message);
  
  /**
   * Downloads attachment to disk and return normalized metadata.
   *
   * @param Attachment $attachment
   * @param string $baseFolder
   * @return array
   */
  public function downloadAttachment(Attachment $attachment, string $baseFolder = 'attachments');
  
  /**
   * Marks given messages as read.
   *
   * @param string $mailbox
   * @param string $messagesNumbers
   * @return mixed
   */
  public function markAsRead(string $mailbox, string $messagesNumbers);
  
  /**
   * Marks given messages as unread.
   *
   * @param string $mailbox
   * @param string $messagesNumbers
   * @return mixed
   */
  public function markAsUnRead(string $mailbox, string $messagesNumbers);
  
  /**
   * Moves message to given mailbox path.
   *
   * @param Message $message
   * @param string $mailbox
   * @return void
   */
  public function move(Message $message, string $mailbox);
  
  /**
   * Deletes given message.
   *
   * @param int $messageNumber
   * @param string $mailbox
   */
  public function delete(int $messageNumber, string $mailbox = 'INBOX');
}
```

Now I could, if needed, swap the concrete implementation, at any point in the future, without ever needing to change the Process Job class, as long as I respect that Interface.

## Rules & Actions

Not all communications are to be imported, because it may be SPAM, irrelevant to the Bucket scope and, so, are not to be made available. To handle those cases, I created a simple rule based validation system and post-import actions (for cleaning up, afterwards).

The rules, which are defined through the UI, basically consists on saying that the Bucket only cares for specific data. For example, a Bucket could be configured to only archive communications sent from a specific e-mail address. That rule configuration would set the ProcessEmailBuckets class to ignore any message that was not be sent by that e-mail.

Rules could be configured to check for the sender address, the receivers’ addresses, keywords on the subject and/or body, if it has attachments and others… They can even be stacked together, meaning that a message would only be imported if it passes all the rules defined for that Bucket. Calm down… It looks a lot more complicated than it really is. Here’s the validator method, for clarification:

```php
/**
 * Validates given message against import rules.
 *
 * @param array $message
 * @return mixed
 */
protected function validate(array $message)
{
    return $this->bucket->rules()->get()->every(function ($rule) use ($message) {
        switch ($rule->validator) {
            case 'all':
                return true;
                break;

            case 'sender':
                return str_contains($message['from'], $rule->param);
                break;

            case 'receiver':
                return str_is($message['to'], $rule->param);
                break;

            case 'subject':
                return str_contains($message['subject'], $rule->param);
                break;

            case 'body':
                return str_contains($message['body'], $rule->param);
                break;

            case 'attachments':
                return !empty($message['attachments']);
                break;

            default:
                return false;
        }
    });
}
```

Following the same logic, actions, which are also defined through the UI, configures the work to be done, by the ProcessEmailsBuckets class, after importing a message, successfully. Actions could be one of the follow: marking a message as read/imported, move a message to another mailbox, and delete the message from the source. This will ensure that the source would stay organized and optimized for the next run. Here’s how I implemented this:

```php
/**
 * Executes after process actions defined.
 *
 * @param array $message
 */
protected function executeActions(array $message)
{
  $this->bucket->actions()->get()->each(function ($action) use ($message) {
      if ($action->type === 'flag_as_seen') {
          $this->repository->markAsRead($this->bucket->connection['mailbox'], $message['number']);
      } elseif ($action->type === 'move') {
          $message = $this->repository->getEmails($this->bucket->connection['mailbox'], ['number' => $message['number']]);
          $this->repository->move($message, $action->param);
      } elseif ($action->type === 'delete') {
          $this->repository->delete($this->bucket->connection['mailbox'], $message['number']);
      }
  });
}
```

Handling Attachments

Another little nuance I needed to handle was extracting text from PDFs. So, if the ProcessEmailBuckets Job class detected a PDF file/attachment, not only would it download and archive it, but it should dispatch another job class, called ScanPdfAttachments.

This class would run a third-party software called PdfToText, installed on the server, that takes the PDF file path, as input, and outputs it’s text. The ScanPdfAttachments class would, then, take that output and save it to a column associated to the PDF file, on our database. The Extractor, then, could use this text to gather relevant business data.

I need to refer that this was as simple as pull a package named pdf-to-text, from a well known company called Spatie (they develop a lot of great packages), and call a simple static method they provide: Pdf::getText().

> By the way, this reminds me I need to send a postcard to them, for using their package!

## Last Save!

Awesome! Now I have my Collector ready for action! It successfully allows the creation of buckets, define their rules and actions, and access to the data imported through a Web UI, I have the Process Jobs running in the background and I have an API layer so my colleague could get the normalized data from my Collector and deal with specific customers’ business extraction rules and data delivery.

And now, we don’t need to create a new script for each customer that subscribes our “Digital Archive” service. We simply create the necessary Buckets, set the rules for the data import and normalization, and carry on with our life, knowing that the Collector will be actively listening for data to be fetched and make it available to other internal systems, like the Extractor.

And all of this was made in about two weeks, thanks to the Laravel framework, which allowed me to abstract common needs and focus on my main problem. Tests included!

***

### Notes From The Author

Thank you very much for reading this!

As you may notice, I’ve not revealed many of the technical details, nor have I revealed several other parts of the system. I have a very strict contract with my current employer that doesn’t allow me to show you all the bits and pieces behind this project. If you got disappointed by that, I’m really sorry…

What I wanted to show you, however, was my process of thinking, tools chosen and the reasons behind my decisions. Everyone can make the same, or even better, solution on other languages and/or frameworks. The interesting, and the hard, part is the process of thinking, the ability to break down a big problem into smaller ones and not losing focus on the main goal.
