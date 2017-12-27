<?php
namespace app\admin\controller;

use think\Controller;

class Error extends Controller{
	public function index(){
		$this->redirect(url('admin/login/index'));
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}
?>