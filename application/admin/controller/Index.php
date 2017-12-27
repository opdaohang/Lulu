<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Session;

class Index extends Controller{
	public function index(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取已审核站点数量
		$already	=	Model('Url')->where('status',1)->count();

		// 获取待审核站点数量
		$wait		=	Model('Url')->where('status',0)->count();

		// 赋值
		$this->assign('already',$already);
		$this->assign('wait',$wait);
		return view();
	}
	// 退出
	public function loginout(){
		Session::set('administer','');
		$this->redirect('admin/login/index');
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}