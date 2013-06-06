Simple Guardbuilder
===================
[![Build Status](https://travis-ci.org/rtablada/Simple-GuardBuilder.png?branch=master)](http://travis-ci.org/rtablada/Simple-GuardBuilder)

This repository is a simple build interface for Laravel 4.

Installation
==================
To install this package add `"rtablada/simple-guardbuilder": "dev-master"` to your composer.json file.
After running `composer update` add 'Rtabada\SimpleGuardbuilder\SimpleGuardbuilderServiceProvider' to your service providers in your app config.
To publish the configuration file for this package run `php artisan config:publish rtablada/simple-guardbuilder`.

Now you can modify the configuration to create your various deployment states.
