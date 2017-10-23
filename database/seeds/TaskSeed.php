<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TaskSeed extends Seeder
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
            $task = new App\Task();

            $task->name = $faker->word;
            $task->description = $faker->text;
            $task->type = $faker->randomElement(\App\Task::$types);
            $task->user_id = $faker->randomElement($userIds);
            $task->deadline = $faker->dateTime;

            $task->save();

        }
    }
}
