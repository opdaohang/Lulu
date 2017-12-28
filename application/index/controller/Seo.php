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

        // 获取seo数据【最近查询】
        $seoShowNum     =   $setting['seo_show_num'];
        $seoArr     =   Model('Seo')->order('time desc')->limit($seoShowNum)->select();
    	// 如果有get数据
    	if(input('url')){
    		$seoUrl		=	input('url');

    		// 检测网址
    		if(!preg_match("/[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/", $seoUrl)){
    			$message	=	false;
    			// 赋值url
    			$this->assign('seoUrl',$seoUrl);
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
                $webTitle   =   $seoUrl."的SEO综合查询-".$title;
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

        $this->assign('message',$message);

    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('webUrl',$webUrl);
    	$this->assign('tongji_code',$tongji_code);

    	$this->assign('menu',$menu);
        $this->assign('seoArr',$seoArr);
		return view();
	}
    // seo信息查询
    public function seomessage($type,$url){
        /*
        // 定义接口地址
        $whois_api  =   'http://www.whois365.com/cn/domain/';

        $result     =   [];

        switch ($type) {
            case 'whois':
                // 如果有https则去掉https
                if(preg_match("/https{0,1}:\/\//", $url)){
                    $url =  preg_replace("/https{0,1}:\/\//", '', $url);
                }
                // 拼接地址
                $whoisUrl   =   $whois_api.$url;

                // curl访问
                $ch     =   curl_init();
                @curl_setopt($ch, CURLOPT_URL,$whoisUrl);
                @curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                @curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                @curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
                $html   = @curl_exec($ch);
                @curl_close($ch);
                if(empty($html)){
                    $html = @file_get_contents($whoisUrl);
                }
                //  开始匹配
                @preg_match("/<p\s+class=\"raw_data1\">(.*)<\/p>/", $html,$whoisArr);

                // dump($whoisArr);

                 // $result    =  ['status'=>'yes','whois'=>$whoisArr[1]];
            break;
            // 收录量查询
            case 'shoulu':
                // 定义各大搜索引擎的搜索地址
                $baiduUrl   =   '';
                $haosouUrl  =   '';
                $sogouUrl   =   '';
                $googleUrl  =   '';
                $bingUrl    =   '';
            break;
            // 如果没有指定返回状态码error
            default:
                $result['status']   =   'error';
            break;

            // dump(json($result));
        }
        */
    }
    public function _empty(){
        $this->redirect(url('index/index/errors'));
    }
}

?>