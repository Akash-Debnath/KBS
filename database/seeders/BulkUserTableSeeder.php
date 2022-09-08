<?php


namespace Database\Seeders;


use App\Helpers\Helper;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Str;

class BulkUserTableSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $userPermission     = Permission::all();
        $userRole           = Role::where('slug', 'admin')->first();


        $userRole->permissions()->attach($userPermission);

        for($i=0; $i<=50; $i++){
            $first_name     = Str::random(4);
            $last_name      = Str::random(6);

            User::create([
                'id'                => Helper::idGenarator(),
                'username'          => Helper::slugifyUsername($first_name,$last_name),
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'slug'              => Helper::slugifyFullName($first_name,$last_name),
                'email'             => $first_name . $last_name . '@genusys.us',
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'email_verified_at' => now(),
                'remember_token'    => Str::random(10)
            ]);

            $user = User::where('email', $first_name . $last_name . '@genusys.us')->first();
            $user->roles()->attach($userRole);
            $user->media()->create([
                'url' => url('/').'/office-project/ccKnowledgeBase/kbs2.1/public/avatar/placeholder.png'
            ]);
        }

        Schema::enableForeignKeyConstraints();

    }
}