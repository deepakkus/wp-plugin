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

if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='addfields'))
{
	add_formfields();
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='feaddfields'))
{
	add_fe_form();
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='deletefields'))
{
	if(isset($_REQUEST['profileId'])&&($_REQUEST['profileId']!=''))
	{
		$id = $_REQUEST['profileId'];
		delete_form_fields($id);
	}
}
else
{
	main();
}
?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" href="<?php echo plugins_url();?>/profile-management/css/style.css" />
<script language="javascript">
function check_form(frm)
{
	
	frm.mode.value = "feaddfields";
	
	

}
function add_fields(userid)
{
	
	frm = document.frm_fields;
	frm.mode.value = "addfields";
	frm.profileId.value = userid;
	//frm.profileId.value = val2;
	//alert(userid);
	frm.submit();
}

function showFields(val)
{
	frm = document.frm_fields;
	frm.mode.value = "managefields";
	frm.userId.value = val;
	frm.submit();
}
function delete_fields(id)
{
	
	frm = document.frm_fields;
	frm.mode.value = "deletefields";
	frm.profileId.value = id;
	var userResp = window.confirm("Are you sure you want to delete?");
	if( userResp == true )
 	{
  		frm.submit();
 	}
	
}

</script>
<form name="frm_fields" id="frm_fields" method="post" action="">
<input type="hidden" name="mode"  />
<input type="hidden" name="fieldId"  />
<input type="hidden" name="profileId"  />
</form>
<?php 

function main(){
	$current_user = wp_get_current_user();
	$parentId = $current_user->ID;
	$role = $current_user->roles[0];
	$franchisedb = new FranchisefieldsDB();
	$fes_label = $franchisedb -> getoptionLabel('f',$parentId,$role);
	$franchise_dtl = $franchisedb -> getallfranchiseFields($parentId,$role);
	
	$id = "";
	
	?>
<div class="form_mains">
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Franchise Management</h1>
</div>
<?php if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }?>
<div class="add_field_text">
			<a class="add-new-h2" href="javascript:void(0)" onclick="add_fields();">Add Franchise</a>
		</div>
        <div class="form_main">
<div class="main_user">
<table style="width:100%;" class="wp-list-table widefat fixed posts">
	<tr>
    	<td>Username</td>
        
        <td>Email</td>
        <?php
		foreach($fes_label as $fields)
		{
			?>
		<td><?php echo $fields->display_name;?></td>
		<?php }
		?>
        <td>Action</td>
    </tr>
    <?php
	
	global $current_user;

	foreach($franchise_dtl as $franchise){
		$id = $franchise->ID;
		unset($franchise->ID);
		unset($franchise->user_pass);
		$franchise_flds = get_object_vars($franchise);
		//echo '<pre>';
		//print_r($franchise_flds);
	?>
    <tr>
    	<?php foreach($franchise_flds as $flds){
			?>
    	<td>
			<?php echo $flds;?>
        </td>
        <?php
			}
		?>
        <td>
        <a title="edit"  href="javascript:void(0)" onclick="add_fields(<?php echo $id?>)"> Edit</a> | <a title="delete" onclick="delete_fields(<?php echo $id ?>);" href="javascript:void(0)" > Delete</a>
			
   		</td>
    </tr>
    <?php
		
	}
	?>
    
</table>
</div>

<?php
}

function add_formfields()
{
	$franchisedb = new FranchisefieldsDB();
	$franchise_info = array();
	$franchise_fields = $franchisedb -> getoptionLabel('d');
	$fes = $franchisedb -> getoptionLabel('d');
	$franchise_dtl = $franchisedb -> getallfranchiseFields();
	$i = 0;
	if(isset($_REQUEST['profileId'])&&($_REQUEST['profileId']!=''))
	{
		$user_id = $_REQUEST['profileId'];
	}
	foreach($franchise_dtl as $fields)
	{
		unset($fields->user_login);
		unset($fields->user_pass);
		unset($fields->user_email);
		//unset($fields->ID);
		if($fields->ID==$user_id)
		{
			$fr_fields = get_object_vars($fields);
		}
	}
	$i = 0;
	
	foreach($franchise_fields as $fields)
	{
		$franchiseInfo[$i]["name"] = $fields->display_name."<br>";
		$franchiseInfo[$i]["column"] = $fields->profile_column;
		$franchiseInfo[$i]["fid"] = $fields->field_id;
		$franchiseInfo[$i]["ftype"] = $fields->field_type;
		if(count($fr_fields)>0)
		{
			foreach($fr_fields as $key=>$value)
			{
			  
			  if($key==$fields->profile_column)
			  {
				 $franchiseInfo[$i]["value"] = $value;
			  }
			}
		}
		$i++;
	}
	
	$i = 0;
	
	
?>
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Franchise Management </h1>
<?php 
 if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }
$profileid=0;
$parentId = 0;
$current_user = wp_get_current_user();
$parentId = $current_user->ID;
if(isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0)
{
	$profileid = $_REQUEST['profileId'];
	$profileinfo = $franchisedb -> getdetailsById($profileid);
	$franchise_info = $franchisedb -> getFranchiseDetails($profileid);
	
	$username = $franchise_info[0]->user_login;
	$email = $franchise_info[0]->user_email;
	$theme = $profileinfo[0]->theme_status;
}
else
{
	$username = "";
	$email = "";
	$theme = "";
}
?>
<form action="" method="post" name="frm_fe" onsubmit="return check_form(this)">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="profileid" value="<?php echo $profileid;?>" />
<input type="hidden" name="parentId" value="<?php echo $parentId;?>" />

		<table cellpadding="0" cellspacing="5" >
       
		  <tr>
			<td><span class="zip_code"><strong>Username:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="username" <?php if($profileid > 0){echo ' disabled="disabled"';} ?> value="<?php echo $username; ?>"></td>
		  </tr>
            <tr>
			<td><span class="zip_code"><strong>Password:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="password" name="password" value=""></td>
		  </tr>
            <tr>
			<td><span class="zip_code"><strong>Email:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="email" value="<?php echo $email; ?>"></td>
		  </tr>
          <tr>
			<td><span class="zip_code"><strong>Change Theme:</strong></span></td>
		  </tr>
          <tr>
			<td><input type="radio" name="ftheme" value="Y" <?php if($theme=='Y'){echo 'checked';}else{echo '';}?>>Yes
            <input type="radio" name="ftheme" value="N" <?php if($theme=='N'){echo 'checked';}else{echo '';}?>>No
            </td>
		  </tr>
           <?php $i = 0;
		   if(isset($franchiseInfo)){
		   foreach($franchiseInfo as $fields){
			   if(isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0)
			   {
				   $fldvalue = $fields["value"];
			   }
			   else
			   {
				   $fldvalue = "";
			   }
			   $fldvalue = $fields["value"];
		   
			   ?>
			<tr>
			<td><span class="zip_code"><strong><?php echo $fields["name"];?></strong></span></td>
		  </tr>
         <?php 
		  if($fields["ftype"]=='S'){?>
		  <tr>
			<td>
            <select name="<?php echo $fields["column"]; ?>" >
            	<?php 
				
				$optionfields = $franchisedb -> getoptionFields($fields["fid"]);
				
				foreach($optionfields as $opt){?>
            	<option value="<?php echo $opt->option_value; ?>" <?php if($opt->option_value==$fldvalue){echo "selected";}?>><?php echo $opt->option_display; ?></option>
                <?php }?>
            </select>
            </td>
		  </tr> 
          <?php  
		  }
		  elseif($fields["ftype"]=='C'){?>
		  <tr>
          <?php 
				
				$optionfields = $franchisedb -> getoptionFields($fields["fid"]);
				foreach($optionfields as $opt){
			?>
		<td><input type="checkbox" name="<?php echo $fields["value"] ?>" value="<?php echo $opt->option_value; ?>" >
		<?php echo $opt->option_display; ?></td>
            	<?php }?>
		  </tr> 
          <?php
          }
		  elseif($fields["ftype"]=='R'){?>
		  <tr>
          <?php 
				$optionfields = $franchisedb -> getoptionFields($fields["fid"]);
				foreach($optionfields as $opt){?>
		<td><input type="radio" name="<?php echo $fields["column"] ?>" value="<?php echo $opt->option_value; ?>" <?php if($opt->option_value==$fldvalue){echo 'checked';}else{echo '';}?>>
		<?php echo $opt->option_display; ?></td>
            	<?php }?>
		  </tr> 
          <?php
          }
		  elseif($fields["ftype"]=='TA'){?>
		  <tr>
          
		<td><textarea name="<?php echo $fields["column"] ?>" id="address" cols="45" rows="5"><?php echo $fldvalue?></textarea></td>
		  </tr> 
          <?php
          }
		  else
		  {?>
		  <tr>
		<td><input type="text" name="<?php echo $fields["column"] ?>" value="<?php echo $fldvalue?>"></td>
		  </tr> 
          <?php } 
          $i++;
		   }
		  }
			   
		   ?>
          <tr>
          </tr>
          
		  <tr>
			<td>
			  <input type="submit" name="submit" value="Submit" class="button button-primary button-large"></td>
		  </tr>
		</table>

	
</form>
<?php 
}
function add_fe_form()
{
	//echo "add";
	
	$franchisedb = new FranchisefieldsDB();
	$str_response = $franchisedb -> addFeForms();
	if($str_response=='insert')
	{
		$GLOBALS['str_response']='Franchise added successfully !!';
	}
	else
	{
		$GLOBALS['str_response']='Franchise updated successfully !!';
	}
	main();
}

function delete_form_fields($id)
{
	$franchisedb = new FranchisefieldsDB();
	$str_response = $franchisedb -> deleteFormFields($id);
	$GLOBALS['str_response']=$str_response;
	main();
}
?>
       