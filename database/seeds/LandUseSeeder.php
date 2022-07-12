<?php

use Illuminate\Database\Seeder;

class LandUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $land_uses=array(array('idlahan' => '337', 'tema' => '3', 'jenis' => '2', 'kegiatan' => '203', 'sumber' => '290.22','geom' => 'Polygon((480253.31991278665373102 9162805.62636781483888626, 480255.45991278666770086 9162806.02636781521141529, 480263.80991278664441779 9162804.04636781476438046, 480256.47991278662811965 9162777.96636781468987465, 480245.79991278663510457 9162781.67636781558394432, 480253.31991278665373102 9162805.62636781483888626))'));
		foreach ($land_uses as $key) 
		{ 
		 DB::table('land_uses')->insert($key);
		}
    }
}
 