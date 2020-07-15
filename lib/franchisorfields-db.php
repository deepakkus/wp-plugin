<?php
class FranchisorfieldsDB
{
	var $franchisorfields = false;

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
	*	Get all franchisor fields
	*/

	function getoptionLabel($order="")
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_custom_fields where profile_type ='FO' or profile_type ='B'";
		if($order=="f")
		{
			$devSQL .= " order by field_id DESC";
		}
		else
		{
			$devSQL .= " order by displayorder";
		}
		$devRes = $wpdb->get_results($devSQL);
		return $devRes;
	}
	function getallfranchisorFields_old()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_profile,wp_users where zc_profile.profile_type ='FO' and zc_profile.user_id=wp_users.ID order by zc_profile.profile_id DESC";
		$devRes = $wpdb->get_results($devSQL);
		return $devRes;	
	}
	function getallfranchisorFields()
	{
		global $wpdb;
		$res=array();
		array_push($res,'u.user_login','u.user_pass','u.user_email');
		$devSQL = "SHOW COLUMNS FROM zc_profile where Field NOT 
		IN('profile_id','user_id','profile_type','status','theme_status','theme_id','added_on')";
		//echo $devSQL;
		$devRes = $wpdb->get_results($devSQL);
		$cfields = $this -> getoptionLabel();
		foreach($devRes as $dev)
		{
			foreach($cfields as $fld)
			{
				if($fld->profile_column==$dev->Field)
				{
					array_push($res,'p.'.$dev->Field);
				}
			}
			
		}
		array_push($res,'u.ID');
		
		$sql_p=implode(',',$res);
		
		$devSQL = "SELECT $sql_p FROM ".$wpdb->prefix."users u, zc_profile p where u.ID=p.user_id and p.profile_type='FO'";
		$devRes = $wpdb->get_results($devSQL);
		$res_p=array();
		//echo '<pre>';
		//print_r($devRes);
		foreach($devRes as $dev)
		{
			array_push($res_p,$dev);
			//echo $value;
		// echo $dev[0];
			
		}
		//echo '<pre>';
		//print_r($res_p);
		return $devRes;
	}
	function addFeForms()
	{
		//echo '<pre>';
		global $wpdb;
  
		  unset($_REQUEST['page']);unset($_REQUEST['mode']);unset($_REQUEST['submit']);
		  if(isset($_REQUEST['username']) && $_REQUEST['username']!='')
		  {
		   $username=$_REQUEST['username'];
		  }
		  if(isset($_REQUEST['email']) && $_REQUEST['email']!='')
		  {
		   $email=$_REQUEST['email'];
		  }
		  if(isset($_REQUEST['password']) && $_REQUEST['password']!='')
		  {
		   //echo 'update';
		   $password=$_REQUEST['password'];
		   $user_id = wp_update_user( array( 'ID' => $_REQUEST['profileid'],'user_pass' =>($password)) );
		   
		  }
		  if(isset($_REQUEST['profileid']) && $_REQUEST['profileid']>0)
		  {
		   //echo 'update';
		   $profileid=$_REQUEST['profileid'];
		   $user_id = wp_update_user( array( 'ID' => $profileid, 'user_email' =>$email   ) );
		  
		   foreach ($_REQUEST as $key=>$value)
		   {
			   
			$wpdb->update('zc_profile',array(
			 "$key" => $value
			), array('user_id' =>$user_id )); 
		   }
		   
		   
		  }
		else
		{
			//echo 'insert';
			$user_id = wp_insert_user(
					 array(
					  'user_login' => $username,
					  'user_pass' => ($password),
					  'first_name' =>'',
					  'last_name' => '',
					  'user_email' => $email,
					  'display_name' => strtoupper($username),
					  'nickname' => $username,
					  'role'  => 'franchisor'
					 )
					);
					//echo $user_id;
			$wpdb->insert('zc_profile',array(
					"user_id" => $user_id,
					"profile_type"=>'FO',
					"status" =>'P',
					"theme_status" =>'N',
					"theme_id"=>0,
					"added_on"=>date("Y-m-d H:i:s")					
				));
			$user_last_insert_id = $wpdb->insert_id;
			foreach ($_REQUEST as $key=>$value)
			{
				$wpdb->update('zc_profile',array(
					"$key" => $value
				), array('user_id' =>$user_id ));	
			}
			return 'insert';
			
		}
		//print_r($_REQUEST);
	}
	function getdetailsById($profileid)
	{
		global $wpdb;
		//echo $profileid;
		$devSQL = "SELECT * FROM  zc_profile where user_id=$profileid";
		//echo $devSQL;
		$devRes = $wpdb->get_results($devSQL);
		//echo '<pre>';
		//print_r($devRes);
		//return $devRes;
	}
	
	function getoptionFields($field_id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_custom_field_options where field_id=$field_id order by display_order";
		//echo $SQL;
		$fieldList = $wpdb->get_results($SQL);
		//print_r($SQL);
		return $fieldList;
	}
	function getfranchisoroptions()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_custom_fields LEFT JOIN zc_custom_field_options
ON zc_custom_fields.field_id =  zc_custom_field_options.field_id and  zc_custom_fields.profile_type ='FO' or zc_custom_fields.profile_type ='B' ";

		//$devRes = $wpdb->get_results($devSQL);
		
		/*foreach ($devRes as $devRes)
		{
			$devSQL2 = "SELECT *  FROM  zc_custom_field_options where field_id=$devRes->field_id ";
			$devRes2 = $wpdb->get_results($devSQL2);
			
			print_r($devRes2);
			
				
		}*/
		
		//return $devRes2;
	}
	/*
	*	Get Franchisor details
	*/
	function getFranchisorDetails($id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM ".$wpdb->prefix."users WHERE ID= $id";
		$results = $wpdb->get_results($SQL);
		return $results;
	}
	function deleteFormFields($id)
	{
		global $wpdb;
		$SQL = "DELETE FROM zc_profile WHERE user_id= $id";
		$wpdb->query($SQL);
		$SQL = "DELETE FROM ".$wpdb->prefix."users WHERE ID = $id";
		$wpdb->query($SQL);
		$SQL = "DELETE FROM ".$wpdb->prefix."usermeta WHERE user_id= $id";
		$wpdb->query($SQL);
		wp_delete_user( $id); 
		
		return "Franchisor Succesfully deleted !!";
		
	}
}
?>