<?php namespace Marcanuy\Popularity;

interface PopularityInterface {
    
    /**
     * Sets the polymorphic relation
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function popularityStats();

    /**
     * Increments this model instance hits and calculates the rest of
     * the days stats. It only works when this instance already has
     * an id.
     * 
     * @return boolean true if the hit was set, false if not
     */
    public function hit();
        
}