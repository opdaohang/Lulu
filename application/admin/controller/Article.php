<?php
namespace app\admin\controller;

use think\Session;
use think\Controller;

class Article extends Controller {
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
		$setting	=	Model('Setting')->where('id',1)->find()->toArray();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		// 获取文章

	}
}

?>