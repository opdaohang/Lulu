<?php
namespace app\index\controller;

use think\Model;
use think\Controller;
use think\Cookie;
use think\Session;

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
		$data = input('post.');

        // 截取描述字数
        // 处理描述
        $description = $data['description'];
        $description = strip_tags($description);
        $description = preg_replace("/\s+/", '', $description);
        $description = preg_replace("/\/n/", '', $description);
        $description = preg_replace("/\/r/", '', $description);
        $description = mb_substr($description, 0,100);
        $data['description'] = $description;
		

		// 开始过滤
		foreach ($data as $key => $value) {
			$data[$key] = htmlspecialchars($data[$key]);
		}


		

		// 添加时间参数与状态
		$time = @date('Y-m-d H:i:s');

		$data['time']	=	$time;
		$data['status']	=	0;

		// 开始插入
		$insert = Model('Article')->insert($data);
		if($insert){
            // 设置session
            Cookie::set('add','no',['expire'=>3600]);
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
    	$common_limit 		=	$setting['common_limit_num'];
    	// 重新赋值标题
    	if($page >= 2){
    		$webTitle 		=	"文章资讯-第{$page}页".$title;
    		// 判断是否恶意输入页数
    		// 总数量
    		$allNum	=	Model('Article')->where('status',1)->count();
    		if(ceil($allNum/$common_limit)< $page ){
    			$this->redirect(url('index/index/errors'));
    		}
    	}

    	// 赋值名站推荐展示多少数量
    	$mztjShowNum	    =	$setting['index_mztj_num'];
    	// 赋值首页随机展示多少数量
    	$suijiShowNum	    =	$setting['index_suiji_num'];
    	// 赋值最新加入展示多少数量
    	$newShowNum		    =	$setting['index_new_num'];



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
    	$articleNew 	=	Model('Article')
                                ->where('status',1)
                                ->order('id desc')
                                ->limit($common_limit)
                                ->page($page)
                                ->select();
    	// 分页
    	$pageination	=	Model('Article')
                                ->where('status',1)
                                ->paginate($common_limit);

    	$this->assign('articleNew',$articleNew);
    	$this->assign('pageination',$pageination);

		return view();
	}
	// 预览
	public function look($id){

		// ——————————————————————————————————————————

		if(!$id){
			$this->redirect(url('index/index/errors'));
		}
		$id 			 	=	htmlspecialchars($id);

		// 根据id获取
		$message 			=	Model('Article')->get($id);
		if(!$message){
			$this->redirect(url('index/index/errors'));
		}else{
			$message 		= 	$message->toArray();
		}

		// 判断是否显示
		if($message['status'] == 0){
			// 判断session
			if(Session::get('administer') != 1 ){
				$this->redirect(url('index/index/errors'));
			}
		}

		// 还原html
		foreach ($message as $key => $value) {
			$message[$key]	=	htmlspecialchars_decode($message[$key]);
		}

		// ——————————————————————————————————————————

		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

    	// 赋值网站标题 关键词等
    	$title				= 	$setting['web_title'];
    	$webUrl				=	$setting['web_url'];
    	$tongji_code		=	$setting['tongji_code'];

    	$cacheTime			=	$setting['cache_time'];

    	$webTitle 			=	$message['title']."-文章资讯-".$title;
    	$webDescription		=	$message['description'];
    	$webKeywords		=	$message['keywords'];

    	// 赋值名站推荐展示多少数量
    	$mztjShowNum	    =	$setting['index_mztj_num'];
    	// 赋值首页随机展示多少数量
    	$suijiShowNum	    =	$setting['index_suiji_num'];
    	// 赋值最新加入展示多少数量
    	$newShowNum		    =	$setting['index_new_num'];

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

    	// 随机推荐5篇
    	$suiji  		=	Model('Article')->where('status',1)->order('rand()')->limit(5)->select();

    	// 获取最新评论5个
    	$common 		=	Model('Common')
                                ->where(['post_id'=>$id,'status'=>1])
                                ->order('id desc')
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

    	// 侧边栏最新
    	$this->assign('new',$new);

    	// 赋值统计代码
    	$this->assign('tongji_code',$tongji_code);

		// 赋值文章信息
		$this->assign('message',$message);

		// 赋值随机推荐
		$this->assign('suiji',$suiji);

		// 赋值评论
		$this->assign('common',$common);

		// 浏览量自增1
		Model('Article')->where('id',$id)->setInc('view');

		return view();
	}
	// 文章评论
	public function common(){
		$data = input('post.');
		if(!$data['content']){
			$this->error('评论失败');
		}

		if(empty($data['username'])){
			$data['username'] 	= "匿名用户" . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
		}

		// 过滤
		foreach ($data as $key => $value) {
			$data[$key] = strip_tags($data[$key]);
		}

		// 添加时间
		$time 			=	date('Y-m-d H:i:s');
		$data['time']	=	$time;

		$add 	=	Model('Common')->insert($data);
		if($add){
			// 设置session
			$this->success('评论成功');
		}else{
			$this->error('评论失败');
		}
	}
}


?>