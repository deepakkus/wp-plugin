<?php
require_once(dirname(dirname (__FILE__)).'/lib/franchisefields-db.php');
$franchisedb = new FranchisefieldsDB();
global $wpdb;
$str_response = '';
$current_user = wp_get_current_user();
$labelname = '';
$placeholder = '';
$fieldname = '';
$fieldtype = '';
$hash = wp_hash_password( "sam123" );
if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='fields'))
{
	if(isset($_REQUEST['labelname'])&&($_REQUEST['labelname']!=''))
	{
		$labelname = $_REQUEST['labelname'];
	}
	if(isset($_REQUEST['placeholder'])&&($_REQUEST['placeholder']!=''))
	{
		$placeholder = $_REQUEST['placeholder'];
	}
	if(isset($_REQUEST['fieldname'])&&($_REQUEST['fieldname']!=''))
	{
		$fieldname = $_REQUEST['fieldname'];
	}
	if(isset($_REQUEST['fieldtype'])&&($_REQUEST['fieldtype']!=''))
	{
		$fieldtype = $_REQUEST['fieldtype'];
	}
	$SQL = "INSERT INTO ".$wpdb->prefix."fr_formfields SET
			labelname = '".$labelname."',
			placeholdername = '".$placeholder."',
			fieldname = '".$fieldname."',
			fieldtype = '".$fieldtype."'
			";
	$res = $wpdb->query($SQL);
	if($res)
	{
		$str_response = 'Save form fields';
	}
}

//$content = $franchisedb -> getPagecontent();

?>
<link rel="stylesheet" href="<?php echo plugins_url();?>/Fr-Fields/css/style.css" />
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "fields";
}
</script>

<div class="form_main">
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Profile Management</h1>
<div class="page_text">
<h2><?php echo $content;?></h2>
</div>
</div>     
  
       