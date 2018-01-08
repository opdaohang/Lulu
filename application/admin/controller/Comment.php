<?php
namespace app\admin\controller;

use think\Session;
use think\Controller;
use think\Model;

class Comment extends Controller {
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

		// 评论
		$commentArr		=	Model('Common')
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->select();
		// 分页
		$pageination	=	Model('Common')->paginate($common_limit);

		$this->assign('commentArr',$commentArr);
		$this->assign('pageination',$pageination);

		return view();
	}
	// 更改审核状态
	public function status($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取状态
		$status = Model('Common')->get($id)->toArray();
		if($status['status'] == 1){
			$update = Model('Common')->where('id',$id)->update(['status'=>0]);
		}else{
			$update = Model('Common')->where('id',$id)->update(['status'=>1]);
		}

		if($update){
			$this->success('更改成功');
		}else{
			$this->error('更改失败');
		}
	}
	// 删除
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$del = Model('Common')->where('id',$id)->delete();
		if($del){
			$this->success('删除成功');
		}else{
			$this->success('删除失败');
		}

	}

	// 未审核评论
	public function nogo(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		if(!input('page')){
			$page = 1;
		}else{
			$page = input('page');
		}

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		// 
		$result			=	Model('Common')
								->where('status',0)
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->select();

		$pageination	=	Model('Common')
								->where('status',0)
								->order('id desc')
								->paginate($common_limit);

		$count 			=	Model('Common')
								->where('status',0)
								->count();

		$this->assign('commentArr',$result);
		$this->assign('pageination',$pageination);
		$this->assign('count',$count);

		return view();
	}
}

?>