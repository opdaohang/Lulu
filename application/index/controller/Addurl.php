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
    					->cache('menu',$cacheTime)
    					->select();

    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					->cache('cate',$cacheTime)
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
			$this->error('网址不规范',url('index/addurl/index'));
		}

		// 匹配验证码
		if(!captcha_check($data['yzm'])){
			$this->error('验证码不匹配',url('index/addurl/index'));
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
			$this->error('已经有这个数据，请勿重复提交',url('index/addurl/index'));
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
			$this->error('系统错误，请联系管理员',url('index/addurl/index'));
		}else{
			$this->success('提交成功，请等待审核',url('index/addurl/index'));
		}

	}
	// 更新网站数据
	public function updates($url){
		if(!$url){
			exit;
		}
		// 初始化curl
		$ch = curl_init();
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); 
		// 执行
		$html 	= curl_exec($ch);
		curl_close($ch);

		// 标题
		$pptitle	= 	preg_match("/<title>(.*)<\/title>/", $html,$titleArr);
		if($pptitle){
			$titles  =  $titleArr[1];
		}

		// 更新网站标题
		$updates = Model('Url')->where('url',$url)->update(['title'=>$titles]);

	}
	public function _empty(){
		$this->redirect(url('index/index/index'));
	}
}

?>