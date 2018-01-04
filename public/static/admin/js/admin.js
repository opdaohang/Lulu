$(function(){
	// 快速生成分类描述
	var fastCateBtn = $('#fastCate');

	fastCateBtn.on('click',function(){

		var keywords =  "网站目录,分类目录,网站收录,网站提交,网址收录,网址大全,分类目录网,目录网,网站大全,网址提交,网站推广,目录网程序,目录网源码,分类目录网源码";
		var description = "路路目录网（www.ioululu.com）推广免费送您网站流量,是中小网站和新网站推广首选网络推广服务平台.同时分类目录网为大家提供国内外网址大全,整理和收藏网站大全导航,您也可以到分类目录网免费发布自己的网站及网站相关信息!";
		$("#keywords").val(keywords);
		$("#description").val(description);
	});

	// 添加网站获取基本信息
	var insertWebBtn = $("#insertjbxx");

	insertWebBtn.on('click',function(){
		// 获取url
		var wurl = $("#wurl").val();

		re = new RegExp("/","g");

		rc = new RegExp("\.",'i');

		wurl = wurl.replace(re,'+--+');
		wurl = wurl.replace('.','+0+');
		wurl = wurl.replace('.','+0+');
		wurl = wurl.replace('.','+0+');
		wurl = wurl.replace('.','+0+');

		console.log(wurl);

		// 生成url

		var ajaxUrl = "/admin/all/jbxx/type/url/id/"+wurl;
		console.log(ajaxUrl);

		$(".jbxx-status").html("正在获取").show();

		$.ajax({
			url:ajaxUrl,
			async:false,
			success:function(data){
				if(data == 'no'){
					$(".jbxx-status").html("获取失败").show();
					setTimeout(function(){
						$(".jbxx-status").hide();
					},2000);

					$('#title').val('');
					$('#keywords').val('');
					$('#description').val('');

				}else{
					$(".jbxx-status").html("获取成功").show();
					setTimeout(function(){
						$(".jbxx-status").hide();
					},2000);
					var kong = [];
					$.each(data,function(n,value){
						kong[n] = value;
					});
					// 赋值
					$('#title').val(kong['title']);
					$('#keywords').val(kong['keywords']);
					$('#description').val(kong['description']);

				}
				
			}
		});

	});
})
// 更新网站基本信息
function updateJbxx(url){
	var statusDiv = $('.jbxx-status');
	statusDiv.show();
	$.ajax({
		url:url,
		success:function(data){
			console.log(data);
			if(data == 'yes'){
				$('.jbxx-status').html('更新成功');
				location.href=location;
			}else{
				$('.jbxx-status').html('更新失败');
			}

			setTimeout(function(){
				$('.jbxx-status').hide();
			},3000);
		}
	});
}

/*
	@	简单的get请求的的方法
	@	用于：取消审核，删除
*/
function getFw(url,tips){
	var successMessage	=	tips + "成功";
	var errorMessage	=	tips + "失败";
	var alerts			=	$('.jbxx-status');
	$.ajax({
		url:url,
		success:function(data){
			console.log(data['code']);
			console.log(successMessage);
			if(data['code'] == 1){
				alerts.html(successMessage).show();
			}else{
				alerts.html(errorMessage).show();
				return false;
			}

			setTimeout(function(){
					alerts.hide();
			},2000);
			setTimeout(function(){
				window.location.href=location.href
			},500);
			
		}
	});
}
// ajax改变表单提交
function postFw(e,m){
	// 获取当前form的action 
	var ajaxUrl			=	e.attr('action');
	var input			=	e.find('.form-control');
	var alerts			=	$('.jbxx-status');
	var successMessage	=	m + '成功！';
	var errorMessage	=	m + '失败';
	var zu = {};
	for(i=0;i<input.length;i++){
		var name = input.eq(i).attr('name');
		var val  = input.eq(i).val();
		zu[name] = val;
	}


	// 开始提交
	$.ajax({
		type:'post',
		url:ajaxUrl,
		data:zu,
		success:function(msg){
			if(msg['code'] == 1){
				alerts.html(successMessage).show();
			}else{
				alerts.html(errorMessage).show();
			}
			setTimeout(function(){
				alerts.hide();
			},2000);
			if(msg['code'] == 1){
				setTimeout(function(){
					window.location.href = location.href;
				},1000);
			}
		}
	});


	return false;
};


// 查询是否有此网址
$('#selectrepeat').on('click',function(){
	// 获取网址
	var url 	=	$('#wurl').val();

	// 提交查询
	$.ajax({
		type:'post',
		url:'/admin/all/repeat',
		data:{'url':url},
		success:function(msg){
			if(msg == 'yes'){
				$('.jbxx-status').html('已经有此网址').show();
			}else{
				$('.jbxx-status').html('没有此网址').show();
			}
			setTimeout(function(){
				$('.jbxx-status').hide();
			},3000);
			
		}
	});
});
function pageAjax(){

}
// 阻止分页
$(document).delegate('.pagination a','click',function(){
	var ajaxUrl = $(this).attr('href');
	var nexts	= $('.list-group');;

	// ajax获取
	$.ajax({
		url:ajaxUrl,
		success:function(data){
			// 获取元素
			var html = $(data).find('.list-group').html();
			// 替换
			nexts.fadeToggle(600);
			setTimeout(function(){
				nexts.html(html);
			},600);
			setTimeout(function(){
				nexts.fadeToggle(600);
				$('html,body').animate({scrollTop: '0px'}, 600);
			},600);

		}
	});
	

	return false;
});
// 添加按钮滚动栏特效
function insert(){
	// 获取锚点的位置
	var top = $('#insert-map').offset().top;
	// 滚动
	$("body,html").animate({scrollTop:top+'px'},300);
}
function goTop(){
	$("body,html").animate({scrollTop:0},400);
}

// 搜索
function search(e){


	// 获取action 
	var url = e.attr('action');

	// 获取搜索框内容
	var val = e.find('input:text').val()

	if(val == null || val.length == 0){
		return false;
	}

	// 组合url
	var goUrl	=	url + "/s/" + val;

	window.location.href = goUrl;

	return false;
}