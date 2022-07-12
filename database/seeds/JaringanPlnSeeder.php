<?php

use Illuminate\Database\Seeder;

class JaringanPlnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $jaringan_plns=array(array('handle' => '337', 'keterangan' => '2','geom' => 'Linestring (104.74365234375 -0.9228116626856938, 107.05078125 -3.1405161039832357, 110.61035156249999 -5.025282908609298, 113.97216796875 -1.5598658653430082, 112.47802734375 1.8014609294680355, 109.64355468749999 -0.10986321392741416, 112.25830078125 3.601142320158735, 116.4111328125 -1.7136116598836224, 118.21289062499999 -0.9447814006873896, 117.20214843749999 2.2406396093827334, 113.75244140624999 5.0690578267840465, 108.43505859374999 1.537901237431487, 114.60937499999999 6.315298538330033, 116.98242187499999 3.601142320158735, 119.66308593749999 1.0546279422758869, 118.76220703125001 -4.696879026871413)'));
		foreach ($jaringan_plns as $key) 
		{ 
		 DB::table('jaringan_plns')->insert($key);
		}
    }
}
