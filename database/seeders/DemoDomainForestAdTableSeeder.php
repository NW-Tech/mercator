<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoDomainForestAdTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('domain_forest_ad')->delete();

        \DB::table('domain_forest_ad')->insert([
            0 => [
                'forest_ad_id' => 1,
                'domaine_id' => 1,
            ],
            1 => [
                'forest_ad_id' => 2,
                'domaine_id' => 1,
            ],
            2 => [
                'forest_ad_id' => 1,
                'domaine_id' => 3,
            ],
            3 => [
                'forest_ad_id' => 2,
                'domaine_id' => 5,
            ],
            4 => [
                'forest_ad_id' => 1,
                'domaine_id' => 4,
            ],
        ]);

    }
}
