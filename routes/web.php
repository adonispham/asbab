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

Route::namespace('Guest')->group(function () {
    Route::get('/', 'HomeController@index')->name('asbab.home');
    Route::post('/', 'HomeController@login')->name('asbab.login');
    Route::get('logout', 'HomeController@logout')->name('asbab.logout');

    // Route about page login by socialite
    Route::get('redirect/{provider}', 'SocialteController@redirect')->name('asbab.login.redirect');
    Route::get('callback/{provider}', 'SocialteController@callback')->name('asbab.login.callback');

    // Route about page product
    Route::get('product/{slug}', 'ProductController@show')->name('asbab.product.show');
    Route::get('product/getSubComm/{id}', 'ProductController@getSubComm')->name('asbab.product.getSubComm');
    Route::get('product/getComment/{id}', 'ProductController@getComment')->name('asbab.product.getComment');
    Route::post('product/{id}', 'ProductController@stars')->name('asbab.product.stars');
    Route::post('product/add_comment/{id}', 'ProductController@add_comment')->name('asbab.product.add_comment');
    Route::get('product/remove_comment/{id}', 'ProductController@remove_comment')->name('asbab.product.remove_comment');
    Route::get('product/like_comment/{id}', 'ProductController@like_comment')->name('asbab.product.like_comment');

    // Route wishlist page
    Route::get('wishlist', function () {
        return view('asbab.wishlist');
    })->name('asbab.wishlist');
    Route::get('wishlist/data', 'ProductController@wishlist')->name('asbab.wishlist.data');
    Route::get('wishlist/add', 'ProductController@add_wishlist')->name('asbab.wishlist.add');
    Route::get('wishlist/remove/{id}', 'ProductController@remove_wishlist')->name('asbab.wishlist.remove');

    // Route compare page
    Route::get('compare', function () {
        return view('asbab.compare');
    })->name('asbab.compare');
    Route::get('compare/data', 'ProductController@compare')->name('asbab.compare.data');

    // Route about page cart
    Route::get('cart/addcart/{id}', 'CartController@addcart')->name('asbab.cart.addcart');
    Route::get('cart/removecart/{id}', 'CartController@removecart')->name('asbab.cart.removecart');
    Route::get('shop', 'ShopController@index')->name('asbab.shop.index');
    Route::get('shop/get_data', 'ShopController@get_data')->name('asbab.shop.data');
    Route::get('cart', 'CartController@index')->name('asbab.cart.index');
    Route::get('cart/update', 'CartController@update')->name('asbab.cart.update');

    // Route about page checkout
    Route::get('checkout/coupon', 'CheckoutController@coupon')->name('asbab.checkout.coupon');
    Route::get('checkout/calc_fee_ship', 'CheckoutController@calc_fee_ship')->name('asbab.checkout.calc_fee_ship');
    Route::get('checkout/cash_on_delivery', 'CheckoutController@cash_on_delivery')->name('asbab.checkout.cash_on_delivery');
    Route::get('checkout/confirm_order', 'CheckoutController@confirm_order')->name('asbab.checkout.confirm_order');

    // Route about page news
    Route::get('news', 'NewsController@index')->name('asbab.news.index');
    Route::get('news/data', 'NewsController@data')->name('asbab.news.data');
    Route::get('news/getComment/{id}', 'NewsController@getComment')->name('asbab.news.getComment');
    Route::get('news/getSubComm/{id}', 'NewsController@getSubComm')->name('asbab.news.getSubComm');
    Route::get('news/{slug}', 'NewsController@details')->name('asbab.news.details');
    Route::post('news/add_comment/{id}', 'NewsController@add_comment')->name('asbab.news.add_comment');
    Route::get('news/remove_comment/{id}', 'NewsController@remove_comment')->name('asbab.news.remove_comment');
    Route::get('news/like_comment/{id}', 'NewsController@like_comment')->name('asbab.news.like_comment');

    // Route about page account
    Route::get('account', 'AccountController@index')->name('asbab.account.index');
    Route::post('account/edit_profile', 'AccountController@edit_profile')->name('asbab.account.edit_profile');
    Route::post('account/reset_password', 'AccountController@reset_password')->name('asbab.account.reset_password');

    // Route about page account
    Route::get('order', function () {
        if (auth()->user()) {
            return view('asbab.order');
        } else {
            return redirect()->to('asbab#login_account');
        }
    })->name('asbab.order.index');
    Route::get('order/history', 'AccountController@history')->name('asbab.order.history');
    Route::get('order/detail/{id}', 'AccountController@detail')->name('asbab.order.detail');
    Route::get('order/cancel/{id}', 'AccountController@cancel_order')->name('asbab.order.cancel');

    // Route about page contact
    Route::get('contact', 'ContactController@index')->name('asbab.contact.index');
    Route::post('contact/question', 'ContactController@question')->name('asbab.contact.question');
});

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/', 'AuthController@index')->name('admin');
    Route::get('logout', 'AuthController@logout')->name('admin.logout');
    Route::post('/', 'AuthController@login')->name('admin.login');

    Route::prefix('dashboard')->group(function () {
        Route::get('line_chart', 'AuthController@line_chart')->name('admin.dashboard.line_chart');
        Route::get('donuts_chart', 'AuthController@donuts_chart')->name('admin.dashboard.donuts_chart');
    });

    Route::prefix('user')->group(function () {
        Route::get('index', function () {
            return view('admin.user.index');
        })->name('admin.user.index');
        Route::get('data', 'UserController@index')->name('admin.user.data');
        Route::get('create', 'UserController@create')->name('admin.user.create');
        Route::post('store', 'UserController@store')->name('admin.user.store');
        Route::get('edit/{id}', 'UserController@edit')->name('admin.user.edit');
        Route::post('update/{id}', 'UserController@update')->name('admin.user.update');
        Route::get('delete/{id}', 'UserController@destroy')->name('admin.user.delete');
    });

    Route::prefix('customer')->group(function () {
        Route::get('index', function () {
            return view('admin.user.customer');
        })->name('admin.customer.index');
        Route::get('data', 'UserController@customers')->name('admin.customer.data');
        Route::post('update', 'UserController@cus_update')->name('admin.customer.update');
    });

    Route::prefix('category')->group(function () {
        Route::get('index', function () {
            return view('admin.category.index');
        })->name('admin.category.index');
        Route::get('data', 'CategoryController@index')->name('admin.category.data');
        Route::get('create', 'CategoryController@create')->name('admin.category.create');
        Route::post('store', 'CategoryController@store')->name('admin.category.store');
        Route::get('edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
        Route::post('update/{id}', 'CategoryController@update')->name('admin.category.update');
        Route::get('delete/{id}', 'CategoryController@destroy')->name('admin.category.delete');
    });

    Route::prefix('brand')->group(function () {
        Route::get('index', function () {
            return view('admin.brand.index');
        })->name('admin.brand.index');
        Route::get('data', 'BrandController@index')->name('admin.brand.data');
        Route::post('store', 'BrandController@store')->name('admin.brand.store');
        Route::get('edit/{id}', 'BrandController@edit')->name('admin.brand.edit');
        Route::post('update/{id}', 'BrandController@update')->name('admin.brand.update');
        Route::get('delete/{id}', 'BrandController@destroy')->name('admin.brand.delete');
    });

    Route::prefix('product')->group(function () {
        Route::get('index', function () {
            return view('admin.product.index');
        })->name('admin.product.index');
        Route::get('data', 'ProductController@index')->name('admin.product.data');
        Route::get('create', 'ProductController@create')->name('admin.product.create');
        Route::post('store', 'ProductController@store')->name('admin.product.store');
        Route::get('edit/{id}', 'ProductController@edit')->name('admin.product.edit');
        Route::post('update/{id}', 'ProductController@update')->name('admin.product.update');
        Route::get('delete/{id}', 'ProductController@destroy')->name('admin.product.delete');
    });

    Route::prefix('news')->group(function () {
        Route::get('index', function () {
            return view('admin.news.index');
        })->name('admin.news.index');
        Route::get('data', 'NewsPaperController@index')->name('admin.news.data');
        Route::get('create', 'NewsPaperController@create')->name('admin.news.create');
        Route::post('store', 'NewsPaperController@store')->name('admin.news.store');
        Route::get('edit/{id}', 'NewsPaperController@edit')->name('admin.news.edit');
        Route::post('update/{id}', 'NewsPaperController@update')->name('admin.news.update');
        Route::get('delete/{id}', 'NewsPaperController@destroy')->name('admin.news.delete');
    });

    Route::prefix('comment')->group(function () {
        Route::get('product', function () {
            return view('admin.comment.product');
        })->name('admin.comment.product');
        Route::get('product/data', 'CommentController@PRDcomments')->name('admin.comment.product.data');
        Route::get('product/{slug}', 'CommentController@PRDcommDT')->name('admin.comment.product.details');
        Route::get('product/{slug}/reply', 'CommentController@PRDreply')->name('admin.comment.product.reply');
        Route::get('product/{slug}/delete', 'CommentController@PRDdelete')->name('admin.comment.product.delete');

        Route::get('news', function () {
            return view('admin.comment.news');
        })->name('admin.comment.news');
        Route::get('news/data', 'CommentController@NEWScomments')->name('admin.comment.news.data');
        Route::get('news//{slug}', 'CommentController@NEWScommDT')->name('admin.comment.news.details');
        Route::get('news/{slug}/reply', 'CommentController@NEWSreply')->name('admin.comment.news.reply');
        Route::get('news/{slug}/delete', 'CommentController@NEWSdelete')->name('admin.comment.news.delete');
    });

    Route::prefix('slider')->group(function () {
        Route::get('index', function () {
            return view('admin.slider.index');
        })->name('admin.slider.index');
        Route::get('data', 'SliderController@index')->name('admin.slider.data');
        Route::post('store', 'SliderController@store')->name('admin.slider.store');
        Route::get('edit/{id}', 'SliderController@edit')->name('admin.slider.edit');
        Route::post('update/{id}', 'SliderController@update')->name('admin.slider.update');
        Route::get('delete/{id}', 'SliderController@destroy')->name('admin.slider.delete');
    });

    Route::prefix('order')->group(function () {
        Route::get('index', 'OrderController@index')->name('admin.order.index');
        Route::get('data', 'OrderController@data')->name('admin.order.data');
        Route::get('show/{id}', 'OrderController@show')->name('admin.order.show');
        Route::get('print/{id}', 'OrderController@print')->name('admin.order.print');
        Route::post('update/{status}', 'OrderController@update')->name('admin.order.update');
    });

    Route::prefix('coupon')->group(function () {
        Route::get('index', function () {
            return view('admin.coupon.index');
        })->name('admin.coupon.index');
        Route::get('data', 'CouponController@index')->name('admin.coupon.data');
        Route::get('create', 'CouponController@create')->name('admin.coupon.create');
        Route::post('store', 'CouponController@store')->name('admin.coupon.store');
        Route::get('edit/{id}', 'CouponController@edit')->name('admin.coupon.edit');
        Route::get('send/{id}/{type}', 'CouponController@send')->name('admin.coupon.send');
        Route::post('update/{id}', 'CouponController@update')->name('admin.coupon.update');
        Route::get('delete/{id}', 'CouponController@destroy')->name('admin.coupon.delete');
    });

    Route::prefix('role')->group(function () {
        Route::get('index', function () {
            return view('admin.role.index');
        })->name('admin.role.index');
        Route::get('data', 'RoleController@index')->name('admin.role.data');
        Route::get('create', 'RoleController@create')->name('admin.role.create');
        Route::post('store', 'RoleController@store')->name('admin.role.store');
        Route::get('edit/{id}', 'RoleController@edit')->name('admin.role.edit');
        Route::post('update/{id}', 'RoleController@update')->name('admin.role.update');
        Route::get('delete/{id}', 'RoleController@destroy')->name('admin.role.delete');
    });

    Route::prefix('delivery')->group(function () {
        Route::get('index', function () {
            return view('admin.delivery.index');
        })->name('admin.delivery.index');
        Route::get('data', 'DeliveryController@index')->name('admin.delivery.data');
        Route::get('provinces', 'DeliveryController@provinces')->name('admin.delivery.provinces');
        Route::get('districts/{id}', 'DeliveryController@wards')->name('admin.delivery.wards');
        Route::get('wards/{id}', 'DeliveryController@districts')->name('admin.delivery.districts');
        Route::post('store', 'DeliveryController@store')->name('admin.delivery.store');
        Route::post('update/{id}', 'DeliveryController@update')->name('admin.delivery.update');
    });

    Route::prefix('permission')->group(function () {
        Route::get('create', 'PermissionController@create')->name('admin.permission.create');
        Route::get('get_actions', 'PermissionController@get_actions')->name('admin.permission.get_actions');
        Route::post('store', 'PermissionController@store')->name('admin.permission.store');
    });

    Route::prefix('setting')->group(function () {
        Route::get('index', function () {
            return view('admin.setting.index');
        })->name('admin.setting.index');
        Route::get('data', 'SettingController@index')->name('admin.setting.data');
        Route::post('store', 'SettingController@store')->name('admin.setting.store');
        Route::post('update/{id}', 'SettingController@update')->name('admin.setting.update');
    });
});
