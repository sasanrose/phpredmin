PHPRedMin
=========

PHPRedMin is a simple web interface to manage your redis database.

## Technologies Used

![Nanophp](https://github.com/sasanrose/NanoPHP) framework

![phpredis](https://github.com/nicolasff/phpredis) redis module for PHP

[Bootstrap](http://twitter.github.com/bootstrap) front-end framework

[JQuery](http://jquery.com/) JavaScript library

![Nvd3](https://github.com/novus/nvd3) reusable chart library for d3.JS

## Installation

Just drop phpredmin in your webserver's root directory and point your browser to it (You also need [phpredis](https://github.com/nicolasff/phpredis) installed)

## Features

### Statistics

_Note:_ If you want this feature to work, you have to setup the cron to gather data from your redis server as follows:

```bash
* * * * * root cd /var/www/phpmyredis/public && php index.php cron/index
```

#### Memory

![](http://dl.dropbox.com/u/5413590/phpredmin/memory.jpg)

#### CPU And Clients

![](http://dl.dropbox.com/u/5413590/phpredmin/cpu.jpg)

#### Keys and Connections

![](http://dl.dropbox.com/u/5413590/phpredmin/keyspace.jpg)

#### Databases

![](http://dl.dropbox.com/u/5413590/phpredmin/dbkeys.jpg)

### Info

Information about your redis setup

![](http://dl.dropbox.com/u/5413590/phpredmin/info.jpg)

### Configurations

View your redis runtime configurations

![](http://dl.dropbox.com/u/5413590/phpredmin/config.jpg)

### Slowlog

Find slow redis commands

_Note:_ PHPRedMin uses eval to fetch slow log. So to use this feature you need redis version >= 2.6.0

![](http://dl.dropbox.com/u/5413590/phpredmin/slowlog.jpg)

### Database Manipulation

You can switch between databases easily:

![](http://dl.dropbox.com/u/5413590/phpredmin/dbselect.jpg)

You can flush selected database or all databases. You can also save database to a file on disk:

![](http://dl.dropbox.com/u/5413590/phpredmin/actions.jpg)

### Key-Value Manipulation

#### Search

The search box will let you to easily search keys in the selected database:
_Note:_ Becareful, since this still doesn't support pagination, try to limit your search otherwise if your search result is too long (e.g. *) then your browser might crash.

![](http://dl.dropbox.com/u/5413590/phpredmin/search.jpg)

The search results will be shown to you as a table. In this table besides the basic information about each key, PHPRedMin provides you with some actions:

* Expire (Sets TTL for a key)
* View (Shows keys' value/values and lets you manipulate it/them)
* Rename
* Move (Moves key to another database)
* Delete

![](http://dl.dropbox.com/u/5413590/phpredmin/results.jpg)

#### Add key-Value

From the main page of PHPRedMin you can add different types of key-values.

##### Strings

![](http://dl.dropbox.com/u/5413590/phpredmin/addstring.jpg)

##### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/addhash.jpg)

##### Lists

![](http://dl.dropbox.com/u/5413590/phpredmin/addlist.jpg)

##### Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addset.jpg)

##### Sorted Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addzset.jpg)

### View keys' values

PHPRedMin makes it easier for you to manage your lists, hashes, sets and sorted sets. After searching for a special key, you can choose view action to see the contents of that key (According to its type) and manipulate them.

#### Lists

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/listresult.jpg)

#### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/hashresult.jpg)

#### Sets

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/setresult.jpg)

#### Sorted Sets

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/zsetresult.jpg)

## License

BSD License

Copyright © 2013, Sasan Rose

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
* Neither the name of the PHPRedMin nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
