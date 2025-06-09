<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'HUGO JARAMILLO',
                'email' => 'ti@load.com.mx',
                'password' => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
            [
                'id' => 2,
                'name' => 'MIGUEL ALEJANDRO MENDOZA',
                'email' => '99mm7@load.com.mx',
                'password' => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
            [
                'id' => 3,
                'name' => 'ALEJANDRO CARREON HERRERA',
                'email' => '3ac12@load.com.mx',
                'password' => '$2y$10$UnLIBQB1uZZC1r5msFWTPuZCZsMBUpZINpJ48G5FmMxz6yVGP83rO',
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
