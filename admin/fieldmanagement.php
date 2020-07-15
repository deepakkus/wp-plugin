<?php
require_once(dirname(dirname (__FILE__)).'/lib/franchisefields-db.php');
require_once(dirname(dirname (__FILE__)).'/lib/profilefields-db.php');
$franchisedb = new FranchisefieldsDB();
$profiledb = new ProfileFieldsDB();
global $wpdb;
global $str_response;
$current_user = wp_get_current_user();
$labelname = '';
$placeholder = '';
$fieldname = '';
$fieldtype = '';
$userId = "";


if(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='addfields'))
{
	add_formfields();
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='managefields'))
{
	manage_formfields($userId);
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='profilefields'))
{
	add_form_fields();
}
elseif(isset($_REQUEST['mode'])&&($_REQUEST['mode']=='deletefields'))
{
	if(isset($_REQUEST['fieldId'])&&($_REQUEST['fieldId']!=''))
	{
		$id = $_REQUEST['fieldId'];
		delete_form_fields($id);
	}
}
else
{
	main();
}
//echo '<pre>';
//print_r($_REQUEST);

//$content = $franchisedb -> getPagecontent();



?>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<link rel="stylesheet" href="<?php echo plugins_url();?>/profile-management/css/style.css" />
<script language="javascript">
function check_form(frm)
{
	frm.mode.value = "profilefields";

}
function add_fields()
{
	frm = document.frm_fields;
	frm.mode.value = "addfields";
	//frm.userId.value = val;
	//frm.profileId.value = val2;
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
	frm.fieldId.value = id;
	var userResp = window.confirm("Are you sure you want to delete?");
	if( userResp == true )
 	{
  		frm.submit();
 	}
	
}
function show_fields(val)
{
	
	if(val=='S' || val=='R' || val=='C')
	{
		$(".optvalueno").css("display","block");
	}
	else
	{
		$(".optvalueno").css("display","none");
	}
	
	
}
function showOptions()
{
	var optno = $(".option_value").val();
	var html = '';
	for(i=0;i<optno;i++)
	{
		html += '<ul class="opt_frm"><li class="input optvalue"><label>Display Value:</label><input type="text" name="displayvalue[]" value="" class="inploginbig" size="40" style="width:94%;"></li><li class="input optvalue"><label>Option Value:</label><input type="text" name="optvalue[]" value="" class="inploginbig" size="40" style="width:94%;"></li><li class="input optvalue"><label>Display Order:</label><input type="text" name="displayorder[]"+i value="" class="inploginbig" size="40" style="width:94%;"></li></ul>';
	}
	//alert(html);
	$(".opt_display_sect").html(html);
	$(".opt_display_sect").css("display","block");
}
</script>

<?php 

function main(){
	$profiledb = new ProfileFieldsDB();

	$formfields = $profiledb -> getFormFields();
	?>
<div class="form_mains">
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Field Management</h1>
</div>
<?php if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }?>
<div class="add_field_text">
			<a class="add-new-h2" href="javascript:void(0)" onclick="add_fields()">Add Fields</a>
		</div>
<div class="form_main">

<!--<div class="page_text">
<h2><?php //echo $content;?></h2>

</div>-->
<form name="frm_fields" method="post" action="">
<input type="hidden" name="mode"  />
<input type="hidden" name="fieldId"  />
<input type="hidden" name="profileId"  />
</form>
<div class="main_user">
<table style="width:100%;" class="wp-list-table widefat fixed posts">
	<tr>
    	<td >
        Display Name
        </td>
         <td >
        Field Name
        </td>
        <td >
        Field Type
        </td>
        <td >
        Options
        </td>
         <td >
        Field For
        </td>
        <td>
        Action
        </td>
    </tr>
    <?php
	
	foreach($formfields as $fields){
		
		$ftype = $fields->field_type;
		
		if($ftype=='S' || $ftype=='C' || $ftype=='R')
		{
			$optionfields = $profiledb -> getoptionFields($fields->field_id);
		}
		$ffor = $fields->profile_type;
		if($ffor== 'FE')
		{
			$fldfor='Franchise';
		}
		elseif($ffor== 'FO')
		{
			$fldfor='Franchisor';
		}
		else
		{
			$fldfor='Franchise & Franchisor';
		}
		$fld_type = "";
		if($ftype=='T')
		{
			$fld_type = "Textbox";
		}
		elseif($ftype=='TA')
		{
			$fld_type = "Textarea";
		}
		elseif($ftype=='R')
		{
			$fld_type = "Radio";
		}
		elseif($ftype=='C')
		{
			$fld_type = "Checkbox";
		}
		elseif($ftype=='S')
		{
			$fld_type = "Select";
		}
		
	?>
    <tr>
    	<td >
        <?php echo $fields->display_name;?>
        </td>
         <td >
        <?php echo $fields->profile_column;?>
        </td>
        <td >
        <?php echo $fld_type;?>
        </td>
         <td >
        <?php 
		if($ftype=='S' || $ftype=='C' || $ftype=='R')
		{
			$opt1='';
			foreach($optionfields as $opt)
			{
			$opt1.=$opt->option_display.' , ';
			}
			echo substr($opt1,0,-2);
		}
		?>
        </td>
        <td >
        <?php echo $fldfor;?>
        </td>
        <td>
			<a href="javascript:void(0)" onclick="delete_fields(<?php echo $fields->field_id?>)">Delete Fields</a>
        </td>
    </tr>
    <?php
		
	}
	?>
</table>
</div>
<?php
}
?>
<?php

?>
<?php
function add_formfields()
{
?>
<div class="form_mains">
<h1 style="border-bottom:1px solid #ccc;line-height:30px;">Field Management</h1>
</div>
<div class="total_right_box">
<div class="middle_box">
<?php if(isset($GLOBALS['str_response'])){?>
<div class="show_box"><?php if(isset($GLOBALS['str_response'])){echo $GLOBALS['str_response'];}?></div>
<?php }?>
<div class="box">
      <div class="box-header">
        <span class="title">Add Form Field Form</span>
        <ul class="box-toolbar">
          <li>
            <i class="icon-refresh"></i>
          </li>
          
        </ul>
      </div>
		      <div class="box-content">
<form name="frm_form_fields" action="" method="post" class="fill-up" onsubmit="return check_form(this)">
<input type="hidden" value="" name="mode">
<input type="hidden" value="83" name="schoolId">
    <input type="hidden" value="" name="fieldId">
     
               <input type="hidden" value="true" name="backlists"> 
               		 <div class="from_box">
			   <div class="from_inner">
				<select name="profiletype">
                <option value="FE">Franchise</option>
                <option value="FO">Franchisor</option>
                <option value="B">Both</option>
                </select>
              <ul class="padded separate-sections">
				<li class="input">
                 <label>Label:</label>
                <input type="text" name="label" value="" class="inploginbig" size="40"></li>
                <!--<li class="input">
                 <label>Placeholder:</label>
               <input type="text" name="placeholder" value="" class="inploginbig" size="40"></li>-->
                <li class="input">
                 <label>Field Name:</label>
               <input type="text" name="fldname" value="" class="inploginbig" size="40"></li>
                <!--<li class="input">
                 <label>Error Message:</label>
               <input type="text" name="emsg" value="" class="inploginbig" size="40" ></li>-->
               <li class="input">
                 <label>Display Order:</label>
               <input type="text" name="displayorder" value="" class="inploginbig" size="40" ></li>
               <!--<li>
                <label>Status</label>
                  &nbsp;&nbsp;
                        <input name="status" type="radio" checked="" value="Y"  class="icheck">
                        <label for="iradio1">Active</label>
                      	&nbsp;&nbsp;
                        <input name="status" type="radio" value="N"  class="icheck" >
                        <label for="iradio2">In-Active</label> 
                        </li>-->
               
              </ul>
              </div> 
               
               <div class="from_inner">
               <ul class="padded separate-sections">
                <li class="input">
                <!-- <label>Form Element:</label>
               <input type="radio" onclick="chkinpt();" checked="" value="I" class="icheck" id="frm_elementI" name="frm_element">
                        <label for="iradio1">Input</label>
                      	&nbsp;&nbsp;
               <input type="radio" value="S" class="icheck" id="frm_elementS" name="frm_element" onclick="return chkinpt();">
                        <label for="iradio2">Select</label>-->
               </li>
               
               <div id="only_input">
               <ul>
               <li id="input_type" class="input" size="40" >
                 <label>Field Type:</label>
                 <select id="inputtype" name="input_type" onchange="show_fields(this.value)">
                 <option value="T">Textbox</option>
                 <option value="TA">Textarea</option>
                 <option value="R">Radio</option>
                 <option value="C">Checkbox</option>
                 <option value="S">Select</option>
                 </select>
               </li>               
                <li class="input optvalueno">
                 <label>Option Number:</label>
               <input type="text" name="optvalue" value="" class="option_value" size="40" style="width:30%;">
               <input class="btn btn-blue_go" type="button" value="Go" onclick="return showOptions()" >
               </li>
               </ul>
               <div class="opt_display_sect">
               
               </div>
               <!--<li id="ordercolumn" class="input">
                 <label>Column Order:</label>
          <input type="radio" checked="" value="ONE" id="order_column" class="icheck" name="order_column">
                        <label for="iradio1">ONE</label>
                      	&nbsp;&nbsp;
          <input type="radio" value="TWO" id="order_column" class="icheck" name="order_column">
                        <label for="iradio2">TWO</label>
               </li>-->
               </div>
               
               
               
              </ul>
            </div>
             
          </div>
          <div class="form-actions">
          <?php
		  	if(isset($_REQUEST['userId'])&&($_REQUEST['userId']!=''))
			{
				$userId = $_REQUEST['userId'];
			}
		  ?>
          	<input type="hidden" name="userId" value="<?php echo $userId;?>"  />
			<input type="hidden" name="profileId"  />
            <input class="btn btn-blue" type="submit" value="Submit" >
            <!--<button class="btn btn-default" type="button">Cancel</button>-->
          </div>
          
         </form>
      </div>
    </div>
</div>

</div>
<?php
}
?>
<?php
function add_form_fields()
{
	$profiledb = new ProfileFieldsDB();
	$str_response= $profiledb -> editProfileList();
	
	//echo .'iiii';
	if($str_response== 1)
	{
		
		$GLOBALS['str_response']='Successfully saved form field !!';
		main();
	}
	else
	{
		
		$GLOBALS['str_response']=$str_response;
		add_formfields();
		
		
	}
}
function delete_form_fields($id)
{
	$profiledb = new ProfileFieldsDB();
	$str_response = $profiledb -> deleteFormFields($id);
	$GLOBALS['str_response']=$str_response;
	main();
}

?>
</div>     
  
       