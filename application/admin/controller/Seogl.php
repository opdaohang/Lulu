<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class Seogl extends Controller {
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

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];	

		// 获取所有SEOURL
		$urlArr	=	Model('Seo')
						->order('id desc')
						->limit($common_limit)
						->page($page)
						->select();
		// 分页
		$pageination	=	Model('Seo')->paginate($common_limit);
		// 总数量
		$allNum			=	Model('Seo')->count();

		$this->assign('urlArr',$urlArr);
		$this->assign('pageination',$pageination);
		$this->assign('allNum',$allNum);

		return view();
	}
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$del	=	Model('Seo')->where('id',$id)->delete();
		if($del){
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}
}