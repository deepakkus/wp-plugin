<?php
require_once(dirname(dirname (__FILE__)).'/lib/franchisefields-db.php');
require_once(dirname(dirname (__FILE__)).'/lib/franchisorfields-db.php');
require_once( ABSPATH . WPINC . '/class-phpass.php');
global $wpdb;
/*echo "<pre>";
print_r($_REQUEST);*/
if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='franaddfields'))
{
	
	$wp_hasher = new PasswordHash(8, true);
	$pass = $wp_hasher->HashPassword( trim( $password ) );
	add_fr_form();
	
}
function add_fr_form()
{
	//echo "add";
	global $wpdb;
	$franchisedb = new FranchisefieldsDB();
	$str_response = $franchisedb -> updateFranchise();
	if($str_response=='insert')
	{
		$GLOBALS['str_response']='Franchise added successfully !!';
	}
	else
	{
		$GLOBALS['str_response']='Franchise updated successfully !!';
	}
	//franchisor_fields();
}
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
		//unset($fields->ID);
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
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "fraddfields";
}
</script>
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
            <!--<tr>
			<td><span class="zip_code"><strong>Password:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="password" name="password" value="<?php //echo $profileinfo->password; ?>"></td>
		  </tr>-->
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

function franchise_fields($id="")
{
	
	
	$i = 0;
	
	//echo '<pre>';
	//print_r($franchiseInfo);
?>

<?php 
 if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }
$profileid=0;
$franchise_info = array();
	$franchisedb = new FranchisefieldsDB();
	$franchise_info = array();
	$franchise_fields = $franchisedb -> getoptionLabel('d');
	$fes = $franchisedb -> getoptionLabel('d');
	$franchise_dtl = $franchisedb -> getallfranchiseFields();
	$i = 0;
	foreach($franchise_dtl as $fields)
	{
		unset($fields->user_login);
		unset($fields->user_pass);
		unset($fields->user_email);
		//unset($fields->ID);
		if($fields->ID==$id)
		{
			$fr_fields = get_object_vars($fields);
		}
	}
	$i = 0;
	foreach($franchise_fields as $fields)
	{
		$franchiseInfo[$i]["name"] = $fields->display_name;
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
	
	//echo "<pre>";
	//print_r($franchiseInfo);
if((isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0))
	{
		$id = $_REQUEST['profileId'];
	}
//if(isset($_REQUEST['profileId'])&& $_REQUEST['profileId']>0)
if($id>0)
{
	$profileid = $id;
	$profileinfo = $franchisedb -> getdetailsById($profileid);
	$franchise_info = $franchisedb -> getFranchiseDetails($profileid);
	//echo "<pre>";
	//print_r($profileinfo);
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
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "franaddfields";
}
</script>
<form action="" method="post" name="frm_fe" onsubmit="return check_form(this)">
<input type="hidden" name="mode" value="" />
<input type="hidden" name="profileid" value="<?php echo $profileid;?>" />

		<table cellpadding="0" cellspacing="5" >
       
		  <tr>
			<td><span class="zip_code"><strong>Username:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="username" <?php if($profileid > 0){echo ' disabled="disabled"';} ?> value="<?php echo $username; ?>"></td>
		  </tr>
            <!--<tr>
			<td><span class="zip_code"><strong>Password:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="password" name="password" value=""></td>
		  </tr>-->
            <tr>
			<td><span class="zip_code"><strong>Email:</strong></span></td>
		  </tr>
		  <tr>
			<td><input type="text" name="email" value="<?php echo $email; ?>"></td>
		  </tr>
          <!--<tr>
			<td><span class="zip_code"><strong>Change Theme:</strong></span></td>
		  </tr>
          <tr>
			<td><input type="radio" name="ftheme" value="Y" <?php //if($theme=='Y'){echo 'checked';}else{echo '';}?>>Yes
            <input type="radio" name="ftheme" value="N" <?php //if($theme=='N'){echo 'checked';}else{echo '';}?>>No
            </td>
		  </tr>-->
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
?>