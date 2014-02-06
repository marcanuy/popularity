<?php namespace Marcanuy\Popularity;

use \Illuminate\Database\Eloquent\Model;
use \Marcanuy\Popularity\PopularityInterface;

/**
 * Class that shows how to implement the polymorphic relation in other
 * models that extends Eloquent, also used for testing.
 */
class ExamplePost extends Model implements PopularityInterface{
    
    public function popularityStats()
    {
        return $this->morphOne('Marcanuy\Popularity\Stats', 'trackable');
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