<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];
use think\Route;

/*
	静态路由
*/

// 首页路由
Route::rule('/','index/index/index');

// 网址提交
Route::rule('/addurl','index/addurl/index');

// 网址提交页面
Route::rule('/addurls','index/addurl/adds');

// 404页面
Route::rule('/404','index/index/errors');

// sitemap begin

Route::any('/sitemap','index/sitemap/index','[]',['ext'=>'xml','denyext'=>'']);
Route::any('/sitemap_list','index/sitemap/lists','[]',['ext'=>'xml','denyext'=>'']);
Route::any('/sitemap_siteinfo','index/sitemap/siteinfo','[]',['ext'=>'xml','denyext'=>'']);
Route::any('/sitemap_search','index/sitemap/search','[]',['ext'=>'xml','denyext'=>'']);
Route::any('/sitemap_seo','index/sitemap/seo','[]',['ext'=>'xml']);
Route::any('/sitemap_article','index/sitemap/article','[]',['ext'=>'xml']);


// sitemap end


// search
Route::get('/searchs','index/search/index');
Route::rule('/search','index/search/views');

// seo
Route::any('/seo','index/seo/index'); 

// 文章路由
Route::rule('/addarticle','index/addarticle/index');
Route::any('/addarticles','index/addarticle/adds');

// 文章列表
Route::rule('article','index/addarticle/lists');

Route::get('arcicle/:id','index/addarticle/look',['ext'=>'html']);

// 评论路由
Route::rule('/artocle/common','index/addarticle/common');

// 批量注册get
Route::rule([
	// url路由
	'siteinfo/:id'			=>	'index/siteinfo/index',
	// 分类路由
	'list/:id'				=>	'index/cate/index',
	// 文章
],'','get',['ext'=>'html']);

// 后台