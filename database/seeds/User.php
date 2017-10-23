<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ru_RU');

        for ($i = 0; $i < 50; $i++)
        {
            $user = new App\User();
            $user->name = $faker->name;
            $user->phone = $faker->phoneNumber;
            $user->phone_work = $faker->phoneNumber;
            $user->email = $faker->email;

            $user->role_id = mt_rand(0,2);
            $user->department_id = mt_rand(0,1);
            $user->password = Hash::make($faker->word);

            $user->save();

        }
    }
}
