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
            $id = htmlspecialchars(input('id'));
		}

		// 如果没有定义page  page为1
		if(!input('?page')){
			$page = 1;
		}else{
            $page = htmlspecialchars(input('page'));
            // 判断页数是否超出限制
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
		$setting = getSetting();

    	// 赋值基本参数
        $webTitle       =   $setting['web_title'];
    	$webUrl			=	$setting['web_url'];
        $newShowNum     =   $setting['index_new_num'];
        $listShowNum    =   $setting['common_limit_num'];
        $pic_api        =   $setting['common_pic_api'];
        $tongji_code    =   $setting['tongji_code'];

    	// 设置meta
        $meta           =   getMeta('cateList',$page,$id,'');

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
    	$this->assign('webTitle',$webTitle);
    	$this->assign('webUrl',$webUrl);
        $this->assign('meta',$meta);

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