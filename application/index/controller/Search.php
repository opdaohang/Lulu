<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Search extends Controller{
	public function index(){
		$wd = input('wd');
		// 过滤
		$wd =	htmlspecialchars($wd);
		if(empty($wd) || !$wd){
			$this->redirect(url('index/index/errors'));
		}

		// 判断页码
		if(!input('page')){
			$page = 1;
		}else{
			$page = htmlspecialchars(input('page'));
		}

		// 获取系统变量
		$setting	=	Model('Setting')->get(1)->toArray();

		// 获取分页数量
		$common_limit	=	$setting['common_limit_num'];
		// 获取图片接口
		$pic_api		=	$setting['common_pic_api'];
		// 获取统计代码
		$tongji_code	=	$setting['tongji_code'];
		$webUrl			=	$setting['web_url'];
		$newShowNum		=	$setting['index_new_num'];

		// 赋值基本信息
		$title		=	$setting['web_title'];

		$webTitle 	=	$wd.'的搜索结果-'.$title;
		if($page >= 2){
			$webTitle 	=	$webTitle.'-第{$page}页';

		}
		$webDescription		=	'';
		$webKkeywords		=	'';

		// 菜单
		$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();
    	// 获取最新加入网站
    	$new 	=	Model('Url')
    					->where('status',1)
    					->order('id desc')
    					->limit($newShowNum)
    					->select();

    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					->select();

		// 开始搜网址
		$searchUrl	=	Model('Url')
							->where('url','like',"%{$wd}%")
							->whereOr('title','like',"%{$wd}%")
							->order('title asc,url asc,status desc')
							->limit($common_limit)
							->page($page)
							->select();

		// 分页
		$searchPage	=	Model('Url')
							->where('url','like',"%{$wd}%")
							->whereOr('title','like',"%{$wd}%")
							->paginate([
								'list_rows'	=> $common_limit,
								'query'		=> ['wd'=>$wd]
							]);

		// 获取 搜到多少数据
		$searchNum	=	Model('Url')
							->where('url','like',"%{$wd}%")
							->whereOr('title','like',"%{$wd}%")
							->count();
		// 插入搜索数据
		if($page == 1){
			// 判断是否存在
			$searchExits	=	Model('Search')->where('wd',$wd)->count();
			if($searchExits == 0){
				// 准备插入的数据
				$data	=	['wd'=>$wd,'num'=>0];
				$searchInsert	=	Model('Search')->insert($data);
			}else{
				// 自增1
				$searchZeng		=	Model('Search')->where('wd',$wd)->setInc('num');
			}
		}
		if($page > 1){
			// 判断页数是否超出限制
			if($searchNum != 0){
				if(ceil($searchNum/$common_limit)<$page){
					$this->redirect(url('index/index/errors'));
				}
			}
		}


		$this->assign('title',$title);
		$this->assign('webTitle',$webTitle);
		$this->assign('webKeywords',$webKkeywords);
		$this->assign('webDescription',$webDescription);
		$this->assign('webUrl',$webUrl);
		$this->assign('pic_api',$pic_api);
		$this->assign('tongji_code',$tongji_code);

		// 
		$this->assign('searchList',$searchUrl);
		$this->assign('searchPage',$searchPage);
		$this->assign('searchNum',$searchNum);
		$this->assign('menu',$menu);
		$this->assign('cate',$cate);
		$this->assign('new',$new);
		$this->assign('wd',$wd);


		return view();

	}
	public function views(){
		// 获取系统变量
		$setting	=	Model('Setting')->get(1)->toArray();

		// 获取分页数量
		$common_limit	=	$setting['common_limit_num'];
		// 获取图片接口
		$pic_api		=	$setting['common_pic_api'];
		// 获取统计代码
		$tongji_code	=	$setting['tongji_code'];
		$webUrl			=	$setting['web_url'];
		$newShowNum		=	$setting['index_new_num'];

		// 赋值基本信息
		$title		=	$setting['web_title'];

		$webTitle 	=	"搜索-".$title;
		$webDescription		=	'';
		$webKeywords		=	'';

		// 菜单
		$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();

    	// 获取搜索榜
    	
    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('tongji_code',$tongji_code);
    	$this->assign('webUrl',$webUrl);

    	$this->assign('menu',$menu);
		return view();
	}
	public function _empty(){
		$this->redirect((url('index/index/errors')));
	}
}
?>