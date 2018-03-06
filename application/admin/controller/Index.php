<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Session;

class Index extends Controller{
	public function _initialize(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}
	}
	public function index(){

		// 获取已审核站点数量
		$already	=	Model('Url')->where('status',1)->count();

		// 获取待审核站点数量
		$wait		=	Model('Url')->where('status',0)->count();

		// 获取分类个数
		$cate		=	Model('Cate')->count();

		// 获取名站个数
		$mz			=	Model('Mztj')->count();

		// 菜单个数
		$menu		=	Model('Menu')->count();

		// 搜索词个数
		$search		=	Model('Search')->count();

		// seo查询词个数
		$seo 		=	Model('Seo')->count();

		// 获取所有文章
		$article	=	Model('Article')->count();

		// 已经审核文章数量
		$articleYes	=	Model('Article')->where('status',1)->count();

		// 未审核文章数量
		$articleNo	=	Model('Article')->where('status',0)->count();

		// 总评论个数
		$comment	=	Model('Common')->count();

		// 未审核评论数
		$commentNo	=	Model('Common')->where('status',0)->count();

		// 赋值
		$this->assign('already',$already);
		$this->assign('wait',$wait);
		$this->assign('cate',$cate);
		$this->assign('mz',$mz);
		$this->assign('menu',$menu);
		$this->assign('search',$search);
		$this->assign('seo',$seo);
		$this->assign('article',$article);
		$this->assign('articleYes',$articleYes);
		$this->assign('articleNo',$articleNo);
		$this->assign('comment',$comment);
		$this->assign('commentNo',$commentNo);
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