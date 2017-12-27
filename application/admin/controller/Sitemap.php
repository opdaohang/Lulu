<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;

class Sitemap extends Controller{
    public function index(){
        set_time_limit(0);
        
        // 定义sitemap目录
        $sitemapPath = "../public/sitemap";
        // 定义网站地址
        $url         = "http://mulus.com/sitemap/";  
        
        // 检查sitemap目录
        if(!is_dir($sitemapPath)){
            mkdir($sitemapPath);
        }
        
        // 定义总sitemap文件名称
        $sitemapName        = "sitemap.xml";
        // 定义分类sitemap文件名称
        $cateSitemapName    =   "sitemap_cate.xml";
        // 定义linksitemap文件名称
        $urlSitemapName     =   "sitemap_site.xml";

        // 定义总文件内容
        $sitemapContent     =   "
        <sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">
            <sitemap>
                <loc>{$url}{$cateSitemapName}</loc>
            </sitemap>
            <sitemap>
                <loc>{$url}{$urlSitemapName}</loc>
            </sitemap>
        </sitemapindex>
        ";

        // 开始写入文件
        // 拼接写入地址
        $writePath = $sitemapPath.'/'.$sitemapName;
        file_put_contents($writePath,$sitemapContent);

        
        /*
            @   分类地图
        */
        // 获取所有分类
        $cateSitemapArr = Model('Cate')->order('id desc')->select();
        
    }
    public function _empty(){
        $this->redirect(url('admin/login/index'));
    }
}