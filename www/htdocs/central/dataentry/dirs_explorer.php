<?php
/*
20210315 fho4abcd The destination form no longer fixed to "upload" but specified by option &targetForm=...&..
20210914 fho4abcd Standard header+ use div_helper+better indicator dr_path+standard divs+move function to sanitize code
*/
session_start();

if (!isset($_SESSION["permiso"])){
	header("Location: ../common/error_page.php") ;
}
include "dirs_explorer.class.php";
include("../common/get_post.php");

include("../config.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";
include("../common/header.php");
if (!isset($arrHttp["Opcion"])) $arrHttp["Opcion"]="";
$lang=$_SESSION["lang"];

include("../lang/admin.php");
include("../lang/dbadmin.php");
$db=$arrHttp["base"];
if (isset($_SESSION["permiso"]["CENTRAL_ALL"]) or isset($_SESSION["permiso"][$db."_CENTRAL_ALL"])
    or isset($_SESSION["permiso"][$db."_CENTRAL_CREC"]) or isset($_SESSION["permiso"][$db."_CENTRAL_EDREC"])){
}else{
	echo $msgstr["invalidright"];
	die;

}
echo "<body>";
include "../common/inc_div-helper.php";

// The default target form (where the selected folder will be set) for this script can be overridden by an an option
$targetForm="upload";// the default form used by other apps
if (isset($arrHttp["targetForm"]) and ($arrHttp["targetForm"] != "") ) $targetForm=$arrHttp["targetForm"];
$expl = new php_dirs_explorer();

//Now it's needed set FULL path of directory which should be seen
//root_dir - name of variable and it should be static!
// /home/shared_dir - full path of shared directory for *nix
// c:/shared_dir - full path of shared direcotory for win
if (isset($arrHttp["desde"]) and $arrHttp["desde"]=="dbcp"){
	$img_path=$db_path;
	$expl->Set("root_dir",$img_path);
	$name_path="";
}else{
	$arrHttp["desde"]="dataentry";
	if (file_exists($db_path.$arrHttp["base"]."/dr_path.def")){
		$def = parse_ini_file($db_path.$arrHttp["base"]."/dr_path.def");
		if (isset($def["ROOT"])){
			$img_path=trim($def["ROOT"]);
			$name_path="dr_path.def &rarr; ROOT";
		}else{
			$img_path=getenv("DOCUMENT_ROOT")."/bases/".$arrHttp["base"]."/";
			$name_path="[DOCUMENT_ROOT]"."/bases/".$arrHttp["base"]."/";
		}
	}else{
		$img_path=getenv("DOCUMENT_ROOT")."/bases/".$arrHttp["base"]."/";
		$name_path="[DOCUMENT_ROOT]"."/bases/".$arrHttp["base"]."/";
	}
	if (isset($arrHttp["root"]))$img_path.=$arrHttp["root"];
    if (!is_dir($img_path)){
		echo "<h3>".$name_path ." ".$msgstr["dirne"].".</h3> ";
		die;
	}
	$expl->Set("root_dir",$img_path);
}
if (!isset($arrHttp["source"])){
	$source="";
}else{
	$source=$arrHttp["source"];
}
if (!isset($arrHttp["path"])){
	$path="";
}else{
	$path=$arrHttp["path"];
}
$source= trim($path.$source);
if (trim($source)!=""){
	if (substr($source,0,1)=="/") $source=substr($source,1);
	if (substr($source,strlen($source),1)!="/") $source=$source."/";
}
?>
<div class="middle form">
<div class="formContent">
<input type=radio name=sel value=""
    onclick="window.opener.document.<?php echo $targetForm;?>.storein.value='<?php echo $source;?>'; window.opener.focus(); self.close()" >
    <?php echo $name_path;?>
<?php
//Now it's needed set path of icons
//icons_dir - name of variable and it should be static!
//icons/ - directory of icons
$expl->Set("icons_dir","img/dir_explorer/");


//Now it's needed to set files of icons for various file types
if (isset($arrHttp["mx"])){
	$types["db_add.png"]=array("mst");
}else{
	$types['image.gif'] = array ('jpg', 'gif','png','tif','bmp');
	$types['txt.gif'] = array ('txt','tab','dat','def','fdt','fmt','pft','fst','val','wks','beg','end','cfg','bat');
	$types['winamp.gif'] = array ('mp3');
	$types['mov.gif'] = array ('mov');
	$types['wmv.gif'] = array ('avi', 'mpeg','mpg');
	$types['rar.gif'] = array ('rar');
	$types['zip.gif'] = array ('zip');
	$types['doc.gif'] = array ('doc');
	$types['pdf.gif'] = array ('pdf');
	$types['excel.gif'] = array ('xls');
	$types['html.gif'] = array ('htm','html');
	#$types['exe.gif'] = array ('exe');
	#$types['mdb.gif'] = array ('mdb');
	$types['ppt.gif'] = array ('ppt');
	//types - name of variable and it should be static!
	//$types - array of icons files and file types
}
$expl->Set("types",$types);


//Now it's needed set file of icon for undefined file types
//un_icon - name of variable and it should be static!
//file.gif file of icon for undefined file types
$expl->Set("un_icon","unident.gif");

//Now it's needed to set file of icon for directory
//dir_icon - name of variable and it should be static!
//directory.gif file of icon for directories
$expl->Set("dir_icon","folder.gif");

if (isset($arrHttp["tag"]))
	$tag=$arrHttp["tag"];
else
	$tag="";

$expl->show_dirs($arrHttp["Opcion"],$img_path,$tag);
// Show dirs calls function Encabezamiento

reset($arrHttp);
echo "\n\n<form name=newfolder action=newfolder.php method=post>\n";
foreach ($arrHttp as $var=>$value){
	echo "<input type=hidden name=$var value=\"$value\">\n";
}
echo "<input type=hidden name=folder>\n";
echo "</form>";
die;

//==============function=========================
function Encabezamiento(){
global $tag,$msgstr,$arrHttp;

?>
<title><?php echo $msgstr["explore"];?></title>
<script>
<?php if (isset($tag) and trim($tag)!=""){
?>
	function CopiarImagen(Img){
		campo=window.opener.document.forma2.<?php echo $tag?>.value
		tag="<?php echo $tag?>"
		t=tag.split("_");
		if (campo=="")
			 window.opener.document.forma2.<?php echo $tag?>.value=Img
		else
		     window.opener.document.forma2.<?php echo $tag?>.value=campo+"\r"+Img
		if (t.length>1) self.close();
	}
<?php } ?>
	function SelectedFolder(Folder){
		alert(Folder)
	}

	function CrearCarpeta(){
       folder=prompt("<?php echo $msgstr["folder_name"]?>")
       folder=Trim(folder)
       if (folder=="")
       		return
       document.newfolder.folder.value=folder
       document.newfolder.submit()
       return
	}

	function MostrarImagen(source,type,base,desde,path,cont_type,Opcion){
		url="dirs_explorer.php?source="+source+"&type="+type+"&base="+base+"&desde="+desde+"&path="+path+"&cont_type="+cont_type+"&Opcion="+Opcion
  		msgwin=window.open(url,"show","width=600,height=600,scrollbars,resizable")
  		msgwin.focus()
  	}
</script>
<script language="JavaScript" type="text/javascript" src=js/lr_trim.js></script>


<h4>
<?php
$path="";
$source="";
if (isset($arrHttp["path"]))  $path=$arrHttp["path"];
if (isset($arrHttp["source"]))  $source=$arrHttp["source"];
echo $msgstr["database"].": ".$arrHttp["base"]."<p>".$msgstr["explore"]." ".$source."</h4>";
}
?>
