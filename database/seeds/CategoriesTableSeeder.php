<?php

use App\Category;
use App\User;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        // Primero, asegúrate de que los usuarios existan antes de asignarles categorías
        $users = User::all()->keyBy('id'); // Obtener todos los usuarios y indexar por ID
        $categories = [
            [
                'name' => "WALS",
                'color' => $faker->hexcolor,
                'user_id' => 4, // Asignar al Admin
            ],
            [
                'name' => "IMPRESORA",
                'color' => $faker->hexcolor,
                'user_id' => 2, // Asignar a Agent 1
            ],
            [
                'name' => "PERMISOS DE INTERNET",
                'color' => $faker->hexcolor,
                'user_id' => 5, // Asignar a Agent 2
            ],
            [
                'name' => "TERMINAL",
                'color' => $faker->hexcolor,
                'user_id' => 3, // Asignar a Agent 2
            ],
            [
                'name' => "COMPUTADORA",
                'color' => $faker->hexcolor,
                'user_id' => 2, // Asignar a Agent 2
            ],
            [
                'name' => "SALA DE JUNTAS",
                'color' => $faker->hexcolor,
                'user_id' => 2, // Asignar a Agent 2
            ],
            [
                'name' => "CORREO",
                'color' => $faker->hexcolor,
                'user_id' => 5, // Asignar a Agent 2
            ],
            [
                'name' => "TELEFONIA",
                'color' => $faker->hexcolor,
                'user_id' => 3, // Asignar a Agent 2
            ],
            [
                'name' => "MANTENIMIENTO",
                'color' => $faker->hexcolor,
                'user_id' => 2, // Asignar a Agent 2
            ],
            [
                'name' => "RED",
                'color' => $faker->hexcolor,
                'user_id' => 3, // Asignar a Agent 2
            ],
            [
                'name' => "SERVIDORES",
                'color' => $faker->hexcolor,
                'user_id' => 4, // Asignar a Agent 2
            ],
            [
                'name' => "CARPETAS COMPARTIDAS",
                'color' => $faker->hexcolor,
                'user_id' => 4, // Asignar a Agent 2
            ],
            // Añadir más categorías si es necesario
        ];

        foreach ($categories as $categoryData) {
            // Verificar si el usuario asociado existe
            if (isset($users[$categoryData['user_id']])) {
                Category::create([
                    'name' => $categoryData['name'],
                    'color' => $categoryData['color'],
                    'user_id' => $categoryData['user_id'], // Asignar el usuario correspondiente
                ]);
            }
        }
    }
}
