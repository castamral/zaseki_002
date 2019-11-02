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

	//マスターリスト
	$ML_NAME = array();
	$ML_NAME[1] = "基本設定";
	$ML_NAME[2] = "フロアーマスタ";
	$ML_NAME[3] = "状態マスタ(座席)";
	$ML_NAME[4] = "状態マスタ(施設)";
	$ML_NAME[5] = "状態マスタ(その他)";

	$dflt_f_width = "1152px";

	//基本マスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_COMMON";
	$T_99999_M_COMMON = new $tmpCLASS_NAME;

	//状態メニュー
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_STATUS";
	$T_99999_M_STATUS = new $tmpCLASS_NAME;

	//フロアーマスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_FLOOR";
	$T_99999_M_FLOOR = new $tmpCLASS_NAME;

	//画像マスタ
	$tmpCLASS_NAME = "T_".$CONST_GRP_NO."_M_ICON";
	$T_99999_M_ICON = new $tmpCLASS_NAME;

	//GETパラメータ
	if(isset($_GET['master'])){
		$GET_MASTER = htmlspecialchars($_GET['master']);
	}
	$GET_SORT = "0";
	if(isset($_GET['sort'])){
		$GET_SORT = htmlspecialchars($_GET['sort']);
	}
	$SORT_DISABLE = "true";
    $SORT_CHECKED = "";
	if($GET_SORT == "1") {
		$SORT_DISABLE = "false";
		$SORT_CHECKED = "checked=\"checked\"";
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
<script type="text/javascript" src="Sortable.min.js"></script>

<title>みんなの座席表マスター管理</title>

<script>
$(document).ready(function(){
	//GETパラメータ
	var getparam = "<?php print $GET_MASTER; ?>";
	//初期処理　開始
	$(".masterlist_sub_add").hide();
	$(".masterlist_sub_del").hide();
	if (getparam =="") {
		//基本設定をデフォルトにする
		var obj1 = $("#masterlist").find(".ml_name");
		$(obj1[0]).css("background-color","green");
		$(obj1[0]).css("color","white");
		var obj2 = $("#masterlist_sub_1").find(".mls_name");
		$(obj2[0]).css("background-color","green");
		$(obj2[0]).css("color","white");
		$("#detail_1").css("display","inline-block");
	} else {
		var obj1 = $("#masterlist").find(".ml_name");
		$(obj1[getparam-1]).css("background-color","green");
		$(obj1[getparam-1]).css("color","white");
		masterchange(getparam);
	}
	//初期処理　終わり

	//座席表へ
	$('#main').on('click',function(e){
		location.href = "main.php";
	});

	//マスタ選択時
	$('.ml_name').on('click',function(e){
		$(".ml_name").css("background-color","silver");
		$(".mls_name").css("background-color","silver");
		$(".ml_name").css("color","black");
		$(".mls_name").css("color","black");
		$(this).css("background-color","green");
		$(this).css("color","white");
		var ml_key = $(this).parent().find(".ml_key").text();
		masterchange(ml_key);
	});

	//マスタ選択時
	function masterchange(ml_key) {
		$(".masterlist_sub").hide();
		$(".detail").hide();
		$(".masterlist_sub_add").hide();
		$(".masterlist_sub_del").hide();
		$(".masterlist_sub_del").css("background","rgba(50, 50, 50, 1.0)");
		switch (ml_key) {
			case "1":
				$("#masterlist_sub_1").css("display","inline-block");
				var obj = $("#masterlist_sub_1").find(".mls_name");
				$(obj[0]).css("background-color","green");
				$(obj[0]).css("color","white");
				$("#detail_1").css("display","inline-block");
				break;
			case "2":
				$("#masterlist_sub_2").css("display","inline-block");
				$("#detail_EMPTY").css("display","inline-block");
				$("#masterlist_sub_add_2").show();
				$("#masterlist_sub_del_2").show();
				break;
			case "3":
				$("#masterlist_sub_3").css("display","inline-block");
				$("#detail_EMPTY").css("display","inline-block");
				$("#masterlist_sub_add_3").show();
				$("#masterlist_sub_del_3").show();
				break;
			case "4":
				$("#masterlist_sub_4").css("display","inline-block");
				$("#detail_EMPTY").css("display","inline-block");
				$("#masterlist_sub_add_4").show();
				$("#masterlist_sub_del_4").show();
				break;
			case "5":
				$("#masterlist_sub_5").css("display","inline-block");
				$("#detail_EMPTY").css("display","inline-block");
				$("#masterlist_sub_add_5").show();
				$("#masterlist_sub_del_5").show();
				break;
		}
		return;
	}

	//リスト選択時
	$('.mls_row').on('click',function(e){
		$(".mls_name").css("background-color","silver");
		$(".mls_name").css("color","black");
		$(this).find(".mls_name").css("background-color","green");
		$(this).find(".mls_name").css("color","white");
		var mls_id = $(this).parent().attr("ID");
		switch (mls_id) {
			case "masterlist_sub_2":
				$("#detail_EMPTY").hide();
				$("#detail_2").css("display","inline-block");
				$("#masterlist_sub_del_2").css("background","rgba(256, 10, 10, 1.0)");
				var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_FLOOR";
				var s_key = $(this).find(".mls_key").text();
				$.ajax({
					url:'./mastergetdata.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						dataType:"json",
						timespan:1000,
					}
				})
				.done( (data) => {
					var data = $.parseJSON(data);
					$("#detail_2_f001").val(data["FIELD001"]);
					$("#detail_2_f002").val(data["FIELD002"]);
					$("#detail_2_f003").val(data["FIELD003"]);
					$("#detail_2_f004").val(parseInt(data["FIELD004"]));
					$("#detail_2_f005").val(parseInt(data["FIELD005"]));
				})
				.fail( (data) => {
					alert('読み込みが失敗しました。:' + data);
				});
				break;
			case "masterlist_sub_3":
				$("#detail_EMPTY").hide();
				$("#detail_3").css("display","inline-block");
				$("#masterlist_sub_del_3").css("background","rgba(256, 10, 10, 1.0)");
				var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
				var s_key = $(this).find(".mls_key").text();
				$.ajax({
					url:'./mastergetdata.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						dataType:"json",
						timespan:1000,
					}
				})
				.done( (data) => {
					var data = $.parseJSON(data);
					$("#detail_3_f001").val(data["FIELD001"]);
					$("#detail_3_f002").val(data["FIELD002"]);
					$("#detail_3_f003").val(data["FIELD003"]);
					$("#detail_3_f004").val(data["FIELD004"]);
					$("#detail_3_f005").val(data["FIELD005"]);
				})
				.fail( (data) => {
					alert('読み込みが失敗しました。:' + data);
				});
				break;
			case "masterlist_sub_4":
				$("#detail_EMPTY").hide();
				$("#detail_4").css("display","inline-block");
				$("#masterlist_sub_del_4").css("background","rgba(256, 10, 10, 1.0)");
				var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
				var s_key = $(this).find(".mls_key").text();
				$.ajax({
					url:'./mastergetdata.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						dataType:"json",
						timespan:1000,
					}
				})
				.done( (data) => {
					var data = $.parseJSON(data);
					$("#detail_4_f001").val(data["FIELD001"]);
					$("#detail_4_f002").val(data["FIELD002"]);
					$("#detail_4_f003").val(data["FIELD003"]);
					$("#detail_4_f004").val(data["FIELD004"]);
					$("#detail_4_f005").val(data["FIELD005"]);
				})
				.fail( (data) => {
					alert('読み込みが失敗しました。:' + data);
				});
				break;
			case "masterlist_sub_5":
				$("#detail_EMPTY").hide();
				$("#detail_5").css("display","inline-block");
				$("#masterlist_sub_del_5").css("background","rgba(256, 10, 10, 1.0)");
				var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
				var s_key = $(this).find(".mls_key").text();
				$.ajax({
					url:'./mastergetdata.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						dataType:"json",
						timespan:1000,
					}
				})
				.done( (data) => {
					var data = $.parseJSON(data);
					$("#detail_5_f001").val(data["FIELD001"]);
					$("#detail_5_f002").val(data["FIELD002"]);
					$("#detail_5_f003").val(data["FIELD003"]);
					$("#detail_5_f004").val(data["FIELD004"]);
					$("#detail_5_f005").val(data["FIELD005"]);
				})
				.fail( (data) => {
					alert('読み込みが失敗しました。:' + data);
				});
				break;
		}
		e.stopPropagation();
	});

	//追加ボタン押下時　フロアーマスタ
	$('#masterlist_sub_add_2').on('click',function(e){
		var obj2 = $("#masterlist_sub_2").find(".mls_name");
		$(obj2).css("background-color","silver");
		$(obj2).css("color","black");
		$("#detail_EMPTY").hide();
		$("#detail_2").css("display","inline-block");
		$("#detail_2_f001").val("");
		$("#detail_2_f002").val("");
		$("#detail_2_f003").val("");
		$("#detail_2_f004").val("1152");
		$("#detail_2_f005").val("700");
	});

	//削除ボタン押下時　フロアーマスタ
	$('#masterlist_sub_del_2').on('click',function(e){
		if(confirm('本当に削除しますか？')){
			/*　OKの時の処理 */
			var s_key = "";
			$("#masterlist_sub_2").find(".mls_name").each(function(i, elem) {
    			var color = $(elem).css("background-color");
				if (color=="rgb(0, 128, 0)") {
					s_key = $(elem).parent().find(".mls_key").text();
				}
			});
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_FLOOR";
			$.ajax({
					url:'./masterdelete.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
					}
				})
				.done( (data) => {
					alert('削除しました');
					location.href = "master.php?master=2";
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
		}		
	});

	//追加ボタン押下時　状態マスタ（座席）
	$('#masterlist_sub_add_3').on('click',function(e){
		var obj = $("#masterlist_sub_3").find(".mls_name");
		$(obj).css("background-color","silver");
		$(obj).css("color","black");
		$("#detail_EMPTY").hide();
		$("#detail_3").css("display","inline-block");
		$("#detail_3_f001").val("");
		$("#detail_3_f002").val("1");
		$("#detail_3_f003").val("");
		$("#detail_3_f004").val("");
		$("#detail_3_f005").val("");
	});

	//削除ボタン押下時　状態マスタ（座席）
	$('#masterlist_sub_del_3').on('click',function(e){
		if(confirm('本当に削除しますか？')){
			/*　OKの時の処理 */
			var s_key = "";
			$("#masterlist_sub_3").find(".mls_name").each(function(i, elem) {
    			var color = $(elem).css("background-color");
				if (color=="rgb(0, 128, 0)") {
					s_key = $(elem).parent().find(".mls_key").text();
				}
			});
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$.ajax({
					url:'./masterdelete.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
					}
				})
				.done( (data) => {
					alert('削除しました');
					location.href = "master.php?master=3";
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
		}		
	});

	//追加ボタン押下時　状態マスタ（施設）
	$('#masterlist_sub_add_4').on('click',function(e){
		var obj = $("#masterlist_sub_4").find(".mls_name");
		$(obj).css("background-color","silver");
		$(obj).css("color","black");
		$("#detail_EMPTY").hide();
		$("#detail_4").css("display","inline-block");
		$("#detail_4_f001").val("");
		$("#detail_4_f002").val("2");
		$("#detail_4_f003").val("");
		$("#detail_4_f004").val("");
		$("#detail_4_f005").val("");
	});

	//削除ボタン押下時　状態マスタ（施設）
	$('#masterlist_sub_del_4').on('click',function(e){
		if(confirm('本当に削除しますか？')){
			/*　OKの時の処理 */
			var s_key = "";
			$("#masterlist_sub_4").find(".mls_name").each(function(i, elem) {
    			var color = $(elem).css("background-color");
				if (color=="rgb(0, 128, 0)") {
					s_key = $(elem).parent().find(".mls_key").text();
				}
			});
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$.ajax({
					url:'./masterdelete.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
					}
				})
				.done( (data) => {
					alert('削除しました');
					location.href = "master.php?master=4";
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
		}		
	});

	//追加ボタン押下時　状態マスタ（その他）
	$('#masterlist_sub_add_5').on('click',function(e){
		var obj = $("#masterlist_sub_5").find(".mls_name");
		$(obj).css("background-color","silver");
		$(obj).css("color","black");
		$("#detail_EMPTY").hide();
		$("#detail_5").css("display","inline-block");
		$("#detail_5_f001").val("");
		$("#detail_5_f002").val("3");
		$("#detail_5_f003").val("");
		$("#detail_5_f004").val("");
		$("#detail_5_f005").val("");
	});

	//削除ボタン押下時　状態マスタ（その他）
	$('#masterlist_sub_del_5').on('click',function(e){
		if(confirm('本当に削除しますか？')){
			/*　OKの時の処理 */
			var s_key = "";
			$("#masterlist_sub_5").find(".mls_name").each(function(i, elem) {
    			var color = $(elem).css("background-color");
				if (color=="rgb(0, 128, 0)") {
					s_key = $(elem).parent().find(".mls_key").text();
				}
			});
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$.ajax({
					url:'./masterdelete.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
					}
				})
				.done( (data) => {
					alert('削除しました');
					location.href = "master.php?master=5";
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
		}		
	});
	//共通設定の更新
	$('#detail_1_upd').on('click',function(e){
		var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_COMMON";
		var s_key = "1";
		var s_f002 = $("#detail_1_f002").val();
		var s_f003 = "";
		var s_f004 = "";
		var s_f005 = "";
		$.ajax({
			url:'./masterupdate.php',
			type:'POST',
			data:{
				's_tablename':s_tablename,
				's_key'      :s_key,
				's_f002'     :s_f002,
				's_f003'     :s_f003,
				's_f004'     :s_f004,
				's_f005'     :s_f005,
			}
		})
		.done( (data) => {
			alert('更新しました');
			location.href = "master.php?master=1";
		})
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});
	//フロアーマスタの更新
	$('#detail_2_upd').on('click',function(e){
		var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_FLOOR";
		var s_key = $("#detail_2_f001").val();
		var s_f002 = $("#detail_2_f002").val();
		var s_f003 = $("#detail_2_f003").val();
		var s_f004 = $("#detail_2_f004").val() + "px";
		var s_f005 = $("#detail_2_f005").val() + "px";
		var errmsg = "";
		//入力チェック
		if (s_f003=="") { errmsg = errmsg + "フロアー名:名称を入力してください\n"; }
		if (s_f004=="px") { errmsg = errmsg + "フロアーの広さ（横）:数値を入力してください\n"; }
		if (s_f005=="px") { errmsg = errmsg + "フロアーの広さ（縦）:数値を入力してください\n"; }
		if (Math.round(s_f004) === s_f004) { errmsg = errmsg + "フロアーの広さ（横）:整数を入力してください\n"; }
		if (Math.round(s_f005) === s_f005) { errmsg = errmsg + "フロアーの広さ（縦）:整数を入力してください\n"; }
		if (parseInt(s_f004) < 1000) { errmsg = errmsg + "フロアーの広さ（横）:1000以上の値を入力してください\n"; }
		if (parseInt(s_f005) < 500) { errmsg = errmsg + "フロアーの広さ（縦）:500以上の値を入力してください\n"; }
		if (errmsg != "") { 
			alert(errmsg); 
			return;
		}
		$.ajax({
			url:'./masterupdate.php',
			type:'POST',
			data:{
				's_tablename':s_tablename,
				's_key'      :s_key,
				's_f002'     :s_f002,
				's_f003'     :s_f003,
				's_f004'     :s_f004,
				's_f005'     :s_f005,
			}
		})
		.done( (data) => {
			alert('更新しました');
			location.href = "master.php?master=2";
		})
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});
	//状態マスタ（座席）の更新
	$('#detail_3_upd').on('click',function(e){
		var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
		var s_key = $("#detail_3_f001").val();
		var s_f002 = $("#detail_3_f002").val();
		var s_f003 = $("#detail_3_f003").val();
		var s_f004 = $("#detail_3_f004").val();
		var s_f005 = $("#detail_3_f005").val();
		var errmsg = "";
		//入力チェック
		if (s_f004=="") { errmsg = errmsg + "状態名:名称を入力してください\n"; }
		if (s_f005=="") { errmsg = errmsg + "表示色:色を選択してください\n"; }
		if (errmsg != "") { 
			alert(errmsg); 
			return;
		}
		$.ajax({
			url:'./masterupdate.php',
			type:'POST',
			data:{
				's_tablename':s_tablename,
				's_key'      :s_key,
				's_f002'     :s_f002,
				's_f003'     :s_f003,
				's_f004'     :s_f004,
				's_f005'     :s_f005,
			}
		})
		.done( (data) => {
			alert('更新しました');
			location.href = "master.php?master=3";
		})
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});
	//状態マスタ（施設）の更新
	$('#detail_4_upd').on('click',function(e){
		var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
		var s_key = $("#detail_4_f001").val();
		var s_f002 = $("#detail_4_f002").val();
		var s_f003 = $("#detail_4_f003").val();
		var s_f004 = $("#detail_4_f004").val();
		var s_f005 = $("#detail_4_f005").val();
		var errmsg = "";
		//入力チェック
		if (s_f004=="") { errmsg = errmsg + "状態名:名称を入力してください\n"; }
		if (s_f005=="") { errmsg = errmsg + "表示色:色を選択してください\n"; }
		if (errmsg != "") { 
			alert(errmsg); 
			return;
		}
		$.ajax({
			url:'./masterupdate.php',
			type:'POST',
			data:{
				's_tablename':s_tablename,
				's_key'      :s_key,
				's_f002'     :s_f002,
				's_f003'     :s_f003,
				's_f004'     :s_f004,
				's_f005'     :s_f005,
			}
		})
		.done( (data) => {
			alert('更新しました');
			location.href = "master.php?master=4";
		})
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});
	//状態マスタ（その他）の更新
	$('#detail_5_upd').on('click',function(e){
		var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
		var s_key = $("#detail_5_f001").val();
		var s_f002 = $("#detail_5_f002").val();
		var s_f003 = $("#detail_5_f003").val();
		var s_f004 = $("#detail_5_f004").val();
		var s_f005 = $("#detail_5_f005").val();
		var errmsg = "";
		//入力チェック
		if (s_f004=="") { errmsg = errmsg + "状態名:名称を入力してください\n"; }
		if (s_f005=="") { errmsg = errmsg + "表示色:色を選択してください\n"; }
		if (errmsg != "") { 
			alert(errmsg); 
			return;
		}
		$.ajax({
			url:'./masterupdate.php',
			type:'POST',
			data:{
				's_tablename':s_tablename,
				's_key'      :s_key,
				's_f002'     :s_f002,
				's_f003'     :s_f003,
				's_f004'     :s_f004,
				's_f005'     :s_f005,
			}
		})
		.done( (data) => {
			alert('更新しました');
			location.href = "master.php?master=5";
		})
		.fail( (data) => {
			alert('更新が失敗しました。:' + data);
		});
	});
	//チェックボックス　並べ替え　オン／オフ
	$('.sortchk').on('click',function(e){
		var s_mode = "";
		$("#masterlist").find(".ml_name").each(function(i, elem) {
    			var color = $(elem).css("background-color");
				if (color=="rgb(0, 128, 0)") {
					s_mode = $(elem).parent().find(".ml_key").text();
				}
		});
		var s_sort = "0";
		if($('.sortchk').is(':checked')) {
			s_sort = "1";
		}
		location.href = "master.php?master=" + s_mode + "&sort=" + s_sort;
	});
	//リストをドラッグ移動で並べ替えできるようにする　フロアーマスタ
	let sortable_sub2 = Sortable.create(masterlist_sub_2, {
		group: "masterlist_sub_2",
		animation: 100,
		disabled: <?php print $SORT_DISABLE; ?>,
		onEnd: function (evt) {
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_FLOOR";
			$("#masterlist_sub_2").find(".mls_key").each(function(i, elem) {
				var s_sort = i+1;
				var s_key = $(this).text();
				$.ajax({
					url:'./sortchange.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						's_sort'     :s_sort,
					}
				})
				.done( (data) => {
					//alert('ok:' + data);
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
			});
		},
	});
	//リストをドラッグ移動で並べ替えできるようにする　状態マスタ（座席）
	let sortable_sub3 = Sortable.create(masterlist_sub_3, {
		group: "masterlist_sub_3",
		animation: 100,
		disabled: <?php print $SORT_DISABLE; ?>,
		onEnd: function (evt) {
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$("#masterlist_sub_3").find(".mls_key").each(function(i, elem) {
				var s_sort = i+1;
				var s_key = $(this).text();
				$.ajax({
					url:'./sortchange.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						's_sort'     :s_sort,
					}
				})
				.done( (data) => {
					//alert('ok:' + data);
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
			});
		},
	});
	//リストをドラッグ移動で並べ替えできるようにする　状態マスタ（施設）
	let sortable_sub4 = Sortable.create(masterlist_sub_4, {
		group: "masterlist_sub_4",
		animation: 100,
		disabled: <?php print $SORT_DISABLE; ?>,
		onEnd: function (evt) {
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$("#masterlist_sub_4").find(".mls_key").each(function(i, elem) {
				var s_sort = i+1;
				var s_key = $(this).text();
				$.ajax({
					url:'./sortchange.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						's_sort'     :s_sort,
					}
				})
				.done( (data) => {
					//alert('ok:' + data);
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
			});
		},
	});

	//リストをドラッグ移動で並べ替えできるようにする　状態マスタ（その他）
	let sortable_sub5 = Sortable.create(masterlist_sub_5, {
		group: "masterlist_sub_5",
		animation: 100,
		disabled: <?php print $SORT_DISABLE; ?>,
		onEnd: function (evt) {
			var s_tablename= "T_" + "<?php print $CONST_GRP_NO;?>" + "_M_STATUS";
			$("#masterlist_sub_5").find(".mls_key").each(function(i, elem) {
				var s_sort = i+1;
				var s_key = $(this).text();
				$.ajax({
					url:'./sortchange.php',
					type:'POST',
					data:{
						's_tablename':s_tablename,
						's_key'      :s_key,
						's_sort'     :s_sort,
					}
				})
				.done( (data) => {
					//alert('ok:' + data);
				})
				.fail( (data) => {
					alert('更新が失敗しました。:' + data);
				});
			});
		},
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
	height: 15px ;
	padding-left:  0;
	padding-right: 0;
	margin: 0 auto;
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
#main {
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
#sortmode {
	font-size : 120% ;
	margin-left: 180px;
	width : 400px ;
	height : 20px ;
	text-align : center;
	position: relative;
}
.sortchk {
	margin-top: 5px;
}

.bg-white {
	background: url('/images/hogan.png') repeat;
	position: relative;
	padding-left:  0;
	padding-right: 0;
	width : 1152px ;
	margin: 0 auto;
}

#base {
	width : 100% ;
	height : 100% ;
}

#masterlist {
	width : 200px ;
	height : 700px ;
	display : inline-block;
	background: rgba(125, 125, 125, 1.0);
	border : 1px solid black
	overflow-y:scroll;
	font-size : 120% ;
}
.ml_row {
	width : 200px ;
	height : 40px ;
	color :blue;
	font-style : bold;
	margin:  0 auto;
}
.ml_name {
	background: silver;
	color: black;
	font-size : 120% ;
	display : block ;
	float : left ;
	width : 195px ;
	height : 35px ;
	padding-left : 5px;
	border-radius: 5px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
}
#listframe {
	position: relative;
	width : 350px ;
	height : 700px ;
	display : inline-block;
	background: rgba(125, 125, 125, 1.0);
	vertical-align: top;
}
.masterlist_sub {
	position: relative;
	width : 350px ;
	height : 650px ;
	display : inline-block;
	background: rgba(125, 125, 125, 1.0);
	color: white;
	border : 1px solid black
	overflow-y:scroll;
	font-size : 120% ;
	display: none;
}
.masterlist_sub_add {
	text-align : center ;
	background: darkslateblue;
	color: white;
	font-size : 120% ;
	display : inline-block;
	width : 200px ;
	height : 40px ;
	margin :  2px auto;
	padding-left : 5px;
	padding-top : 5px;
	border-radius: 5px;
}
.masterlist_sub_del {
	text-align : center ;
	background: rgba(255, 10, 10, 1.0);
	color: white;
	font-size : 120% ;
	display : inline-block;
	width : 135px ;
	height : 40px ;
	margin :  2px auto;
	padding-left : 5px;
	padding-top : 5px;
	border-radius: 5px;
}
.mls_row {
	position: relative;
	width : 350px ;
	height : 40px ;
	color :blue;
	font-style : bold;
	margin:  0 auto;
}
.mls_name {
	background: silver;
	color: black;
	font-size : 120% ;
	display : block ;
	float : left ;
	width : 345px ;
	height : 35px ;
	margin :  2px auto;
	padding-left : 5px;
	border-radius: 5px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
}
.detail {
	width : 590px ;
	height : 700px ;
	display : inline-block;
	background: rgba(125, 125, 125, 1.0);
	color: white;
	border : 1px solid black
	overflow-y:scroll;
	display: none;
}
.mld_row {
	position: relative;
	width : 350px ;
	height : 40px ;
	color :blue;
	font-style : bold;
}
.mld_name {
	background: silver ;
	color: black;
	font-size : 120% ;
	display : inline-block;
	float : left ;
	width : 585px ;
	height : 35px ;
	margin :  0 auto;
	padding-left : 5px;
	padding-top : 5px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
}
.info_title {
	display : inline-block;
	width : 200px ;
	height : 25px ;
}
.info_btn {
	text-alogn : left ;
	background: rgba(255, 10, 10, 1.0);
	color: white;
	font-size : 120% ;
	display : inline-block;
	float : left ;
	width : 585px ;
	height : 25px ;
	margin :  2px auto;
	padding-left : 5px;
	padding-top : 5px;
	border-radius: 5px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	-webkit-text-overflow: ellipsis;
	-o-text-overflow: ellipsis;
}
</style>
</head>
<body>
	<div id="headline">
		<div id="logout" admin="<?php print $admin; ?>">
			<div><?php	print $message." <a href='/logout.php'>ログアウトはこちら。</a>";	?></div>
		</div>
	</div>
	<div id="floorarea">
		<div id="main" style="display:<?php print $mente_disp; ?>;">
			座席表へ
		</div>
		<div id="sortmode">
			<label><input type="checkbox" class="sortchk" value="1" name="sortchk" <?php print $SORT_CHECKED; ?> ></input> ドラッグ＆ドロップで名称の並べ替えができるようにする</label>
		</div>
	</div>
	<div class="bg-white">
		<div id="base">
			<div id="masterlist">
				<?php 
				print "<div class=\"ml_row\">";
				print "<div class=\"ml_key\" style=\"display:none;\">1</div>";
				print "<div class=\"ml_name\">".htmlspecialchars($ML_NAME[1])."</div>";
				print "</div>"."\n";
				print "<div class=\"ml_row\">";
				print "<div class=\"ml_key\" style=\"display:none;\">2</div>";
				print "<div class=\"ml_name\">".htmlspecialchars($ML_NAME[2])."</div>";
				print "</div>"."\n";
				print "<div class=\"ml_row\">";
				print "<div class=\"ml_key\" style=\"display:none;\">3</div>";
				print "<div class=\"ml_name\">".htmlspecialchars($ML_NAME[3])."</div>";
				print "</div>"."\n";
				print "<div class=\"ml_row\">";
				print "<div class=\"ml_key\" style=\"display:none;\">4</div>";
				print "<div class=\"ml_name\">".htmlspecialchars($ML_NAME[4])."</div>";
				print "</div>"."\n";
				print "<div class=\"ml_row\">";
				print "<div class=\"ml_key\" style=\"display:none;\">5</div>";
				print "<div class=\"ml_name\">".htmlspecialchars($ML_NAME[5])."</div>";
				print "</div>"."\n";
				?>
			</div>
			<div id="listframe">
				<div id="masterlist_sub_1" class="masterlist_sub" style="display:inline-block;">
					<?php 
					print "<div class=\"mls_row\">";
					print "<div class=\"mls_key\" style=\"display:none;\">1</div>";
					print "<div class=\"mls_name\">共通設定</div>";
					print "</div>"."\n";
					?>
				</div>
				<div id="masterlist_sub_2" class="masterlist_sub">
				<?php 
					$fstmt = $T_99999_M_FLOOR->getList();
					while($frow = $fstmt->fetch(PDO::FETCH_NUM)) {
						$f001 = htmlspecialchars($frow[0]);
						$f002 = htmlspecialchars($frow[1]);
						$f003 = htmlspecialchars($frow[2]);
						$f004 = htmlspecialchars($frow[3]);
						$f005 = htmlspecialchars($frow[4]);
						print "<div class=\"mls_row\">";
						print "<div class=\"mls_key\" style=\"display:none;\">".$f001."</div>";
						print "<div class=\"mls_name\">".$f003."</div>";
						print "</div>"."\n";
					}
				?>
				</div>
				<div id="masterlist_sub_3" class="masterlist_sub">
				<?php 
					$mstmt = $T_99999_M_STATUS->getList();
					while($mrow = $mstmt->fetch(PDO::FETCH_NUM)) {
						$mf001 = htmlspecialchars($mrow[0]);
						$mf002 = htmlspecialchars($mrow[1]);
						$mf003 = htmlspecialchars($mrow[2]);
						$mf004 = htmlspecialchars($mrow[3]);
						$mf005 = htmlspecialchars($mrow[4]);
						if ($mf002 == "1") {
							print "<div class=\"mls_row\">";
							print "<div class=\"mls_key\" style=\"display:none;\">".$mf001."</div>";
							print "<div class=\"mls_name\">".$mf004."</div>";
							print "</div>"."\n";
						}
					}
				?>
				</div>
				<div id="masterlist_sub_4" class="masterlist_sub">
				<?php 
					$mstmt = $T_99999_M_STATUS->getList();
					while($mrow = $mstmt->fetch(PDO::FETCH_NUM)) {
						$mf001 = htmlspecialchars($mrow[0]);
						$mf002 = htmlspecialchars($mrow[1]);
						$mf003 = htmlspecialchars($mrow[2]);
						$mf004 = htmlspecialchars($mrow[3]);
						$mf005 = htmlspecialchars($mrow[4]);
						if ($mf002 == "2") {
							print "<div class=\"mls_row\">";
							print "<div class=\"mls_key\" style=\"display:none;\">".$mf001."</div>";
							print "<div class=\"mls_name\">".$mf004."</div>";
							print "</div>"."\n";
						}
					}
				?>
				</div>
				<div id="masterlist_sub_5" class="masterlist_sub">
				<?php 
					$mstmt = $T_99999_M_STATUS->getList();
					while($mrow = $mstmt->fetch(PDO::FETCH_NUM)) {
						$mf001 = htmlspecialchars($mrow[0]);
						$mf002 = htmlspecialchars($mrow[1]);
						$mf003 = htmlspecialchars($mrow[2]);
						$mf004 = htmlspecialchars($mrow[3]);
						$mf005 = htmlspecialchars($mrow[4]);
						if ($mf002 == "3") {
							print "<div class=\"mls_row\">";
							print "<div class=\"mls_key\" style=\"display:none;\">".$mf001."</div>";
							print "<div class=\"mls_name\">".$mf004."</div>";
							print "</div>"."\n";
						}
					}
				?>
				</div>
				<div id="masterlist_sub_add_2" class="masterlist_sub_add">✚　新規追加する</div>
				<div id="masterlist_sub_add_3" class="masterlist_sub_add">✚　新規追加する</div>
				<div id="masterlist_sub_add_4" class="masterlist_sub_add">✚　新規追加する</div>
				<div id="masterlist_sub_add_5" class="masterlist_sub_add">✚　新規追加する</div>
				<div id="masterlist_sub_del_2" class="masterlist_sub_del">✖　削除する</div>
				<div id="masterlist_sub_del_3" class="masterlist_sub_del">✖　削除する</div>
				<div id="masterlist_sub_del_4" class="masterlist_sub_del">✖　削除する</div>
				<div id="masterlist_sub_del_5" class="masterlist_sub_del">✖　削除する</div>
			</div>
			<div id="detail_1" class="detail">
				<?php 
					$cstmt = $T_99999_M_COMMON->getData(1);
					while($crow = $cstmt->fetch(PDO::FETCH_NUM)) {
						$cf001 = htmlspecialchars($crow[0]);
						$cf002 = htmlspecialchars($crow[1]);
					}
					print "<div class=\"mld_row\">";
					print "<div class=\"mld_key\" style=\"display:none;\">1</div>";
					print "<div class=\"mld_name\">";
					print "<label class=\"info_title\">画面をリロードする間隔（秒）</label>";
					print "<input class=\"info_input\" id=\"detail_1_f002\" type=\"text\" value=\"".$cf002."\" maxlength=\"3\" style=\"width:40px;\" />　秒";
					print "</div>";
					print "</div>"."\n";
					print "<div class=\"mld_row\">";
					print "<div id=\"detail_1_upd\" class=\"info_btn\">入力内容を確定する</div>";
					print "</div>"."\n";
				?>
			</div>
			<div id="detail_2" class="detail">
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※フロアー名</label>
						<input class="info_input" id="detail_2_f003" type="text" value="" maxlength="50" style="width:370px;" />
					</div>
				</div>
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※フロアーの広さ（横） 1000以上</label>
						<input class="info_input" id="detail_2_f004" type="text" value="" maxlength="4" style="width:40px;" />　ピクセル
					</div>
				</div>
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※フロアーの広さ（縦） 500以上</label>
						<input class="info_input" id="detail_2_f005" type="text" value="" maxlength="4" style="width:40px;" />　ピクセル
					</div>
				</div>
				<div class="mld_row">
					<div id="detail_2_upd" class="info_btn">入力内容を確定する</div>
				</div>
				<input id="detail_2_f001" type="hidden" value="" />
				<input id="detail_2_f002" type="hidden" value="" />
				<input id="detail_2_f005" type="hidden" value="" />
			</div>
			<div id="detail_3" class="detail">
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※状態名</label>
						<input class="info_input" id="detail_3_f004" type="text" value="" maxlength="50" style="width:370px;" />
					</div>
				</div>
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※表示色</label>
						<input class="info_input" id="detail_3_f005" type="color" style="width:100px;" />
					</div>
				</div>
				<div class="mld_row">
					<div id="detail_3_upd" class="info_btn">入力内容を確定する</div>
				</div>
				<input id="detail_3_f001" type="hidden" value="" />
				<input id="detail_3_f002" type="hidden" value="1" />
				<input id="detail_3_f003" type="hidden" value="" />
			</div>
			<div id="detail_4" class="detail">
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※状態名</label>
						<input class="info_input" id="detail_4_f004" type="text" value="" maxlength="50" style="width:370px;" />
					</div>
				</div>
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※表示色</label>
						<input class="info_input" id="detail_4_f005" type="color" style="width:100px;" />
					</div>
				</div>
				<div class="mld_row">
					<div id="detail_4_upd" class="info_btn">入力内容を確定する</div>
				</div>
				<input id="detail_4_f001" type="hidden" value="" />
				<input id="detail_4_f002" type="hidden" value="2" />
				<input id="detail_4_f003" type="hidden" value="" />
			</div>
			<div id="detail_5" class="detail">
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※状態名</label>
						<input class="info_input" id="detail_5_f004" type="text" value="" maxlength="50" style="width:370px;" />
					</div>
				</div>
				<div class="mld_row">
					<div class="mld_key" style="display:none;"></div>
					<div class="mld_name">
						<label class="info_title">※表示色</label>
						<input class="info_input" id="detail_5_f005" type="color" style="width:100px;" />
					</div>
				</div>
				<div class="mld_row">
					<div id="detail_5_upd" class="info_btn">入力内容を確定する</div>
				</div>
				<input id="detail_5_f001" type="hidden" value="" />
				<input id="detail_5_f002" type="hidden" value="3" />
				<input id="detail_5_f003" type="hidden" value="" />
			</div>
			<div id="detail_EMPTY" class="detail">
			</div>
		</div>
	</div>
</body>

</html>
