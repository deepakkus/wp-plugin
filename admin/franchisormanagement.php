<?php
require_once(dirname(dirname (__FILE__)).'/lib/franchisorfields-db.php');
$franchisordb = new FranchisorfieldsDB();
global $wpdb;
$str_response = '';
$current_user = wp_get_current_user();
$labelname = '';
$placeholder = '';
$fieldname = '';
$fieldtype = '';
//echo $turn_on = !$this->multisite;


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
	$franchisordb = new FranchisorfieldsDB();
	$fos_label = $franchisordb -> getoptionLabel('f');
	$franchisor_dtl = $franchisordb -> getallfranchisorFields();
	//$fos = $franchisordb -> getallfranchisorFields_old();
	
	?>
<div class="form_mains">
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Franchisor Management</h1>
</div>
<?php if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }?>
<div class="add_field_text">
			<a class="add-new-h2" href="javascript:void(0)" onclick="add_fields();">Add Franchisor</a>
		</div>
        <div class="form_main">



<div class="main_user">
<table style="width:100%;" class="wp-list-table widefat fixed posts">
	<tr>
    	<th>Username</th>
       
        <th>Email</th>
        <?php
		foreach($fos_label as $fields)
		{?>
		<th><?php echo $fields->display_name;?></th>
		<?php }
		?>
        <th>Action</th>
    </tr>
    <?php
	//echo '<pre>';
	//print_r($fos);
	global $current_user;

	foreach($franchisor_dtl as $franchisor){
		$id = $franchisor->ID;
		unset($franchisor->ID);
		unset($franchisor->user_pass);
		$franchisor_flds = get_object_vars($franchisor);
		
	?>
    <tr>
    	<?php foreach($franchisor_flds as $flds){
			
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
	$franchisordb = new FranchisorfieldsDB();
	$fos = $franchisordb -> getoptionLabel('d');
	
	$franchisorInfo = array();
	$franchisor_fields = $franchisordb -> getoptionLabel('d');
	$fes = $franchisordb -> getoptionLabel('d');
	$franchisor_dtl = $franchisordb -> getallfranchisorFields();
	$i = 0;
	foreach($franchisor_dtl as $fields)
	{
		unset($fields->user_login);
		unset($fields->user_pass);
		unset($fields->user_email);
		unset($fields->ID);
		$fr_fields = get_object_vars($fields);
	}
	$i = 0;
	foreach($franchisor_fields as $fields)
	{
		$franchisorInfo[$i]["name"] = $fields->display_name;
		$franchisorInfo[$i]["column"] = $fields->profile_column;
		$franchisorInfo[$i]["fid"] = $fields->field_id;
		$franchisorInfo[$i]["ftype"] = $fields->field_type;
		if(count($fr_fields)>0)
		{
		foreach($fr_fields as $key=>$value)
		{
		  if($key==$fields->profile_column)
		  {
			  $franchisorInfo[$i]["value"] = $value;
		  }
		}
		}
		$i++;
	}
	//echo '<pre>';
	//print_r($franchisor_info);
	$i = 0;
	
?>
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Franchisor Management </h1>
<?php 
 if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }
$profileid=0;
if(isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0)
{
	
	$profileid = $_REQUEST['profileId'];
	$profileinfo = $franchisordb -> getdetailsById($profileid);
	$franchisor_info = $franchisordb -> getFranchisorDetails($profileid);
	$username = $franchisor_info[0]->user_login;
	$email = $franchisor_info[0]->user_email;
}
else
{
	$username = "";
	$email = "";
}
?>
<form action="" method="post" name="frm_fo" onsubmit="return check_form(this)">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="profileid" value="<?php echo $profileid;?>" />
<input type="hidden" name="userid" value="<?php echo $profileid;?>" />

		<table cellpadding="0" cellspacing="5">
       
		  <tr>
			<td><span class="zip_code"><strong>Username:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" <?php if($profileid > 0){echo ' disabled="disabled"';} ?> name="username" value="<?php echo $username; ?>"></td>
		  </tr>
            <tr>
			<td><span class="zip_code"><strong>Password:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="password" name="password" value="<?php //echo $profileinfo->password; ?>"></td>
		  </tr>
            <tr>
			<td><span class="zip_code"><strong>Email:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="email" value="<?php echo $email; ?>"></td>
		  </tr>
           <?php 
		   
		   foreach($franchisorInfo as $fields){
			   if(isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0)
			   {
				   $fldvalue = $fields["value"];
			   }
			   else
			   {
				   $fldvalue = "";
			   }
			   ?>
			<tr>
			<td><span class="zip_code"><strong><?php echo  $fields["name"];?></strong></span></td>
		  </tr>
         <?php 
		  if($fields->field_type=='S'){?>
		  <tr>
			<td>
            <select name="<?php echo $fields["column"]; ?>" >
            	<?php 
				
				$optionfields = $franchisordb -> getoptionFields($fields->field_id);
				
				foreach($optionfields as $opt){?>
            	<option value="<?php echo $opt->option_value; ?>"><?php echo $opt->option_display; ?></option>
                <?php }?>
            </select>
            </td>
		  </tr> 
          <?php  
		  }
		  elseif($fields->field_type=='C'){?>
		  <tr>
          <?php 
				$optionfields = $franchisordb -> getoptionFields($fields->field_id);
				foreach($optionfields as $opt){?>
		<td><input type="checkbox" name="<?php echo $fields->profile_column ?>" value="<?php echo $opt->option_value; ?>">
		<?php echo $opt->option_display; ?></td>
            	<?php }?>
		  </tr> 
          <?php
          }
		  elseif($fields->field_type=='R'){?>
		  <tr>
          <?php 
				$optionfields = $franchisordb -> getoptionFields($fields->field_id);
				foreach($optionfields as $opt){?>
		<td><input type="radio" name="<?php echo $fields["column"] ?>" value="<?php echo $fldvalue; ?>">
		<?php echo $opt->option_display; ?></td>
            	<?php }?>
		  </tr> 
          <?php
          }
		  elseif($fields->field_type=='TA'){?>
		  <tr>
          
		<td><textarea name="<?php echo $fields["column"] ?>" id="address" cols="45" rows="5"></textarea></td>
		  </tr> 
          <?php
          }
		  else
		  {?>
		  <tr>
		<td><input type="text" name="<?php echo $fields["column"] ?>" value="<?php echo $fldvalue?>"></td>
		  </tr> 
          <?php } 
          
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
	
	$franchisordb = new FranchisorfieldsDB();
	$str_response = $franchisordb -> addFeForms();
	if($str_response=='insert')
	{
		$GLOBALS['str_response']='Franchisor added successfully !!';
	}
	else
	{
		$GLOBALS['str_response']='Franchisor updated successfully !!';
	}
	main();
}

function delete_form_fields($id)
{
	$franchisordb = new FranchisorfieldsDB();
	$str_response = $franchisordb -> deleteFormFields($id);
	$GLOBALS['str_response']=$str_response;
	main();
}
?>
       