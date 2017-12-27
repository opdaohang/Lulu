<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

/*
    @   总网站地图地址:sitemap.xml
    @   分地图：分类地图：sitemap_list.xml
    @   分地图：url地图：sitemap_link.xml;
    
*/

class Sitemap extends Controller{
    public function index(){
        header('content-type:text/xml');
        
        
        return view();
    }
    public function lists(){
        header('content-type:text/xml');
        // 获取所有分类
        $cateArr = Model('Cate')->select();
        
        $this->assign('cateArr',$cateArr);
        
        return view();
    }
    public function siteinfo(){
        header('content-type:text/xml');
        // 获取所有url地址
        // 按照id排序
        $urlArr = Model('Url')->order('id desc')->limit(50000)->select();
        
        $this->assign('urlArr',$urlArr);
        
        return view();
    }
}