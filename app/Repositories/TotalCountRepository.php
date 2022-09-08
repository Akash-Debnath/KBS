<?php


namespace App\Repositories;


use App\Models\Article;
use Illuminate\Support\Facades\DB;

class TotalCountRepository
{
    public function allTotalCount($request)
    {
        $totalCategory = DB::table('categories')->count();
        $totalArticle  = DB::table('articles')->count();
        $totalFAQ      = DB::table('faqs')->count();
        $totalUser     = DB::table('users')->count();
        $totalQuiz     = DB::table('quizzes')->count();

        $totalArray = [
          'tatal_category' =>  $totalCategory,
          'total_article'  =>  $totalArticle,
          'total_faq'      =>  $totalFAQ,
          'total_user'     =>  $totalUser,
          'total_quiz'     =>  $totalQuiz,
        ];

       // dd($totalArray);

        return $totalArray;

    }
}
