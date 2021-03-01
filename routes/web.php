<?php

use Illuminate\Support\Facades\Route;

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


// Frontend group
Route::namespace('Frontend')
    ->group(function () {
        //home
        Route::get('/', 'HomeController@home')->name('home');
        Route::get('/help', 'HomeController@getHelp')->name('help');
        Route::get('/page/{slug}', 'HomeController@getPage')->name('page.view');
        Route::get('/search/result', 'HomeController@getSearchResult')->name('search.result');
        //blog
        Route::get('/blog', 'BlogController@list')->name('blog.list');
        Route::get('/blog/{slug}', 'BlogController@view')->name('blog.view');
        //catalog
        Route::get('/catalog/{slug?}', 'CatalogController@index')->name('catalog');
        //product
        Route::get('/product/{slug?}', 'ProductController@view')->name('product.view');
        //cart
        Route::get('/cart/list', 'CartController@list')->name('cart.list');
        Route::post('/cart/attach', 'CartController@attach')->name('cart.attach');
        Route::post('/cart/detach', 'CartController@detach')->name('cart.detach');
        //favorite
        Route::get('/favorite/list', 'FavoriteController@list')->name('favorite.list');
        Route::post('/favorite/attach', 'FavoriteController@attach')->name('favorite.attach');
        Route::post('/favorite/detach', 'FavoriteController@detach')->name('favorite.detach');
        //brand
        Route::get('/brands', 'BrandController@list')->name('brand.list');
        Route::get('/brands/{id}', 'BrandController@view')->name('brand.view');
        //stock
        Route::get('/stocks', 'StockController@list')->name('stock.list');
        Route::get('/stocks/{id}', 'StockController@view')->name('stock.view');
});


// Profile group
Route::namespace('Profile')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {

        Route::group([
            'middleware' => 'guest'
        ], function () {
            //register
            Route::get('/register', 'AuthController@getRegister')->name('register');
            Route::post('/register', 'AuthController@postRegister')->name('register.post');

            Route::post('/send-activate-code', 'AuthController@sendActivateCode')->name('send-activate-code');
            Route::post('/confirm-activate-code', 'AuthController@postConfirmActivateCode')->name('confirm-activate-code');

            //login
            Route::get('/login', 'AuthController@getLogin')->name('login');
            Route::post('/login', 'AuthController@postLogin')->name('login.post');

            //restore
            Route::get('/restore', 'AuthController@getRestore')->name('restore');
            Route::post('/restore', 'AuthController@postRestore')->name('restore.post');

            //reset password by email
            Route::get('/reset-password/{token}', 'AuthController@getResetPassword')->name('reset-password');
            Route::post('/reset-password/{token}', 'AuthController@postResetPassword')->name('reset-password.post');

            //reset password by phone
            Route::get('/reset-password-by-phone/{phone}', 'AuthController@getResetPasswordByPhone')->name('reset-password-by-phone');
            Route::post('/reset-password-by-phone/{phone}', 'AuthController@postResetPasswordByPhone')->name('reset-password-by-phone.post');
        });

        Route::group([
            'middleware' => ['auth']
        ], function () {
            Route::post('/logout', 'AuthController@logout')->name('logout');
        });

        Route::group([
            'middleware' => ['auth', 'user.verified']
        ], function () {
            Route::get('/', 'IndexController@index')->name('index');
            Route::post('/', 'IndexController@update')->name('update');
            Route::get('/favorite', 'IndexController@getFavorite')->name('favorite');
            Route::get('/getUdsInfo', 'IndexController@getUdsInfo')->name('uds.info');

            // history
            Route::get('/history', 'HistoryController@list')->name('history.list');
            Route::get('/history/{id}', 'HistoryController@view')->name('history.view');

            // purchasing
            Route::get('/purchasing', 'PurchasingController@index')->name('purchasing');
            Route::post('/purchasing', 'PurchasingController@post')->name('purchasing.post');
            Route::match(['get', 'post'],'/order-response/{id}', 'PurchasingController@response')->name('purchasing.response');
            Route::get('/purchasing/apelsin/{id}', 'PurchasingController@apelsin')->name('purchasing.apelsin');
        });

        Route::match(['post', 'get'], '/payment/postback', 'PaymentController@postback')->name('payment.postback');
        Route::match(['post', 'get'], '/payment/postback/apelsin', 'PaymentController@apelsin')->name('payment.postback.apelsin');
});


// Admin group
Route::namespace('Admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::group([
            'middleware' => 'guest',
        ], function () {
            Route::get('/login', 'AuthController@showLogin')->name('login.show');
            Route::post('/login', 'AuthController@postLogin')->name('login.post');
            //Route::get('/register', 'AuthController@showRegister')->name('register.show');
            //Route::post('/register', 'AuthController@postRegister')->name('register.post');
            //Route::get('/forgot-password', 'AuthController@showForgotPassword')->name('forgot-password.show');
            //Route::post('/forgot-password', 'AuthController@postForgotPassword')->name('forgot-password.post');

            //Route::get('/reset-password/{token}', 'AuthController@showResetPassword')->name('reset-password.show');
            //Route::post('/reset-password/{token}', 'AuthController@postResetPassword')->name('reset-password.post');
        });

        Route::group([
           'middleware' => 'admin'
        ], function () {
            Route::post('regos/method', 'HomeController@callRegosMethod')->name('regos.method');
            Route::post('logout', 'HomeController@logout')->name('logout');
            Route::post('destroy/image', 'HomeController@destroyImage')->name('destroy-image');

            /* -------------------------------------------------
                ЗАКАЗЫ START
            ------------------------------------------------- */

            // Пользователи
            Route::get('users/', 'UserController@index')->name('user.index');
            Route::get('users/form/{id?}', 'UserController@form')->name('user.form');
            Route::post('users/form/{id?}', 'UserController@post')->name('user.post');
            Route::delete('users/{id}', 'UserController@destroy')->name('user.destroy');

            // Заказы
            Route::get('orders/', 'OrderController@index')->name('order.index');
            Route::get('orders/form/{id?}', 'OrderController@form')->name('order.form');
            Route::post('orders/form/{id?}', 'OrderController@post')->name('order.post');

            // Регионы
            Route::get('regions/', 'RegionController@index')->name('region.index');
            Route::get('regions/form/{id?}', 'RegionController@form')->name('region.form');
            Route::post('regions/form/{id?}', 'RegionController@post')->name('region.post');
            Route::delete('regions/{id}', 'RegionController@destroy')->name('region.destroy');

            /* -------------------------------------------------
                ЗАКАЗЫ END
            ------------------------------------------------- */

            /* -------------------------------------------------
                RG MODELS START
            ------------------------------------------------- */

            // Product
            Route::get('products/', 'ProductController@index')->name('product.index');
            Route::get('products/form/{id}', 'ProductController@form')->name('product.form');
            Route::post('products/form/{id}', 'ProductController@post')->name('product.post');

            // Акции и скидки
            Route::get('stocks/', 'StockController@index')->name('stock.index');
            Route::get('stocks/form/{id}', 'StockController@form')->name('stock.form');
            Route::post('stocks/form/{id}', 'StockController@post')->name('stock.post');

            // Бренды
            Route::get('brands/', 'BrandController@index')->name('brand.index');
            Route::get('brands/form/{id}', 'BrandController@form')->name('brand.form');
            Route::post('brands/form/{id}', 'BrandController@post')->name('brand.post');

            // Цвета
            Route::get('colors/', 'ColorController@index')->name('color.index');
            Route::get('colors/form/{id}', 'ColorController@form')->name('color.form');
            Route::post('colors/form/{id}', 'ColorController@post')->name('color.post');

            // Страны
            Route::get('country/', 'CountryController@index')->name('country.index');
            Route::get('country/form/{id}', 'CountryController@form')->name('country.form');
            Route::post('country/form/{id}', 'CountryController@post')->name('country.post');

            /* -------------------------------------------------
                RG MODELS END
            ------------------------------------------------- */

            // Главная
            Route::get('/', 'HomeController@home')->name('home');

            // Категории
            Route::get('categories/', 'CategoryController@index')->name('category.index');
            Route::get('categories/form/{id?}', 'CategoryController@form')->name('category.form');
            Route::post('categories/form/{id?}', 'CategoryController@post')->name('category.post');
            Route::delete('categories/{id}', 'CategoryController@destroy')->name('category.destroy');
            Route::post('categories', 'CategoryController@update')->name('category.update');

            // Баннер
            Route::get('banners/', 'BannerController@index')->name('banner.index');
            Route::get('banners/form/{id?}', 'BannerController@form')->name('banner.form');
            Route::post('banners/form/{id?}', 'BannerController@post')->name('banner.post');
            Route::delete('banners/{id}', 'BannerController@destroy')->name('banner.destroy');

            // Блог
            Route::get('posts/', 'PostController@index')->name('post.index');
            Route::get('posts/form/{id?}', 'PostController@form')->name('post.form');
            Route::post('posts/form/{id?}', 'PostController@post')->name('post.post');
            Route::delete('posts/{id}', 'PostController@destroy')->name('post.destroy');

            // Pages
            Route::get('pages/', 'PageController@index')->name('page.index');
            Route::get('pages/form/{id?}', 'PageController@form')->name('page.form');
            Route::post('pages/form/{id?}', 'PageController@post')->name('page.post');
            Route::delete('pages/{id}', 'PageController@destroy')->name('page.destroy');

            // Опции
            Route::get('option/', 'OptionController@index')->name('option.index');
            Route::get('option/form/{id?}', 'OptionController@form')->name('option.form');
            Route::post('option/form/{id?}', 'OptionController@post')->name('option.post');
            Route::delete('option/{id}', 'OptionController@destroy')->name('option.destroy');

            // Values
            Route::get('option/{option_id}/value', 'OptionValueController@index')->name('option-value.index');
            Route::get('option/{option_id}/value/form/{id?}', 'OptionValueController@form')->name('option-value.form');
            Route::post('option/{option_id}/value/form/{id?}', 'OptionValueController@post')->name('option-value.post');
            Route::delete('option/value/{id}', 'OptionValueController@destroy')->name('option-value.destroy');

            Route::get('option/value/form/{id}', 'OptionValueController@formSingle')->name('option-value.form-single');
            Route::post('option/value/form/{id?}', 'OptionValueController@postSingle')->name('option-value.post-single');

            // Теги
            Route::get('tag/', 'TagController@index')->name('tag.index');
            Route::get('tag/form/{id?}', 'TagController@form')->name('tag.form');
            Route::post('tag/form/{id?}', 'TagController@post')->name('tag.post');
            Route::delete('tag/{id}', 'TagController@destroy')->name('tag.destroy');

            // Меню
            Route::get('menu/', 'MenuController@index')->name('menu.index');
            Route::get('menu/form/{id?}', 'MenuController@form')->name('menu.form');
            Route::post('menu/form/{id?}', 'MenuController@post')->name('menu.post');
            Route::delete('menu/{id}', 'MenuController@destroy')->name('menu.destroy');

            // Настройки сайта
            Route::get('settings/', 'SettingController@form')->name('setting.form');
            Route::post('settings/', 'SettingController@post')->name('setting.post');
        });
});


// Artisan group
Route::prefix('artisan')
    ->group(function () {

        //---------------------config---------------------//
        Route::get('/config/cache', 'ArtisanController@configCache');
        Route::get('/config/clear', 'ArtisanController@configClear');

        //---------------------route---------------------//
        Route::get('/route/cache', 'ArtisanController@routeCache');
        Route::get('/route/clear', 'ArtisanController@routeClear');

        //---------------------cache---------------------//
        Route::get('/cache/clear', 'ArtisanController@cacheClear');

        //---------------------view---------------------//
        Route::get('/view/cache', 'ArtisanController@viewCache');
        Route::get('/view/clear', 'ArtisanController@viewClear');

        //---------------------optimize---------------------//
        Route::get('/optimize', 'ArtisanController@optimize');
        Route::get('/optimize/clear', 'ArtisanController@optimizeClear');

        //---------------------storage-link---------------------//
        Route::get('/storage/link', 'ArtisanController@storageLink');
});

