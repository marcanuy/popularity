<?php

Route::get('popularity/{timeRange?}', array('as' => 'popularity', function($timeRange = null)
{
    $items = null;
    switch($timeRange){
    case 'day':
        $items = Popularity::getStats()->paginate();
        break;
    case 'week':
        $items = Popularity::getStats('seven_days_stats')->paginate();
        break;
    case 'month':
        $items = Popularity::getStats('thirty_days_stats')->paginate();
        break;
    case 'all_time':
        $items = Popularity::getStats('all_time_stats')->paginate();
        break;
    default:
        return Redirect::to('popularity/day');
    }
    $topItems = Popularity::getStats('one_day_stats', 'DESC', '', 3)->get();
    return View::make('popularity::item_list')->with(array('items' => $items, 'topItems' => $topItems));
}));
