<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SeedLeadActivity extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ru_RU');
        $userIds = App\User::all()->pluck('id')->toArray();

        foreach (\App\Lead::all() as $_contact)
        {
            for ($i = 0; $i < 15; $i++)
            {
                $contactActivity = new \App\LeadActivity();

                $contactActivity->lead_id = $_contact->id;
                $contactActivity->user_id = $faker->randomElement($userIds);

                $contactActivity->type = \App\LeadActivity::TYPES[array_rand(\App\LeadActivity::TYPES)];
                $contactActivity->text = $faker->realText(30);

                $contactActivity->save();
            }
        }
    }
}
