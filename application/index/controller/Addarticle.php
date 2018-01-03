<?php
namespace app\index\controller;

use think\Model;
use think\Controller;

class Addarticle extends Controller {
	public function index(){
		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

    	// 赋值网站标题 关键词等
    	$title			= 	$setting['web_title'];
    	$webUrl			=	$setting['web_url'];
    	$tongji_code	=	$setting['tongji_code'];

    	$cacheTime		=	$setting['cache_time'];

    	$webTitle 		=	"文章投稿-".$title;
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
	public function adds(){
		$data = input();
		if(isset($data['/index/addarticle/adds'])){
			unset($data['/index/addarticle/adds']);
		}
		

		// 开始过滤
		foreach ($data as $key => $value) {
			$data[$key] = htmlspecialchars($data[$key]);
		}

		// 截取描述字数
		if(mb_strlen($data['description']) > 65){
			$data['description'] = mb_substr($data['description'], 0,65);
		}
		

		// 添加时间参数与状态
		$time = @date('Y-m-d H:i:s');

		$data['time']	=	$time;
		$data['status']	=	0;

		// 开始插入
		$insert = Model('Article')->insert($data);
		if($insert){
			$this->success('添加成功，请等待审核');
		}else{
			$this->error('添加失败请检查未填项');
		}		
	}
	// list
	public function lists(){
		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

		// 判断page
		if(!input('page')){
			$page = 1;
		}else{
			$page = input('page');
			$page = htmlspecialchars($page);
		}

    	// 赋值网站标题 关键词等
    	$title			= 	$setting['web_title'];
    	$webUrl			=	$setting['web_url'];
    	$tongji_code	=	$setting['tongji_code'];

    	$cacheTime		=	$setting['cache_time'];

    	$webTitle 		=	"文章资讯-".$title;
    	$webDescription	=	"";
    	$webKeywords	=	"";
    	// 重新赋值标题
    	if($page >= 2){
    		$webTitle 		=	"文章资讯-第{$page}页".$title;
    	}

    	// 赋值名站推荐展示多少数量
    	$mztjShowNum	    =	$setting['index_mztj_num'];
    	// 赋值首页随机展示多少数量
    	$suijiShowNum	    =	$setting['index_suiji_num'];
    	// 赋值最新加入展示多少数量
    	$newShowNum		    =	$setting['index_new_num'];

    	$common_limit 		=	$setting['common_limit_num'];

    	// 获取菜单
    	$menu	=	Model('Menu')
    					->order('top asc')
    					->select();

    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					->select();

    	// 获取最新加入网站
    	$new 	=	Model('Url')
    					->where('status',1)
    					->order('id desc')
    					->limit($newShowNum)
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

    	// 
    	$this->assign('new',$new);

    	// 赋值统计代码
    	$this->assign('tongji_code',$tongji_code);

    	// ----------------------------------


    	// 最新排行
    	$articleNew 	=	Model('Article')->where('status',1)->order('id desc')->limit($common_limit)->select();

    	$this->assign('articleNew',$articleNew);

		return view();
	}
	// 预览
	public function look($id){
		if(!$id){
			$this->redirect(url('index/index/errors'));
		}
		$id 			 =	htmlspecialchars($id);

		// 根据id获取
		$message 		=	Model('Article')->get($id);
		if(!$message){
			$this->redirect(url('index/index/errors'));
		}else{
			$message 	= 	$message->toArray();
		}

		// 判断是否显示
		if($message['status'] == 0){
			// 判断session
			if(session::get('administer') != 1){
				$this->redirect(url('index/index/errors'));
			}
		}

		// 赋值
		$this->assign('message',$message);

		return view();
	}
}


?>