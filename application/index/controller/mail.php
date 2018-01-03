<?php

namespace app\index\controller;

use think\Controller;

class Mail extends Controller{
	public function index(){
		$send = mail('2280120391@qq.com','有新网站提交','xxx网站提交了','','');
	}
}