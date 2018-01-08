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
			$page 		= 	1;
		}else{
			$page 		= 	input('page');
		}

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		// 获取文章
		$articleArr		=	Model('Article')
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->select();

		// 分页
		$pageination 	=	Model('Article')->paginate($common_limit);

		// 赋值
		$this->assign('articleArr',$articleArr);
		$this->assign('pageination',$pageination);

		return view();
	}
	// 更改状态
	public function status($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$message = Model('Article')->get($id)->toArray();

		if($message['status'] == 1){
			$update = Model('Article')->where('id',$id)->update(['status'=>0]);
		}else{
			$update = Model('Article')->where('id',$id)->update(['status'=>1]);
		}

		if($update){
			$this->success('状态更改成功');
		}else{
			$this->error('状态更改失败');
		}
	}
	// 删除
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$del = Model('Article')->where('id',$id)->delete();
		if($del){
			$this->success('成功');
		}else{
			$this->error('失败');
		}
	}
	// 添加
	public function insert(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input();

		if(isset($data['/admin/article/insert'])){
			unset($data['/admin/article/insert']);
		}
		

		// 开始过滤
		foreach ($data as $key => $value) {
			$data[$key] = htmlspecialchars($data[$key]);
		}

		// 截取描述字数
		if(mb_strlen($data['description']) > 65){
			$data['description'] = mb_substr($data['description'], 0,65);
		}
		

		// 添加时间参数与状态
		$time = @date('Y-m-d H:i:s');

		$data['time']	=	$time;
		$data['status']	=	1;

		// 开始插入
		$insert = Model('Article')->insert($data);
		if($insert){
			$this->success('添加成功，请等待审核');
		}else{
			$this->error('添加失败请检查未填项');
		}		
	}
	// 编辑
	public function edit($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取信息
		$message = Model('Article')->get($id)->toArray();

		// 转换
		foreach ($message as $key => $value) {
			$message[$key]  = htmlspecialchars_decode($message[$key]);
		}

		$this->assign('message',$message);
		return view();
	}
	// 更新
	public function update(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input('post.');
		if(!$data){
			$this->error('错误');
		}
		// 处理描述
		$description = $data['description'];
		$description = strip_tags($description);
		$description = preg_replace("/\s+/", '', $description);
		$description = preg_replace("/\/n/", '', $description);
		$description = preg_replace("/\/r/", '', $description);
		$description = mb_substr($description, 0,100);
		$data['description'] = $description;
		
		// 转义html
		foreach ($data as $key => $value) {
			$data[$key] = htmlspecialchars($data[$key]);
		}
		// 去除标签
		// 开始更新
		$update = Model('Article')->where('id',$data['id'])->update($data);
		if($update){
			$this->success('更新成功');
		}else{
			$this->success('更新失败');
		}
	}
	// 查看所有未审核
	public function nogo(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 判断page
		if(!input('?page')){
			$page 		= 	1;
		}else{
			$page 		= 	input('page');
		}

		// 获取setting信息
		$setting		=	getSetting();

		// 分页数量
		$common_limit	=	$setting['admin_limit_num'];

		// 获取文章
		$articleArr		=	Model('Article')
								->where('status',0)
								->order('id desc')
								->limit($common_limit)
								->page($page)
								->select();

		// 分页
		$pageination 	=	Model('Article')->where('status',1)->paginate($common_limit);

		// 赋值
		$this->assign('articleArr',$articleArr);
		$this->assign('pageination',$pageination);

		return view();
	}
}

?>