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
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
         \App\User::insert([
            [
              'name'        => 'user',
              'email'       => 'user@user.com', 
              'password'    => bcrypt('user123'),  
              'rule'        =>'user',
              'updated_at'  => \Carbon\Carbon::now('Asia/Jakarta'), 
              'created_at'  => \Carbon\Carbon::now('Asia/Jakarta')
            ], 
        ]);
    }
}
