JumpUp.me Application
=======================

Introduction
------------

JumpUp.me is the car-pooling community of the future. 
We want to change the mobility towards a request-when-you-need-it-model. Both drivers and passengers shall need less time in offering and finding rides.
If a passenger needs a ride from a place to another, JumpUp.me will find the best routes for him. 
If you are the driver, you just tell the system from which place you will drive to another. The system automatically calculates the correct prices, contains a meeting-point-finding system and finds trips that are near several routes of drivers and may contain via-waypoints.


Technologies
------------

- Backend: Zend Framework 2
- Database Management: Doctrine 2 (ORM)
- Frontend: JQuery, Bootstrap, GoogleMap


Installation
------------

Using Composer (recommended)
----------------------------

    cd my/project/dir
    git clone https://github.com/sasfeld/Jumpup.git
    cd de.bht.fb6.mme2.mfg
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

You would then invoke `composer` to install dependencies per the previous
example.

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!


Database
--------

