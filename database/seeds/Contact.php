<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class Contact extends Seeder
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

        for ($i = 0; $i < 50; $i++)
        {
            $contact = new App\Contact();
            $contact->name = $faker->name;
            $contact->phone = $faker->phoneNumber;
            $contact->email = $faker->email;
            $contact->origin_id = mt_rand(1,2);
            $contact->user_id = $faker->randomElement($userIds);
            $contact->save();

            $contactData = new \App\ContactData();
            $contactData->contact_id = $contact->id;
            $contactData->number = $faker->randomNumber(4). ' '.$faker->randomNumber(6);
            $contactData->code = $faker->randomNumber(3).'-'.$faker->randomNumber(3);
            $contactData->address = $faker->address;
            $contactData->date = $faker->date();
            $contactData->issued = 'УФМС России';

            $contactData->save();
        }
    }
}
