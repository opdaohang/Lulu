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
            $id = htmlspecialchars(input('id'));
		}

		// 根据id获取信息
		$urlArr = Model('Url')->get($id);
		if(!$urlArr){
			$this->redirect(url('index/index/errors'));
		}else {
			$urlArr = $urlArr->toArray();
            // 判断状态
            if($urlArr['status'] != 1){
                $this->redirect(url('index/index/errors'));
            }
		}

		// 获取基本信息
		$setting = getSetting();

    	// 获取基本信息
    	$webTitle			    = 	$setting['web_title'];
    	$webUrl			        =	$setting['web_url'];
        $cacheTime              =   $setting['cache_time'];
        $pic_api                =   $setting['common_pic_api'];
        $likeNum                =   $setting['siteinfo_like_num'];
        $sidebarFastType        =   $setting['siteinfo_fast_type'];
        $sidebarFastNum         =   $setting['siteinfo_fast_num'];
        $tongji_code            =   $setting['tongji_code'];

    	// 去除url的https://
    	if(preg_match("/https{0,1}:\/\//",$urlArr['url'])){
    	    $titleUrl     =    preg_replace("/https{0,1}:\/\//",'',$urlArr['url']);
    	}
    	
        // 设置meta
        $meta                   =   getMeta('siteinfo','',$id,'');

    	

    	// 获取菜单
    	$menu	         =	Model('Menu')
            					->order('top asc')
            					->select();

    	// 根据cate id获取分类目录
    	$cateNameArr	=	Model('Cate')->get($urlArr['cate'])->toArray();
    	$cateName		=	$cateNameArr['title'];

    	// 猜你喜欢 
    	$cai 	         =	Model('Url')
            					->where('status',1)
            					->limit($likeNum)
            					->order('rand()')
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
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webUrl',$webUrl);
        $this->assign('meta',$meta);
        $this->assign('cateName',$cateName);


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