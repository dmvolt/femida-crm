<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SeedContactActivity extends Seeder
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

        foreach (\App\Contact::all() as $_contact)
        {
            for ($i = 0; $i < 15; $i++)
            {
                $contactActivity = new \App\ContactActivity();

                $contactActivity->contact_id = $_contact->id;
                $contactActivity->user_id = $faker->randomElement($userIds);

                $contactActivity->type = \App\ContactActivity::TYPES[array_rand(\App\ContactActivity::TYPES)];
                $contactActivity->text = $faker->realText(30);

                $contactActivity->save();
            }
        }
    }
}
