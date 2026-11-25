<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin/cms', 'middleware' => ['admin', 'auth:admin']], function () {
    // Categories
    Route::get('/addcategory', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addcategory',
        'uses' => 'ExtensionsValley\Cms\Controllers\CategoryController@addCategory',
    ]);
    Route::post('/savecategory', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savecategory',
        'uses' => 'ExtensionsValley\Cms\Controllers\CategoryController@saveCategory',
    ]);
    Route::get('/editcategory/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editcategory',
        'uses' => 'ExtensionsValley\Cms\Controllers\CategoryController@editCategory',
    ]);
    Route::post('/updatecategory', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatecategory',
        'uses' => 'ExtensionsValley\Cms\Controllers\CategoryController@updateCategory',
    ]);
    Route::get('/viewcategory/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewcategory',
        'uses' => 'ExtensionsValley\Cms\Controllers\CategoryController@viewCategory',
    ]);

    // Tags
    Route::get('/addtag', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addtag',
        'uses' => 'ExtensionsValley\Cms\Controllers\TagController@addTag',
    ]);
    Route::post('/savetag', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savetag',
        'uses' => 'ExtensionsValley\Cms\Controllers\TagController@saveTag',
    ]);
    Route::get('/edittag/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.edittag',
        'uses' => 'ExtensionsValley\Cms\Controllers\TagController@editTag',
    ]);
    Route::post('/updatetag', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatetag',
        'uses' => 'ExtensionsValley\Cms\Controllers\TagController@updateTag',
    ]);
    Route::get('/viewtag/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewtag',
        'uses' => 'ExtensionsValley\Cms\Controllers\TagController@viewTag',
    ]);

    // Pages
    Route::get('/addpage', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addpage',
        'uses' => 'ExtensionsValley\Cms\Controllers\PageController@addPage',
    ]);
    Route::post('/savepage', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savepage',
        'uses' => 'ExtensionsValley\Cms\Controllers\PageController@savePage',
    ]);
    Route::get('/editpage/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editpage',
        'uses' => 'ExtensionsValley\Cms\Controllers\PageController@editPage',
    ]);
    Route::post('/updatepage', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatepage',
        'uses' => 'ExtensionsValley\Cms\Controllers\PageController@updatePage',
    ]);
    Route::get('/viewpage/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewpage',
        'uses' => 'ExtensionsValley\Cms\Controllers\PageController@viewPage',
    ]);

    // Posts
    Route::get('/addpost', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addpost',
        'uses' => 'ExtensionsValley\Cms\Controllers\PostController@addPost',
    ]);
    Route::post('/savepost', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savepost',
        'uses' => 'ExtensionsValley\Cms\Controllers\PostController@savePost',
    ]);
    Route::get('/editpost/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editpost',
        'uses' => 'ExtensionsValley\Cms\Controllers\PostController@editPost',
    ]);
    Route::post('/updatepost', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatepost',
        'uses' => 'ExtensionsValley\Cms\Controllers\PostController@updatePost',
    ]);
    Route::get('/viewpost/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewpost',
        'uses' => 'ExtensionsValley\Cms\Controllers\PostController@viewPost',
    ]);

    // Menus & Menu Items
    Route::get('/addmenu', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addmenu',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@addMenu',
    ]);
    Route::post('/savemenu', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savemenu',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@saveMenu',
    ]);
    Route::get('/editmenu/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editmenu',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@editMenu',
    ]);
    Route::post('/updatemenu', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatemenu',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@updateMenu',
    ]);
    Route::get('/viewmenu/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewmenu',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@viewMenu',
    ]);
    Route::post('/savemenuitem', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.savemenuitem',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@saveMenuItem',
    ]);
    Route::post('/deletemenuitem/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.deletemenuitem',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@deleteMenuItem',
    ]);
    Route::post('/reordermenuitems', [
        'as' => 'extensionsvalley.cms.reordermenuitems',
        'uses' => 'ExtensionsValley\Cms\Controllers\MenuController@reorderMenuItems',
    ]);

    // Media Library (WordPress-style)
    Route::get('/media', [
        'as' => 'extensionsvalley.cms.media',
        'uses' => 'ExtensionsValley\Cms\Controllers\MediaController@index',
    ]);
    Route::post('/media/upload', [
        'as' => 'extensionsvalley.cms.media.upload',
        'uses' => 'ExtensionsValley\Cms\Controllers\MediaController@upload',
    ]);
    Route::post('/media/update/{id}', [
        'as' => 'extensionsvalley.cms.media.update',
        'uses' => 'ExtensionsValley\Cms\Controllers\MediaController@update',
    ]);
    Route::post('/media/delete/{id}', [
        'as' => 'extensionsvalley.cms.media.delete',
        'uses' => 'ExtensionsValley\Cms\Controllers\MediaController@destroy',
    ]);
    Route::get('/media/picker', [
        'as' => 'extensionsvalley.cms.media.picker',
        'uses' => 'ExtensionsValley\Cms\Controllers\MediaController@picker',
    ]);

    // Banners & Sliders
    Route::get('/addbanner', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addbanner',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@addBanner',
    ]);
    Route::post('/savebanner', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savebanner',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@saveBanner',
    ]);
    Route::get('/editbanner/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editbanner',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@editBanner',
    ]);
    Route::post('/updatebanner', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatebanner',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@updateBanner',
    ]);
    Route::get('/viewbanner/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewbanner',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@viewBanner',
    ]);
    Route::post('/savebannerslide', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.savebannerslide',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@saveSlide',
    ]);
    Route::post('/deletebannerslide/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.deletebannerslide',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@deleteSlide',
    ]);
    Route::post('/reorderbannerslides', [
        'as' => 'extensionsvalley.cms.reorderbannerslides',
        'uses' => 'ExtensionsValley\Cms\Controllers\BannerController@reorderSlides',
    ]);

    // Dynamic Forms & Submissions
    Route::get('/addform', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addform',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@addForm',
    ]);
    Route::post('/saveform', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.saveform',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@saveForm',
    ]);
    Route::get('/editform/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editform',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@editForm',
    ]);
    Route::post('/updateform', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updateform',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@updateForm',
    ]);
    Route::get('/viewform/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewform',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@viewForm',
    ]);
    Route::post('/saveformfield', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.saveformfield',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@saveField',
    ]);
    Route::post('/deleteformfield/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.deleteformfield',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@deleteField',
    ]);
    Route::post('/reorderformfields', [
        'as' => 'extensionsvalley.cms.reorderformfields',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@reorderFields',
    ]);
    Route::get('/formsubmissions/{id}', [
        'as' => 'extensionsvalley.cms.formsubmissions',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@submissions',
    ]);
    Route::post('/deleteformsubmission/{id}', [
        'as' => 'extensionsvalley.cms.deleteformsubmission',
        'uses' => 'ExtensionsValley\Cms\Controllers\FormController@deleteSubmission',
    ]);

    // Languages Master
    Route::get('/addlanguage', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.addlanguage',
        'uses' => 'ExtensionsValley\Cms\Controllers\LanguageController@addLanguage',
    ]);
    Route::post('/savelanguage', [
        'middleware' => 'acl:add',
        'as' => 'extensionsvalley.cms.savelanguage',
        'uses' => 'ExtensionsValley\Cms\Controllers\LanguageController@saveLanguage',
    ]);
    Route::get('/editlanguage/{id}', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.editlanguage',
        'uses' => 'ExtensionsValley\Cms\Controllers\LanguageController@editLanguage',
    ]);
    Route::post('/updatelanguage', [
        'middleware' => 'acl:edit',
        'as' => 'extensionsvalley.cms.updatelanguage',
        'uses' => 'ExtensionsValley\Cms\Controllers\LanguageController@updateLanguage',
    ]);
    Route::get('/viewlanguage/{id}', [
        'middleware' => 'acl:view',
        'as' => 'extensionsvalley.cms.viewlanguage',
        'uses' => 'ExtensionsValley\Cms\Controllers\LanguageController@viewLanguage',
    ]);

    // Theme Customizer
    Route::get('/customizer', [
        'as' => 'extensionsvalley.cms.customizer',
        'uses' => 'ExtensionsValley\Cms\Controllers\ThemeCustomizerController@index',
    ]);
    Route::post('/customizer/save', [
        'as' => 'extensionsvalley.cms.customizer.save',
        'uses' => 'ExtensionsValley\Cms\Controllers\ThemeCustomizerController@save',
    ]);
    Route::post('/customizer/reset', [
        'as' => 'extensionsvalley.cms.customizer.reset',
        'uses' => 'ExtensionsValley\Cms\Controllers\ThemeCustomizerController@reset',
    ]);
});

