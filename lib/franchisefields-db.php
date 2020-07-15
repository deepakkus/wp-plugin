<?php
class FranchisefieldsDB
{
	var $franchisefields = false;

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
	*	Get Franchise custom fields
	*/
	function getoptionLabel($order="",$pid="",$role="")
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_custom_fields where (profile_type ='FE' or profile_type ='B')";
		/*if($role=="franchisor")
		{
			$devSQL .= " AND parentId= $pid";
		}*/
		if($order=='f')
		{
			$devSQL .= " order by field_id DESC";
		}
		else
		{
			$devSQL .= " order by displayorder";
		}
		//echo $devSQL;
		$devRes = $wpdb->get_results($devSQL);
		
		return $devRes;
	}
	function getallfranchiseFields_old()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_profile,wp_users where zc_profile.profile_type ='FE' and zc_profile.user_id=wp_users.ID order by zc_profile.profile_id DESC";
		$devRes = $wpdb->get_results($devSQL);
		return $devRes;	
	}
	/*
	*	Get all franchise fields
	*/
	function getallfranchiseFields($pid="",$role="")
	{
		global $wpdb;
		$res = array();
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
		
		$devSQL = "SELECT $sql_p FROM ".$wpdb->prefix."users u, zc_profile p where u.ID=p.user_id and p.profile_type='FE'";
		if($role=="franchisor")
		{
			$devSQL .= " AND parentId= $pid";
		}
		//echo $pid;
		//echo $devSQL;
		$devRes = $wpdb->get_results($devSQL);
		$res_p=array();
		//echo "<pre>";
		//print_r($devRes);
		return $devRes;
	}
	function addFeForms()
	{
		//echo '<pre>';
		global $wpdb;
  
			  unset($_REQUEST['page']);unset($_REQUEST['mode']);unset($_REQUEST['submit']);
			  
			  $theme = "N";
			  $parentId = 0;
			  if(isset($_REQUEST['username']) && $_REQUEST['username']!='')
			  {
			   $username=$_REQUEST['username'];
			  }
			  if(isset($_REQUEST['email']) && $_REQUEST['email']!='')
			  {
			   $email=$_REQUEST['email'];
			  }
			  if(isset($_REQUEST['ftheme']) && $_REQUEST['ftheme']!='')
			  {
			   	$theme = $_REQUEST['ftheme'];
			  }
			  if(isset($_REQUEST['parentId']) && $_REQUEST['parentId']!='')
			  {
			   	$parentId = $_REQUEST['parentId'];
			  }
			  if(isset($_REQUEST['password']) && $_REQUEST['password']!='')
			  {
			   //echo 'update';
			   $password = $_REQUEST['password'];
			  
			   //$pass = wp_hash_password($password);
			   $user_id = wp_update_user( array( 'ID' => $_REQUEST['profileid'],'user_pass' =>$password) );
			   
			  }
			  if(isset($_REQUEST['profileid']) && $_REQUEST['profileid']>0)
			  {
			   //echo 'update';
			   $profileid=$_REQUEST['profileid'];
			   $user_id = wp_update_user( array( 'ID' => $profileid, 'user_email' =>$email   ) );
			   $wpdb->update('zc_profile',array(
				 "theme_status" => $theme
				), array('user_id' =>$user_id ));
				$wpdb->update('zc_profile',array(
					"parentId" => $parentId
				), array('user_id' =>$user_id ));	
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
			if(isset($_REQUEST['password']) && $_REQUEST['password']!='')
			  {
			   //echo 'update';
			   $password = $_REQUEST['password'];
			   
			   
			  }
			$user_id = wp_insert_user(
					 array(
					  'user_login' => $username,
					  'user_pass' => $password,
					  'first_name' =>'',
					  'last_name' => '',
					  'user_email' => $email,
					  'display_name' => strtoupper($username),
					  'nickname' => $username,
					  'role'  => 'franchise'
					 )
					);
					
			$wpdb->insert('zc_profile',array(
					"user_id" => $user_id,
					"parentId" => $parentId,
					"profile_type"=>'FE',
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
		return $devRes;
	}
	
	/*
	* 
	*/
	function getfranchiseFields()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_custom_fields where profile_type ='FE' or profile_type ='B' order by displayorder";
		$devRes = $wpdb->get_results($devSQL);
		return $devRes;
	}
	function getoptionFields($field_id)
	{
		global $wpdb;
		$SQL = "SELECT * FROM zc_custom_field_options where field_id=$field_id order by display_order";
		//echo $SQL;
		$fieldList = $wpdb->get_results($SQL);
		
		return $fieldList;
	}
	function getfranchiseoptions()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM  zc_custom_fields LEFT JOIN zc_custom_field_options
ON zc_custom_fields.field_id =  zc_custom_field_options.field_id and  zc_custom_fields.profile_type ='FE' or zc_custom_fields.profile_type ='B' ";

		
	}
	/*
	*	Get Franchise details
	*/
	function getFranchiseDetails($id)
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
		
		return "Franchise Succesfully deleted !!";
		
	}
	
	function updateFranchise()
	{
		//echo '<pre>';
		global $wpdb;
  
			  unset($_REQUEST['page']);unset($_REQUEST['mode']);unset($_REQUEST['submit']);
			  
			  $theme = "N";
			  if(isset($_REQUEST['username']) && $_REQUEST['username']!='')
			  {
			   $username=$_REQUEST['username'];
			  }
			  if(isset($_REQUEST['email']) && $_REQUEST['email']!='')
			  {
			   $email=$_REQUEST['email'];
			  }
			  if(isset($_REQUEST['ftheme']) && $_REQUEST['ftheme']!='')
			  {
			   	$theme = $_REQUEST['ftheme'];
			  }
			  if(isset($_REQUEST['password']) && $_REQUEST['password']!='')
			  {
			   //echo 'update';
			   	$password = $_REQUEST['password'];
			  	  $wpdb->update('wp_users',array(
				 "user_pass" => $this->wp_hash_password_fr($password)
				), array('ID' =>$profileid ));

			   
			  }
			  if(isset($_REQUEST['profileid']) && $_REQUEST['profileid']>0)
			  {
			   	$profileid = $_REQUEST['profileid'];
			  }
			  $wpdb->update('wp_users',array(
				 "user_email" => $email
				), array('ID' =>$profileid ));
				
				foreach ($_REQUEST as $key=>$value)
			   {
				$wpdb->update('zc_profile',array(
				 "$key" => $value
				), array('user_id' =>$profileid )); 
			   }
				
	}
	
	function wp_hash_password_fr($password) {
	        global $wp_hasher;
	
	        if ( empty($wp_hasher) ) {
	                require_once( ABSPATH . WPINC . '/class-phpass.php');
	                // By default, use the portable hash from phpass
	                $wp_hasher = new PasswordHash(8, true);
	        }
	
	        return $wp_hasher->HashPassword( trim( $password ) );
	}
}
?>