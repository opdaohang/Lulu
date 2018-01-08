<?php

namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Session;

class Cate extends Controller {
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
		$setting 		=	getSetting();

		// 获取limit
		$common_limit	=	$setting['admin_limit_num'];

		// 根据page获取列表
		$cateList 		= 	Model('Cate')
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->select();

		// 分页
		$pageination	=	Model('Cate')
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->paginate($common_limit);

		

		// 赋值
		$this->assign('cateList',$cateList);
		$this->assign('pageination',$pageination);
		return view();
	}
	// 添加分类
	public function cateinsert(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		
		// 判断是否有
		if(!input('post.')){
			$this->success('没有变量','admin/cate/index');
		}else{
			$data = input();
		}

		// 去掉自带变量
		unset($data['/admin/cate/cateinsert']);

		if(empty($data['title'])){
			$this->success('不能为空','admin/cate/index');
		}

		// 判断是否有同样命名分类
		$same = Model('Cate')->where('title',$data['title'])->find();
		if($same){
			$this->success('已经有此分类','admin/cate/index');
			exit;
		}
		// 开始添加
		$insert = Model('Cate')->insert($data);
		if($insert){
			$this->success('添加成功','admin/cate/index');
		}else{
			$this->success('添加失败','admin/cate/index');
		}
	}
	// 删除
	public function catedel($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}
		
		$del = Model('Cate')->where('id',$id)->delete();
		if($del){
			// 删除分类站点
			$delsNum = Model('Url')->where('cate',$id)->count();
			if($delsNum > 0){
				$del = Model('Url')->where('cate',$id)->delete();
				if($del){
				$this->success('成功(子站点)','admin/cate/index');
				}else{
					$this->success('失败(子站点)','admin/cate/index');
				}
			}else{
				$this->success('删除成功','admin/cate/index');
			}
			
		}else{
			$this->success('失败(大分类)','admin/cate/index');
		}
		
	}
	// 分类编辑
	public function cateedit($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 
		// 根据id获取信息
		$message = Model('Cate')->get($id);

		// 赋值
		$this->assign('message',$message);

		return view();
	}
	// 分类编辑提交
	public function editadmin(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}


		$data = input();
		unset($data['/admin/cate/editadmin']);

		$update = Model('Cate')->where('id',$data['id'])->update($data);
		if($update){
			$this->success('更新成功','admin/cate/index');
		}else{
			$this->success('更新失败)','admin/cate/index');
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}

?>