<?php

use App\Models\Goal;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->addGoals();
    }

    private function addGoals()
    {
        collect([
            [
                'identifier' => 'neptunium-200',
                'description' => 'Be the first to obtain 200kg of Neptunium',
            ],
            [
                'identifier' => 'outposts-15',
                'description' => 'Be the first to obtain 15 outposts',
            ],
        ])->each(function ($goal) {
            Goal::updateOrCreate($goal);
        });
    }
}
