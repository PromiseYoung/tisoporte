<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'Admin',
            ],
            [
                'id' => 2,
                'name' => 'Analista TI',
            ],
            [
                'id' => 3,
                'name' => 'Mantenimientos',
            ],
        ];

        Role::insert($roles);
    }
}
