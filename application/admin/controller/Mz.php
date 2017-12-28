<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Mz extends Controller{
	public function index(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 判断page
		if(!input('?page')){
			$page = 1;
		}else{
			$page = input('page');
		}

		// 获取系统参数
		$setting 	=	Model('Setting')->get(1)->toArray();

		// 获取limit
		$common_limit	=	$setting['admin_limit_num'];

		// 获取根据分页所有站点
		$all = Model('Mztj')->order('id desc')->limit($common_limit)->page($page)->select();

		// 获取分页
		$pageination = Model('Mztj')->order('id desc')->paginate($common_limit);

		// 赋值
		$this->assign('all',$all);
		$this->assign('pageination',$pageination);

		return view();
	}
	// 删除
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$del = Model('Mztj')->where('id',$id)->delete();

		if($del){
			$this->success('删除成功','admin/mz/index');
		}else{
			$this->success('删除失败','admin/mz/index');
		}
	}
	// 状态
	public function insert(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input();
		unset($data['/admin/mz/insert']);

		$insert = Model('Mztj')->insert($data);
		if($insert){
			$this->success('添加成功','admin/mz/index');
		}else{
			$this->success('添加成功','admin/mz/index');
		}
	}
	// 编辑
	public function edit($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取信息
		$message = Model('Mztj')->get($id);

		// 赋值
		$this->assign('message',$message);

		return view();
	}
	// 
	public function editadmin(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input();
		unset($data['/admin/mz/editadmin']);
		
		$update = Model('Mztj')->where('id',$data['id'])->update($data);

		if($update){
			$this->success('修改成功','admin/mz/index');
		}else{
			$this->success('修改失败','admin/mz/index');
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}