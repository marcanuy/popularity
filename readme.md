#Laravel 4 Popularity Package

Laravel 4 Popularity Package tracks your most popular Eloquent models based on hits in a date range and lets you display them.

----
## Table of contents
 
* [Description](#description)
* [Features](#features)
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

For each Eloquent model you want to track, you need to implement src/models/PopularityInterface.php contract like this:

    #e.g. in models/ExamplePost.php

    class ExamplePost implements \Marcanuy\Popularity\PopularityInterface
    {
        public function popularityStats()
        {
            return $this->morphOne('Stats', 'trackable');
        }

        public function hit()
        {
            //check if a polymorphic relation can be set
            if($this->exists){
                $stats = $this->popularityStats()->first();
                if( empty( $stats ) ){
                    //associates a new Stats instance for this instance
                    $stats = new Stats();
                    $this->popularityStats()->save($stats);
                }
                return $stats->updateStats();
            }
                return false;            
            }
        }

## Usage

It makes use of Eloquent's [polymorphic relations](http://laravel.com/docs/eloquent#polymorphic-relations), so each tracked model has its own stats.

### Tracking hits
For each model instance that has already been saved into the db (or already has an id), call hit() method to increase count for each time frame, e.g. in routes.php each time a post or an article is viewed, or an Eloquent event is fired.

    Route::get('post/{id}', function($id)
    {
        $post = ExamplePost::find($id);
        **$post->hit();**
        ...
    }

### Retrieving most popular elements
By default it register the route **popularity**, **popularity/day**, etc, where you can see an example of its usage. It is based on the following views that can be easily modified.

    //copy package views into your app
    php artisan view:publish marcanuy/popularity

You can include this views as subviews or adapt them to your project needs

    app/views/packages/marcanuy/popularity/item_list.blade.php
    app/views/packages/marcanuy/popularity/widget.blade.php
    
Then use them like

    $items = Popularity::getStats('one_day_stats', 'DESC', '\Marcanuy\Popularity\ExamplePost')->paginate();
    View::make('popularity::item_list')->with(array('items' => $items));

    $topItems = Popularity::getStats('one_day_stats', 'DESC', '', 3)->get();
    View::make('popularity::widget')->with(array('topItems' => $topItems));
    
## License

This is free software distributed under the terms of the MIT license

## Additional information

Inspired by and based on [WP-Most-Popular](https://github.com/MattGeri/WP-Most-Popular)

Any questions, post an [issue](https://github.com/marcanuy/Popularity/issues) or feel free to [contact me](http://twitter.com/marcanuy).
