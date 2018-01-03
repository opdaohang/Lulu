<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Addurl extends Controller{
	public function index(){
		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

    	// 赋值网站标题 关键词等
    	$title			= 	$setting['web_title'];
    	$webUrl			=	$setting['web_url'];
    	$tongji_code	=	$setting['tongji_code'];

    	$cacheTime		=	$setting['cache_time'];

    	$webTitle 		=	"网站提交-".$title;
    	$webDescription	=	"";
    	$webKeywords	=	"";

    	// 获取菜单
    	$menu	=	Model('Menu')
    					->order('top asc')
    					->select();

    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					->select();


    	// 赋值网站基本信息
    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('webUrl',$webUrl);

    	// 赋值菜单
    	$this->assign('menu',$menu);

    	// 赋值所有分类
    	$this->assign('cate',$cate);

    	// 赋值统计代码
    	$this->assign('tongji_code',$tongji_code);

		return view();
	}
	/*
		网址提交
	*/
	public function adds(){
		// 如果没有提交则跳转到首页
		if(!input('post.')){
			$this->error('错误',url('index/addurl/index'));
		}

		// 接收数据
		$data = input();

		// 转义html
		foreach ($data as $key => $value) {
			$data[$key]	=	htmlspecialchars($data[$key]);
		}
		
		// 正则表达式匹配
		if(!preg_match("/https{0,1}:\/\//", $data['url'])){
			return json(['sutus'=>'error','msg'=>'urlerror']);
		}

		// 匹配验证码
		if(!captcha_check($data['yzm'])){
			return json(['status'=>'error','msg'=>'yzmerror']);
		}
		
		// 检测网址最后一位是否有/
		$strlen = strlen($data['url']);
		$strpos = strripos($data['url'], '/');

		if($strlen-1 == $strpos){
			$data['url'] = substr($data['url'], 0,$strlen-1);
		}
		
		// 查询是否有这个数据
		$select = Model('Url')->where('url',$data['url'])->count();
		
		if($select > 0){
			return json(['status'=>'error','msg'=>'urlrepeat']);
			exit;
		}
	
		// 准备插入数据
		$time    =		@date('Y-m-d H:i:s');
		$insertData		=	[
			'url'=>$data['url'],
			'cate'=>$data['cate'],
			'time'=>$time,
		];
		// 如果都匹配
		$insert	=	Model('Url')->insert($insertData);
		if(!$insert){
			return json(['status'=>'error','msg'=>'xitong']);
		}else{
			return json(['status'=>'yes','msg'=>'yes']);
		}

	}
	public function _empty(){
		$this->redirect(url('index/index/index'));
	}
}

?>