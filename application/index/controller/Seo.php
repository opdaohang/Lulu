<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Seo extends Controller{
	public function index(){
		// 获取系统变量
		$setting		=	getSetting();

        // 基本信息
        $webTitle       =   $setting['web_title'];
		$webUrl			=	$setting['web_url'];
		$tongji_code	=	$setting['tongji_code'];

        // 获取meta
        $meta           =   getMeta('seo','','','');

		// 菜单
		$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();

        // 获取seo数据【最近查询】
        $seoShowNum     =   $setting['seo_show_num'];
        $seoArr     =   Model('Seo')->order('time desc')->limit($seoShowNum)->select();

    	// 如果有get数据
    	if(input('url')){
    		$seoUrl		=	input('url');

    		// 检测网址
    		if(!preg_match("/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/", $seoUrl)){
    			$message	=	false;
    		}else{
                $seoUrl = preg_match("/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/", $seoUrl,$seoUrlArrs);
                $seoUrl =   $seoUrlArrs[0];

                // 去掉尾部/号
                $strlen     =   strlen($seoUrl);
                $stripos    =   strripos($seoUrl, '/');
                if($strlen-1 == $stripos){
                    $seoUrl     =   substr($seoUrl, 0,$strlen-1);
                }

    			$message	=	true;

               // 设置meta
                $meta = getMeta('seo','','',$seoUrl);

                // 更改数量
                $seoUrlNum  =   Model('Seo')->where('url',$seoUrl)->find();

                $time   =   @date('Y-m-d H:i:s');
                if(!$seoUrlNum){
                    // 准备插入数据
                    $insertData     =   ['url'=>$seoUrl,'num'=>0,'time'=>$time];
                    $insertSeoUrl   =   Model('Seo')->insert($insertData);
                }else{
                    // 数量更新1
                    $update     =   Model('Seo')->where('url',$seoUrl)->setInc('num');
                    // 更新时间
                    Model('Seo')->where('url',$seoUrl)->update(['time'=>$time]);
                }
                
    		}

    		
    		$this->assign('seoUrl',$seoUrl);
    	}else{
            $message    =   false;
        }

        $this->assign('meta',$meta);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webUrl',$webUrl);
    	$this->assign('tongji_code',$tongji_code);
        $this->assign('message',$message);

    	$this->assign('menu',$menu);
        $this->assign('seoArr',$seoArr);
		return view();
	}
    
    public function _empty(){
        $this->redirect(url('index/index/errors'));
    }
}

?>