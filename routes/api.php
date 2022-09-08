<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\TotalCountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizFormController;
use App\Http\Controllers\QuizFormFieldController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuizTakeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\EmailSettingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CrudHistoryController;
use App\Http\Controllers\DynamicPageController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\InterestRateController;

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResultController;

use App\Http\Controllers\VisitorController;
use App\Http\Controllers\UserQuizListController;
use App\Http\Controllers\SearchReplaceController;
use App\Http\Controllers\ExchangeRateController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('users/export/', [ExportController::class, 'exportUsers'])->name('users.export_mapping');
Route::get('categories/export/', [ExportController::class, 'exportCategories'])->name('categories.export_mapping');
Route::get('articles/export/', [ExportController::class, 'exportArticles'])->name('articles.export_mapping');
Route::get('faqs/export/', [ExportController::class, 'exportFaqs'])->name('faqs.export_mapping');

Route::get('front-page-config', [PageController::class, 'index']);

Route::get('category-list', [CategoryController::class, 'categoryList']);
Route::get('category-article-list', [CategoryController::class, 'categoryArticleList']);
Route::get('latest-category', [CategoryController::class, 'latestCategory']);


Route::get('article-list', [ArticleController::class, 'articleList']);
Route::get('article-details/{slug}', [ArticleController::class, 'articleDetails']);
Route::get('article/category/{slug}', [ArticleController::class, 'articleCategory']);
Route::get('article/search/{any}',[ArticleController::class, 'articleSearch']);

Route::get('faq-list',[FaqController::class, 'faqList']);
Route::get('faqs/{any}',[FaqController::class, 'show']);
Route::get('faq/search/{any}',[FaqController::class, 'faqSearch']);

// Route::get('quiz-list',[QuizController::class, 'getQuizList']);
Route::get('quiz-form/field-list/{id}',[QuizTakeController::class, 'index']);
Route::post('customer/username', [CustomerController::class, 'checkUserNameExist']);
Route::post('customer/email', [CustomerController::class, 'checkUserEmailExist']);
Route::post('customer/add',[CustomerController::class, 'store']);
Route::post('result-create',[UserQuizListController::class, 'storeResult']);

Route::get('home-banners',[BannerController::class, 'showHometBannerList']);
Route::get('usefull-links',[LinkController::class, 'getAllUsefulLinks']);
Route::get('all-menu-navbars',[MenuController::class, 'getAllMenuNavbars']);
Route::get('all-interest-rate',[InterestRateController::class, 'getAllInterestRate']);
Route::get('get-menu-page-data/{slug}',[MenuController::class, 'getMenuPageData']);
Route::get('exchange-rate-list',[ExchangeRateController::class, 'getExchangeRateList']);


Route::post('visitor-login',[VisitorController::class, 'index']);
//Route::post('visitor-logout',[VisitorController::class, 'logout']);
Route::post('logout', [UserController::class, 'logout']);


Route::middleware('auth:api')->group(function(){

    Route::get('replace/{id}/{any}',[SearchReplaceController::class,'searchText']);
    Route::post('replace/{id}/{any}',[SearchReplaceController::class,'replaceText']);



    Route::apiResource('comments', CommentController::class);
    Route::get('post-comments/{id}', [CommentController::class, 'articleComments']);
    Route::post('comment-status', [CommentController::class, 'commentStatus']);

    Route::apiResource('articles', ArticleController::class);
    Route::apiResource('contents', ContentController::class);
    Route::apiResource('banners', BannerController::class);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('faqs', FaqController::class);

    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('groups', GroupController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('interest-rate', InterestRateController::class);
    Route::apiResource('links', LinkController::class);

    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('quiz-forms', QuizFormController::class);
    Route::apiResource('quiz-form-fields', QuizFormFieldController::class);

    Route::apiResource('pages', PageController::class);
    Route::apiResource('dynamic-pages', DynamicPageController::class);
    Route::apiResource('email-setting', EmailSettingController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('results', ResultController::class);
    Route::apiResource('exchange-rate', ExchangeRateController::class);

    Route::post('category/name', [CategoryController::class, 'checkCategoryNameExist']);
    Route::post('menu/name', [MenuController::class, 'checkMenuNameExist']);
    Route::get('all-menus', [MenuController::class, 'getAllMenu']);
    Route::post('category/update-data', [CategoryController::class, 'update']);
    Route::post('category-list-for-update', [CategoryController::class, 'getCategoryForEdit']);

    Route::post('article/update-data', [ArticleController::class, 'update']);
    Route::post('save-file', [ArticleController::class, 'saveFiles']);
    Route::post('delete-file', [ArticleController::class, 'deleteFiles']);
    Route::get('latest-article-list', [ArticleController::class, 'articleList']);
    Route::get('all-article-list', [ArticleController::class, 'articleAll']);

    Route::post('change-article-status', [ArticleController::class, 'changeArticleStatus']);
    Route::post('change-article-comment-status', [ArticleController::class, 'changeArticleCommentStatus']);
    Route::post('change-faq-status', [FaqController::class, 'changeFAQStatus']);

    Route::post('pages/update-data', [PageController::class, 'update']);
    Route::post('email-setting/update', [EmailSettingController::class, 'update']);

    Route::post('role/name', [RoleController::class, 'checkRoleNameExist']);

    Route::post('user/update-password', [UserController::class,'changePassword']);
    Route::post('user/username', [UserController::class, 'checkUserNameExist']);
    Route::post('user/email', [UserController::class, 'checkUserEmailExist']);

    Route::post('total-count-data', [TotalCountController::class, 'totalCount']);

    Route::get('quiz-form/quiz-field-list/{id}',[QuizTakeController::class, 'totalCount']);

    Route::get('contents-article/{id}', [ContentController::class, 'showArticleContent']);
    Route::get('contents-article-exist/{id}', [ContentController::class, 'checkArticleAvailability']);
    Route::get('contents-faq-exist/{id}', [ContentController::class, 'checkFaqAvailability']);

    Route::post('role-banners', [BannerController::class, 'showLatestBannerList']);

    Route::get('histories', [CrudHistoryController::class, 'showAll']);
    Route::post('delete-post-history', [CrudHistoryController::class, 'deleteAllHistory']);
    Route::get('history/{post_id}', [CrudHistoryController::class, 'index']);

    Route::get('user-quiz-list', [UserQuizListController::class, 'index']);
    Route::get('user-quiz-result-list', [UserQuizListController::class, 'getQuizResultList']);
    Route::get('result-details', [UserQuizListController::class, 'getQuizResultDetails']);
    Route::get('quiz-results', [ResultController::class, 'quizResults']);
    Route::get('get-quiz-list',[QuizController::class, 'getQuizList']);
    Route::get('user-quiz-results', [ResultController::class, 'userQuizResults']);
    Route::get('quiz-results-details', [ResultController::class, 'quizResultsDetails']);
    Route::post('dynamic-page-exists', [DynamicPageController::class, 'checkPageTitleExist']);
    Route::post('menu-id-exists', [DynamicPageController::class, 'checkMenuNameExist']);
    Route::post('logout', [UserController::class, 'logout']);

});

Route::post('login', AuthController::class);

Route::get('genrate-sitemap',[SitemapController::class, 'index']);
