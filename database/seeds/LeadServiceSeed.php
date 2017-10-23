<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class LeadServiceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ru_RU');

        for ($i = 0; $i < 15; $i++)
        {
            $lead = new App\LeadService();

            $lead->name = $faker->realText(30);
            $lead->cost = $faker->randomNumber(4);

            $lead->save();

        }
    }
}
