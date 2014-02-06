#Laravel 4 Popularity Package

Laravel 4 Popularity Package tracks your most popular Eloquent models based on hits in a date range and lets you display them.

----
## Table of contents
 
* [Description](#description)
* [Features](#features)
* [How does it work](how-does-it-work)
* [Issues](#issues)
* [How to install](#how-to-install)
* [Configuration](#configuration)
* [Usage](#usage)
* [License](#license)

## Features

* Tracks Eloquent models
* Tracked date ranges
  * Last day
  * Last 7 days
  * Last 30 days
  * All time

## How does it work

It makes use of Eloquent's [polymorphic relations](http://laravel.com/docs/eloquent#polymorphic-relations), so each tracked model has its own stats.
  
## Issues
See [github issue list](https://github.com/marcanuy/Popularity/issues) for current list.

-----

## How to install
### Setup
In the `require` key of `composer.json` file add the following

    "marcanuy/popularity": "1.0.x"

Run the Composer update comand

    $ composer update


In your `config/app.php` add `'Marcanuy\Popularity\PopularityServiceProvider'` to the end of the `$providers` array

    'providers' => array(

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Marcanuy\Popularity\PopularityServiceProvider',

    ),

It also automatically registers the following aliases to have them available in the app container

    'aliases' => array(
        ..
        'Stats'      => 'Marcanuy\Popularity\Stats',
        'Popularity' => 'Marcanuy\Popularity\Facades\Popularity',
	..
    ),

### Run package migrations
Generate the table that will contain hits for each Eloquent model

    php artisan migrate --package=marcanuy/popularity
    
## Configuration

For each Eloquent model you want to track you need to implement src/models/PopularityInterface.php contract like this:

    #e.g. in models/ExamplePost.php

    class ExamplePost implements \Marcanuy\Popularity\PopularityInterface
    {
        public function popularityStats()
        {
            return $this->morphOne('Stats', 'trackable');
        }

        public function hit()
        {
            //to do
        }
    }

## Usage
### Tracking hits
Call the Stats::updateStats() method each time you want to increase hits. e.g.

    $stats = new Stats();
    $stats->updateStats();
    $post->popularityStats()->save($stats);
    $stats->updateStats();

### Retrieving most popular elements
There are defined the following query scopes in \Marcanuy/Popularity/Stats

    orderByOneDayStats
    orderBySevenDaysStats
    orderByThirtyDaysStats
    orderByAllTimeStats
    
-----
## License

This is free software distributed under the terms of the MIT license

## Additional information

Inspired by and based on [WP-Most-Popular](https://github.com/MattGeri/WP-Most-Popular)

Any questions, feel free to [contact me](http://twitter.com/marcanuy).
