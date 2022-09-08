<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;


class SitemapController extends Controller
{
    public function index(){
        $sitemap = App::make("sitemap");

        $sitemap->add(URL::to('api/category-list'), date('j M, Y'), '1', 'daily');
        $sitemap->add(URL::to('api/category-article-list'), date('j M, Y'), '1', 'daily');

        $sitemap->add(URL::to('api/article-list'), date('j M, Y'), '2', 'daily');
        $sitemap->add(URL::to('api/article-details/{slug}'), date('j M, Y'), '2', 'daily');
        $sitemap->add(URL::to('api/article/category/{slug}'), date('j M, Y'), '2', 'daily');
        $sitemap->add(URL::to('api/article/search/{any}'), date('j M, Y'), '2', 'daily');

        $sitemap->add(URL::to('api/faq-list'), date('j M, Y'), '3', 'daily');
        $sitemap->add(URL::to('api/faqs/{any}'), date('j M, Y'), '3', 'daily');
        $sitemap->add(URL::to('api/faq/search/{any}'), date('j M, Y'), '3', 'daily');

        $sitemap->add(URL::to('api/quiz-list'), date('j M, Y'), '4', 'daily');
        $sitemap->add(URL::to('api/quiz-form/field-list/{id}'), date('j M, Y'), '5', 'daily');

        $sitemap->add(URL::to('api/front-page-config'), date('j M, Y'), '6', 'daily');

        $sitemap->add(URL::to('api/genrate-sitemap'), date('j M, Y'), '7', 'daily');

//        $sitemap->add(URL::to('users/export/'), date('j M, Y'), '2', 'daily');
//        $sitemap->add(URL::to('categories/export/'), date('j M, Y'), '2', 'daily');
//        $sitemap->add(URL::to('articles/export/'), date('j M, Y'), '2', 'daily');
//        $sitemap->add(URL::to('faqs/export/'), date('j M, Y'), '2', 'daily');

        $sitemap->store('xml', 'sitemap');
        $xmlString      = file_get_contents(public_path('sitemap.xml'));
        $xmlObject      = simplexml_load_string($xmlString);
        $json           = json_encode($xmlObject);
        $phpArray       = json_decode($json, true);

        return response()->json([
            'status_code'  => 200,
            'messages'     => config('status.status_code.200'),
            'map_info' => $phpArray
        ]);
    }
}
