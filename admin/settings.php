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
$content = '';
$devSQL = "SELECT * FROM ".$wpdb->prefix."development";

if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='development'))
{
	if(isset($_REQUEST['content'])&&($_REQUEST['content']!=''))
	{
		$content = $_REQUEST['content'];
	}
	
	$SQL = "UPDATE ".$wpdb->prefix."development SET
			pagetext = '".$content."'
			WHERE id = 1
			";
	$res = $wpdb->query($SQL);
	if($res)
	{
		$str_response = 'Save data successfully';
	}
}

$devRes = $wpdb->get_results($devSQL);
$content = $devRes[0]->pagetext;

?>
<link rel="stylesheet" href="<?php echo plugins_url();?>/Fr-Fields/css/style.css" />
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "development";
}
</script>

<div class="form_main">
<?php echo $str_response;?><br />
<span>Settings</span><br />
<form name="frm_fields" method="post" action="" onsubmit="return check_form(this)">

<span>Page Text</span><br />
<input type="text" name="content" value="<?php echo $content;?>" /><br />

<input type="hidden" name="mode"  />
<input type="submit" value="Save"  />
</form>
</div>     
  
       