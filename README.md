# identicon

Identicon generator inspired by Github's [Identicons](https://github.com/blog/1586-identicons) project.
It is built with [Silex](http://silex.sensiolabs.org) and [Imagine](http://imagine.readthedocs.org).

[![Build Status](https://travis-ci.org/rmhdev/identicon.png)](https://travis-ci.org/rmhdev/identicon)

**What is an identicon?** (from [wikipedia](http://en.wikipedia.org/wiki/Identicon))

>It is a visual representation of a hash value [...] that serves to
identify a user of a computer system as a form of avatar while protecting
the users' privacy.

![Identicon demo](doc/identicon.png)

This project will help you to generate identicons for your users.
For example, if you want to get `username`'s avatar, you only have to use the next URL:

```
http://your-identicon-server/username.png
```

You can check the [online demo](http://identicon.rmhdev.net).

## Installation

Get the source code of the project from GitHub. You have two options:

A. Clone it:

```bash
git clone git@github.com:rmhdev/identicon.git /YOUR/FOLDER
```

B. Download it:

Check the [latest release](https://github.com/rmhdev/identicon/releases) and copy it to your installation folder.

### Project dependencies

Retrieve all the dependencies using [Composer](http://getcomposer.org/).
Install it and then run the `update` command:

```bash
./composer.phar update
```

## Server configuration

You must configure your `vhost` and adjust the path to point to the `web/` folder.

Don't forget to set the proper permissions on the `cache` folder:

```bash
chmod 777 cache/
```

## Customize the identicon generator

In the `config` folder, copy the file `parameters.json.dist` and paste it with the name `parameters.json`.
The default values are:

```javascript
{
    "identicon.config": {
        "blocks": 5,
        "block-size": 70,
        "margin": 35,
        "background-color": "f0f0f0"
    },
    "identicon.type": {
        "default": "square",
        "extra": ["circle", "pyramid", "rhombus"]
    }
}
```

## Unit tests

For running tests you need [PHPUnit](http://www.phpunit.de).
After installing it, you can run the tests with the next command:

```bash
./vendor/bin/phpunit
```

