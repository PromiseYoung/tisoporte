<?php

use App\Priority;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = FakerFactory::create();
        $priorities = [
            'BAJA',
            'MEDIA',
            'ALTA',
            'URGENTE'
        ];

        foreach ($priorities as $priority) {
            Priority::create([
                'name' => $priority,
                'color' => $faker->hexcolor
            ]);
        }
    }
}
