<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Search extends Controller{
	public function index(){
		// 过滤并获取搜索词语
		$wd =	htmlspecialchars(input('wd'));
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
		$setting	=	getSetting();

		// 获取分页数量
		$common_limit	=	$setting['common_limit_num'];
		// 获取图片接口
		$pic_api		=	$setting['common_pic_api'];
		// 获取统计代码
		$tongji_code	=	$setting['tongji_code'];
		// 站点标题
		$webTitle 		=	$setting['web_title'];
		// 站点url
		$webUrl			=	$setting['web_url'];
		// 获取最新站点展示数量
		$newShowNum		=	$setting['index_new_num'];

		// 设置meta
		$meta			=	getMeta('siteinfoSearch',$page,'',$wd);

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

		// 赋值基本信息
		$this->assign('webTitle',$webTitle);
		$this->assign('webUrl',$webUrl);
		$this->assign('pic_api',$pic_api);
		$this->assign('tongji_code',$tongji_code);
		$this->assign('meta',$meta);

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
		$setting			=	getSetting();

		// 获取分页数量
		$common_limit		=	$setting['common_limit_num'];
		// 获取图片接口
		$pic_api			=	$setting['common_pic_api'];
		// 获取统计代码
		$tongji_code		=	$setting['tongji_code'];
		$webTitle 			=	$setting['web_title'];
		$webUrl				=	$setting['web_url'];
		$newShowNum			=	$setting['index_new_num'];
		$searchShowType		=	$setting['search_show_type'];
    	$searchShowNum		=	$setting['search_show_num'];

		// 获取meta
		$meta 				=	getMeta('siteinfoSearch','','','');


		// 菜单
		$menu	=	Model('Menu')
    					->order('top asc')
    					->select();

 

    	// 根据展示类型来查找
    	switch ($searchShowType) {
    		// 最新
    		case 'new':
    			$searchShowArr	=	Model('Search')
    									->order('id desc')
    									->limit($searchShowNum)
    									->select();
    		break;
    		// 最热
    		case 'hot':
    			$searchShowArr	=	Model('Search')
    									->order('num desc')
    									->limit($searchShowNum)
    									->select();
    		// 随机
    		case 'suiji':
    			$searchShowArr	=	Model('Search')
    									->order('rand()')
    									->limit($searchShowNum)
    									->select();
    		// 默认随机
    		default:
    			$searchShowArr	=	Model('Search')
    									->order('rand()')
    									->limit($searchShowNum)
    									->select();
    		break;
    	}
    	

    	$this->assign('webTitle',$webTitle);
    	$this->assign('tongji_code',$tongji_code);
    	$this->assign('webUrl',$webUrl);
    	$this->assign('meta',$meta);

    	$this->assign('menu',$menu);
    	$this->assign('searchShowArr',$searchShowArr);
		return view();
	}
	public function _empty(){
		$this->redirect((url('index/index/errors')));
	}
}
?>