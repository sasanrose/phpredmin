NanoPHP
=======

NanoPHP framework is a simple PHP5 MVC framework with just core functionalities. Nanophp provides you with the least features you need to write simple yet powerful web/cli applications.
The idea is to remove all the overheads and headaches of complicated libraries, and subsitute them with simple frequent code frames.

Structure
=========

There is an autoload functions that can easily load all the classes placed at libraries, controllers, models and helpers directory.
Index.php is the bootstrap of the framework. The main class is router.php that parses the input to index.php and routes the request to controllers.

Features
========
* Router
  * Infinite Method Parameters
  * Query Strings
  * URL Redirect
* URL-Friendly
* Logger
* Error Handler
* Session Handler
* Input Handler (POST, GET, PUT, DELETE)
* Template Drivers
  * Simple Nested PHP Template with main layout
  * JSON Templates
* Database Drivers
  * Redis

Projects using Nanophp
======================
[PHPRedmin](https://github.com/sasanrose/phpredmin): Simple web administrator panel for Redis
