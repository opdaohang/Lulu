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
}


?>