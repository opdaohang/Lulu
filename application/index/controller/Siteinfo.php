<?php

namespace app\index\controller;

use think\Controller;
use think\Model;

class Siteinfo extends Controller{
	public function index(){
		// 判断是否有id 没有id错误
		if(!input('?id')){
			$this->redirect(url('index/index/errors'));
		}else{
			$id = input('id');
            $id = htmlspecialchars($id);
		}

		// 根据id获取信息
		$urlArr = Model('Url')->get($id);
		if(!$urlArr){
			$this->redirect(url('index/index/errors'));
		}else {
			$urlArr = $urlArr->toArray();
		}

		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

    	// 获取基本信息
    	$title			    = 	$setting['web_title'];
    	$webUrl			    =	$setting['web_url'];
        $cacheTime          =   $setting['cache_time'];
        $pic_api            =   $setting['common_pic_api'];
        $likeNum            =   $setting['siteinfo_like_num'];
        $sidebarFastType    =   $setting['siteinfo_fast_type'];
        $sidebarFastNum     =   $setting['siteinfo_fast_num'];
        $tongji_code        =   $setting['tongji_code'];

    	// 改变基本信息
    	$webTitle      	    =	$urlArr['title'].'-'.$urlArr['url'].'-'.$title;
    	$webKeywords        =	$urlArr['keywords'];
    	$webDescription     =	$urlArr['description'];

    	

    	// 获取菜单
    	$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();

    	// 根据cate id获取分类目录
    	$cateNameArr	=	Model('Cate')->get($urlArr['cate'])->toArray();
    	$cateName		=	$cateNameArr['title'];

    	// 猜你喜欢 
    	$cai 	=	Model('Url')
    					->where('status',1)
    					->limit($likeNum)
    					->order('rand()')
    					// ->cache('like',$cacheTime)
    					->select();

    	// 快速审核 ** 根据类型来获取
        switch ($sidebarFastType) {
            // 如果为最新
            case 'new':
                $su     =   Model('Url')
                                ->where(['fast'=>1,'status'=>1])
                                ->limit($sidebarFastNum)
                                ->order('id asc')
                                ->select();
            break;
            // 如果为随机
            case 'suiji':
                $su     =    Model('Url')
                                ->where(['fast'=>1,'status'=>1])
                                ->limit($sidebarFastNum)
                                ->order('rand()')
                                ->select();
            break;
            // 如果为hot
            case 'hot':
                $su     =    Model('Url')
                                ->where(['fast'=>1,'status'=>1])
                                ->limit($sidebarFastNum)
                                ->order('view desc')
                                ->select();
            break;
            // 不设置默认随机
            default:
                $su     =    Model('Url')
                                ->where(['fast'=>1,'status'=>1])
                                ->limit($sidebarFastNum)
                                ->order('rand()')
                                ->select();
            break;
        }
    	

    	// 赋值基本信息
    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('webUrl',$webUrl);


    	// 赋值url信息
    	$this->assign('urlArr',$urlArr);

    	// 赋值菜单
    	$this->assign('menu',$menu);
    	// 赋值彩泥喜欢
    	$this->assign('cai',$cai);
    	// 赋值快速审核
    	$this->assign('su',$su);
    	// 赋值分类目录
    	$this->assign('cate',$cateNameArr);
        // 赋值图片接口
        $this->assign('pic_api',$pic_api);
        // 赋值统计代码
        $this->assign('tongji_code',$tongji_code);

        // 浏览数量+1
        $jia = Model('Url')->where('id',$id)->setInc('view');

		return view();


	}
    public function _empty(){
        $this->redirect(url('index/index/errors'));
    }
}