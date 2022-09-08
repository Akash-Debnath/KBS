<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Helpers\Helper;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Group::truncate();

            $groups = [
                'Account',
                'Marketing',
                'Software',
                'Data Entry',
                'NOC',
                'QA',
                'Support'
            ];


            foreach ($groups as $group) {

                Group::Create(['name' => $group, 'slug' => Helper::slugify($group)]);

            }

            Schema::enableForeignKeyConstraints();
    }
}
