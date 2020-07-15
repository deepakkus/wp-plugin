<?php
class ProfileFieldsDB
{
	function __construct() 
	{
        global $wpdb;

        $this->fr_fields = array();
        register_shutdown_function(array(&$this, '__destruct'));

    }
	function __destruct() {
        return true;
    }
	/*
	*	Get profile list
	*/
	function getProfileList()
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_profile";
		$profilelist = $wpdb->get_results($SQL);
		
		return $profilelist;
	}
	
	/*
	*	Get user profile details
	*/
	function getProfileDetails($id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM ".$wpdb->prefix."users WHERE ID= $id";
		$profilelist = $wpdb->get_results($SQL);
		
		return $profilelist;
	}
	/*
	*	Get profile from fields
	*/
	function getProfileFields($id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_profile WHERE user_id = $id";
		$profilelist = $wpdb->get_results($SQL);
		
		return $profilelist;
	}
	
	/*
	*	Get from fields
	*/
	function getFormFields()
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_custom_fields order by displayorder";
		$fieldList = $wpdb->get_results($SQL);
		
		return $fieldList;
	}
	/*
	*	Get Option fields
	*/
	function getoptionFields($field_id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_custom_field_options where field_id=$field_id order by display_order";
		$fieldList = $wpdb->get_results($SQL);
		
		return $fieldList;
	}
	/*
	*	Add form fields
	*/
	function editProfileList($id="")
	{
		global $wpdb;
		$profile_id = '';
		$user_id = '';
		$labellist = '';
		$optvalue = '';
		$opt_fields = array();
		if(isset($_REQUEST["profiletype"]) && $_REQUEST["profiletype"]!='')
		{
			
			$profiletype = $_REQUEST["profiletype"];
		}
		if(isset($_REQUEST["label"]) && $_REQUEST["label"]!='')
		{
			$label = $_REQUEST["label"];
		}
		if(isset($_REQUEST["profileId"]) && $_REQUEST["profileId"]!='')
		{
			$profile_id = $_REQUEST["profileId"];
		}
		if(isset($_REQUEST["userId"]) && $_REQUEST["userId"]!='')
		{
			$user_id = $_REQUEST["userId"];
		}
		if(isset($_REQUEST["fldname"]) && $_REQUEST["fldname"]!='')
		{
			$fldname = str_replace(' ', '_',$_REQUEST["fldname"]);
		}
		if(isset($_REQUEST["input_type"]) && $_REQUEST["input_type"]!='')
		{
			$input_type = $_REQUEST["input_type"];
		}
		if(isset($_REQUEST["displayorder"]) && $_REQUEST["displayorder"]!='')
		{
			$displayorder = $_REQUEST["displayorder"];
		}
		if(isset($_REQUEST["optvalue"]) && $_REQUEST["optvalue"]!='')
		{
			$optvalue = $_REQUEST["optvalue"];
			$i=0;
			foreach($optvalue as $val)
			{
				$opt_fields[$i++]["option"]= $val;
			}
		}
		if(isset($_REQUEST["displayvalue"]) && $_REQUEST["displayvalue"]!='')
		{
			$displayvalue = $_REQUEST["displayvalue"];
			$i = 0;
			foreach($displayvalue as $val)
			{
				$opt_fields[$i++]["display"]= $val;
			}
		}
		if(isset($_REQUEST["optvalue"]) && $_REQUEST["optvalue"]!='')
		{
		
		if(isset($_REQUEST["displayorder"]) && $_REQUEST["displayorder"]!='')
		{
			$displayorder = $_REQUEST["displayorder"];
			$i = 0;
			foreach($displayorder as $val)
			{
				$opt_fields[$i++]["displayorder"]= $val;
			}
		}
		
		}
		
		$sql_c= "SELECT count(*) as count FROM `zc_custom_fields` WHERE profile_column = '$fldname'";
		//echo $sql_c;
		$check_duplicate = $wpdb->get_row($sql_c);
		//print_r($check_duplicate);
		//print_r($check_duplicate);
		
		//echo $check_duplicate->count;
		if($check_duplicate->count >0)
		{
			return $fldname.' already exists !!';
		}
		else
		{
			$SQL = "ALTER TABLE `zc_profile` ADD COLUMN ".$fldname." VARCHAR(255) DEFAULT'' AFTER user_id";
			$wpdb->query($SQL);
			/*$SQL = "UPDATE zc_profile SET ".$fldname ."= ' ' WHERE user_id = $user_id";
			$wpdb->query($SQL);*/
			
				 $SQL = "INSERT INTO zc_custom_fields SET
					display_name = '".$label."',
					profile_column = '".$fldname."',
					field_type = '".$input_type."',
					profile_type= '".$profiletype."',
					displayorder = '".$displayorder."'
					";
					//echo $SQL;
				$res = $wpdb->query($SQL);
				//echo 'rrrr'.$res;
				$field_id = $wpdb->insert_id;
				if(count($optvalue)>0)
				{
					foreach($opt_fields as $val)
					{
						$option = $val["option"];
						$display = $val["display"];
						$displayorder = $val["displayorder"];
				$SQL = "INSERT INTO zc_custom_field_options SET
					field_id = '".$field_id."',
					option_value = '".$option."',
					option_display = '".$display."',
					display_order = '".$displayorder."'
					";
					$wpdb->query($SQL);
					}
				}
				if($res)
				{
					$str_response = 'Succesfully Saved form fields !!';
				}
			
			
			return 1;
			
		}
		
		
		
		
	}
	/*
	*	Delete form fields
	*/
	function deleteFormFields($id)
	{
		global $wpdb;
		
		$SQL = "select * from zc_custom_fields where field_id=$id";
		$get_fname=$wpdb->get_row($SQL);
		
		$SQL = "ALTER TABLE zc_profile DROP COLUMN $get_fname->profile_column";
		$wpdb->query($SQL);
		
		$SQL = "DELETE FROM zc_custom_fields WHERE field_id= $id";
		$wpdb->query($SQL);
		$SQL = "DELETE FROM zc_custom_field_options WHERE field_id= $id";
		$wpdb->query($SQL);
		
		return "Field Succesfully deleted !!";
		
	}
}
?>