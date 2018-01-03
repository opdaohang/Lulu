<?php
namespace app\admin\controller;

use think\Session;
use think\Controller;

class Searchs extends Controller {
	public function index(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 
		$data = input();
		if(!$data || empty($data)){
			$this->redirect(url('admin/index/index'));
		}

		$wd = $data['s'];

		// 获取setting信息
		$setting	=	Model('Setting')->where('id',1)->find()->toArray();

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
	public function _empty(){
		$this->redirect(url('admin/index/index'));
	}
}

?>