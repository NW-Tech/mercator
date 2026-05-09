<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoApplicationFlowsTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('application_flows')->delete();
    }
}
