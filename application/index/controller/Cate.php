<?php
namespace app\index\controller;

use think\Controller;
use think\Model;

class Cate extends Controller{
	public function index(){
		// 判断是否有id
		if(!input('?id')){
			$this->redirect(url('index/index/errors'));
		}else{
			$id = input('id');
            $id = htmlspecialchars($id);
		}

		// 如果没有定义page  page为1
		if(!input('?page')){
			$page = 1;
		}else{
			$page =	input('page');
            $page = htmlspecialchars($page);
            // 入股页数超出限制
            // 得到总数量
            $zongNum = Model('Url')->where('cate',$id)->count();
            if(ceil($zongNum/10)<$page){
                $this->redirect(url('index/index/errors'));
            }
		}


		// 根据id查询标题等
		$cateArr = Model('Cate')->get($id);
		if(!$cateArr){
			$this->redirect(url('index/index/errors'));
		}else{
			$cateArr = $cateArr->toArray();
		}

		// 获取基本信息
		$setting = Model('Setting')->get(1)->toArray();

    	// 赋值基本参数
    	$title			= 	$setting['web_title'];
    	$webUrl			=	$setting['web_url'];
        $newShowNum     =   $setting['index_new_num'];
        $listShowNum    =   $setting['common_limit_num'];
        $pic_api        =   $setting['common_pic_api'];
        $tongji_code    =   $setting['tongji_code'];

    	// 改变赋值
    	$webTitle  		=	$cateArr['title'].'-'.$title;
        $webKeywords    =   '';
    	$webDescription	=	'';
    	if($page >= 2){
    	    $webTitle  		=	$cateArr['title']."-第{$page}页-".$title;
    	}

    	$cacheTime		=	$setting['cache_time'];


		// 获取菜单
    	$menu	=	Model('Menu')
    					->order('top asc')
    					// ->cache('menu',$cacheTime)
    					->select();
    	// 获取所有的分类
    	$cate 	= 	Model('Cate')
    					->order('id asc')
    					// ->cache('cate',$cacheTime)
    					->select();

    	// 获取分类为这个的所有网站
    	$url 	=	Model('Url')
    					->where(['cate'=>$id,'status'=>1])
    					->order('id desc')
    					->limit($listShowNum)
    					->page($page)
    					->select();
    	// 分页
    	$page	=	Model('Url')
    					->where('cate',$id)
    					->order('id desc')
    					->paginate($listShowNum);

        // 获取最新加入网站
        $new    =   Model('Url')
                        ->where('status',1)
                        ->order('id desc')
                        ->limit($newShowNum)
                        ->select();
        // 获取所有数量
        $allUrlNum     =   Model('Url')->where('cate',$id)->count();

    	// 赋值基本信息
    	$this->assign('title',$title);
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webKeywords',$webKeywords);
    	$this->assign('webDescription',$webDescription);
    	$this->assign('webUrl',$webUrl);

    	// 赋值菜单
    	$this->assign('menu',$menu);
    	// 赋值所有分类
    	$this->assign('cate',$cate);
    	// 赋值单个分类信息
    	$this->assign('cateArr',$cateArr);

    	// 赋值url信息
    	$this->assign('url',$url);
    	// 赋值分页
    	$this->assign('page',$page);
        // 赋值最新加入网站
        $this->assign('new',$new);
        // 赋值图片接口
        $this->assign('pic_api',$pic_api);
        // 赋值所有数量
        $this->assign('allUrlNum',$allUrlNum);
        // 统计代码
        $this->assign('tongji_code',$tongji_code);
		return view();
	}
    public function _empty(){
        $this->redirect(url('index/index/index'));
    }
}


?>