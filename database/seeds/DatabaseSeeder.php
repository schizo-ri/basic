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
        DB::table('companies')->insert([
			'id'	=> 1,
            'name' => Str::random(10),
            'address' => Str::random(10),
            'city' => Str::random(10),
			'oib' => rand(10000000000,99999999999 )
        ]);
		
		DB::table('departments')->insert([
			'id'	=> 1,
            'company_id' => 1,
            'name' => Str::random(10),
            'level1' => 0,
			'level2' => 0,
			'email'	=> Str::random(10).'@gmail.com'
        ]);
		
		DB::table('works')->insert([
			'id'	=> 1,
            'department_id' => 1,
            'name' => Str::random(10)         
        ]);
		
		DB::table('employees')->insert([
			'id'	=> 1,
			'user_id'	=> 1,
            'work_id' => 1,
				
        ]);
    }
}
