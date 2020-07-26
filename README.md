# Symfony RESTful API
===============================================================

This application is implemented as a RESTful API using the Symfony. Application allows users to create posts and make comments or like on those posts.

Using this application users should be able to:
 * Add user
 * View/Edit their profile details.
 * View other users’ profiles.
 * Create a post.
 * View all posts.
 * Edit their own posts.
 * Archive/unarchive their own posts.
 * Delete their own posts.
 * Like a post.
 * Unlike a post.
 * View likes on a post.
 * Sort posts by date (default newest to oldest). 
 * Create a comment on a post.
 * Delete their own comments.  

NOTE: This application does not include authentication

Requirements
------------

  * PHP ^7.2.5;
  * Symfony 5.1.3;
  * MySql;
  * XAMPP;
  * and the [usual Symfony application requirements][1].

Installation
------------

Execute this command to install the project:

```bash
$ git clone https://github.com/Egzonag/symfony-rest-api.git
$ cd symfony-rest-api
$ composer update
```
Database create and migration
-----------------------------
```bash
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Usage
-----

There's no need to configure anything to run the application. Just execute this
command to run the built-in web server and access the application in your
browser at <http://localhost:8000>:

```bash
$ php bin/console server:start
```

