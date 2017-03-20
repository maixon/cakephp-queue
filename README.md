# CakePHP Queue Plugin
[![Build Status](https://api.travis-ci.org/rgoro/cakephp-queue.svg?branch=master)](https://travis-ci.org/rgoro/cakephp-queue)
[![Coverage Status](https://img.shields.io/codecov/c/github/rgoro/cakephp-queue/master.svg)](https://codecov.io/github/rgoro/cakephp-queue?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rgoro/cakephp-queue/v/stable.svg)](https://packagist.org/packages/rgoro/cakephp-queue)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg)](https://php.net/)
[![License](https://poser.pugx.org/rgoro/cakephp-queue/license)](https://packagist.org/packages/rgoro/cakephp-queue)
[![Total Downloads](https://poser.pugx.org/rgoro/cakephp-queue/d/total)](https://packagist.org/packages/rgoro/cakephp-queue)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-yellow.svg)](https://github.com/php-fig-rectified/fig-rectified-standards)

This branch is for use with **CakePHP 3**.


## Background:

This is a very simple and minimalistic job queue (or deferred-task) system for CakePHP.
It is mainly **for demo purposes** on how queues work and doesn't have any dependencies.

Overall functionality is inspired by systems like Gearman, Beanstalk or dropr, but without
any illusion to compete with these more advanced Systems.

The plugin is an attempt to provide a basic, simple to use method to enable deferred job execution,
without the hassle of setting up or running an extra queue daemon, while integrating nicely into
CakePHP and also simplifying the creation of worker scripts.

Please also read the [blog post](http://www.dereuromark-deferred-execution-in-cakephp/).
For more robust and production use cases please see the [awesome list](https://github.com/FriendsOfCake/awesome-cakephp#queue).

### Why use deferred execution?

Deferred execution makes sense (especially in PHP) when your page wants to execute tasks, which are not directly related to rendering the current page.
For instance, in a BBS-type system, a new users post might require the creation of multiple personalized email messages,
notifying other users of the new content.
Creating and sending these emails is completely irrelevant to the currently active user, and should not increase page response time.
Another example would be downloading, extraction and/or analyzing an external file per request of the user.
The regular solution to these problems would be to create specialized cronjobs which use specific database states to determine which action should be done.

The Queue plugin provides a simple method to create and run such non-user-interaction-critical tasks.

While you can run multiple workers, and can (to some extent) spread these workers to different machines via a shared database,
you should seriously consider using a more advanced system for high volume/high number of workers systems.

## What this package is

First go read the original background and use cases on [the readme for the forked package](https://github.com/dereuromark/cakephp-queue/blob/master/README.md)

This is an implementation of Mark Scherer's [simple Cake PHP queues](https://github.com/dereuromark/cakephp-queue/) replacing MySQL with MongoDB for the backend.

## What this package is not

This is not a drop-in replacement for the original package.  While I intend to work with that
package's mantainer to integrate this backend, this is intended to satisfy a particular use case
first and be of generic use later.

A big issue is that the main classes ([QueuedJobsTable](https://github.com/rgoro/cakephp-queue/blob/master/src/Model/Table/QueuedJobsTable.php),
[QueueShell](https://github.com/rgoro/cakephp-queue/blob/master/src/Shell/QueueShell.php) and
[QueueTask](https://github.com/rgoro/cakephp-queue/blob/master/src/Shell/Task/QueueTask.php)) were
just copied to new MongoDB based implementations ([QueuedJobsCollection](https://github.com/rgoro/cakephp-queue/blob/master/src/Model/MongoCollection/QueuedJobsCollection.php),
[MongoQueueShell](https://github.com/rgoro/cakephp-queue/blob/master/src/Shell/MongoQueueShell.php) and
[MongoQueueTask](https://github.com/rgoro/cakephp-queue/blob/master/src/Shell/Task/MongoQueueTask.php)).  An integrated implementation should have these classes as subclasses of
the originals (or a common base class) and a factory to select which implementation to use based on
the configuration.

## Installation and Usage
See [Documentation](docs).

## To Do items:
 - The statistics that were implemented in the original package are yet to be ported to Mongo.
 - The backend controller has not been revised nor tested.

## History

### Recent Improvements
- MongoDB Backend
- QueuedJobs table instead of QueuedTasks (Tasks are the implementing classes only)
- json_encode/decode instead of serialize
- Priority for jobs
- Transactions on getting a new job if supported from the database
- Code improvements, stricter typehinting

### And...

A huge thx to Max ([Dee-Fuse](https://github.com/Dee-Fuse)) for making the 3.x upgrade complete!

Modified by David Yell ([davidyell](https://github.com/davidyell))
- Basic CakePHP 3.x support

Modified by Mark Scherer ([dereuromark](https://github.com/dereuromark))
- CakePHP 2.x support
- Some minor fixes
- Added crontasks (as a different approach on specific problems)
- Possible (optional) Tools Plugin dependencies for frontend access via /admin/queue
- Config key "queue" is now "Queue" ($config['Queue'][...])

Added by Christian Charukiewicz ([charukiewicz](https://github.com/charukiewicz)):
- Configuration option 'gcprop' is now 'gcprob'
- Fixed typo in README and variable name (Propability -> Probability)
- Added a few lines about createJob() usage to README
- Added comments to queue.php explaining configuration options
