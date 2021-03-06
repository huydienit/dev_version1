<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AdtechCoreAclTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('adtech_core_acl')->insert([
            'object_id' => 1,
            'object_type' => 'role',
            'created_user_id' => 1,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'allow' => null,
        ]);
    }
}
