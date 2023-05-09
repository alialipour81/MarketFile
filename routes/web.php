<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\dashboard\IndexController as Indexdashboard;
use App\Http\Controllers\dashboard\CategoriesController;
use App\Http\Controllers\dashboard\TagsController;
use App\Http\Controllers\dashboard\StoresController;
use App\Http\Controllers\dashboard\ProductsController;
use App\Http\Controllers\dashboard\CollapsesController;
use App\Http\Controllers\dashboard\DownloadsController;
use App\Http\Controllers\dashboard\PostersController;
use App\Http\Controllers\dashboard\SlidersController;
use App\Http\Controllers\dashboard\InstantoffersController;
use App\Http\Controllers\dashboard\AmazingsController;
use App\Http\Controllers\dashboard\UsersController;
use App\Http\Controllers\dashboard\CommentsController;
use App\Http\Controllers\dashboard\FavouritesController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\dashboard\DiscountsController;
use App\Http\Controllers\dashboard\CartController;
use App\Http\Controllers\Pay\ZarinpalController;
use App\Http\Controllers\dashboard\CheckoutsController ;
use App\Http\Controllers\dashboard\MessagesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[IndexController::class,'index'])->name('index');
Route::get('page/{product}',[IndexController::class,'product'])->name('product');
Route::get('products/{value}',[IndexController::class,'products'])->name('products');
Route::post('search',[IndexController::class,'search'])->name('search');
Route::post('products-filter/{value}',[IndexController::class,'products_filter'])->name('products.filter');
Route::post('addproducttofavourite/{product}',[IndexController::class,'addprotofavourite'])->name('addprotofavourite')->middleware('auth');
Route::post('show/template/{product}',[IndexController::class,'showTemplate'])->name('show.template');
Route::resource('cart',CartsController::class)->except(['create','show','edit','update']);
Route::put('validate/discount/{text}',[CartsController::class,'validate_discount'])->name('validate.discount');

Auth::routes(['verify'=>true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('google',[GoogleController::class,'next'])->name('google');
Route::get('google-callback',[GoogleController::class,'handel']);
Route::post('google/register',[GoogleController::class,'register'])->name('google.register');

Route::prefix('dashboard')->middleware(['auth','verified'])->group(function (){
    Route::get('index',[Indexdashboard::class,'index'])->name('dashboard.index');
    Route::resource('categories',CategoriesController::class)->except('show');
    Route::resource('tags',TagsController::class)->except('show');
    Route::resource('stores',StoresController::class)->except('show');
    Route::resource('products',ProductsController::class)->except('show');
    Route::post('ckeditor/upload',[ProductsController::class,'CK_upload'])->name('products.ck_upload');
    Route::put('product/template/{product}',[ProductsController::class,'file_template'])->name('products.file_template');
    Route::put('product/template/delete/{product}',[ProductsController::class,'deleteTemplate'])->name('products.deleteTemplate');
    Route::get('download/template/ontest/{product}',[DownloadsController::class,'downloadTemplate'])->name('download.templateonTest');
    Route::resource('collapses',CollapsesController::class)->except(['index','show']);
    Route::get('collapsess/{product}',[CollapsesController::class,'index2'])->name('collapses.index2');
    Route::post('collapse/upload',[CollapsesController::class,'CK_upload'])->name('collapses.ck.upload');
    Route::resource('downloads',DownloadsController::class)->except(['index','create','show']);
    Route::get('downloadss/{product}',[DownloadsController::class,'index2'])->name('downloads.index2');
    Route::get('downloadtest/{download}',[DownloadsController::class,'downloadTest'])->name('downloadTest');
    Route::resource('posters',PostersController::class)->except('show');
    Route::resource('sliders',SlidersController::class)->except('show');
    Route::resource('instantoffers',InstantoffersController::class)->except('show');
    Route::resource('amazings',AmazingsController::class)->except('show');
    Route::resource('users',UsersController::class)->except(['create','store','show']);
    Route::delete('image/profile/{user}',[UsersController::class,'deleteimageprofile'])->name('users.delimgprofile');
    Route::resource('comments',CommentsController::class)->except(['create','show','edit']);
    Route::resource('favourites',FavouritesController::class)->except(['create','store','show','edit','update']);
    Route::resource('discounts',DiscountsController::class)->except('show');
    Route::resource('cart_orders',CartController::class)->except(['create','store','show']);
    Route::resource('checkouts',CheckoutsController::class)->except(['create','show','edit']);
    Route::resource('messages',MessagesController::class)->except(['show','create']);
    Route::post('ckeditor/upload/formessage',[MessagesController::class,'uploadImageCKeditor'])->name('messages.uploadImageCKeditor');
    Route::get('message/{message}',[MessagesController::class,'clickMessage'])->name('messages.clickMessage');
    Route::post('reply/ticket/{message}',[MessagesController::class,'reply_ticket'])->name('messages.reply_ticket');
});
Route::get('download/{download}',[DownloadsController::class,'download'])->name('download');
Route::post('template/download/{product}',[DownloadsController::class,'Template_download'])->name('product.Template_download');


// pay
Route::prefix('payment')->middleware('auth')->group(function (){
    Route::post('request/zarinpal',[ZarinpalController::class,'request'])->name('zarinpal.request');
    Route::get('zarinpal/callback',[ZarinpalController::class,'callback'])->name('zarinpal.callback');
});

