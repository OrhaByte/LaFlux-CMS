<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    // Multilingual prefixed routes (active when 2+ languages are enabled: /en/page/about-us, /ar/post/hello, /en/cms, /en)
    Route::group(['prefix' => '{lang}', 'where' => ['lang' => '[a-z]{2}']], function () {
        Route::get('/', [
            'as' => 'cms.frontend.lang.root',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@homeLang',
        ]);
        Route::get('/cms', [
            'as' => 'cms.frontend.lang.home',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@homeLang',
        ]);
        Route::get('/page/{slug}', [
            'as' => 'cms.frontend.lang.page',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@pageLang',
        ]);
        Route::get('/post/{slug}', [
            'as' => 'cms.frontend.lang.post',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@postLang',
        ]);
        Route::get('/category/{slug}', [
            'as' => 'cms.frontend.lang.category',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@categoryLang',
        ]);
        Route::get('/tag/{slug}', [
            'as' => 'cms.frontend.lang.tag',
            'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@tagLang',
        ]);
    });

    // Unprefixed routes (used directly when single-language; auto-redirect to /{defaultLang}/... when multilingual)
    Route::get('/cms', [
        'as' => 'cms.frontend.home',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@home',
    ]);
    Route::get('/page/{slug}', [
        'as' => 'cms.frontend.page',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@page',
    ]);
    Route::get('/post/{slug}', [
        'as' => 'cms.frontend.post',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@post',
    ]);
    Route::get('/category/{slug}', [
        'as' => 'cms.frontend.category',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@category',
    ]);
    Route::get('/tag/{slug}', [
        'as' => 'cms.frontend.tag',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@tag',
    ]);

    Route::post('/cms/form/{slug}/submit', [
        'as' => 'cms.frontend.form.submit',
        'uses' => 'ExtensionsValley\Cms\Controllers\FrontendController@submitForm',
    ]);
});
