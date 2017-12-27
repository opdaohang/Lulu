<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
use think\Model;

class All extends Controller {
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

		// 获取根据分页所有站点
		$all = Model('Url')->order('id desc')->limit(10)->page($page)->select();

		// 获取分页
		$pageination = Model('Url')->order('id desc')->paginate(10);

		// 获取所有分类
		$cate = Model('Cate')->order('id','asc')->select();

		// 赋值
		$this->assign('all',$all);
		$this->assign('pageination',$pageination);
		$this->assign('cate',$cate);

		return view();
	}
	// 删除
	public function del($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}


		$delete = Model('Url')->where('id',$id)->delete();
		if($delete){
			$this->success('删除成功','admin/all/index');
		}else{
			$this->success('删除失败','admin/all/index');
		}
	}
	// 状态
	public function status($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		// 获取状态
		$statusArr = Model('Url')->get($id)->toArray();
		$status    = $statusArr['status'];

		if($status == 1){
			$change = Model('Url')->where('id',$id)->update(['status'=>0]);
		}else{
			$change = Model('Url')->where('id',$id)->update(['status'=>1]);
		}

		if($change){
			$this->success('状态更改成功','admin/all/index');
		}else{
			$this->success('状态更改失败','admin/all/index');
		}
	}
	// 基本信息
	public function jbxx($type,$id){
// 		set_time_limit(0);



		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		if($type == 'id'){
			// 根据id获取基本信息
			$message = Model('Url')->get($id)->toArray();

			$url = $message['url'];
		}else{
			$url = $id;
			$url = str_replace("+--+", "/", $url);
			$url = str_replace("+0+", ".", $url);
		}


		// 开始获取信息
		$ch = curl_init();
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_HEADER, false);  
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
		$html = curl_exec($ch);
		curl_close($ch);
		
		if(empty($html)){
			$html = file_get_contents($url);
			if(empty($html)){
				echo 'no';
				exit;
			}
		}
		// dump($html);
		try{
			// 开始匹配title 已知知乎例外
			if(!preg_match('/zhihu\.com/', $url)){
				$ppTitle = preg_match("/<title>(.*)<\/title>/", $html,$titleArr);
				$title = $titleArr[1];
				if(empty($title)){
					$title = '';
				}
			}else{
				$ppTitle = preg_match("/<title\s+data\-react\-helmet\=\"true\">(.*)<\/title>/", $html,$titleArr);
				$title  = $titleArr[1];
				if(empty($title)){
					$title = '';
				}
			}
		}catch(Exception $e){
			return 'no';
			exit;
		}
		
		// 匹配关键词
		preg_match('/<META\s+name="keywords"\s+content="([\w\W]*?)"/si', $html, $matches);         
          if (empty($matches[1])) {
               preg_match("/<META\s+name='keywords'\s+content='([\w\W]*?)'/si", $html, $matches);              
          }
          if (empty($matches[1])) {
               preg_match('/<META\s+content="([\w\W]*?)"\s+name="keywords"/si', $html, $matches);              
          }
          if (empty($matches[1])) {
               preg_match('/<META\s+http-equiv="keywords"\s+content="([\w\W]*?)"/si', $html, $matches);              
          }
          if (!empty($matches[1])) {
               $keywords = $matches[1];
          }else{
          		$keywords = '';
          }

          // 匹配描述
          preg_match('/<META\s+name="description"\s+content="([\w\W]*?)"/si', $html, $matches);         
          if (empty($matches[1])) {
               preg_match("/<META\s+name='description'\s+content='([\w\W]*?)'/si", $html, $matches);              
          }
          if (empty($matches[1])) {
               preg_match('/<META\s+content="([\w\W]*?)"\s+name="description"/si', $html, $matches);                        
          }
          if (empty($matches[1])) {
               preg_match('/<META\s+http-equiv="description"\s+content="([\w\W]*?)"/si', $html, $matches);              
          }
          if (!empty($matches[1])) {
              $description = $matches[1];
          }else{
          	  $description = '';
          }

          // 处理title
          // 删除空格
          if(preg_match("/\s+/", $title)){
          	$title = str_replace(' ', '', $title);
          }
          // 删除 - 
          if(preg_match("/-/", $title)){
          		$strpos = strpos($title,'-');
          		$title = substr($title, 0,$strpos);
          }
          // 删除 _
          if(preg_match("/_/", $title)){
          		$strpos = strpos($title,'_');
          		$title = substr($title, 0,$strpos);
          }

          // 匹配完成开始组合数组
          $updateArr = [
          		'title'			=>	$title,
          		'keywords'		=>	$keywords,
          		'description'	=>	$description,
          ];

          // 返回数据
          if($type == 'id'){
          		$update = Model('Url')->where('id',$id)->update($updateArr);
         			if($update){
          				echo 'yes';
         			}else{
          				echo 'no';
         			}
          }else{
          	return json($updateArr);
          }
          

	}
	// 添加
	public function allinsert(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input();

		if(empty($data) || empty($data['url'])){
			$this->error('不能为空','admin/all/index');
		}
		unset($data['/admin/all/allinsert']);



		// 检查网址最后一位是否为/
		$strlen = strlen($data['url']);
		$strright = strripos($data['url'],'/');
		if($strright == $strlen-1){
			$data['url'] = substr($data['url'], 0,$strlen-1);
		}

		// 检查是否有同样网址
		$same = Model('Url')->where('url',$data['url'])->find();
		if($same){
			$this->success('已有此网址','admin/all/index');
			exit;
		}

		// 添加状态为1
		$data['status'] = 1;

		// 开始插入
		$insert = Model('Url')->insert($data);
		if($insert){
			$this->success('添加成功','admin/all/index');
		}else{
			$this->success('添加失败','admin/all/index');
		}
	}

	// 编辑
	public function edit($id){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}


		// 根据ID获取信息
		$message = Model('Url')->get($id);
		if(!$message){
			$this->error('没有id');
		}

		// 根据cate获取名称
		$cateNameArr = Model('Cate')->get($message['cate'])->toArray();

		// 获取所有分类
		$cateArr = Model('Cate')->order('id desc')->select();

		// 添加
		$message['catename'] = $cateNameArr['title'];

		// 赋值
		$this->assign('message',$message);
		$this->assign('cateArr',$cateArr);
		return view();
	}
	// edit admin
	public function editadmin(){
		// 判断session
		if(session::get('administer') != 1 || !session::has('administer')){
			$this->redirect(url('admin/login/index'));
		}

		$data = input('post.');
		if(!$data){
			exit;
		}
		// dump($data);
		$update = Model('Url')->where('id',$data['id'])->update($data);
		if($update){
			$this->success('更新成功','admin/all/index');
		}else{
			$this->error('更新失败','admin/all/index');
		}
	}
	public function _empty(){
		$this->redirect(url('admin/login/index'));
	}
}