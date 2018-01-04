// 菜单
$(function(){
	var mobileMenu = $('.common-nav-mobile');

	mobileMenu.on('click',function(){
		$(".common-nav-list").toggle();
	});
})
// 提交网站
$('#addurl').on('click',function(){
	// 获取所有值
	var cate 	=	$('#addurl-cate').val();
	var url		=	$('#addurl-url').val();
	var yzm		=	$('#addurl-yzm').val();
	var ajaxUrl	=	$('#addurl-form').attr('action');

	// 匹配是否有https://
	var reg		=	new RegExp("https{0,1}:\/\/");
	var ppresult	=	url.match(reg);
	if(!ppresult || ppresult == null){
		$('.addurl-tips').html('网址不规范').fadeToggle(500);
		setTimeout(function(){
			$('.addurl-tips').fadeToggle(500);
		},2000);
		return false;
	}
	// 匹配验证码
	if(!yzm || yzm == null){
		$('.addurl-tips').html('验证码不能为空').fadeToggle(500);
		setTimeout(function(){
			$('.addurl-tips').fadeToggle(500);
		},2000);
		return false;
	}
	// 开始提交
	$.ajax({
		type:'post',
		url:ajaxUrl,
		data:{'cate':cate,'url':url,'yzm':yzm},
		success:function(msg){
			console.log(msg);
			// 如果验证码不匹配
			if(msg['msg'] == 'yzmerror'){
				$('.addurl-tips').html('验证码错误').fadeToggle(500);
				setTimeout(function(){
					$('.addurl-tips').fadeToggle(500);
				},2000);
			}
			// 如果网址已经存在
			if(msg['msg'] == 'urlrepeat'){
				$('.addurl-tips').html('网址已经存在').fadeToggle(500);
				setTimeout(function(){
					$('.addurl-tips').fadeToggle(500);
				},2000);
			}
			// 如果网址不规范
			if(msg['msg'] == 'urlerror'){
				$('.addurl-tips').html('网址不规范').fadeToggle(500);
				setTimeout(function(){
					$('.addurl-tips').fadeToggle(500);
				},2000);
			}
			// 如果成功
			if(msg['msg'] == 'yes'){
				$('.addurl-tips').html('提交成功').fadeToggle(500);
				setTimeout(function(){
					$('.addurl-tips').fadeToggle(500);
				},2000);
			}
		}
	});
	return false;
});

// 写cookie
function setCookie(name,value) 
{ 
    var Days = 1; 
    var exp = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 

//读取cookies 
function getCookie(name) 
{ 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
    if(arr=document.cookie.match(reg))
 
        return unescape(arr[2]); 
    else 
        return null; 
} 


//删除cookies 
function delCookie(name) 
{ 
    var exp = new Date(); 
    exp.setTime(exp.getTime() - 1); 
    var cval=getCookie(name); 
    if(cval!=null) 
        document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
} 

// 评论ajax
function common(e){
	var url 	=	e.attr('action');

	// 获取内容
	var content 	=	$('textarea[name="content"]').val();
	// 获取用户名
	var username	=	$('input[name="username"]').val();
	// 获取网址
	var userurl		=	$('input[name="userurl"]').val();
	// id
	var post_id		=	$('input[name="post_id"]').val();
	// 获取验证码
	var yzm 		=	$('input[name="yzm"]').val();

	var tip 		=	$('.addurl-tips');


	// 开始提交
	$.ajax({
		type:'post',
		data:{'content':content,'username':username,'userurl':userurl,'post_id':post_id,'yzm':yzm},
		url:url,
		success:function(msg){
			if(msg['code'] == 1){
				tip.html('评论成功(等待审核)').fadeIn(500);
			}else{
				tip.html('评论失败').fadeIn(500);
			}
			setTimeout(function(){
				tip.fadeOut(800);
			},1500);
			if(msg['code'] == 1){
				setTimeout(function(){
					window.location.href = window.location.href;
				},1600);
			}
		}
	});
	return false;		
}