<?php 

class zipClass
{
	public function getZipById($zipid)
	{
		 global $wpdb;
		 $res = $wpdb->get_row("SELECT * FROM zc_zip where zipid='$zipid'"); 
		 return $res;
	}
	
	public 	function editzip($zipid)
	{
		global $wpdb;
		$zipcode = $_REQUEST['zipcode'];
		global $current_user;get_currentuserinfo();
 		$currentuserID=$current_user->ID;
		
	 $url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($_REQUEST['zipcode'])."&sensor=false";
     $result_string = file_get_contents($url);
     $resultarr = json_decode($result_string, true);
     $lat = "";
     $long = "";
     $finalresult = array();
     if(isset($resultarr['results'][0]['geometry']['location']['lat']))
     {
      $lat = $resultarr['results'][0]['geometry']['location']['lat']; // get first if more than 1 
     }
     if(isset($resultarr['results'][0]['geometry']['location']['lng']))
     {
      $long = $resultarr['results'][0]['geometry']['location']['lng'];
     }
     if(($lat!='')&&($long!=''))
     {
      $finalresult['lat'] = $lat;
      $finalresult['long'] = $long;
      
     }
     else
     {
      if(isset($resultarr['result']['geometry']['location']['lat']))
      {
        $lat = $resultarr['result']['geometry']['location']['lat'];
      }
      if(isset($resultarr['result']['geometry']['location']['lng']))
      {
        $long = $resultarr['result']['geometry']['location']['lng'];
      }
      
      $finalresult['lat'] = $lat;
      $finalresult['long'] = $long;
     }
		
		if($zipid>0)
		{
			$res = $wpdb->update('zc_zip',array(
					"user_id" => $currentuserID,
					"zip"=>$zipcode,
					"lat" => $lat,
					"longitude" => $long,
					"status"=>'Y' ,
				), array('zipid' => $zipid));			 
		}
		else
		{
			
			$res = $wpdb->insert('zc_zip',array(
					"user_id" => $currentuserID,
					"zip"=>$zipcode,
					"lat" => $lat,
					"longitude" => $long,
					"status"=>'Y' 
					
				));
				$user_last_insert_id = $wpdb->insert_id;	
			
		}
		
		
		if($res)
		{
			return 'Insert/Update Successfull';
		}
		else
		{
			return 'Insert/Update Failed';
		}
	}
	
	public 	function deletezip($zipid)
	{
		global $wpdb;
		$wpdb->delete( 'zc_zip', array( 'zipid' => $zipid ), array( '%d' ) );
	
	}
	public function getallZip()
	{
		global $wpdb;
		$devSQL = "SELECT * FROM zc_zip";
		$devRes = $wpdb->get_results($devSQL);
		return $devRes;	
		
	}
	

}


?>