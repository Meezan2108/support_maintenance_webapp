<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UpdateDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AslUpdateSeeder::class);
        $this->call(ImportedGermplasmUpdateSeeder::class);
        $this->call(IprUpdateSeeder::class);
        $this->call(OutputUpdateSeeder::class);

        $this->call(PublicationUpdateSeeder::class);
        $this->call(RecognitionUpdateSeeder::class);
    }
}
