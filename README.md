# Identicon

PHP aplication that generates identicons.
It is built with [Silex](http://silex.sensiolabs.org) and [Imagine](http://imagine.readthedocs.org), 
and is inspired by Github's [Identicons](https://github.com/blog/1586-identicons) project.

[![Build Status](https://travis-ci.org/rmhdev/identicon.svg)](https://travis-ci.org/rmhdev/identicon)

**What is an identicon?** (from [wikipedia](http://en.wikipedia.org/wiki/Identicon))

>It is a visual representation of a hash value [...] that serves to
identify a user of a computer system as a form of avatar while protecting
the users' privacy.

## Example

If you want to get `username`'s avatar, you only have to use the next URL:

```
http://your-identicon-server/username.png
```
The response of the app will be the next image: 

![Identicon demo](doc/identicon.png)

You can check the [online demo](http://identicon.rmhdev.net).

## Installation

Get the source code of the project from GitHub. You have two options:

A. Clone it:

```bash
git clone git@github.com:rmhdev/identicon.git /YOUR/FOLDER
git checkout v1.0.0
```

B. Download it:

Check the [latest release](https://github.com/rmhdev/identicon/releases) and copy it to your installation folder.

### Project dependencies

Retrieve all the dependencies using [Composer](http://getcomposer.org/).
Install it and then run the next command:

```bash
php ./composer.phar install --no-dev
```

### Server configuration

This project is built using [Silex](http://silex.sensiolabs.org).
The official docs will give you more information about
[how to configure your server](http://silex.sensiolabs.org/doc/web_servers.html). Some tips:

- the **document root** must point to the `identicon/web/` directory.
- folders in `identicon/var/` must be **writable** by the web server.

### Play with Identicon

If you are using PHP 5.4+, its built-in web server will help you to play with this project:

```bash
cd identicon/
php -S localhost:8080 -t web web/index.php
```

Easy, right? Just open a browser and enter `http://localhost:8080`

## Customize the identicon generator

In the `config` folder, copy the file `parameters.dist.json` and paste it with the name `parameters.json`.
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
        "default": "plain",
        "extra": ["circle", "pyramid", "rhombus"]
    }
}
```

### Default Identicon types

Plain, pyramid, circle and rhombus

![Plain type](doc/plain.png)
![Pyramid type](doc/pyramid.png)
![Circle type](doc/circle.png)
![Rhombus type](doc/rhombus.png)

### Create your own Identicons

Take a look at `src/Identicon/Type/`. If you want to create a new Identicon type,
add a new folder and extend the `AbstractIdenticon` class. For example:

```php
<?php

namespace Identicon\Type\MyType;

use Identicon\AbstractIdenticon;
use Imagine\Image\Point;

class Identicon extends AbstractIdenticon
{
    protected function drawBlock($x, $y)
    {
        //parent::drawBlock($x, $y);
        $this->image->draw()->dot(
            $this->getCell($x, $y)->getCenter(),
            $this->getColor()
        );
    }
}
```

## Unit tests

Check the [Travis page](https://travis-ci.org/rmhdev/identicon) to see the build status.
If you want to run the tests by yourself, run the next command:

```bash
php ./vendor/bin/phpunit
```

If you don't have `phpunit` installed, run:

```bash
php ./composer.phar install --no-dev
```
