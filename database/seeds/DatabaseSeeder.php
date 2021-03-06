<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
         					UsersTableSeeder::class,
         					BuildingSeeder::class,
         					FiscalParcelSeeder::class,
         					LegalParcelSeeder::class, 
                            JalanSeeder::class,
                            LandUseSeeder::class,
                            JaringanPdamSeeder::class,
                            JaringanPlnSeeder::class
         				]);
    }
}
