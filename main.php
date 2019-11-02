<?php
	$CONST_GRP_NO = "99999";

	function h($s){
		return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
	}

	session_start();
	//ログイン済みの場合
	if (isset($_SESSION['DISPNAME'])) {
		$message='ようこそ'.h($_SESSION['DISPNAME'])."さん";
		$admin=h($_SESSION['ADMIN']);
		if ($admin=="1") {
			$mente_disp="block";
		} else {
			$mente_disp="none";
		}
	} else {
		http_response_code( 301 ) ;
		header( "Location: ./entrance.php" ) ;
	}

	//クラスの読み込み
	function autoload($className){
	require './class/'.$className.'.php';
	}
	spl_autoload_register('autoload');

	//基本設定マスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_COMMON";
	$T_99999_M_COMMON = new $tmpCLASS_NAME;
	$cstmt = $T_99999_M_COMMON->getData(1);
	$M_COMMON_RELOAD = "600";
	while($crow = $cstmt->fetch(PDO::FETCH_NUM)) {
		$cf001 = htmlspecialchars($crow[0]);
		$cf002 = htmlspecialchars($crow[1]);
		$cf003 = htmlspecialchars($crow[2]);
		$cf004 = htmlspecialchars($crow[3]);
		$cf005 = htmlspecialchars($crow[4]);
		$M_COMMON_RELOAD = $cf002;
	}

	//状態マスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_STATUS";
	$T_99999_M_STATUS = new $tmpCLASS_NAME;
	$mstmt = $T_99999_M_STATUS->getList();
	$M_STATUS = array();
	while($mrow = $mstmt->fetch(PDO::FETCH_NUM)) {
		$mf001 = htmlspecialchars($mrow[0]);
		$mf002 = htmlspecialchars($mrow[1]);
		$mf003 = htmlspecialchars($mrow[2]);
		$mf004 = htmlspecialchars($mrow[3]);
		$mf005 = htmlspecialchars($mrow[4]);
		$M_STATUS_NAME[$mf002."-".$mf001] = $mf004;
		$M_STATUS_COLOR[$mf002."-".$mf001] = $mf005;
	}

	//フロアーマスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_FLOOR";
	$T_99999_M_FLOOR = new $tmpCLASS_NAME;
	$fstmt = $T_99999_M_FLOOR->getList();
	$M_FLOOR_NAME = array();
	$M_FLOOR_WIDTH = array();
	$M_FLOOR_HEIGHT = array();
	$dflt_f_key = "0";
	$dflt_f_width = "1152px";  //"1152px";
	$dflt_f_height = "715px";  //"715px"; 
	$cnt = 0;
	while($frow = $fstmt->fetch(PDO::FETCH_NUM)) {
		$ff001 = htmlspecialchars($frow[0]);
		$ff002 = htmlspecialchars($frow[1]);
		$ff003 = htmlspecialchars($frow[2]);
		$ff004 = htmlspecialchars($frow[3]);
		$ff005 = htmlspecialchars($frow[4]);
		$M_FLOOR_NAME[$ff001] = $ff003;
		$M_FLOOR_WIDTH[$ff001] = $ff004;
		$M_FLOOR_HEIGHT[$ff001] = $ff005;
		if($cnt==0) { 
			$dflt_f_key = $ff001;
			$dflt_f_width = $ff004;
			$dflt_f_height = $ff005;
		}
		$cnt++;
	}
	if(isset($_GET['f_key'])){
		$gff001 = htmlspecialchars($_GET['f_key']);
		$dflt_f_key = $gff001;
		$dflt_f_width = $M_FLOOR_WIDTH[$gff001];
		$dflt_f_height = $M_FLOOR_HEIGHT[$gff001];
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="0">

<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="refresh" content="<?php print $M_COMMON_RELOAD; ?>">
<meta name="copyright" content="Copyright XSERVER Inc." />
<meta name="robots" content="noindex,nofollow,noarchive" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="jquery.pep.js"></script>

<title>みんなの座席表</title>

<script>
$(document).ready(function(){

	
	//フロアーメニューを表示する
	$('#floor').on('click',function(e){
		var pos = $(this).offset();
		$('#f_menu').css("left",pos.left + "px");
		$('#f_menu').css("top" ,pos.top - 5  + "px");
		$('#f_menu').show();
		e.stopPropagation();
	});

	//フロアー変更する
	$('.fm_row').on('click',function(){
		var f_key = $(this).find(".fm_key").text();
		location.href = "main.php?f_key=" + f_key;
	});

	//状態変更メニューを表示する
	$('.s_status').on('click',function(e){
		$('#s_menu').css("z-index","2");
		var s_type = $(this).parent().find(".s_type").text();
		var s_key = $(this).parent().find(".s_key").text();
		if (s_type > 0) {
			if (s_type=='1') {
				$(".sm_row[sm_type='1']").css("display","block");
				$(".sm_row[sm_type='2']").css("display","none");
				$(".sm_row[sm_type='3']").css("display","none");
			} else if(s_type=='2') {
				$(".sm_row[sm_type='1']").css("display","none");
				$(".sm_row[sm_type='2']").css("display","block");
				$(".sm_row[sm_type='3']").css("display","none");
			} else {
				$(".sm_row[sm_type='1']").css("display","none");
				$(".sm_row[sm_type='2']").css("display","none");
				$(".sm_row[sm_type='3']").css("display","block");
			}
			var height = parseInt($('#s_menu').css("height"));
			var pos = $(this).offset();
			$('#s_menu').css("left",pos.left + "px");
			$('#s_menu').css("top" ,pos.top-height/2 + "px");
			$('#s_menu').attr("s_key",s_key);
			$('#s_menu').show();
		} else {
			$('#s_menu').hide();
		}
		e.stopPropagation();
	});

	//状態変更する
	$('.sm_row').on('click',function(){
		var s_key = $(this).parent().attr('s_key');
		var sm_status = $(this).find('.sm_status').text();
		var sm_name = $(this).find('.sm_name').text();
		var sm_color = $(this).find('.sm_color').text();
		var z_index = $('#s_menu').css("z-index");
		if (z_index == 2) {
			//状態変更時は即更新
			$.ajax({
				url:'./statuschange.php',
				type:'POST',
				data:{
					's_key':s_key,
					's_status':sm_status
				}
			})
			.done( (data) => {
				//alert('ok:' + data);
				location.reload(false);
			})
			.fail( (data) => {
				alert('更新が失敗しました。:' + data);
			});
		} else if (z_index == 4) {
			//情報編集フォームの場合は更新しない
			$('#info_status').text(sm_name);
			$('#info_status_cd').val(sm_status);
			$('#info_status').css("background-color",sm_color);
			$('#s_menu').hide();
		}
	});

	//フロアー選択メニュー、状態変更メニュー、吹き出し、情報編集フォームを非表示にする
	$('#contents').on('click',function(e){
		$('#f_menu').hide();
		$('#s_menu').hide();
		$('#s_fukidashi').hide();
		$('#s_info').hide();
	});

	//吹き出しを表示する。
	$('.fukidashi').on('click',function(e){
		var s_tokki = $(this).parent().find(".s_tokki").text();
		$('#s_fukidashi_msg').text(s_tokki);
		var pos = $(this).parent().offset();
		var height = parseInt($('#s_fukidashi').css("height"));
		var leftpos = pos.left-75;
		var toppos = pos.top-height-15;
		if (leftpos<0) { leftpos = 0; } 
		if (toppos<0) { toppos = 0; } 
		$('#s_fukidashi').css("left",leftpos + "px");
		$('#s_fukidashi').css("top" ,toppos + "px");
		$('#s_fukidashi').show();
		e.stopPropagation();
	});

	//座席情報編集フォームを表示する
	$('.s_name').on('click',function(e){
		var s_key = $(this).parent().find(".s_key").text();
		var s_userid = $(this).parent().find(".s_userid").text();
		var s_name = $(this).parent().find(".s_name").text();
		var s_naisen = $(this).parent().find(".s_naisen").text();
		var s_tokki = $(this).parent().find(".s_tokki").text();
		var pos = $(this).parent().offset();
		var s_left = parseInt($(this).parent().find(".s_left").text());
		var s_top = parseInt($(this).parent().find(".s_top").text());
		var s_pic = $(this).parent().find(".s_pic").text();
		var s_type = $(this).parent().find(".s_type").text();
		var s_status_cd = $(this).parent().find(".s_status_cd").text();
		var s_status_nm = $(this).parent().find(".s_status").text();
		var s_status_color = $(this).parent().find(".s_status").css("background-color");
		var s_rotate = $(this).parent().find(".s_rotate").text();
		var s_lastupdate = $(this).parent().find(".s_lastupdate").text();
		$('#info_key').val(s_key);
		$('#info_userid').val(s_userid);
		$('#info_name').val(s_name);
		$('#info_naisen').val(s_naisen);
		$('#info_tokki').text(s_tokki);
		$('#info_status').text(s_status_nm);
		$('#info_status').css("background-color",s_status_color);
		$('#info_left').val(s_left);
		$('#info_top').val(s_top);
		$('#info_pic').val(s_pic);
		$('#info_type').val(s_type);
		$('#info_status_cd').val(s_status_cd);
		$('#info_rotate').val(s_rotate);
		$('#info_lastupdate').text(s_lastupdate);
		$('#s_info').show();
		e.stopPropagation();
	});

	//座席情報編集内容を確定をする
	$('#info_btn').on('click',function(e){
		var s_key = $('#info_key').val();
		var s_userid = $('#info_userid').val();
		var s_name = $('#info_name').val();
		var s_naisen = $('#info_naisen').val();
		var s_tokki = $('#info_tokki').val();
		var tmpleft = parseInt($('#info_left').val());
		var s_left = (isNaN(tmpleft) || !tmpleft) ? 0 : tmpleft;
		var tmptop = parseInt($('#info_top').val());
		var s_top = (isNaN(tmptop) || !tmptop) ? 0 : tmptop;
		var s_pic = $('#info_pic').val();
		var s_type = $('#info_type').val();
		var s_status_cd = $('#info_status_cd').val();
		var s_rotate = $('#info_rotate').val();
		var f_key = $('#floor').attr("f_key");
		var s_floor = "";
		$.ajax({
			url:'./sinfochange.php',
			type:'POST',
			data:{
				's_key'      :s_key,
				's_userid'   :s_userid,
				's_name'     :s_name,
				's_naisen'   :s_naisen,
				's_tokki'    :s_tokki,
				's_left'     :s_left + "px",
				's_top'      :s_top  + "px",
				'f_key'      :f_key,
				's_pic'      :s_pic,
				's_type'     :s_type,
				's_status_cd':s_status_cd,
				's_rotate'   :s_rotate
			}
		})
		// Ajaxリクエストが成功した時発動
		.done( (data) => {
			location.reload(false);
		})
		// Ajaxリクエストが失敗した時発動
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});

	//座席変更画面より状態変更メニューを表示する
	$('#info_status').on('click',function(e) {
		$('#s_menu').css("z-index","4");
		var s_type = $("#info_type").val();
		var s_key = $("#info_key").val();
		if (s_type > 0) {
			if (s_type=='1') {
				$(".sm_row[sm_type='1']").css("display","block");
				$(".sm_row[sm_type='2']").css("display","none");
				$(".sm_row[sm_type='3']").css("display","none");
			} else if(s_type=='2') {
				$(".sm_row[sm_type='1']").css("display","none");
				$(".sm_row[sm_type='2']").css("display","block");
				$(".sm_row[sm_type='3']").css("display","none");
			} else {
				$(".sm_row[sm_type='1']").css("display","none");
				$(".sm_row[sm_type='2']").css("display","none");
				$(".sm_row[sm_type='3']").css("display","block");
			}
			var height = parseInt($('#s_menu').css("height"));
			var pos = $(this).offset();
			$('#s_menu').css("left",pos.left + "px");
			$('#s_menu').css("top" ,pos.top-height/2 + "px");
			$('#s_menu').attr("s_key",s_key);
			$('#s_menu').show();
		} else {
			$('#s_menu').hide();
		}
		e.stopPropagation();
	});

	//メンテ画面へ
	$('#mente').on('click',function(e){
		var f_key = $("#floor").attr("f_key");
		location.href = "mente.php?f_key=" + f_key;
	});

});
</script>


<style type="text/css">

* {
	margin : 0 ;
	padding : 0 ; 
	font-size : 100% ;
}

@media screen and (min-width: 479px) { /*ウィンドウ幅が479px以上の場合に適用*/
	body {
		font-size : 75% ;
		text-align : left ;
		font-family:"ＭＳ Ｐゴシック", Osaka, "ヒラギノ角ゴ Pro W3" ;
		line-height : 1.4 ;
		background: url('/images/bgcjpg.jpg') no-repeat;
		background-size:100% auto;
	}
}
@media screen and (max-width: 479px) { /*ウィンドウ幅が最大479pxまでの場合に適用*/
	body {
		font-size : 75% ;
		text-align : left ;
		font-family:"ＭＳ Ｐゴシック", Osaka, "ヒラギノ角ゴ Pro W3" ;
		line-height : 1.4 ;
		background: rgba(0, 0, 0, 1.0);
		background-size:100% auto;
	}
}
#headline {
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	width : <?php print $dflt_f_width; ?> ;
	height: 55px ;
	padding-left:  0;
	padding-right: 0;
	margin: 0 auto;
	position: relative;
}
#logout {
	font-size : 105% ;
	margin-top: 10px;
	margin-left: 10px;
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	height: 20px ;
	top:  10px;
}
#lastupdate {
	font-size : 105% ;
	margin-left: 10px;
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	height: 15px ;
	top:  10px;
}
#logo {
	position: absolute;
	right: 0px;
	top: 0px;
	float: right;
	background: url('/images/zasekilogo.png');
	background-size: contain;
	width: 160px;
	height: 40px;
}
#mymenu {
	font-size : 105% ;
	margin-left: 10px;
	text-align: center;	
	background: rgba(200, 200, 256, 1.0);
}
#mymenu ul{
	margin: 0 ;
	padding: 0 ;
}
#mymenu li{
	list-style: none;
	display: inline-block;
	width: 10%;
	min-width: 90px;
}
#mymenu li:not(:last-child){
	border-right:2px solid #ddd;
}
#mymenu a{
	text-decoration: none;
	color: #333;
}
#mymenu a.current{
	color: #00B0F0;
	border-bottom:2px solid #00B0F0;
}
#mymenu a:hover{
	color:red;
	border-bottom:2px solid #F7CB4D;
}
#floorarea {
	text-align : left;
	background: rgba(255, 255, 255, 1.0);
	width : <?php print $dflt_f_width; ?> ;
	height: 20px ;
	padding-left:  0;
	padding-right: 0;
	padding-bottom: 5px;
	margin: 0 auto;
}
#floor {
	background: mediumpurple;
	color: white;
	font-size : 120% ;
	margin-left: 10px;
	width : 300px ;
	height : 20px ;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 5px;
	position: relative;
	float: left;
}

#mente {
	background: rgba(125, 125, 125, 1.0);
	color: white;
	font-size : 120% ;
	margin-right: 10px;
	width : 200px ;
	height : 20px ;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 5px;
	position: relative;
	float: right;
	display: none;
}

.bg-white {
	background: url('/images/hogan.png') repeat;
	position: relative;
	padding-left:  0;
	padding-right: 0;
	width : <?php print $dflt_f_width; ?> ;
	margin: 0 auto;
}

#base {
	width : 100% ;
	height : 100% ;
}

#container {
	width : <?php print $dflt_f_width; ?> ;
}

#container dl#contents {
	height : <?php print $dflt_f_height; ?> ;
	margin : 0px ;
}

.seat {
	position: absolute;
	top:  0px;
	left: 0px;
	width : 100px ;
	height :100px ;
	vertical-align:  middle;
	z-index : 0 ;
}

.s_pic {
	background: rgba(125, 125, 125, 1.0);
	position: absolute;
	top:  0px;
	left: 0px;
	width : 100% ;
	height :100% ;
	vertical-align:  middle;
	z-index : -1 ;
}


.s_space {
	background: rgba(256, 256, 256, 0);
	width : 30px ;
	height : 10px ;
}
.s_naisen {
	background: rgba(156, 156, 156, 0.7);
	font-size : 100% ;
	width : 50px ;
	height : 15px ;
	margin:  5px 5px;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis; 
	-o-text-overflow: ellipsis;
	border-radius: 5px;
	float: left;
}
.s_name {
	background: rgba(147, 112, 219, 0.8);
	color: white;
	font-size : 120% ;
	width : 90px ;
	height : 22px ;
	margin:  5px auto;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 5px;
	clear: left;
}
.s_status {
	background: rgba(156, 156, 156, 0.7);
	color: white;
	font-size : 120% ;
	font-style : bold;
	width : 90px ;
	height : 20px ;
	margin:  5px auto;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 5px;
}
.s_tokki {
	display:none;
}
.s_type {
	display:none;
}
#f_menu {
	position: absolute;
	background: rgba(156, 156, 156, 0.7);
	font-size : 120% ;
	width : 300px;
	margin:  5px auto;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 3px;
	display:none;
	z-index: 2;
}
.fm_row {
	color :blue;
	font-style : bold;
	margin:  0 auto;
}
.fm_row:hover{
	background: aqua;
}
#s_menu {
	position: absolute;
	background: rgba(156, 156, 156, 0.7);
	font-size : 120% ;
	width : 100px;
	margin:  5px auto;
	text-align : center;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
	border-radius: 3px;
	display:none;
	z-index: 2;
}
.sm_row {
	color :blue;
	font-style : bold;
	margin:  5px auto;
}

.sm_row:hover{
	background: aqua;
}

.fukidashi {
  position: relative;
  width: 30px;
  height: 20px;
  margin:  0 5px;
  line-height: 30px;
  text-align: center;
  color: #FFF;
  font-size: 100%;
  font-weight: bold;
  background: orange;
  border-radius: 50%;
  box-sizing: border-box;
  z-index: 1;
  float: left;
}

.fukidashi:before {
  content: "";
  position: absolute;
  bottom: -4px;
  left: -4px;
  margin-top: -5px;
  border: 5px solid transparent;
  border-left: 5px solid orange;
  z-index: 0;
  -webkit-transform: rotate(135deg);
  transform: rotate(135deg);
}

#s_fukidashi {
  position: absolute;
  display: none; /* inline-block; */
  margin: 0 0;
  padding: 7px 10px;
  min-width: 120px;
  max-width: 100%;
  color: #555;
  font-size: 16px;
  background: orange;
  border-radius: 15px;
  width: 250px;
}

#s_fukidashi:before {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -15px;
  border: 15px solid transparent;
  border-top: 15px solid orange;
}

#s_fukidashi_msg {
  margin: 0;
  padding: 0;
  width: 250px;
  white-space: -moz-pre-wrap;
  white-space: -pre-wrap;
  white-space: -o-pre-wrap;
  white-space: pre-wrap;
}
#s_info {
	position:fixed;
    left: 50%;
    top: 50%;
	background: rgba(156, 156, 156, 0.7);
	font-size : 120% ;
	width : 300px;
    margin-left: -160px;
    margin-top: -190px;
	text-align : left;
	border-radius: 3px;
	display:none;
	z-index: 3;
}

.info_area {
	width : 300px ;
	clear : left ;
}

.info_title {
	background: mediumpurple;
	color: white;
	font-size : 120% ;
	display : block ;
	float : left ;
	width : 100px ;
	height : 25px ;
	margin:  5px auto;
	border-radius: 5px;
}

.info_title_txt {
	background: mediumpurple;
	color: white;
	font-size : 120% ;
	display : block ;
	float : left ;
	width : 100px ;
	height :150px ;
	margin:  5px auto;
	border-radius: 5px;
}

.info_input {
	font-size : 120% ;
	width : 100px ;
	height : 20px ;
	margin:  5px auto;
	border-radius: 5px;
}

#info_lastupdate {
	display : inline-block ;
	font-size : 120% ;
	text-align : left ;
	width : 200px ;
	height : 25px ;
	margin:  5px auto;
}

.info_input_sel {
	background: rgba(60, 60, 60, 1.0);
	color : white;
	display : inline-block ;
	font-size : 120% ;
	text-align : center ;
	width : 100px ;
	height : 25px ;
	margin:  5px auto;
	border-radius: 5px;
}


@media screen and (min-width: 479px) { /*ウィンドウ幅が479px以上の場合に適用*/
	.info_input_txt {
		font-size : 120% ;
		width : 190px ;
		height :145px ;
		margin:  5px auto;
		border-radius: 5px;
	}
}
@media screen and (max-width: 479px) { /*ウィンドウ幅が最大479pxまでの場合に適用*/
	.info_input_txt {
		font-size : 95% ;
		width : 190px ;
		height :145px ;
		margin:  5px auto;
		border-radius: 5px;
	}
}

#info_naisen {
	width : 50px ;
}
#info_name {
	width : 100px ;
}
#info_left {
	width : 50px ;
}
#info_top {
	width : 50px ;
}
#info_rotate {
	width : 50px ;
}
#info_btn {
	position: relative;
	background: green;
	color: white;
	font-size : 120% ;
	font-style : bold;
	width : 300px ;
	height : 30px ;
	text-align : center;
	border-radius: 5px;
}
</style>
</head>
<body>
	<div id="headline">
		<div id="logout" admin="<?php print $admin; ?>">
			<div><?php	print $message." <a href='/logout.php'>ログアウトはこちら。</a>";	?></div>
		</div>
		<div id="lastupdate">
			<div><?php	print date("Y/m/d H:i:s")." 現在（".$M_COMMON_RELOAD."秒毎に自動更新します）";	?></div>
		</div>
		<div id="mymenu">
			<ul>
			<li class=”current”><a href="http://www.google.com" target="_blank" rel="noopener">Google</a></li>
			<li><a href="http://www.yahoo.co.jp" target="_blank" rel="noopener">Yahoo!</a></li>
			<li><a href="http://www.msn.com" target="_blank" rel="noopener">MSN</a></li>
			<li><a href=”#”>社内システムＡ</a></li>
			<li><a href=”#”>社内システムＢ</a></li>
			<li><a href=”#”>社内システムＣ</a></li>
			<li><a href=”#”>ファイルサーバー</a></li>
			</ul>
		</div>
		<div id="logo"></div>
	</div>
	<div id="floorarea">
		<div id="floor" f_key="<?php print $dflt_f_key; ?>">
			<?php print $M_FLOOR_NAME[$dflt_f_key];  ?>
		</div>
		<div id="mente" style="display:<?php print $mente_disp; ?>;">
			メンテナンス(管理者専用)
		</div>
	</div>
	<div class="bg-white">
		<div id="base">
			<div id="container">
				<dl id="contents"> 
					<?php 
					$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_SEAT";
					$T_99999_SEAT = new $tmpCLASS_NAME;
					$stmt = $T_99999_SEAT->getList($dflt_f_key);
					while($row = $stmt->fetch(PDO::FETCH_NUM)) {
						$f001 = htmlspecialchars($row[0]);
						$f002 = htmlspecialchars($row[1]);
						$f003 = htmlspecialchars($row[2]);
						$f004 = htmlspecialchars($row[3]);
						$f005 = htmlspecialchars($row[4]);
						$f006 = htmlspecialchars($row[5]);
						$f007 = htmlspecialchars($row[6]);
						$f008 = htmlspecialchars($row[7]);
						$f009 = htmlspecialchars($row[8]);
						$f010 = htmlspecialchars($row[9]);
						$f011 = htmlspecialchars($row[10]);
						$f012 = htmlspecialchars($row[11]);
						$f013 = htmlspecialchars($row[12]);
						$status_name = $M_STATUS_NAME[$f010."-".$f011];
						$status_color = $M_STATUS_COLOR[$f010."-".$f011];
						print "<div id=\"seat".$f001."\" class=\"seat\" style=\"left:".$f006.";top:".$f007.";\">";
						print "<div class=\"s_pic\" style=\"background:url('/images/".$f009."');transform:rotate(".$f012."deg);\"></div>";
						print "<div class=\"s_space\"></div>";
						if ($f004==""){ print "<div class=\"s_naisen\" style=\"visibility:hidden;\"></div>"; } else { print "<div class=\"s_naisen\">".$f004."</div>"; }
						if ($f005==""){ print "<div class=\"fukidashi\" style=\"display:none;\"></div>"; } else { print "<div class=\"fukidashi\"></div>"; }
						if ($f003==""){ 
							print "<div class=\"s_name\" style=\"opacity:0;\">".$f003."</div>"; 
						} else {
							if ($f010=="1") { 
								print "<div class=\"s_name\" style=\"font-weight: bold;\">".$f003."</div>"; 
							} else { 
								print "<div class=\"s_name\">".$f003."</div>"; 
							}
						}
						if ($status_name==""){ print "<div class=\"s_status\" style=\"visibility:hidden;\"></div>"; } else { 
							print "<div class=\"s_status\" style=\"background-color:".$status_color.";\">".$status_name."</div>"; 
						}
						print "<div class=\"s_tokki\" style=\"display:none;\">".$f005."</div>";
						print "<div class=\"s_type\" style=\"display:none;\">".$f010."</div>";
						print "<div class=\"s_key\" style=\"display:none;\">".$f001."</div>";
						print "<div class=\"s_userid\" style=\"display:none;\">".$f002."</div>";
						print "<div class=\"s_status_cd\" style=\"display:none;\">".$f011."</div>";
						print "<div class=\"s_pic\" style=\"display:none;\">".$f009."</div>";
						print "<div class=\"s_left\" style=\"display:none;\">".$f006."</div>";
						print "<div class=\"s_top\" style=\"display:none;\">".$f007."</div>";
						print "<div class=\"s_rotate\" style=\"display:none;\">".$f012."</div>";
						print "<div class=\"s_lastupdate\" style=\"display:none;\">".$f013."</div>";
						print '</div>'."\n";
					}
					?>
				</dl>
			</div>
		</div>
	</div>
	<!-- フロアーメニュー -->
	<div id="f_menu">
		<?php 
		$fstmt = $T_99999_M_FLOOR->getList();
		while($frow = $fstmt->fetch(PDO::FETCH_NUM)) {
			print "<div class=\"fm_row\">";
			print "<div class=\"fm_key\" style=\"display:none;\">".htmlspecialchars($frow[0])."</div>";
			print "<div class=\"fm_code\" style=\"display:none;\">".htmlspecialchars($frow[1])."</div>";
			print "<div class=\"fm_name\">".htmlspecialchars($frow[2])."</div>";
			print "</div>"."\n";
		}
		?>
	</div>
	<!-- 状態メニュー -->
	<div id="s_menu" s_key="">
		<?php 
		$mstmt = $T_99999_M_STATUS->getList();
		while($mrow = $mstmt->fetch(PDO::FETCH_NUM)) {
			print "<div class=\"sm_row\" sm_type=\"".htmlspecialchars($mrow[1])."\">";
			print "<div class=\"sm_status\" style=\"display:none;\">".htmlspecialchars($mrow[0])."</div>";
			print "<div class=\"sm_name\">".htmlspecialchars($mrow[3])."</div>";
			print "<div class=\"sm_color\" style=\"display:none;\">".htmlspecialchars($mrow[4])."</div>";
			print "</div>"."\n";
		}
		?>
	</div>
	<!-- 吹き出し -->
	<div id="s_fukidashi" >
		<pre id="s_fukidashi_msg"></pre>
	</div>
	<!-- 情報変更フォーム -->
	<div id="s_info" >
		<div class="info_area"><label class="info_title">最終更新</label><div id="info_lastupdate"></div></div>
		<div class="info_area"><label class="info_title">内線番号</label><input class="info_input" id="info_naisen" type="text" value="" maxlength="5" /></div>
		<div class="info_area"><label class="info_title">表示名</label><input class="info_input" id="info_name" type="text" value="" maxlength="10" /></div>
		<div class="info_area"><label class="info_title_txt">ＭＥＭＯ</label><textarea class="info_input_txt" id="info_tokki" maxlength="300"></textarea></div>
		<div class="info_area"><label class="info_title">状態</label><div class="info_input_sel" id="info_status"></div></div>
		<div class="info_area"><div id="info_btn">入力内容を確定する</div></div>
		<!-- <div class="info_area"><label class="info_title">左から</label> --><input class="info_input" id="info_left" type="hidden" value="" />
		<!-- <div class="info_area"><label class="info_title">上から</label> --><input class="info_input" id="info_top" type="hidden" value="" />
		<!-- <div class="info_area"><label class="info_title">回転角度</label> --><input class="info_input" id="info_rotate" type="hidden" value="" />
		<input class="info_input" id="info_key" type="hidden" value="" />
		<input class="info_input" id="info_userid" type="hidden" value="" />
		<input class="info_input" id="info_pic" type="hidden" value="" />
		<input class="info_input" id="info_type" type="hidden" value="" />
		<input class="info_input" id="info_status_cd" type="hidden" value="" />
	</div>
</body>

</html>
