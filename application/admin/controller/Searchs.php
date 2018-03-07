<?php
namespace app\admin\controller;

use think\Session;
use think\Controller;

class Searchs extends Controller {
	public function _initialize(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}
	}
	public function index(){
		// 
		$data = input();
		if(!$data || empty($data)){
			$this->redirect(url('admin/index/index'));
		}

		$wd = $data['s'];

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		if(!input('page')){
			$page = 1;
		}else{
			$page = input('page');
		}

		// 开始搜
		$searchArr = Model('Url')
						->order('id asc')
						->where('url','like',"%{$wd}%")
						->whereOr('title','like',"%{$wd}%")
						->limit($common_limit)
						->page($page)
						->select();

		$pageination = Model('Url')
						->where('url','like',"%{$wd}%")
						->whereOr('title','like',"%{$wd}%")
						->paginate($common_limit);

		$this->assign('wd',$wd);
		$this->assign('all',$searchArr);
		$this->assign('pageination',$pageination);

		return view();
	}
	// 文章搜索
	public function article(){
		$data = input('get.');

		if(empty($data['search'])){
			$this->error('非法闯入');
		}else{
			$wd = $data['search'];
		}

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		if(!input('page')){
			$page = 1;
		}else{
			$page = input('page');
		}

		// 开始搜索
		$result 	 = Model('Article')
						->where('title','like',"%{$wd}%")
						->whereOr('content','like',"%{$wd}%")
						->order('id desc')
						->select();
		// 分页
		$pageination = Model('Article')
						->where('title','like',"%{$wd}%")
						->whereOr('content','like',"%{$wd}%")
						->paginate([
							'list_rows'=>$common_limit,
							'query'=>$wd
						]);

		$this->assign('searchArr',$result);
		$this->assign('pageination',$pageination);
		$this->assign('wd',$wd);

		return view();
	}
	public function _empty(){
		$this->redirect(url('admin/index/index'));
	}
}

?>