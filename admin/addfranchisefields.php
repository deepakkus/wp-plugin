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
$content = $franchisedb -> getPagecontent();


?>
<link rel="stylesheet" href="<?php echo plugins_url();?>/Fr-Fields/css/style.css" />
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "fields";
}
</script>

<div class="form_main">
<?php echo $str_response;?><br />
<span>Add Form Field</span><br />
<form name="frm_fields" method="post" action="" onsubmit="return check_form(this)">
<span>Label</span><br />
<input type="text" name="labelname"  /><br />
<span>Placeholder</span><br />
<input type="text" name="placeholder"  /><br />
<span>Field Name</span><br />
<input type="text" name="fieldname"  /><br />
<span>Field Type</span><br />
<input type="text" name="fieldtype"  /><br />
<input type="hidden" name="mode"  />
<input type="submit" value="Save"  />
</form>
</div>     
  
       