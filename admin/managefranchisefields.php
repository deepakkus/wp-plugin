<?php
require_once(dirname(dirname (__FILE__)).'/lib/franchisefields-db.php');
$newsletterdb = new NewsletterDB();
global $wpdb;
$str_response = '';
$current_user = wp_get_current_user();
if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='del_user'))
{
	if(isset($_REQUEST['ids'])&& $_REQUEST['ids']!='')
	{
		$id = $_REQUEST['ids'];
	}
	if($id !="")
	{
		//echo 'w';
		$userId = rtrim($id,",");
		$subscribers = $wpdb->query("DELETE FROM wp_wysija_user WHERE user_id in (".$userId.")");
		echo 'User has been deleted successfully.';
	}
	/*else
	{
		$subscribers = $wpdb->query("DELETE FROM wp_wysija_user");
	}*/
}
if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='send_allmail'))
{
	sendMail();
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='send_mail'))
{
	if(isset($_REQUEST['ids'])&& $_REQUEST['ids']!='')
	{
		$id = $_REQUEST['ids'];
	}
	sendMail($id);
}

function sendMail($id ="")
{
	global $wpdb;
	$flag = 0;
	$pic = '';
	if($id !="")
	{
		$userId = rtrim($id,",");
		$subscribers = $wpdb->get_results("SELECT * FROM wp_wysija_user WHERE user_id in (".$userId.")");
	}
	else
	{
		$subscribers = $wpdb->get_results("SELECT * FROM wp_wysija_user");
	}
	$args = array( 'post_type' => 'publicity');
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) { $the_query->the_post();
			// Do Stuff
			/*echo the_title();
			echo the_post_thumbnail();*/
		setlocale(LC_ALL, 'fr_FR');
		$curdate = utf8_encode(strftime("%B %d, %G",strtotime(date('Y-m-d'))));
		$url = get_the_title();
		add_image_size( 'category-large', 100, 9999 );
		$pic1= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large',array(10,900)); $pic = $pic1[0];
		/*echo '<pre>';
		echo print_r($pic1);die();*/
		if($pic == 'http://infoaeroquebec.net/wp-includes/images/media/default.png')
		{
			$pic = 'http://infoaeroquebec.net/wp-content/themes/aero/images/logo.jpg';
		}
		list($width, $height, $type, $attr) = getimagesize($pic1);
		if($width > 210) /* Checking the width of image */
		{
			$Twidth = 210;
		}
		else
		{
			$Twidth = $width;
		}
		
		$Theight = round(($height/$width)*$Twidth);
			}
		}
	//$subject = 'Aéro Newsletter'; 
		$subject = 'La Lettre Info Aéro Québec';
		$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
		$to = 'kussoftware@gmail.com';
		$to =  get_option('admin_email');
		$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><link href="http://infoaeroquebec.net/wp-content/themes/aero/css/style3.css" rel="stylesheet" type="text/css" /></head><meta http-equiv="Content-Type"  content="text/html charset=UTF-8" /> ';
	$message .= '<table align="center" width="540px" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" style="padding-top:25px;padding-bottom:40px">
    <tbody>
      <tr>
        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#FFFFFF" align="center">
            <tbody>
              <tr>
                <td><table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-spacing:0">
                    <tbody>
                      <tr>
                        <td height="15">&nbsp;</td>
                      </tr>
                      <tr>
                        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                              <tr>
                                <td><a target="_blank" href="http://infoaeroquebec.net"><img alt="" src="http://infoaeroquebec.net/wp-content/themes/aero/images/logo.jpg" style="border-collapse:collapse" class="CToWUd"></a></td>
                              </tr>
                              <tr>
                                <td align="right"><a href="https://twitter.com/InfoAeroQuebec" target="_blank"><img alt="" src="http://infoaeroquebec.net/rssfeed/images/tw.png"></a>&nbsp;&nbsp;&nbsp;<a href="https://www.facebook.com/pages/Info-Aero-Quebec/544931368882465?fref=ts" target="_blank"><img alt="" src="http://infoaeroquebec.net/rssfeed/images/fb.png"></a> &nbsp;&nbsp;&nbsp;<a href="https://www.linkedin.com/company/info-aero-quebec" target="_blank"><img alt="" src="http://infoaeroquebec.net/rssfeed/images/lin.png"></a>&nbsp;&nbsp;&nbsp;<a href="https://plus.google.com/107354813753518719548/about" target="_blank"><img alt="" src="http://infoaeroquebec.net/rssfeed/images/gplus.png"></a>
                              </tr>';
							  if($pic!='http://infoaeroquebec.net/wp-includes/images/media/default.png')
					{
					$message .= '<tr>
                                <td>
								<a target="_blank" href="'.$url.'"><img alt="" src="'.$pic.'" style="border-collapse:collapse;width: 540px;height:200px;" class="CToWUd" ></a>
								</td>
                              </tr>
	
	';
						}
							  $message .='
                              
                            </tbody>
                          </table></td>
                      </tr>
                      <tr>
                        <td height="15" style="border-bottom:1px solid #dddddd">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;color:#fff;background-color:#1268a0">
                    <tbody>
                      
                      <tr>
                        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                              <tr>
                                <td><div>
                                    <div style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;line-height:20px;color:#000000">
                                      <p style=" margin:5px!important; padding: 0;text-align:center;line-height: 18px;height: 5px;"><span style="font-size:16px;height: 5px;margin: 5px; font-weight:bold;color:#fff;">Articles des sept derniers jours</span></p>
                                      
                                    </div>
                                  </div></td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      
                    </tbody>
                  </table>
                  <table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;color:#000000;border-spacing:0">
                    <tbody>
                      
                      <tr>
                        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                            <tbody>
                              <tr>
                                <td><div>
                                   
                                    
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                                      <tbody>
                                        <tr>
                                          <td valign="top" align="left" >';
                                          
            $temp = $wp_query; $wp_query= null; $wp_query = new WP_Query(); 
	$wp_query->query( array(
			'base' => add_query_arg( 'paged', '%#%' ),'total' => $result->max_num_pages,
	'posts_per_page' => 7, 'cat' => 3,
	'post_type' => 'post','orderby' => 'evnt_date', 'order' => 'DESC','type' => 'DATE','offset'=> 0,'current' => $page,'paged'=>$paged)); 
	
			if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();
			
			$titl = strip_tags(get_the_title()); $cut_len = 70 ; $str_tolen = strlen($titl); $titl = substr($titl,0,$cut_len); if($str_tolen > $cut_len){ $titl = explode(' ',$titl); array_pop($titl); $titl = implode(' ',$titl); } $str_len = strlen($titl);
			
			$art_desc = strip_tags(get_the_content()); $cut_len = 250 ; $str_tolen = strlen($art_desc); $art_desc = substr($art_desc,0,$cut_len); if($str_tolen > $cut_len){ $art_desc = explode(' ',$art_desc); array_pop($art_desc); $art_desc = implode(' ',$art_desc); } $str_len = strlen($art_desc);
			
			$pic1= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail'); $pic=$pic1[0]; if($pic==''){ $pic=catch_that_image(); } if($pic=='') {$pic=get_bloginfo('stylesheet_directory')."/images/No-Image-Basic.png";} ;
			
			$message .='
			<div style="width:100%;font-size:18px;float:left;"><a href="'.get_permalink().'" style="text-decoration:none;"><p style="color:#1B4F7D;font-size:18px; font-weight: bold;">'. $titl.'</p></a></div>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                                          
                                          <tr>
                                          <th scope="col"width="12%"><a href="'.get_permalink().'" style="width:180px;"><img alt="" src="'.$pic.'" ></a></th>
                                            <th scope="col" valign="top"width="88%" align="right">
											<p style="text-align: left;color:#666666;font-size:12px;line-height:18px;padding:0; margin:0px 0px 0px 10px;width:95%;float:right;font-weight: normal; ">'.$art_desc.'</p></th>
                                          </tr>
                                        </table>
			';
			endwhile; endif;                              
                                          $message .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;color:#fff;background-color:#1268a0">
                    <tbody>
                      
                      <tr>
                        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                            <tbody>
                              <tr>
                                <td><div>
                                    <div style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;line-height:20px;color:#000000">
                                      <p style=" margin:5px !important; padding: 0;text-align:center;line-height: 18px; height: 5px;"><span style="font-size:16px;margin: 5px; height: 5px; font-weight:bold;color:#fff;">Éditoriaux des 30 derniers jours</span></p>
                                      
                                    </div>
                                  </div></td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                      
                    </tbody>
                  </table>';
				  $temp = $wp_query; $wp_query= null; $wp_query = new WP_Query(); $wp_query->query( array(
			'base' => add_query_arg( 'paged', '%#%' ),'total' => $result->max_num_pages,
	'posts_per_page' => 7, 'cat' => 4,
	'post_type' => 'post',		'offset'=> 0,'current' => $page,'paged'=>$paged));  
			if ($wp_query->have_posts()) : while ($wp_query->have_posts()) : $wp_query->the_post();
			
			$titl = strip_tags(get_the_title()); $cut_len = 70 ; $str_tolen = strlen($titl); $titl = substr($titl,0,$cut_len); if($str_tolen > $cut_len){ $titl = explode(' ',$titl); array_pop($titl); $titl = implode(' ',$titl); } $str_len = strlen($titl);
			
			$art_desc = strip_tags(get_the_content()); $cut_len = 250 ; $str_tolen = strlen($art_desc); $art_desc = substr($art_desc,0,$cut_len); if($str_tolen > $cut_len){ $art_desc = explode(' ',$art_desc); array_pop($art_desc); $art_desc = implode(' ',$art_desc); } $str_len = strlen($art_desc);
			
			$pic1= wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail'); $pic=$pic1[0]; if($pic==''){ $pic=catch_that_image(); } if($pic=='') {$pic=get_bloginfo('stylesheet_directory')."/images/No-Image-Basic.png";} ;
			list($width, $height, $type, $attr) = getimagesize($pic);
			
			$cti = 1;
		for($ci=1;$ci<=30;$ci++)
		{
			$e_date1 = date('F j, Y',strtotime(' -'.$ci.' day'));
			if($e_date1==get_the_date() && $cti<=7)
			{
			$message .= '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:15px;color:#000000;border-spacing:0">
                    <tbody>
                      
                      <tr>
                        <td><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                            <tbody>
                              <tr>
                                <td><div>
                                   
                                    
                                    <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                                      <tbody>
                                        <tr>
                                          <td valign="top" align="left" >
                                          
                                          <div style="width:100%;font-size:18px;float:left;"><a style="text-decoration: none; "href="'.get_permalink().'"><p style="color:#1B4F7D;font-size:18px; font-weight: bold;">'.$titl.'</p></a></div>
                                          <table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="border-spacing:0">
                                          
                                          <tr>
                                          <th scope="col"width="12%">
										  <a href="'.get_permalink().'" ><img alt="" src="'.$pic.'" width="180" height="124"></a>
										  </th>
                                            <th scope="col" valign="top"width="88%" align="right"><p style="text-align: left;color:#666666;font-size:12px;line-height:18px;padding:0; margin:0px 0px 0px 10px;width:95%;font-weight: normal; float:right;">'.$art_desc.'</p></th>
                                          </tr>
                                        </table>

                                          
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    
                                  </div></td>
                              </tr>
                              
                            </tbody>
                        </table></td>
                      </tr>
                      
                    </tbody>
                  </table>';
			$cti++;
			}
		}
			endwhile; endif;
				  $message .= '

                                          
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <table>
                                    </table>
                                  </div></td>
                              </tr>
                              
                            </tbody>
                        </table></td>
                      </tr>
                      
                    </tbody>
                  </table>
                  </td>
              </tr>
            </tbody>
          </table>
          </td>
      </tr>
    </tbody>
  </table>
  <div style=" width:100%;text-align:center;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:11px;color:#999999;">Info Aéro Québec 
    </div><div style="width:100%;text-align:center;">C.P. 48847 CSP Outremont Outremont, Québec H2V 4V2 CANADA.</div>
  <div style="text-align:center;"><font color="#888888">P.Cauchi@Infoaeroquebec.net </font></div>
	';
	

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	//$headers .= 'From: Info Aéro Québec<info@web-boxdev.co>' . "\r\n";
	$fname = 'Info Aéro Québec';
	$fname = '=?UTF-8?B?'.base64_encode($fname).'?=';
	$headers .= 'From: '.$fname.'<info@infoaeroquebec.net>' . "\r\n";
	
	//'=?UTF-8?B?'.base64_encode($subject).'?=
	//mail('samita@kusmail.com', $subject, $message, $headers);
	//mail('kussoftware@gmail.com', $subject, $message, $headers);
	foreach($subscribers as $subscriber)
	{
		
		$pos = strrpos($subscriber -> email,'@') + 1;
		$e_mail = substr($subscriber -> email,$pos,strlen($subscriber -> email));
		if(mail($subscriber -> email, $subject, $message, $headers))
		{
			$flag = 1;
		}
		//echo substr($subscriber -> email,$pos,strlen($subscriber -> email));die;
		/*if($e_mail == 'hotmail.com')
		{
			if(mail($subscriber -> email, $subject, $message1, $headers))
			{
				$flag = 1;
			}
		}
		else
		{
			if(mail($subscriber -> email, $subject, $message, $headers))
			{
				$flag = 1;
			}
		}*/
	}
	//mail("pravin@kusmail.com", $subject, $message, $headers);
	if($flag == 1)
	{
		echo 'Mail sent successfully.';
	}
}
?>
<link rel="stylesheet" href="<?php echo plugins_url();?>/aero-newsletter/css/style.css" />
<script language="javascript">
function checkAll()
{	
	var arr = new Array();
	for (var i=0;i<document.mail_record.elements.length;i++)
	{
		var e=document.mail_record.elements[i];
		if ((e.name != 'allbox') && (e.type=='checkbox'))
		{
			e.checked=document.mail_record.allbox.checked;
		}
	}
}
function send_mail()
{	
	if(window.confirm('Do you really want to send mail?'))
	{
		var str = "";
		var arr = new Array();
		//alert(document.mail_record.elements.length);
		for (var i=0;i<document.mail_record.elements.length;i++)
		{
			var e=document.mail_record.elements[i];
		
			if ((e.name != 'allbox') && (e.type=='checkbox'))
			{
				if(e.checked==true)
				{
					str += e.value+",";
				}
			}
		}
		if(str=='')
		{
			alert("Please Select Checkbox Option");
			return false;	
		}
		else
		{
			document.mail_record.ids.value=str;
			document.mail_record.mode.value='send_mail';
			document.mail_record.submit();
		}
	}
}
function send_allmail()
{	
	if(window.confirm('Do you really want to send mail to all?'))
	{
		document.mail_record.mode.value='send_allmail';
		document.mail_record.submit();
	}
}
function del_user()
{	
	if(window.confirm('Do you really want to delete?'))
	{
		var str = "";
		var arr = new Array();
		//alert(document.mail_record.elements.length);
		for (var i=0;i<document.mail_record.elements.length;i++)
		{
			var e=document.mail_record.elements[i];
		
			if ((e.name != 'allbox') && (e.type=='checkbox'))
			{
				if(e.checked==true)
				{
					str += e.value+",";
				}
			}
		}
		if(str=='')
		{
			alert("Please Select Checkbox Option");
			return false;	
		}
		else
		{
			document.mail_record.ids.value=str;
			document.mail_record.mode.value='del_user';
			document.mail_record.submit();
		}
	}
}
</script>

<?php if($str_response!=''):?>
<style>
.response_msg
{
	display:block;
}
</style>
<?php endif;?>
<div class="event_detail_view">
<h3><?php if(isset($str_response)) {echo $str_response; } ?></h3>
<form name="frm_business" action="admin.php?page=add_business" method="post">
<input type="hidden" name="id" value="">
<input type="hidden" name="businessId" value="">
<input type="hidden" name="mode" value="">
</form>
<form name="frm_bus" action="admin.php?page=manage_business" method="post">
<input type="hidden" name="id" value="">
<input type="hidden" name="businessId" value="">
<input type="hidden" name="mode" value="">
</form>
<h2>Newsletter</h2>
<form name="mail_record" action="admin.php?page=manage_newsletter" method="post">
<input type="hidden" name="mode"  />
<input type="hidden" name="ids" value=""> 
<table width="99%" cellpadding="2" cellspacing="3" border="0">
	<tr>
		<td align="right" valign="top"><font color="#FF0000"><b><?php if(isset($GLOBALS['err_msg'])) echo $GLOBALS['err_msg'];?></b></font></td>
		<td align="right" valign="top" width="40%">
        <a href="javascript:void(0)" onClick="del_user();">Delete</a>&nbsp;|&nbsp;
         <a href="javascript:void(0)" onClick="send_mail();">Send Mail</a>&nbsp;|&nbsp;
         <a href="javascript:void(0)" onClick="send_allmail();">Send All</a>
        </td>
	</tr>
</table>
<table>
<tr><th>SL NO.</th><th>Email</th><th><!--<input type="checkbox" value=""  name="allbox" onClick="checkAll();"/>-->Check All</th></tr>

<?php $newsletterInfo = $newsletterdb -> get_all_newsletter();?>
<?php
$ni = 1;
?>
<?php
	global $wpdb;
	$tbl_name = $wpdb->prefix."wysija_user";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 3;
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	$query = "SELECT COUNT(*) as num FROM $tbl_name";
	$total_pages = mysql_fetch_array(mysql_query($query));
	$total_pages = $total_pages[num];
	
	/* Setup vars for query. */
	$targetpage = "?page=manage_newsletter"; 	//your file name  (the name of this file)
	//$limit = get_option( 'posts_per_page' );
	$limit = 20;
	if(isset($_REQUEST['pg']) && $_REQUEST['pg']!='') 
		$page = $_REQUEST['pg'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. */
	$sql = "SELECT * FROM $tbl_name LIMIT $start, $limit";
	$result = mysql_query($sql);
	
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&pg=$prev\">Previous</a>";
		else
			$pagination.= "<span class=\"disabled\">Previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&pg=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pg=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&pg=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&pg=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&pg=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&pg=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pg=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&pg=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&pg=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&pg=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&pg=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&pg=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&pg=$next\">Next</a>";
		else
			$pagination.= "<span class=\"disabled\">Next</span>";
		$pagination.= "</div>\n";	
		
	}
?>

	

<?php while($newsletter = mysql_fetch_object($result)):?>
<tr>
    <td>
        <?php echo $ni;?>
    </td>
    <td >
        <?php echo $newsletter->email;?>
    </td>
    <td>
        <input type="checkbox" value="<?php echo $newsletter->user_id;?>"  name="allbox[]" onClick="uncheckAll();"/>
        
    </td>
</tr>
<?php
$ni++;
?>
<?php endwhile;?>
</table>
</form>
<?=$pagination?>
</div>       
  
       