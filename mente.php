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
			http_response_code( 301 ) ;
			header( "Location: ./main.php" ) ;
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

	//座席種類
	$M_TYPE = array();
	$M_TYPE_NAME[1] = "座席";
	$M_TYPE_NAME[2] = "施設";
	$M_TYPE_NAME[3] = "その他";

	//状態メニュー
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_STATUS";
	$T_99999_M_STATUS = new $tmpCLASS_NAME;
	$mstmt = $T_99999_M_STATUS->getList();
	$M_STATUS_NAME = array();
	$M_STATUS_COLOR = array();
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
<meta name="copyright" content="Copyright XSERVER Inc." />
<meta name="robots" content="noindex,nofollow,noarchive" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="jquery.pep.js"></script>

<title>みんなの座席表メンテナンス</title>

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
		location.href = "mente.php?f_key=" + f_key;
	});

	//座席表へ
	$('#mente').on('click',function(e){
		var f_key = $("#floor").attr("f_key");
		location.href = "main.php?f_key=" + f_key;
	});

	//マスター管理画面へ
	$('#master').on('click',function(e){
		location.href = "master.php";
	});

	//回転ボタンで回転
	$('.s_rotate_btn').on('click',function(e){
		var rotate = $(this).parent().find(".s_rotate").text();
		rotate = parseInt(rotate) + 90;
		if (rotate >=360 ) { rotate = rotate -360; }
		$(this).parent().find(".s_rotate").text(rotate);
		$(this).parent().find(".s_pic").css("transform","rotate(" + rotate + "deg)");
		var s_key = $(this).parent().find('.s_key').text();
		var s_userid = $(this).parent().find('.s_userid').text();
		var s_name = $(this).parent().find('.s_name').text();
		var s_naisen = $(this).parent().find('.s_naisen').text();
		var s_tokki = $(this).parent().find('.s_tokki').text();
		var s_left = $(this).parent().find('.s_left').text();
		var s_top = $(this).parent().find('.s_top').text();
		var s_pic = $(this).parent().find('.s_pic').text();
		var s_type = $(this).parent().find('.s_type').text();
		var s_status_cd = $(this).parent().find('.s_status_cd').text();
		var s_rotate = rotate;
		var f_key = $('#floor').attr("f_key");
		$.ajax({
			url:'./sinfochange.php',
			type:'POST',
			data:{
				's_key'      :s_key,
				's_userid'   :s_userid,
				's_name'     :s_name,
				's_naisen'   :s_naisen,
				's_tokki'    :s_tokki,
				's_left'     :s_left,
				's_top'      :s_top,
				'f_key'      :f_key,
				's_pic'      :s_pic,
				's_type'     :s_type,
				's_status_cd':s_status_cd,
				's_rotate'   :s_rotate
			}
		})
		// Ajaxリクエストが成功した時発動
		.done( (data) => {
			//location.reload(false);
		})
		// Ajaxリクエストが失敗した時発動
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
		e.stopPropagation();
	});
	
	//座席移動イベント
	$('#s_selected').pep({
			constrainTo: 'parent',
			grid:       [10,10],
			stop: function(ev, obj) {
				var move_left = $(obj.el).position().left;
				var move_top = $(obj.el).position().top;
				var min_left = 9999;
				var min_top = 9999;
				$("#s_selected").find(".seat").each(function(i, elem) {
					var drag = $(this);
					var s_left = drag.position().left + move_left;
					var s_top = drag.position().top + move_top;
					var s_key = drag.find('.s_key').text();
					var s_userid = drag.find('.s_userid').text();
					var s_name = drag.find('.s_name').text();
					var s_naisen = drag.find('.s_naisen').text();
					var s_tokki = drag.find('.s_tokki').text();
					var s_pic = drag.find('.s_pic').text();
					var s_type = drag.find('.s_type').text();
					var s_status_cd = drag.find('.s_status_cd').text();
					var s_rotate = drag.find('.s_rotate').text();
					var f_key = $('#floor').attr("f_key");
					if (min_left > s_left) { min_left = s_left; }
					if (min_top > s_top) { min_top = s_top; }
					drag.find('.s_left').text(s_left + "px");
					drag.find('.s_top').text(s_top + "px");
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
						//location.reload(false);
					})
					// Ajaxリクエストが失敗した時発動
					.fail( (data) => {
						alert('更新が失敗しました。:' + data);
					});
				});
				$("#s_selected").css("left",min_left + "px");
				$("#s_selected").css("top",min_top + "px");
				$("#s_selected").css("transform","matrix(1, 0, 0, 1, 0, 0)");
			},
          	shouldEase: false
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

	//座席種類選択メニュー、フロアー選択メニュー、状態変更メニュー、吹き出し、情報編集フォームを非表示にする
	$('#contents').on('click',function(e){
		$('#t_menu').hide();
		$('#f_menu').hide();
		$('#s_menu').hide();
		$('#s_info').hide();
		$('#p_menu').hide();
	});

	//座席情報編集フォームをクリックした際もいろいろ閉じる
	$('#s_info').on('click',function(e){
		//いろいろ閉じる
		$('#t_menu').hide();
		$('#f_menu').hide();
		$('#s_menu').hide();
		$('#p_menu').hide();
		e.stopPropagation();
	});

	//座席を選択する
	$('.s_select').on('change',function(e){
		//選択状態なら削除ボタンを活性化
		var rslt = $("#contents").find(".s_select:checked");
		if(rslt.length == 0) {
			$("#seat_del").css("background","rgba(156, 156, 156, 1.0)");
		} else {
			$("#seat_del").css("background","rgba(255, 10, 10, 1.0)");
		}
		//選択状態の要素は#s_selectedの配下にする
		//1.リセット
		$("#s_selected").find(".s_select").each(function(i, elem) {
			$(this).parent().appendTo("#contents");
		});
		$("#contents").find(".s_select").each(function(i, elem) {
			var s_left = $(this).parent().find(".s_left").text();
			var s_top = $(this).parent().find(".s_top").text();
			$(this).parent().css("left", s_left);
			$(this).parent().css("top", s_top);
		});
		//2.#s_selectedの配下にする
		$("#contents").find(".s_select:checked").each(function(i, elem) {
			$(this).parent().appendTo("#s_selected");
		});
		var min_left = 9999;
		var min_top  = 9999;
		$("#s_selected").find(".seat").each(function(i, elem) {
			var drag = $(this);
			var s_left = parseInt($(this).find(".s_left").text());
			var s_top = parseInt($(this).find(".s_top").text());
			if (min_left>s_left) { min_left=s_left; }
			if (min_top>s_top) { min_top=s_top; }
		});
		$("#s_selected").css("left",min_left + "px");
		$("#s_selected").css("top",min_top + "px");
		$("#s_selected").find(".seat").each(function(i, elem) {
			var s_left = parseInt($(this).find(".s_left").text()) - min_left;
			var s_top = parseInt($(this).find(".s_top").text()) - min_top;
			$(this).css("left", s_left + "px");
			$(this).css("top", s_top + "px");
		});
		e.stopPropagation();
	});

	//座席を削除する
	$('#seat_del').on('click',function(e){
		//選択状態なら削除処理
		var rslt = $("#contents").find(".s_select:checked");
		if(rslt.length > 0) {
			if(confirm('本当に削除しますか？')){
				/*　OKの時の処理 */
				var keys = "";
				$("#contents").find(".s_select:checked").each(function(i, elem) {
					keys = keys + $(this).parent().find(".s_key").text() + ",";
				});
				$.ajax({
					url:'./sinfodelete.php',
					type:'POST',
					data:{
						's_keys'      :keys,
					}
				})
				// Ajaxリクエストが成功した時発動
				.done( (data) => {
					location.reload(false);
					//alert(data);
				})
				// Ajaxリクエストが失敗した時発動
				.fail( (data) => {
					alert('削除に失敗しました。:' + data);
				});
			}
		}
		e.stopPropagation();
	});


	//座席情報編集フォームを表示する
	$('.s_name_btn').on('click',function(e){
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
		var s_type_name = "";
		var s_status_cd = $(this).parent().find(".s_status_cd").text();
		var s_status_nm = $(this).parent().find(".s_status").text();
		var s_status_color = $(this).parent().find(".s_status").css("background-color");
		var s_rotate = $(this).parent().find(".s_rotate").text();
		$('#info_key').val(s_key);
		$('#info_userid').val(s_userid);
		$('#info_name').val(s_name);
		$('#info_naisen').val(s_naisen);
		$('#info_tokki').val(s_tokki);
		$('#info_status').text(s_status_nm);
		$('#info_status').css("background-color",s_status_color);
		$('#info_left').val(s_left);
		$('#info_top').val(s_top);
		$('#info_pic').val(s_pic);
		$('#info_pic_sel').text(s_pic);
		$('#info_type').val(s_type);
		if(s_type=="1") {
			s_type_name = "座席";
		} else if(s_type=="2") {
			s_type_name = "施設";
		} else {
			s_type_name = "その他";
		}
		$('#info_type_sel').text(s_type_name);
		$('#info_status_cd').val(s_status_cd);
		$('#info_rotate').val(s_rotate);
		$('#s_info').css("background","rgba(156, 156, 156, 0.7)");
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
		//alert(s_key + ","  + s_userid + ","  + s_name + ","  + s_naisen + ","  + s_tokki + ","  + s_left + ","  + s_top + "," + f_key + ","  + s_pic + ","  + s_type + ","  + s_status_cd + "," + s_rotate);
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
			//alert(data);
		})
		// Ajaxリクエストが失敗した時発動
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});

	//座席変更画面より状態変更メニューを表示する
	$('#info_status').on('click',function(e) {
		$('#t_menu').hide();
		$('#p_menu').hide();
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

	//座席変更画面より座席種類変更メニューを表示する
	$('#info_type_sel').on('click',function(e) {
		$('#s_menu').hide();
		$('#p_menu').hide();
		$('#t_menu').css("z-index","4");
		var height = parseInt($('#t_menu').css("height"));
		var pos = $(this).offset();
		$('#t_menu').css("left",pos.left + "px");
		$('#t_menu').css("top" ,pos.top-height/2 + "px");
		$('#t_menu').show();
		e.stopPropagation();
	});

	//座席種類を変更する
	$('.tm_row').on('click',function(e){
		var tm_key = $(this).find('.tm_key').text();
		var tm_name = $(this).find('.tm_name').text();
		var old_type = $('#info_type').val();
		$('#info_type_sel').text(tm_name);
		$('#info_type').val(tm_key);
		if (old_type!=tm_key) {
			$('#info_status_cd').val("");
			$('#info_rotate').val("0");
			$('#info_status').text("　");
			if (tm_key=='1') {
				$('#info_pic').val("SEAT_01.png");
				$('#info_pic_sel').text("SEAT_01.png");
			} else {
				$('#info_pic').val("OBJECT_01.png");
				$('#info_pic_sel').text("OBJECT_01.png");
			}
		}
		$('#t_menu').hide();
		e.stopPropagation();
	});

	//座席変更画面より画像変更メニューを表示する
	$('#info_pic_sel').on('click',function(e) {
		var s_type = $("#info_type").val();
		$('#s_menu').hide();
		$('#t_menu').hide();
		$('#p_menu').css("z-index","4");
		if (s_type=='1') {
			$(".pm_row[pm_type='1']").css("display","block");
			$(".pm_row[pm_type='2']").css("display","none");
			$(".pm_row[pm_type='3']").css("display","none");
		} else if(s_type=='2') {
			$(".pm_row[pm_type='1']").css("display","none");
			$(".pm_row[pm_type='2']").css("display","block");
			$(".pm_row[pm_type='3']").css("display","none");
		} else {
			$(".pm_row[pm_type='1']").css("display","none");
			$(".pm_row[pm_type='2']").css("display","none");
			$(".pm_row[pm_type='3']").css("display","block");
		}
		var pleft = parseInt($('#s_info').css("left"));
		var ptop  = parseInt($('#s_info').css("top"));
		var pmleft = $('#s_info').css("margin-left");
		var pmtop  = $('#s_info').css("margin-top");
		$('#p_menu').css("left", pleft + 100 + "px");
		$('#p_menu').css("top" , ptop + 50 + "px");
		$('#p_menu').css("margin-left",pmleft);
		$('#p_menu').css("margin-top" ,pmtop);
		$('#p_menu').show();
		e.stopPropagation();
	});
	//画像を変更する
	$('.pm_row').on('click',function(e){
		var pm_name = $(this).find('.pm_name').text();
		$('#info_pic').val(pm_name);
		$('#info_pic_sel').text(pm_name);
		$('#p_menu').hide();
		e.stopPropagation();
	});

	//座席追加ボタンより座席情報編集フォームを表示する
	$('#seat_add').on('click',function(e){
		var s_key = "";
		var s_userid = "0";
		var s_name = "";
		var s_naisen = "";
		var s_tokki = "";
		var s_left = 0;
		var s_top = 0;
		var s_pic = "SEAT_01.png";
		var s_type = "1";
		var s_type_name = "座席";
		var s_status_cd = "1";
		var s_status_nm = "";
		var s_status_color = "";
		var s_rotate = "0";
		$('#info_key').val(s_key);
		$('#info_userid').val(s_userid);
		$('#info_name').val(s_name);
		$('#info_naisen').val(s_naisen);
		$('#info_tokki').val(s_tokki);
		$('#info_status').text(s_status_nm);
		$('#info_status').css("background-color",s_status_color);
		$('#info_left').val(s_left);
		$('#info_top').val(s_top);
		$('#info_pic').val(s_pic);
		$('#info_pic_sel').text(s_pic);
		$('#info_type').val(s_type);
		if(s_type=="1") {
			s_type_name = "座席";
		} else if(s_type=="2") {
			s_type_name = "施設";
		} else {
			s_type_name = "その他";
		}
		$('#info_type_sel').text(s_type_name);
		$('#info_status_cd').val(s_status_cd);
		$('#info_rotate').val(s_rotate);
		$('#s_info').css("background","rgba(220, 220, 220, 0.7)");
		$('#s_info').show();
		e.stopPropagation();
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
		background: url('/images/bgjpg.jpg') no-repeat;
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
#seat_add {
	background: rgba(125, 125, 125, 1.0);
	color: white;
	font-size : 120% ;
	margin-right: 10px;
	width : 180px ;
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
#seat_del {
	background: rgba(156,156,156, 1.0);
	color: white;
	font-size : 120% ;
	margin-right: 10px;
	width : 180px ;
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
#master {
	background: rgba(125, 125, 125, 1.0);
	color: white;
	font-size : 120% ;
	margin-right: 10px;
	width : 150px ;
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
.s_name_btn {
	background: rgba(256, 20, 20, 0.7);
	color: black;
	font-size : 120% ;
	width : 100px ;
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
.s_rotate_btn {
	background: rgba(156, 156, 156, 0.7);
	color: black;
	font-size : 120% ;
	width : 100px ;
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
.s_select {
	position: absolute;
	background: rgba(156, 156, 156, 1.0);
	width : 20px ;
	height : 20px ;
	left : 0px;
	top : 0px;
	z-index : 5;
}

#t_menu {
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
.tm_row {
	color :blue;
	font-style : bold;
	margin:  0 auto;
}
.tm_row:hover{
	background: aqua;
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
#p_menu {
	position:fixed;
	font-size : 120% ;
    left: 50%;
    top: 50%;
	background: rgba(156, 156, 156, 0.7);
	width : 300px;
	height : 300px;
    margin-left: 0px;
    margin-top: 0px;
	text-align : left;
	border-radius: 3px;
	display:none;
	z-index: 2;
	overflow-y:scroll;
}
.pm_row {
	position: relative;
	color :blue;
	font-style : bold;
	margin:  5px;
	width: 110px;
	height: 110px;
	float: left;
}
.pm_pic {
	width: 100px;
	height: 100px;
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

.info_input_pic {
	background: rgba(60, 60, 60, 1.0);
	color : white;
	display : inline-block ;
	font-size : 120% ;
	text-align : left ;
	width : 190px ;
	height : 25px ;
	margin:  5px auto;
	border-radius: 5px;
}

.info_input_type {
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
	<!-- headline -->
	<div id="headline">
		<div id="logout" admin="<?php print $admin; ?>">
			<div><?php	print $message." <a href='/logout.php'>ログアウトはこちら。</a>";	?></div>
		</div>
		<div id="lastupdate">
			<div><?php	print date("Y/m/d H:i:s")." 現在";	?></div>
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
	<!-- フロアー変更 -->
	<div id="floorarea">
		<div id="floor" f_key="<?php print $dflt_f_key; ?>">
			<?php print $M_FLOOR_NAME[$dflt_f_key];  ?>
		</div>
		<div id="mente" style="display:<?php print $mente_disp; ?>;">
			座席表へ
		</div>
		<div id="seat_add" style="display:<?php print $mente_disp; ?>;">
			座席を追加する
		</div>
		<div id="seat_del" style="display:<?php print $mente_disp; ?>;">
			選択中の座席を削除する
		</div>
		<div id="master" style="display:<?php print $mente_disp; ?>;">
			マスター管理画面へ
		</div>
	</div>
	<!-- 座席表 -->
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
						print "<div class=\"s_naisen\" style=\"visibility:hidden;\">".$f004."</div>";
						print "<div class=\"fukidashi\" style=\"display:none;\"></div>";
						print "<input type=\"button\" class=\"s_name_btn\" value=\"".$f003."\" />";
						print "<div class=\"s_space\"></div>";
						print "<input type=\"button\" class=\"s_rotate_btn\" value=\"回転⤵\" />";
						print "<div class=\"s_status\" style=\"background-color:".$status_color.";visibility:hidden;\">".$status_name."</div>"; 
						print "<div class=\"s_name\" style=\"display:none;\">".$f003."</div>";
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
						print "<input type=\"checkbox\" class=\"s_select\" value=\"1\" />";
						print '</div>'."\n";
					}
					?>
					<div id="s_selected"></div>
				</dl>
			</div>
		</div>
	</div>
	<!-- 座席種類メニュー -->
	<div id="t_menu">
		<?php 
		print "<div class=\"tm_row\">";
		print "<div class=\"tm_key\" style=\"display:none;\">1</div>";
		print "<div class=\"tm_name\">".htmlspecialchars($M_TYPE_NAME[1])."</div>";
		print "</div>"."\n";
		print "<div class=\"tm_row\">";
		print "<div class=\"tm_key\" style=\"display:none;\">2</div>";
		print "<div class=\"tm_name\">".htmlspecialchars($M_TYPE_NAME[2])."</div>";
		print "</div>"."\n";
		print "<div class=\"tm_row\">";
		print "<div class=\"tm_key\" style=\"display:none;\">3</div>";
		print "<div class=\"tm_name\">".htmlspecialchars($M_TYPE_NAME[3])."</div>";
		print "</div>"."\n";
		?>
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
	<!-- 画像変更メニュー -->
	<div id="p_menu" p_key="">
		<?php 
		$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_ICON";
		$T_99999_M_ICON = new $tmpCLASS_NAME;
		$pstmt = $T_99999_M_ICON->getList();
		while($prow = $pstmt->fetch(PDO::FETCH_NUM)) {
			print "<div class=\"pm_row\" pm_type=\"".htmlspecialchars($prow[1])."\">";
			print "<div class=\"pm_code\" style=\"display:none;\">".htmlspecialchars($prow[2])."</div>";
			print "<div class=\"pm_pic\" style=\"background:url('/images/".$prow[3]."');\"></div>";
			print "<div class=\"pm_name\">".htmlspecialchars($prow[3])."</div>";
			print "</div>"."\n";
		}
		?>
	</div>
	<!-- 吹き出し -->
	<div id="s_fukidashi" >
		<pre id="s_fukidashi_msg"></pre>
	</div>
	<!-- 情報変更フォーム -->
	<div id="s_info">
		<div class="info_area"><label class="info_title">種類</label><div class="info_input_type" id="info_type_sel"></div></div>
		<div class="info_area"><label class="info_title">内線番号</label><input class="info_input" id="info_naisen" type="text" value="" maxlength="5" /></div>
		<div class="info_area"><label class="info_title">表示名</label><input class="info_input" id="info_name" type="text" value="" maxlength="10" /></div>
		<div class="info_area"><label class="info_title">画像</label><div class="info_input_pic" id="info_pic_sel"></div></div>
		<div class="info_area"><label class="info_title">状態</label><div class="info_input_sel" id="info_status"></div></div>
		<div class="info_area"><label class="info_title">左から</label><input class="info_input" id="info_left" type="text" value="" />ピクセル</div>
		<div class="info_area"><label class="info_title">上から</label><input class="info_input" id="info_top" type="text" value="" />ピクセル</div>
		<div class="info_area"><label class="info_title">回転角度</label><input class="info_input" id="info_rotate" type="text" value="" />度</div>
		<div class="info_area"><div id="info_btn">入力内容を確定する</div></div>
		<input class="info_input" id="info_key" type="hidden" value="" />
		<input class="info_input" id="info_userid" type="hidden" value="" />
		<input class="info_input" id="info_tokki" type="hidden" value="" />
		<input class="info_input" id="info_pic" type="hidden" value="" />
		<input class="info_input" id="info_type" type="hidden" value="" />
		<input class="info_input" id="info_status_cd" type="hidden" value="" />
	</div>
</body>

</html>
