<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SeedTaskActivity extends Seeder
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
        $leadIds = App\Lead::all()->pluck('id')->toArray();

        foreach (\App\Task::all() as $_task)
        {
            for ($i = 0; $i < 15; $i++)
            {
                $contactActivity = new \App\TaskActivity();

                $contactActivity->task_id = $_task->id;

                $contactActivity->user_id = $faker->randomElement($userIds);
                $contactActivity->lead_id = $faker->randomElement($leadIds);

                $contactActivity->type = \App\TaskActivity::TYPES[array_rand(\App\TaskActivity::TYPES)];
                $contactActivity->text = $faker->realText(30);

                $contactActivity->save();
            }
        }
    }
}
