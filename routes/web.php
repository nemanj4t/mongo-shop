<?php

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
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('/', 'LoginController@showLoginForm')->name('admin.showLoginForm');
    Route::post('/', 'LoginController@login')->name('admin.login');
    Route::group(['middleware' => 'auth:admin'], function(){
        Route::get('/home', 'AdminController@index')->name('admin.home');
        Route::post('/logout', 'LoginController@logout')->name('admin.logout');
    });
});

Route::group(['middleware' => 'auth'], function() {
    Route::get('/checkout', 'CheckoutController@index');
    Route::post('/checkout', 'CheckoutController@payment');
});

Route::get('/home', 'HomeController@index')->name('home');


// Kada se promeni kategorija onda se izbace
// proizvodi koji spadaju tu kategoriju ???
Route::get('/categories', 'CategoryController@index');
Route::post('/categories', 'CategoryController@store');
Route::delete('/categories/{category}', 'CategoryController@destroy');
Route::put('/categories/{category}', 'CategoryController@update');
Route::get('/categories/{category}', 'CategoryController@show');

Route::get('/products/create', 'ProductController@create');
Route::get('/products/edit', 'ProductController@edit');
Route::post('/products', 'ProductController@store');
Route::delete('/products/{product}', 'ProductController@destroy');
Route::put('/products/{product}', 'ProductController@update');
Route::get('/products/{product}', 'ProductController@show');

Route::delete('/users/{user}', 'UserController@destroy');

// Shopping cart
Route::get('/shoppingcart/get', 'ShoppingCartController@get');
Route::post('/shoppingcart/add', 'ShoppingCartController@add');
Route::get('/shoppingcart/remove/{item}', 'ShoppingCartController@remove');
Route::get('/shoppingcart/decrement/{item}', 'ShoppingCartController@decrement');

Route::get('/shoppingcart', 'ShoppingCartController@shoppingCart');

Route::get('/wishlist', 'UserController@wishList');

Route::get('/wishes', 'UserController@getWishes');
Route::post('/wishes', 'UserController@addWishes');
Route::post('/wishes/remove', 'UserController@deleteWish');

Route::post('/product/{product}/comments', 'UserController@addComment');

Route::get('/orders', 'OrderController@index')->middleware('auth');

Route::get('/search', function(Request $request) {
    // Search bar
   if(isset($request['keyword'])) {
       $products = App\Product::where('name', 'like', '%'.$request['keyword'].'%')->get();
       return response()->json($products);
   }

   // Price slider
   if(isset($request['category']) && isset($request['price'])) {
       $category = App\Category::find($request['category']);
       $products = App\Category::getProductsForLeafCategories($category)
           ->where('price', '<=', floatval($request['price']));
       return $products;
   }
});
