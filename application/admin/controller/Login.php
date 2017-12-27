<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Session;

class Login extends Controller{
	public function index(){
		if(Session::get('administer') == '1'){
			$this->redirect(url('admin/index/index'));
		}else{
			return view();
		}
		
	}
	// 登录验证
	public function loginyz(){
		if(!input('post.')){
			$this->error('非法访问',url('admin/login/index'));
		}

		$data = input();
		// 过滤
		foreach ($data as $key => $value) {
			$data[$key] = htmlspecialchars($data[$key]);
		}
		// 检索是否正确
		$jiansuo  = Model('User')
					->where(['username'=>$data['username'],'userpass'=>md5($data['userpass'])])
					->find();
		// 如果失败
		if(!$jiansuo){
			$this->error('无此用户',url('admin/login/index'));
		}

		// 检索验证码
		if(!captcha_check($data['yzm'])){
			$this->error('验证码错误',url('admin/login/index'));
		}	

		// 如果都正确设置session
		Session::set('administer',1);
		// 到首页
		$this->redirect(url('admin/index/index'));

	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}

?>