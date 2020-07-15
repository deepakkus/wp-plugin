<?php

require_once(dirname(dirname (__FILE__)).'/lib/zipClass-db.php');




$zipobj= new zipClass();
global $wpdb;
global $current_user;get_currentuserinfo();
 		$currentuserID=$current_user->ID;


	if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='editzip'))
	{
		editfrm();
	}
	elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='delzip'))
	{
		deletefrm();
	}
	elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='insertdatabase'))
	{
		insertdatabase();
	}
	else
	{
		main();
	}
	

?>
<form name="mainfrm" action="" method="post">
<input type="hidden" name="mode" />
<input type="hidden" name="id" />
</form>
<link rel="stylesheet" href="<?php echo plugins_url();?>/profile-management/css/style.css" />
<script language="javascript">
function check_form(frm_zip)
{
	//frm.mode.value = "fields";
	var zipcode=document.frm_zip.zipcode.value;
	if(zipcode.search(/\S/) == -1)
   	{
		alert("Please! Enter Zipcode.");
		document.frm_zip.zipcode.focus();
		return false;
	}
	
	
}
function delete_check(zipid)
{
		 var userResp = window.confirm("Are you sure you want to delete?");
 if( userResp == true )
 {
  	window.location="admin.php?page=manage_zip&mode=delzip&zipid="+zipid;
 }
 
}
</script>

<div class="form_main">

<div class="page_text">
<?php function main(){?>
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Zip Management <a class="add-new-h2" href="admin.php?page=manage_zip&mode=editzip">Add Zip</a></h1>
<table class="wp-list-table widefat fixed posts">
		<thead>
		  <tr>
			
			<th><span>Zipcodes</span></th>
            <th><span>Latitude</span></th>
            <th><span>Longitude</span></th>
			<th><span>Action</span></th>
		  </tr>
		</thead>
		<tbody id="the-list">
		  <?php
		  $zipobj= new zipClass();
		  $allzip = $zipobj->getallZip();
				if(count($allzip) > 0){
					 foreach ( $allzip as $allzips ) { ?>
		  <tr>
          		<td><?php echo $allzips->zip; ?></td>
                <td><?php echo $allzips->lat; ?></td>
                <td><?php echo $allzips->longitude; ?></td>
                <td><a title="edit"  href="admin.php?page=manage_zip&amp;mode=editzip&amp;zipid=<?php echo $allzips->zipid; ?>" > Edit</a> | <a title="delete" onclick="delete_check(<?php echo $allzips->zipid; ?>);" href="javascript:void(0)" > Delete</a></td>
          </tr>
         
        
		  <?php } } else {?>
		  <tr class="no-items">
			<td class="colspanchange" colspan="3">No Record found</td>
		  </tr>
		  <?php } ?>
		</tbody>
	  </table>
<?php }?>


<?php function editfrm(){?>
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Zip Management </h1>
<?php 
$zipobj= new zipClass();
$zipcode= "";
$zipid=0;
if(isset($_REQUEST['zipid'])&&$_REQUEST['zipid']!="")
{
	$zipid = $_REQUEST['zipid'];
	$zipinfo = $zipobj -> getZipById($zipid);
	$zipcode= $zipinfo->zip;
}
?>
<form action="" method="post" name="frm_zip"  onsubmit="return check_form(this);">
<input type="hidden" name="mode" value="insertdatabase" />
<input type="hidden" name="zipid" value="<?php echo $zipid;?>" />

		<table cellpadding="0" cellspacing="5">
		  <tr>
			<td><span class="zip_code"><strong>Zipcode :</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="zipcode" value="<?php echo $zipcode; ?>"></td>
		  </tr>
		  <tr>
			<td>
			  <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
		  </tr>
		</table>

	
</form>
<?php }?>


</div>
</div>     
  
 <?php 
 function insertdatabase()
 {
	$zipid=  $_REQUEST['zipid'];
	$zipobj= new zipClass();
	$res=  $zipobj -> editzip($zipid);
	main();
 }
 function deletefrm()
 {
	 $zipid=  $_REQUEST['zipid'];
	$zipobj= new zipClass();
	$res=  $zipobj -> deletezip($zipid);
	main();
	 
 }
 ?>
       