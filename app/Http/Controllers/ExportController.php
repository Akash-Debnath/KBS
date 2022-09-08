<?php

namespace App\Http\Controllers;

use App\Exports\ArticlesExport;
use App\Exports\CategoriesExport;
use App\Exports\FaqsExport;
use App\Exports\UsersExport;
use App\Models\Article;
use App\Models\Faq;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportUsers(){
        $userData =  User::with(['roles' => function($q) {$q->select('id', 'name');}, 'media'])
            ->get()->map(function ($userData){
                return [
                    'id'          => $userData->id,
                    'username'    => $userData->username,
                    'first_name'  => $userData->first_name,
                    'last_name'   => $userData->last_name,
                    'email'       => $userData->email,
                    'role_name'   => (count($userData->roles)>0) ?  $userData->roles[0]->name : 'N/A',
                    'created_at'  => $userData->created_at,
                    'updated_at'  => $userData->updated_at,
                ];
            });
        return Excel::download(new UsersExport($userData), 'users-'.date("d-m-y-H-i-s").'.csv');
    }

    public function exportCategories(){
        return Excel::download(new CategoriesExport(), 'categories-'.date("d-m-y-H-i-s").'.csv');
    }


    public function exportArticles()
    {
        $articleData = Article::with(['user' => function ($q) {$q->select('id', 'first_name', 'last_name');}, 'category' => function ($q) {$q->select('id', 'name');}])->get()->map(function ($articleData) {
            return [
                'id' => $articleData->id,
                'en_title' => $articleData->en_title,
                'en_body' => strip_tags($articleData->en_body),
                'category_name' => $articleData->category->name,
                'author_name' => $articleData->user->first_name . ' ' . $articleData->user->last_name,
                'status' => $articleData->status,
                'tag' => $articleData->tag ? $articleData->tag : "N/A",
                'publish_date' => $articleData->created_at,
            ];
        });
        return Excel::download(new ArticlesExport($articleData), 'articles-' . date("d-m-y-H-i-s") . '.csv');
    }

    public function exportFaqs()
    {
        $faqData = Faq::with(['user' => function ($q) {$q->select('id', 'first_name', 'last_name');}, 'category' => function ($q) {$q->select('id', 'name');}])->get()->map(function ($faqData) {
            return [
                'id' => $faqData->id,
                'en_title' => $faqData->en_title,
                'en_body' => strip_tags($faqData->en_body),
                'category_name' => $faqData->category->name,
                'author_name' => $faqData->user->first_name . ' ' . $faqData->user->last_name,
                'status' => $faqData->status,
                'tag' => $faqData->tag ? $faqData->tag : "N/A",
                'publish_date' => $faqData->created_at,
            ];
        });
        return Excel::download(new FaqsExport($faqData), 'faqs-' . date("d-m-y-H-i-s") . '.csv');
    }
}
