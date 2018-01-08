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




Route::rule([
	// 首页路由
	'/'					=>	'index/index/index',

	// 网址提交
	'/addurl'			=>	'index/addurl/index',

	// 网址提交处理页面
	'/addurls'			=>	'index/addurl/adds',

	// 404页面
	'/404'				=>	'index/index/errors',

	// 网站搜索
	'/search'			=>	'index/search/views',

	// 搜索处理页面
	'/searchs'			=>	'index/search/index',

	// SEO综合查询页面
	'/seo'				=>	'index/seo/index',

	// 文章列表
	'article'			=>	'index/addarticle/lists',

	// 文章投稿
	'/addarticle'		=>	'index/addarticle/index',

	// 文章投稿处理
	'/addarticles'		=>	'index/addarticle/adds',

	// 文章评论
	'/article_common'	=>	'index/addarticle/common',

	// 网站地图
	'/sitemap'			=>	'index/sitemap/index',
	'/sitemap_list'		=>	'index/sitemap/lists',
	'/sitemap_siteinfo'	=>	'index/sitemap/siteinfo',
	'/sitemap_search'	=>	'index/sitemap/search',
	'/sitemap_seo'		=>	'index/sitemap/seo',
	'/sitemap_article'	=>	'index/sitemap/article',

]);



// 批量注册get
Route::rule([
	// url路由
	'siteinfo/:id'			=>	'index/siteinfo/index',

	// 分类路由
	'list/:id'				=>	'index/cate/index',

	// 文章
	'/article_a/:id'		=>	'index/addarticle/look',

],'','get',['ext'=>'html']);

