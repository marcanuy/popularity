<?php

use Marcanuy\Popularity\Stats;
use Marcanuy\Popularity\ExamplePost;

class StatsTest extends Illuminate\Foundation\Testing\TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->prepareForTests();
    }

    private function prepareForTests()
    {
        Artisan::call('migrate',array('--bench' => 'marcanuy/popularity'));
        Eloquent::unguard();
    }

    public function tearDown()
    {
        parent::tearDown();
        Artisan::call('migrate:reset');
    }

    public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';
		return require __DIR__.'/../../../../bootstrap/start.php';
	}

    /**
     * @expectedException Illuminate\Database\QueryException
     * @expectedExceptionMessage SQL: select * from `example_posts` where `example_posts`.`id` = 1 limit 1
     */
    public function testUpdateStatsPolymorphicRelationSetUp()
    {
        $post = new ExamplePost();
        $post->id = 1;
        
        $model = new Stats();
        $post->popularityStats()->save($model);
        
        $model->updateStats();

        $this->assertEquals( $post->id , $model->trackable_id );
        $this->assertEquals( get_class($post) , $model->trackable_type);
        //test access parent model
        $model->trackable;
    }
    
    public function testUpdateStatsNewDay()
    {
        $raw_stats_data = array(
            "2013-03-31" => 900, 
            "2013-04-01" => 3,
            "2013-04-28" => 4,
            "2013-04-29" => 5,
        );
        $newDate = "2013-04-30";
        $data = array(
            'one_day_stats' => 5,
            'seven_days_stats' => 9,
            'thirty_days_stats' => 12,
            'all_time_stats' => 100,
            'raw_stats' => $raw_stats_data
        );
        $model = Stats::create($data);

        $model->updateStats($newDate);

        $raw_stats = $model->raw_stats;
        $this->assertEquals( 1 , $raw_stats[$newDate] , "Stats for a new date should be 1" );
        $this->assertEquals( 1 , $model->one_day_stats , "Updating one_day_stats for a new date should be 1"); 
        $this->assertEquals( 10 , $model->seven_days_stats , "Wrong stats for seven days");
        $this->assertEquals( 13 , $model->thirty_days_stats , "Wrong stats for thirty days");
        $this->assertEquals( 101 , $model->all_time_stats , "Wrong stats for all time stats");
    }

    public function testUpdateStatsExistingDay()
    {
        $stats = new Stats();
        $dateNow = "2013-03-05";
        $hits = 8;
        $raw_stats_data = array(
            $dateNow => $hits,
        );
        $data = array(
            'one_day_stats' => $hits,
            'seven_days_stats' => $hits,
            'thirty_days_stats' => $hits,
            'all_time_stats' => $hits,
            'raw_stats' => $raw_stats_data
        );
        $model = Stats::create($data);
        
        $model->updateStats($dateNow);

        $expectedHits = $hits + 1;
        $this->assertEquals( $expectedHits , $model->raw_stats[$dateNow] , "Invalid hit increments" );
        $this->assertEquals( $expectedHits , $model->one_day_stats , "Updating one_day_stats for a new date should be ".$hits); 
        $this->assertEquals( $expectedHits , $model->seven_days_stats , "Wrong stats for seven days");
        $this->assertEquals( $expectedHits , $model->thirty_days_stats , "Wrong stats for thirty days");
        $this->assertEquals( $expectedHits , $model->all_time_stats , "Wrong stats for all time stats");
    }

    public function testUpdateStatsContainsMaximum30Days()
    {
        $maxDays = 30;
        $raw_stats_data = array();
        // Generate 30 dates => hits
        for ($i = 1; $i <= $maxDays; $i++)
            {
                $raw_stats_data["2013-01-".$i] = 1;
            }
        $data = array( 'raw_stats' => $raw_stats_data );
        $model = Stats::create($data);
        $this->assertCount( $maxDays, $model->raw_stats, "Raw stats array should track 30 days maximum" );
        
        $model->updateStats();
        
        $this->assertCount( $maxDays, $model->raw_stats, "Raw stats array should track 30 days maximum" );
    }

    public function testUpdateStatsDateNullUsesCurrentDate()
    {
        $model = Stats::create(array());

        $model->updateStats();

        $this->assertNotEmpty( $model->raw_stats[gmdate("Y-m-d")] );
        $this->assertCount( 1, $model->raw_stats );
    }

    public function testUpdateStatsWithInvalidDateFormatNotSaves()
    {
        $model = Stats::create(array());
        $dateWithWrongFormat = "01-01-2013";
        $result = $model->updateStats($dateWithWrongFormat);

        $this->assertFalse($result);
        $this->assertFalse($model->raw_stats);
    }
    
    public function testGetElementsList()
    {
        $post = new ExamplePost();
        $post->id = 1;
        $model = new Stats();
        $post->popularityStats()->save($model);
        
        $model->updateStats();

        $post->popularityStats()->orderBy('one_day_stats')->get()->all();
        $this->markTestSkipped();
        $this->assertEquals(1, $post->popularityStats()->getResults());
    }

    public function testHitCreatesNewStatsForAnElement()
    {
        //object mock
        $post = new ExamplePost();
        $post->id = 1;
        $post->exists = true;
        
        $post->hit();

        $this->assertEquals( 1, $post->popularityStats()->first()->one_day_stats );
    }
    
    public function testHitUpdatesStatsForAnExistingElement()
    {
        $stats = new Stats();
        $dateNow = date("Y-m-d");
        $hits = 8;
        $raw_stats_data = array(
            $dateNow => $hits,
        );
        $data = array(
            'one_day_stats' => $hits,
            'seven_days_stats' => $hits,
            'thirty_days_stats' => $hits,
            'all_time_stats' => $hits,
            'raw_stats' => $raw_stats_data
        );
        $model = Stats::create($data);
        //object mock
        $post = new ExamplePost();
        $post->exists = true; //simulate it has been retrieved from db
        $post->id = 1;
        $post->popularityStats()->save($model);
            
        $post->hit();

        $this->assertEquals( ++$hits, $post->popularityStats()->first()->one_day_stats );
        //$this->assertInternalType('int', gettype($post->popularityStats()->first()->one_day_stats));
    }
}