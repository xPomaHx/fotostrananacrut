<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  <style type="text/css">

  	.spoiler-text{
  		display: none;
  	}

  </style>
</head>
<body>

	ид кого лайкать
	<input type="text" name="" id="idlile" value="90124863">
<textarea id="proxy" wrap=hard>89.236.17.106:3128</textarea>
<textarea id=autologin wrap=hard>http://fotostrana.ru/user/autologin/?u=83464215&eRf=300&h=%242y%2404%24TGqSY99VF5D5BE5OvgwcmeIazLzYby.ZTLSS6i9Tn2KT9TcvWP9Uq&mobile_send_enabled=0&t=300&v=2&time=1440703378&to_url=%2Fuser%2Fconfirm%2F%3Fu%3D83464215%26c%3D%242y%2404%248lY8RQ4VTMvbFec7tbdLd.i5ffYyJNir6nG4q94Rh6bQUfxqcv1Ge%26returnUrl%3D%252Fmeeting%252F</textarea>
<button id=start>start</button>
<div class="out"></div>
<script type="text/javascript">

	$(function () {
		$(document).on("click","#start",function(){
			var proxylist = $("#proxy").html().split("\n");
			var autologin = $("#autologin").html().split("\n");
			var out= $(".out");
			$(this).attr("disabled","true");
			$(this).html("работаю..")
			var outlog=function(texto){
					out.append($("<div>").html(texto));
			}
			outlog(" стартую "+autologin.length+" аккаунов");

			var idlile=$("#idlile").val();
			var  alldone=0;
			var allneed=autologin.length;
			var gosite=function(autologinnow){
				var proxynow=proxylist.pop();
				if(proxynow==undefined){
					outlog("кончились прокси");
					$(this).html("готово");
					return;
				}

			$.ajax({
				url:"/HttpCrosGet.php",
				data:{
				url:"http://fotostrananacrut.bro-dev.tk:9999",
				autologinlink:autologinnow,
				proxynow:proxynow,
				likeid	:idlile,
				}
			}).done((d)=>{
				if(d=="Прокси не работает"){
						outlog("ne ok пробую другую прокси" );
				gosite(autologinnow);
				}else{
				outlog("ok"+`
<div class="spoiler-wrapper">
<div class="spoiler folded"><a href="javascript:void(0);">спойлер</a></div>
<div class="spoiler-text">`+d+`</div>
</div>

					`);
				alldone++;
				}
				//console.dir(d);
			}).fail((e)=>{
				outlog("ne ok пробую другую прокси" );
				//console.dir(e);
				gosite(autologinnow);
			}).always(()=>{
				if(alldone==allneed){
				$(this).html("готово");}
			});
			};

			autologin.slice(0,1).forEach((el)=>{
			gosite(el);
			});
		});
		jQuery(document).on("click",".spoiler",function(){
			jQuery(this).next().slideToggle();
		});
	});
</script>
</body>
</html>