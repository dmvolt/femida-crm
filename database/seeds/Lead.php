<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Lead extends Seeder
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
        $contactIds = App\Contact::all()->pluck('id')->toArray();
        $statuses = App\LeadStatus::all()->pluck('id')->toArray();
        $servicesIds = App\LeadService::all()->pluck('id')->toArray();

        for ($i = 0; $i < 50; $i++)
        {
            $lead = new App\Lead();

            $lead->name = $faker->word;
            $lead->description = $faker->text;

            $lead->budget = $faker->randomNumber(4);

            $lead->user_id = $faker->randomElement($userIds);
            $lead->service_id = $faker->randomElement($servicesIds);
            $lead->status_id = $faker->randomElement($statuses);
            $lead->contact_id = $faker->randomElement($contactIds);

            $lead->save();

        }
    }
}
