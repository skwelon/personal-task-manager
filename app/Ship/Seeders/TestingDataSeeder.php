<?php

namespace App\Ship\Seeders;

use App\Containers\AppSection\TaskManagement\Data\Seeders\TaskManagementSeeder_1;
use App\Ship\Parents\Seeders\Seeder;

final class TestingDataSeeder extends Seeder
{
    /**
     * Note: This seeder is not loaded automatically by Apiato
     * This is a special seeder that can be called by "apiato:seed-test" command
     * It is useful for seeding testing data.
     */
    public function run(): void
    {
        $this->call(TaskManagementSeeder_1::class);
    }
}
