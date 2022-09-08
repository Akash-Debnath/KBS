<?php
namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();

        $Permissions = [
            'Admin Panel',
            'Article List',
            'Article Create',
            'Article Edit',
            'Article Delete',
            'Banner Create',
            'Banner List',
            'Banner Edit',
            'Banner Delete',
            'Category List',
            'Category Create',
            'Category Edit',
            'Category Delete',
            'Comment Create',
            'Comment List',
            'Comment Edit',
            'Comment Delete',
            'Email List',
            'Email Create',
            'Email Edit',
            'Email Delete',
            'Faq List',
            'Faq Create',
            'Faq Edit',
            'Faq Delete',
            'Group List',
            'Group Create',
            'Group Edit',
            'Group Delete',
            'History List',
            'Notification List',
            'Notification Create',
            'Notification Edit',
            'Notification Delete',
            'Page List',
            'Page Create',
            'Page Edit',
            'Page Delete',
            'Permission List',
            'Permission Create',
            'Permission Edit',
            'Permission Delete',
            'Quiz List',
            'Quiz Create',
            'Quiz Edit',
            'Quiz Delete',
            'Quiz Form List',
            'Quiz Form Create',
            'Quiz Form Edit',
            'Quiz Form Delete',
            'Quiz Form Field List',
            'Quiz Form Field Create',
            'Quiz Form Field Edit',
            'Quiz Form Field Delete',
            'Result List',
            'Result Create',
            'Result Edit',
            'Result Delete',
            'Role List',
            'Role Create',
            'Role Edit',
            'Role Delete',
            'User Panel',
            'User List',
            'User Create',
            'User Edit',
            'User Delete',
            'Menu List',
            'Menu Create',
            'Menu Edit',
            'Menu Delete',
        ];


        foreach ($Permissions as $Permission) {

             Permission::Create(['name' => $Permission, 'slug' => Helper::slugify($Permission)]);
//            DB::collection('permission')->create(['name' => $Permission, 'slug' => Helper::slugify($Permission)]);

        }

        Schema::enableForeignKeyConstraints();
    }
}
