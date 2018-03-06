<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Links extends Controller {
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

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		// 获取所有友情链接
		$linkArr		=	Model('Links')
								->order('id asc')
								->limit($common_limit)
								->page($page)
								->select();

		// 分页
		$pageination	=	Model('Links')->paginate($common_limit);

		$this->assign('linkArr',$linkArr);
		$this->assign('pageination',$pageination);
		return view();
	}
	public function linksinsert(){
		$data	=	input('post.');

		$insert =	Model('Links')->insert($data);

		if($insert){
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	public function del($id){

		$del	=	Model('Links')->where('id',$id)->delete();

		if($del){
			$this->success('删除成功');
		}else{
			$this->error('删除成功');
		}
	}
	public function edit($id){
		// 根据id获取信息
		$message	=	Model('Links')->get($id);

		$this->assign('message',$message);

		return view();
	}
	public function update(){

		$data	=	input('post.');
		$update	=	Model('Links')->where('id',$data['id'])->update($data);
		if($update){
			$this->success('更新成功',url('admin/links/index'));
		}else{
			$this->success('更新失败',url('admin/links/index'));
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}

?>