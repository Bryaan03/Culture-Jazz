<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Article;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $hash = Hash::make('azerty');

        User::factory([
            'name' => "inrocks",
            'email' => "inrocks@gmail.com",
            'email_verified_at' => now(),
            'password' => $hash,
        ])->create();
        for ($i = 1; $i <= 50; $i++) {
            User::factory()->create([
                'name' => $name = 'user' . $i,
                'email' => "$name@gmail.com",
                'email_verified_at' => now(),
                'password' => $hash,
            ]);
        }

        $faker = Factory::create('fr_FR');

        $users = User::all();
        foreach($users as $user) {
            $nb = $faker->numberBetween(2, 10);
            $userIds = User::pluck('id');
            $userIdsSelected = $faker->randomElements($userIds, $nb);
            $user->suivis()->attach($userIdsSelected);
        }
    }
}
