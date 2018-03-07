<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Mz extends Controller{
	public function _initialize(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}
	}
	public function index(){
		// 判断page
		if(!input('?page')){
			$page = 1;
		}else{
			$page = input('page');
		}

		// 获取系统参数
		$setting 		=	getSetting();

		// 获取limit
		$common_limit	=	$setting['admin_limit_num'];

		// 获取根据分页所有站点
		$all 			= 	Model('Mztj')->order('id desc')->limit($common_limit)->page($page)->select();

		// 获取分页
		$pageination 	= 	Model('Mztj')->order('id desc')->paginate($common_limit);

		// 赋值
		$this->assign('all',$all);
		$this->assign('pageination',$pageination);

		return view();
	}
	// 删除
	public function del($id){

		$del = Model('Mztj')->where('id',$id)->delete();

		if($del){
			$this->success('删除成功','admin/mz/index');
		}else{
			$this->error('删除失败','admin/mz/index');
		}
	}
	// 状态
	public function insert(){

		$data = input();
		unset($data['/admin/mz/insert']);

		$insert = Model('Mztj')->insert($data);
		if($insert){
			$this->success('添加成功','admin/mz/index');
		}else{
			$this->error('添加成功','admin/mz/index');
		}
	}
	// 编辑
	public function edit($id){

		// 获取信息
		$message = Model('Mztj')->get($id);

		// 赋值
		$this->assign('message',$message);

		return view();
	}
	// 
	public function editadmin(){

		$data 	= 	input('post.');
		
		$update = 	Model('Mztj')->where('id',$data['id'])->update($data);

		if($update){
			$this->success('修改成功','admin/mz/index');
		}else{
			$this->error('修改失败','admin/mz/index');
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}