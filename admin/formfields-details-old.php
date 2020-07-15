<?php
//require_once(plugins_url().'/profile-management/lib/franchisefields-db.php');
require_once(dirname(dirname (__FILE__)).'/lib/franchisorfields-db.php');
global $wpdb;

function franchisor_fields($id="")
{
	$profileid=0;
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
	if((isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0))
	{
		$id = $_REQUEST['profileId'];
	}
//if((isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0) || $id>0)
if($id>0)
{
	
	//$profileid = $_REQUEST['profileId'];
	$profileid = $id;
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
?>