<?php
namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Str;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('categories')->truncate();

        $media_files = Media::where('mediable_type','=','App\Models\Category')->get();

        foreach ($media_files as $aFile){
            $aFile->delete();
        }

        $categories_info = [
            ['name' => 'Knowledge Base Project','url' => '/office-project/ccKnowledgeBase/kbs2.1/public/static_media/category/category.png'],
            ['name' => 'Project Management System','url' => '/office-project/ccKnowledgeBase/kbs2.1/public/static_media/category/category.png'],
            ['name' => 'Ticket Management System','url' => '/office-project/ccKnowledgeBase/kbs2.1/public/static_media/category/category.png'],
        ];

        foreach ($categories_info as $key => $info){
            $id = Helper::idGenarator();

            Category::create([
                "id" => $id,
                "name" => $info['name'],
                "slug" => Helper::slugify($info['name']),
                "role_id" => "1,2,3,4",
                "user_id" => "164015267815671"
            ]);

            $category = Category::where('id','=',$id)->first();

            $category->media()->create([
                'url' => url('/').$info['url']
            ]);
        }

        Schema::enableForeignKeyConstraints();

    }
}
