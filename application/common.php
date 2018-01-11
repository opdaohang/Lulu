<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*
	*	   根据页面来设置title keywords description 信息
	@param  $type 传入当前页面类型
	@param  $p    当前页面的页码
	@param  $id   如果为资讯或者专属页面传入id
	@param  $wd   搜索词语
	
	|--   type的值
	|---- index 	  		首页
	|---- addUrl 	  		网站提交
	|---- addArticle  		文章提交
	|---- articleList 		文章列表
	|---- siteinfo    		站点详情
	|---- cateList    		站点分类列表
	|---- siteinfoSearch 	站点搜索
	|---- seo         		SEO综合查询


*/
function getMeta($type,$p=1,$id=1,$wd=null){
	// 获取系统变量
	$setting 		=	getSetting();
	//  系统标题
	$title 			= 	$setting['web_title'];
 	//	系统关键词 
 	$keywords		=	$setting['web_keywords'];
 	// 系统描述
 	$description	=	$setting['web_description'];

 	// 换行
 	$brs			=	"\r\n";

 	// 拼接符号
 	$splice			=	'-';


 	//  ** 定义固定页面标题 **

 		// 链接提交
 		$addUrlTitle			=	"链接提交";
 		// 文章提交
 		$addArticleTitle		=	"文章投稿";
 		// 文章资讯列表
 		$articleListTitle		=	"文章资讯";
 		// 站点搜索
 		$siteinfoSearchTitle	=	"搜索";
 		// SEO综合查询
 		$seoTitle 				=	"SEO综合查询";

 	// ** end **

 	// 根据类型判断
 	switch ($type) {
 		// 首页
 		case 'index':
 			// 标题 
 			$result 		 = '<title>'.$title.'</title>'.$brs;
 			// 关键词
 			$result			.= '<meta name="keywords" content="'.$keywords.'">'.$brs;
 			// 描述
 			$result  		.= '<meta name="description" content="'.$description.'">'.$brs;
 		break;
 		// 链接提交页面
 		case 'addUrl':
 			// 标题 
 			$result 		 = '<title>'.$addUrlTitle.$splice.$title.'</title>'.$brs;
 			// 关键词
 			$result			.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  		.= '<meta name="description" content="">'.$brs;
 		break;
 		// 文章投稿页面
 		case 'addArticle':
 			// 标题 
 			$result 		 = '<title>'.$addArticleTitle.$splice.$title.'</title>'.$brs;
 			// 关键词
 			$result			.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  		.= '<meta name="description" content="">'.$brs;
 		break;
 		// 文章资讯
 		case 'articleList':
 			// 根据page来判断
 			if($p == 1){
 				$result 		 = '<title>'.$articleListTitle.$splice.$title.'</title>'.$brs;
 			}else{
 				$result 		 = '<title>'.$articleListTitle.$splice."第{$p}页".$splice.$title.'</title>'.$brs;
 			}
 			// 关键词
 			$result				.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  			.= '<meta name="description" content="">'.$brs;
 		break;

 		// 站点详情
 		case 'siteinfo':
 			// 根据id获取站点信息
 			$arr = Model('Url')->get($id)->toArray();
 			// 站点标题
 			$siteinfoTitle			=	$arr['title'];
 			// 站点关键词
 			$siteinfoKeywords		=	$arr['keywords'];
 			// 站点描述
 			$siteinfoDescription	=	$arr['description'];
 			// 站点url
 			$siteinfoUrl			=	$arr['url'];

 			// 处理站点url
 			if(preg_match("/https{0,1}:\/\//", $siteinfoUrl)){
 				$siteinfoUrl		=	preg_replace("/https{0,1}:\/\//", '', $siteinfoUrl);
 			}

 			// 标题 
 			$result					=	'<title>'.$siteinfoTitle.$splice.$siteinfoUrl.$splice.$title.'</title>'.$brs;

 			// 关键词
 			$result				   .=	'<meta name="keywords" content="'.$siteinfoKeywords.'">'.$brs;

 			// 描述
 			$result				   .=   '<meta name="description" content="'.$siteinfoDescription.'">'.$brs;
 		break;

 		// 站点分类
 		case 'cateList':
 			// 根据id 获取标题
 			$arr 	   = Model('Cate')->get($id)->toArray();

 			$cateTitle = $arr['title'];

 			// 根据page判断
 			if($p == 1){
 				$result 		 = '<title>'.$cateTitle.$splice.$title.'</title>'.$brs;
 			}else{
 				$result 		 = '<title>'.$cateTitle.$splice."第{$p}页".$splice.$title.'</title>'.$brs;
 			}

			// 关键词
 			$result				.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  			.= '<meta name="description" content="">'.$brs;
 		break;

 		// 站点搜索
 		case 'siteinfoSearch':
 			// 如果未定义$wd
 			if($wd == null){
 				$result 		 =	'<title>'.$siteinfoSearchTitle.$splice.$title.'</title>'.$brs;
 			}else{
 				// 如果page > 1
 				if($p > 1){
					$result			 =	'<title>'.$wd.'的搜索结果'.$splice."第{$p}页".$splice.$title.'</title>'.$brs;
 				}else{
 					$result			 =	'<title>'.$wd.'的搜索结果'.$splice.$title.'</title>'.$brs;
 				}
 			}

 			// 关键词
 			$result				.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  			.= '<meta name="description" content="">'.$brs;
 		break;

 		// SEO综合查询
 		case 'seo':
 			// 如果未定义$wd
 			if($wd == null){
 				$result 		 =	'<title>'.$seoTitle.$splice.$title.'</title>'.$brs;
 			}else{
				$result			 =	'<title>'.$wd.'的SEO综合查询'.$splice.$title.'</title>'.$brs;
 				
 			}

 			// 关键词
 			$result				.= '<meta name="keywords" content="">'.$brs;
 			// 描述
 			$result  			.= '<meta name="description" content="">'.$brs;
 		break;

 		// 文章资讯查看
 		case 'article':
 			// 根据ID获取信息
 			$arr 					=	Model('Article')->get($id)->toArray();

 			$articleTitle   		=   $arr['title'];
 			$articleKeywords		=	$arr['keywords'];
 			$articleDescription		=	$arr['description'];

 			$result 				= '<title>'.$articleTitle.$splice.$title.'</title>'.$brs;
 			$result 			   .= '<meta name="keywords" content="'.$articleKeywords.'">'.$brs;
 			$result  			   .= '<meta name="description" content="'.$articleDescription.'">'.$brs;
 			$result  			   .= '<meta name="robots" content="index,nofollow">'.$brs;
 			$result 			   .= '<base target="_blank">'.$brs;
 		break;
 		default:
 			$result = false;
 		break;
 	}

 	return $result;
}
/*
	*	获取系统变量
*/
function getSetting(){
	$result = Model('Setting')->get(1)->toArray();
	return $result;
}