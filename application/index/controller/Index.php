<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Index extends Controller
{
    public function index()
    {

    	// 获取setting系统信息
    	$setting = getSetting();

    	// 设置基本信息
    	$webTitle			= 	$setting['web_title'];
    	$webUrl			    =	$setting['web_url'];
        $tongji_code        =   $setting['tongji_code'];

    	// 赋值名站推荐展示多少数量
    	$mztjShowNum	    =	$setting['index_mztj_num'];
    	// 赋值首页随机展示多少数量
    	$suijiShowNum	    =	$setting['index_suiji_num'];
    	// 赋值最新加入展示多少数量
    	$newShowNum		    =	$setting['index_new_num'];

    	// 赋值全站缓存时间
    	$cacheTime		    =	$setting['cache_time'];

        // 设置meta
        $meta               =   getMeta('index','','','');


    	// 获取名站推荐
    	$mztj 	= 	Model('Mztj')
    					->order('id asc')
    					->limit($mztjShowNum)
    					->select();

    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					->select();


    	// 获取随机推荐
    	$suiji 	=	Model('Url')
    					->where('status',1)
    					->limit($suijiShowNum)
    					->order("rand()")
    					->select();
    	
    	// 获取快速审核站点
    	$fast	=	Model('Url')
    					->where(['fast'=>1,'status'=>'1'])
    					->order('id asc')
    					->select();

    	// 获取菜单
    	$menu	=	Model('Menu')
    					->order('top asc')
    					->select();

    	// 获取友情链接
    	$links	=	Model('Links')
    					->order('id asc')
    					->select();

    	// 获取最新加入网站
    	$new 	=	Model('Url')
    					->where('status',1)
    					->order('id desc')
    					->limit($newShowNum)
    					->select();

    	// 获取总收录网站数量
    	$allNums 	=	Model('Url')->where('status',1)->count();
    	$daiNums	=	Model('Url')->where('status',0)->count();

    	// ————————————————————————————————————————————————————————————————
    	// ————————————————————————————————————————————————————————————————


    	// 赋值基本信息
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webUrl',$webUrl);
        $this->assign('meta',$meta);

 
    	// 赋值名站推荐
    	$this->assign('mztj',$mztj);
    	// 赋值分类
    	$this->assign('cate',$cate);
    	// 赋值随机推荐
    	$this->assign('suiji',$suiji);
    	// 赋值菜单
    	$this->assign('menu',$menu);
    	// 赋值快速审核站点
    	$this->assign('fast',$fast);
    	// 赋值友情链接
    	$this->assign('links',$links);
    	// 赋值最新加入
    	$this->assign('new',$new);
    	// 赋值总数量
    	$this->assign('allNums',$allNums);
    	// 赋值待审核
    	$this->assign('daiNums',$daiNums);
        // 赋值统计代码
        $this->assign('tongji_code',$tongji_code);


    	return view();
    }
    // 404
    public function errors(){

        return view();
    }
    
    // 空操作
    public function _empty(){
        $this->redirect(url('index/index/errors'));
    }
}
