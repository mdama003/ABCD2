<?php
/* Modifications
2021-01-04 fho4abcd Removed login encryption
2021-01-04 fh04abcd Corrected "languaje" --> language
2021-02-07 fho4abcd Configured Logo url now used without prefix and strip. Works now according to wiki
2021-02-27 fho4abcd png favicon works better in bookmarks.
2021-08-10 fho4abcd Do not crash if first language file (from the browser) is missing. Visible message if no file found
*/
session_start();
$_SESSION=array();
unset($_SESSION["db_path"]);
include("central/config.php");
include("$app_path/common/get_post.php");
$new_window=time();
//foreach ($arrHttp as $var=>$value) echo "$var = $value<br>";
$lang_config=$lang;

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

if (isset($_SESSION["lang"])){
	$arrHttp["lang"]=$_SESSION["lang"];
	$lang=$_SESSION["lang"];
}else{
	$arrHttp["lang"]=$lang;
	$_SESSION["lang"]=$lang;
}

include ("$app_path/lang/admin.php");
include ("$app_path/lang/lang.php");
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang;?>">

<head profile="http://www.w3.org/2005/10/profile">
		<meta http-equiv="Expires" content="-1" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $meta_encoding;?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<meta http-equiv="Content-Language" content="pt-br" />
		<meta name="robots" content="all" />
		<meta http-equiv="keywords" content="" />
		<meta http-equiv="description" content="" />
        <link rel="shortcut icon" href="/favicon.png" type="image/png" />

		<title>ABCD</title>
		<!-- Stylesheets -->
		<link rel="stylesheet" rev="stylesheet" href="assets/css/template.css?<?php echo time(); ?>" type="text/css" media="screen"/>

		<!--FontAwesome-->
		<link href="../../assets/css/all.min.css" rel="stylesheet"> 
		<!--[if IE]>
			<link rel="stylesheet" rev="stylesheet" href="<?php echo $app_path?>/css/bugfixes_ie.css" type="text/css" media="screen"/>
		<![endif]-->
		<!--[if IE 6]>
			<link rel="stylesheet" rev="stylesheet" href="<?php echo $app_path?>/css/bugfixes_ie6.css" type="text/css" media="screen"/>
		<![endif]-->
<style type="text/css">
	html, body {
		height: 100vh;
		margin: 0;
	}
	.middle {
		height: 70vh;
	}
</style>

<script src=<?php echo $app_path?>/dataentry/js/lr_trim.js></script>

<script language=javascript>

document.onkeypress =
	function (evt) {
			var c = document.layers ? evt.which
	       		: document.all ? event.keyCode
	       		: evt.keyCode;
			if (c==13) Enviar()
			return true;
	}

function UsuarioNoAutorizado(){
	alert("<?php echo $msgstr["menu_noau"]?>")
}
function CambiarClave(){
	document.cambiarPass.login.value=Trim(document.administra.login.value)
	document.cambiarPass.password.value=Trim(document.administra.password.value)
	ix=document.administra.lang.selectedIndex
	document.cambiarPass.lang.value=document.administra.lang.options[ix].value
	ix=document.administra.db_path.value
	document.cambiarPass.db_path.value=document.administra.db_path.value
	document.cambiarPass.submit()
}
function Enviar(){
	login=Trim(document.administra.login.value)
	password=Trim(document.administra.password.value)
	if (login=="" || password==""){
		alert("<?php echo $msgstr["datosidentificacion"]?>")
		return
	}else{
		if (document.administra.newindow.checked){
			new_window=new Date()
			document.administra.target=new_window;
			ancho=screen.availWidth-15
			alto=(screen.availHeight||screen.height) -50
			msgwin=window.open("",new_window,"menubar=no, toolbar=no, location=no, scrollbars=yes, status=yes, resizable=yes, top=0, left=0, width="+ancho+", height="+alto)
			msgwin.focus()
		} else{
			document.administra.target=""
		}
		document.administra.submit()
	}
}

</script>
</head>
<body>
	<div class="heading">
		<div class="institutionalInfo">
			<img src=<?php	if (isset($logo))
						echo "$logo" ;
					else
						echo "assets/images/logoabcd.png";
				 ?>
			><h1><?php echo $institution_name?></h1>
		</div>
		<div class="userInfo"><?php echo $meta_encoding?></div>

		<div class="spacer">&#160;</div>
	</div>
	<div class="sectionInfo">
		<div class="breadcrumb"></div>
		<div class="actions"></div>
		<div class="spacer">&#160;</div>
	</div>
<form name="administra" onsubmit="javascript:return false" method="post" action="<?php echo $app_path?>/common/inicio.php">
<input type="hidden" name="Opcion" value="admin">
<input type="hidden" name="cipar" value="acces.par">
	<div class="middle login">
		<div class="loginForm">

		<div class="boxContent">
<?php
if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<div class=\"helper alert\">".$msgstr["menu_noau"]."
			</div>
		";
}
if (isset($arrHttp["login"]) and $arrHttp["login"]=="P"){
		echo "
			<div class=\"helper success\">".$msgstr["pswchanged"]."
			</div>
		";
}
?>
		<div class="formRow">
			<label for="user"><?php echo $msgstr["userid"]?></label>
<?php
if (isset($arrHttp["login"]) and $arrHttp["login"]=="N"){
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"textEntry superTextEntry inputAlert\" onfocus=\"this.className = 'textEntry superTextEntry inputAlert textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry inputAlert';\" />\n";
}else{
		echo "
			<input type=\"text\" name=\"login\" id=\"user\" value=\"\" class=\"textEntry superTextEntry\" onfocus=\"this.className = 'textEntry superTextEntry textEntryFocus';\" onblur=\"this.className = 'textEntry superTextEntry';\" />\n";
}

?>
		</div>

		<div class="formRow">
			<label for="pwd"><?php echo $msgstr["password"]?></label>
			<input type="password" name="password" id="pwd" value="" class="textEntry superTextEntry" onfocus="this.className = 'textEntry superTextEntry textEntryFocus';" onblur="this.className = 'textEntry superTextEntry';" />
		   <?php if (isset($change_password) and $change_password=="Y") echo "<br><a href=javascript:CambiarClave()>". $msgstr["chgpass"]."</a>\n";?>
		</div>
		<div id="formRow3" class="formRow formRowFocus">
        <?php
        // Check if the language from the browser is present
        $a=$msg_path."lang/$lang/lang.tab";
        if (!file_exists($a)){
            // switch to configured language if browser language is not present
            echo "<div>".$msgstr["flang"].": ".$a."<br>";
            echo $msgstr["using_config"]." '".$lang_config."'<br>&nbsp;</div>";
            $lang=$lang_config;
        }
        // Check if the language file is present
        $a=$msg_path."lang/$lang/lang.tab";
        if (!file_exists($a)){
            echo "<div style='color:red'>".$msgstr["fatal"].": ".$msgstr["flang"].": ".$a."</div>";
            die;
        }
        ?>
			<label ><?php echo $msgstr["lang"]?></label> 
			<select name=lang class="textEntry singleTextEntry" onchange="this.submit()">
<?php

		$fp=file($a);
		$selected="";
		foreach ($fp as $value){
			$value=trim($value);
			if ($value!=""){
				$l=explode('=',$value);
				if ($l[0]!="lang"){
					if ($l[0]==$lang) $selected=" selected";
					echo "<option value=$l[0] $selected>".$msgstr[$l[0]]."</option>\n";
					$selected="";
				}
			}
		}
?>
			</select>
		</div>
		<div class="formRow"><br>
<?php
if (file_exists("dbpath.dat")){
	global $db_path;
	$fp=file("dbpath.dat");
	echo $msgstr["database_dir"].': <select class="textEntry singleTextEntry" name=db_path>\n';
	foreach ($fp as $value){
		if (trim($value)!=""){
			$v=explode('|',$value);
			$v[0]=trim($v[0]);
			echo "<option value=".trim($v[0]).">".$v[1]."\n";
		}

	}
	echo "</select>";
} else {
	echo '<input type="hidden" name="db_path" value="'.$db_path.'">';
}
?>
			<input type="checkbox" name="newindow" value=
<?php
if (isset($open_new_window) and $open_new_window=="Y")
	echo "Y checked";
else
	echo "N";
?> />
			<label for="setCookie" class="inline"><?php echo $msgstr["openwindow"]?></label>
		</div>
		<div class="formRow">
				<a href="javascript:Enviar()" class="bt-blue bt-sign">
					<?php echo $msgstr["entrar"]?> 
				</a>
		</div>
	</div>

</div>
</div>
</form>
<form name="cambiarPass" action="<?php echo $app_path?>/dataentry/change_password.php" method="post">
	<input type="hidden" name="login">
	<input type="hidden" name="password">
	<input type="hidden" name="lang">
	<input type="hidden" name="db_path">
	<input type="hidden" name="Opcion" value="chgpsw">
</form>

<?php include ("$app_path/common/footer.php");?>

