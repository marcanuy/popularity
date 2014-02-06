<?php namespace Marcanuy\Popularity;

class Popularity {

    /**
     * Get the query builder object for the Stats model's table prepared with the requested items,
     * ordered by one of the stats column.
     *
     * @param $days String one_day_stats|seven_days_stats|thirty_days_stats|all_time_stats
     * @param $orderType String ASC|DESC
     * @param $modelType String Filter by this Eloquent Model type
     * @param $limit int Number of items to return
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getStats( $days = 'one_day_stats', $orderType = 'DESC', $modelType = '', $limit = null)
    {
        $stats = new Stats();
        $query = $stats->newQuery();
        
        if( !empty( $modelType )){
            $query->where( 'trackable_type', '=', $modelType );
        }
        // Only retrieve elements with at least 1 hit in the requested period
        if( !empty( $days )){
            $query->where( $days, '!=', 0 );
        }
        if( !empty( $limit )){
            $query->take($limit);
        }
        $query->orderBy( $days, $orderType );
        return $query;
    }
}