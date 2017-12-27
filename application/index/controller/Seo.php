<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Seo extends Controller{
	public function index(){
		// 获取基本信息
		// 获取系统变量
		$setting		=	Model('Setting')->get(1)->toArray();

		$title 			=	$setting['web_title'];
		$webTitle 		=	"SEO查询-".$title;
		$webKeywords	=	'';
		$webDescription	=	'';
		$webUrl			=	$setting['web_url'];
		$tongji_code	=	$setting['tongji_code'];

		// 菜单
		$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();
    	// 如果有get数据
    	if(input('url')){
    		$seoUrl		=	input('url');

    		// 检测网址
    		if(!preg_match("/https{0,1}:\/\//i", $seoUrl)){
    			$message	=	false;
    			// 赋值url
    			$this->assign('seoUrl',$seoUrl);
    		}else{
    			$message	=	true;
    			// 

    		}

    		$this->assign('message',$message);
    		$this->assign('seoUrl',$seoUrl);
    	}


    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('webUrl',$webUrl);
    	$this->assign('tongji_code',$tongji_code);

    	$this->assign('menu',$menu);
		return view();
	}
}

?>