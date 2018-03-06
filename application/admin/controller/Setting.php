<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Setting extends Controller{
	public function _initialize(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}
	}
	public function index(){

		// 获取系统信息
		$setting = Model('Setting')->get(1);

		// 赋值
		$this->assign('setting',$setting);
		return view();
	}
	// 
	public function edit(){
		
		$data = input('post.');

		// 开始更新
		$update = Model('Setting')->where('id',1)->update($data);
		if($update){
			$this->success('更新成功','admin/setting/index');
		}else{
			$this->success('更新失败','admin/setting/index');
		}
	} 
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}

?>