<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
            [
              'name'        => 'admin',
              'email'       => 'admin@admin.com', 
              'password'    => bcrypt('admin123'),  
              'rule'        =>'admin',
              'levelkases'  =>'atrbpn', 
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
         \App\User::insert([
            [
              'name'        => 'atrbpn',
              'email'       => 'atrbpn@atrbpn.com', 
              'password'    => bcrypt('atrbpn123'),  
              'rule'        =>'user',
              'levelkases'  =>'atrbpn', 
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
          \App\User::insert([
            [
              'name'        => 'bppkad',
              'email'       => 'bppkad@bppkad.com', 
              'password'    => bcrypt('bppkad123'),  
              'rule'        =>'user',
              'levelkases'  =>'bppkad',
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
         \App\User::insert([
            [
              'name'        => 'dpupr',
              'email'       => 'dpupr@dpupr.com', 
              'password'    => bcrypt('dpupr123'),  
              'rule'        =>'user',
              'levelkases'  =>'dpupr',
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
    }
}
