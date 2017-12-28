<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Menu extends Controller{
	public function index(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		if(!input('?page')){
			$page 		= 1;
		}else{
			$page 		= input('page');
		}

		// 获取系统设置
		$setting 		= Model('Setting')->get(1)->toArray();

		$common_limit 	=	$setting['admin_limit_num'];


		// 获取所有菜单
		$menuArr 		= Model('Menu')->order('top asc')->limit($common_limit)->page($page)->select();

		// 分页函数
		$pageArr		= Model('Menu')->paginate($common_limit);

		$this->assign('menuArr',$menuArr);
		$this->assign('pageArr',$pageArr);
		return view();

	}
	// 添加
	public function inserts(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input();
		unset($data['/admin/menu/inserts']);

		$insert = Model('Menu')->insert($data);
		if($insert){
			$this->success('添加成功',url('admin/menu/index'));
		}else{
			$this->success('添加失败',url('admin/menu/index'));
		}
		dump($data);
	}
	// 删除
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$del = Model('Menu')->where('id',$id)->delete();
		if($del){
			echo "yes";
		}else{
			echo "no";
		}
	}
	public function edit($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取信息
		$message = Model('Menu')->get($id);

		$this->assign('message',$message);

		return view();
	}
	public function editadmin(){
		$data = input();
		if(isset($data['/admin/menu/editadmin'])){
			unset($data['/admin/menu/editadmin']);
		}
		// 开始修改
		$update = Model('Menu')->where('id',$data['id'])->update($data);
		if($update){
			$this->success('修改成功',url('admin/menu/index'));
		}else{
			$this->success('修改失败',url('admin/menu/index'));
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}

?>