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

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('roles_permissions')->truncate();
        DB::table('users_permissions')->truncate();
        DB::table('users')->truncate();

        $userPermission     = Permission::all();
        $userRole           = Role::where('slug', 'super-admin')->first();
        $userRole->permissions()->attach($userPermission);

        $user               = User::where('email', 'alif@genusys.us')->first();
        $first_name         = 'Syful Islam';
        $last_name          = 'Alif';

        if(!$user)
        {
            User::create([
                'id'                => '164015267815671',
                'username'          => 'admin',
                'first_name'        => $first_name,
                'last_name'         => $last_name,
                'slug'              => Helper::slugifyFullName($first_name,$last_name),
                'email'             => 'alif@genusys.us',
                'password'          => bcrypt('123456'),
                'status'            => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]);

            $user           = User::where('email', 'alif@genusys.us')->first();
            $user->roles()->attach($userRole);
            $user->media()->create([
                'url' => url('/').'/office-project/ccKnowledgeBase/kbs2.1/public/avatar/placeholder.png'
            ]);
        }

        $user->permissions()->attach($userPermission);

        Schema::enableForeignKeyConstraints();

    }
}
