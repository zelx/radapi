<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Quota';
$this->breadcrumbs=array(
	'Quota',
);
?>

<div class="row-fluid" >
	 <div class="span8" align="left">
 		<button id="add_quota" type="button" class="btn btn-info" style="font-size:1em;margin-top:4px;margin-bottom:4px"  data-loading-text="Loading..." onclick="open_addQuotaDi();">เพิ่มโควต้า</button>
 	</div>
	<div class="span2" style="font-size:1em; margin-left:2px;" align="right">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="quota_month" style="font-size:1em;margin-top:7px">เดือน:</label>
			</div>
			<div class="span8" align="left">
				<select class="input-small" type="text" name="quota_month" id="quota_month" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
					<option value="1" >มกราคม</option> 
            		<option value="2" >กุมภาพันธ์</option> 
                	<option value="3" >มีนาคม</option> 
                  	<option value="4" >เมษายน</option> 
                   	<option value="5" >พฤษภาคม</option> 
                  	<option value="6" >มิถุนายน</option> 
                  	<option value="7" >กรกฎาคม</option> 
                  	<option value="8" >สิงหาคม</option> 
                   	<option value="9" >กันยายน</option> 
                   	<option value="10" >ตุลาคม</option> 
                   	<option value="11" >พฤศจิกายน</option> 
                 	<option value="12" >ธันวาคม</option> 
                </select>
        	</div>
    	</div>
	</div>
	<div class="span2" style="font-size:1em; margin-left:2px" align="left">
		<div class="row-fluid">
			<div class="span4" align="right">
				<label for="quota_year" style="font-size:1em;margin-top:7px">ปี:</label>
			</div>
      		<div class="span8" align="left">
       			<select class="input-small" type="text" name="quota_year" id="quota_year" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
              		<option value="2553" >2553</option> 
                 	<option value="2554" >2554</option> 
                 	<option value="2555" >2555</option> 
                  	<option value="2556" >2556</option> 
                  	<option value="2557" >2557</option> 
                 	<option value="2558" >2558</option> 
                 	<option value="2559" >2559</option> 
                 	<option value="2560" >2560</option> 
                 	<option value="2561" >2561</option> 
                	<option value="2562" >2562</option> 
                  	<option value="2563" >2563</option> 
                 	<option value="2564" >2564</option> 
                 	<option value="2565" >2565</option> 
                 	<option value="2566" >2566</option> 
                  	<option value="2567" >2567</option> 
                 	<option value="2568" >2568</option> 
                 	<option value="2569" >2569</option> 
                  	<option value="2570" >2570</option> 
             	</select>
          	</div>
      	</div>
  	</div>
</div>
<div class="container" id="page" style="width:inherit"> 
 <div class="row-fluid">
   	<div class="">
		<table align="center" class="table table-bordered" id="quota_table">
          <thead bgcolor="#99CCCC">
            <tr style="font-size:0.8em;height:25px;border-color:#000;font-stretch:condensed">
            </tr>
          </thead>
          <tbody style="font-size:0.8em">
          </tbody>
        </table>
 	</div>
 </div>
 <!--
 <div class="pagination pagination-right">
  <ul>
    <li ><a href='#'><<</a></li>
    <li class="active"><a href='#'>1</a></li>
    <li ><a href='#'>2</a></li>
    <li ><a href='#'>3</a></li>
    <li ><a href='#'>>></a></li>
  </ul>
 </div>
 -->
</div>

<script>

//-----------> Initial Operation of Quota ------------->

function LastDayOfMonth(Year, Month) {
	
	var quota_date = new Date( (new Date(Year, Month,1))-1 );
	
    return(quota_date.getDate());
}

function generateTableHeader(MaxDayofMonth){

	$("#quota_table thead tr th").remove();	
	
	$("#quota_table thead tr").append(
		
		"<th style='width:28%;text-align:center;padding:6px'>ชื่อเอเจนซี่</th>"
	)
	
	for( var count_day = 1; count_day <= MaxDayofMonth; count_day++){
		
		$("#quota_table thead tr").append(
		
			"<th style='width:2%;text-align:center;padding:2px'>"+count_day+"</th>"
		)		
		
	}		
}

function zeroPadQuota(num, places) {
	
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
  
}


function showQuotaTable(year,month){
	
	var MaxofMonth = 0;
	var cnt_date = 0;
	var cnt_date2 = 0;
	
	MaxofMonth = LastDayOfMonthRe1(year, month);
	
	$.ajaxSetup({
		async: false
	});
	month = zeroPadQuota(month, 2);

	console.log(year,month);
	$.ajax( '?r=quota/japi&action=readerQuota&yr_mo='+year+'-'+month+' ', {
	  type: 'GET',
	  dataType: 'json',
	  
		success: function(readerQuota) {
			
			var current_agency = 0;
			var current_minute = 0;
			
			var date_start = 0;
			var date_end = 0;
			
			$("#quota_table tbody tr").remove();
			$.each(readerQuota, function(k,v){
				
				//console.log("prog_id="+v.prog_id+"program="+v.prog_name+" day="+v.day+" time="+v.onairtime)
				//console.log("agency="+ v.agency_name+" minute="+v.minute)
				
				if(current_agency != v.agency_id){
					
					var current_agencyname = v.agency_name;
						current_agency = v.agency_id;
						
					var trTable = "<tr  style='height:20px;padding-top:4px'>";
					trTable += "<td style='text-align:center;padding:2px'>"+current_agencyname+"</td>";
					
					
					for(var countdate = 1; countdate <= MaxofMonth; countdate++){
						
						cnt_date = zeroPadQuota(countdate, 2);
						
							trTable+="<td style='text-align:center;padding:2px'> <p class='click' id='agency"+current_agency+"_day"+cnt_date+"'></p></td>"; 
	
					}
					
					trTable += "</tr>";
					
					$("#quota_table tbody").append(trTable);
					
						date_start  =  new Date(v.date_start);
						date_end  =  new Date(v.date_end);
						
						date_start =  parseInt(date_start.getDate());	
						date_end =  parseInt(date_end.getDate());	
						
					/*for( var countdate2 = date_start; countdate2 <= date_end; countdate2++){
						
						cnt_date2 = zeroPadQuota(countdate2, 2);
						
						$("#quota_table tbody tr td#agency"+current_agency+"_day"+cnt_date2).text(v.minute);
						
					}*/
					quota_monthly = v.monthly.split(',');
					daycnt = 1;
					$.each(quota_monthly, function(k,v){
						//console.log("day" + cnt_date2);
						daystr = zeroPadQuota(daycnt, 2);
						if(parseInt(v) > 0){
							$("#quota_table tbody tr td p#agency"+current_agency+"_day"+daystr).text(v);
						}
						daycnt++;
					});
					
					
				}else{
					
					
				}
				
			});// Each Reader
		}
	});
	
	$(function() {
		$(".click").editable("?r=quota/addQuotaMinute", { 
/*			indicator : "<img src='img/indicator.gif'>",*/
			tooltip   : "Click to edit...",
			style  : "inherit",
			submitdata : function(value, settings) {
				var addquotaYear = zeroPadQuota($("#quota_month").val(),2);
				var addquotaMonth = $("#quota_year").val()-543;
				return {yr_mo: addquotaMonth+'-'+addquotaYear};
			},
			callback : function(value, settings) {
				 console.log(this);
				 console.log(value);
				 console.log(settings);
			 }
		});	
	});
	
}

$(document).ready(function() {
	
	var quota_current_date = 0;
	var quota_current_month = 0;
	var quota_current_year = 0;
	
	quota_current_date  =  new Date();
	quota_current_month =  parseInt(quota_current_date.getMonth())+1;	
	quota_current_year = parseInt(quota_current_date.getFullYear())+543;
	
	var quotaCurrentYear = quota_current_date.getFullYear();
	
	$("#quota_month").val(quota_current_month);
	$("#quota_year").val(quota_current_year);
	
	generateTableHeader(LastDayOfMonth(quotaCurrentYear, quota_current_month));
	showQuotaTable(quotaCurrentYear,quota_current_month);
	//console.log("Maximum_date="+LastDayOfMonth(quota_current_year, quota_current_month));
});

$("#quota_month").change(function(){
	
	var quota_select_month = parseInt($("#quota_month").attr('value'));
	var quota_select_year = parseInt($("#quota_year").attr('value'))-543;	
	
	generateTableHeader(LastDayOfMonth(quota_select_year, quota_select_month));
	showQuotaTable(quota_select_year,quota_select_month);
	//console.log("Maximum_date="+LastDayOfMonth(quota_select_year, quota_select_month));
	
})

$("#quota_year").change(function(){
	
	var quota_select_month = parseInt($("#quota_month").attr('value'));
	var quota_select_year = parseInt($("#quota_year").attr('value'))-543;	
	
	generateTableHeader(LastDayOfMonth(quota_select_year, quota_select_month));
	showQuotaTable(quota_select_year,quota_select_month);
	//console.log("Maximum_date="+LastDayOfMonth(quota_select_year, quota_select_month));
	
})

//-----------> End Initial Operation of Quota ------------->

</script>


<?php
 //--------------- Start of Dialog for Adding Quota  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'addquotaDi',
        'options'=>array(
            'title'=>'เพิ่มโควต้า',
			'width'=>400,
			'height'=>320,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:addQuota',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
<div class="dialog_input" >
        <div class="row-fluid" align="center" style="margin-top:20px">
        	<div class="span4" align="right">
            	<label for="add_quota_agenname" style="margin-top:4px;font-size:1em">ชื่อเอเจนซี่:</label>
          	</div>
           	<div class="span8" align="left">
          		<select class="input-small" type="text" name="add_quota_agenname" id="add_quota_agenname" style="font-size:1em;width:200px;padding-top:3px;padding-bottom:3px" ></select>
          	</div>
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span4" align="right">
            	<label for="add_start_quota" style="margin-top:4px;font-size:1em">เริ่มต้น:</label>
          	</div>
           	<div class="span3" align="left">
            	<select class="input-small" type="text" name="add_start_quota" id="add_start_quota" style="font-size:1em;width:70px;padding-top:3px;padding-bottom:3px" ></select>
          	</div>
           	<div class="span5" align="left" style="margin-left:0px">
            	<input type="text" name="add_startM_quota" id="add_startM_quota" class="text ui-widget-content ui-corner-all"  style="font-size:1em;width:100px;" readonly="readonly"/>
          	</div>
      	</div>
        <div class="row-fluid" align="center" >
           	<div class="span4" align="right">
            	<label for="add_end_quota" style="margin-top:4px;font-size:1em">ถึง:</label>
          	</div>
           	<div class="span3" align="left">
            	<select class="input-small" type="text" name="add_end_quota" id="add_end_quota" style="font-size:1em;width:70px;padding-top:3px;padding-bottom:3px" ></select>
          	</div>
           	<div class="span5" align="left" style="margin-left:0px">
            	<input type="text" name="add_endM_quota" id="add_endM_quota" class="text ui-widget-content ui-corner-all"  style="font-size:1em;width:100px;" readonly="readonly"/>
          	</div>            
        </div>
        <div class="row-fluid" align="center" >
        	<div class="span4" align="right">
            	<label for="add_minute_quota" style="margin-top:4px;font-size:1em">ระยะเวลา(นาที):</label>
          	</div>
           	<div class="span8" align="left">
				<input type="text" name="add_minute_quota" id="add_minute_quota" class="text ui-widget-content ui-corner-all"  style="font-size:1em;width:55px;" />
          	</div>
        </div>
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for Adding Quota  --------------
?>

<script>

//----------- Add quota operation ----------------
$(function() {
	
$(".click").editable("?r=quota/addQuotaMinute", { 
/*	indicator : "<img src='img/indicator.gif'>",*/
	tooltip   : "Click to edit...",
	style  : "inherit",
	submitdata : function(value, settings) {
		var addquotaYear = zeroPadQuota($("#quota_month").val(),2);
		var addquotaMonth = $("#quota_year").val()-543;
		return {yr_mo: addquotaMonth+'-'+addquotaYear};
	},
	callback : function(value, settings) {
         console.log(this);
         console.log(value);
         console.log(settings);
     }
});
/*$(".editable_textarea").editable("http://www.appelsiini.net/projects/jeditable/php/save.php", { 
      indicator : "<img src='img/indicator.gif'>",
      type   : 'textarea',
      submitdata: { _method: "put" },
      select : true,
      submit : 'OK',
      cancel : 'cancel',
      cssclass : "editable"
});*/
});

function open_addQuotaDi(){
	
$('#addquotaDi').dialog('open');
showAgency_addQuota();
generateDateSelector();

return false;
}

function showAgency_addQuota(){
	
	$.ajaxSetup({
		async: false
	});

	$.ajax('?r=quota/japi&action=readerAgency',{
		type: 'GET',
		dataType: 'json',
		success: function(readerAgency){
		$("#add_quota_agenname option").remove();			
			$.each(readerAgency,function(k,v){
					
				$("#add_quota_agenname").append( 
				
					"<option value="+v.agency_id+">"+v.agency_name+"</option>"
					
				);		
			});
		}
	});
}

function generateDateSelector(){
	
	var addquotaYear = $("#quota_month").val();
	var addquotaMonth = $("#quota_year").val();
	
	var MaxDay = LastDayOfMonth(addquotaYear,addquotaMonth);

	$("#add_start_quota option").remove();	
	for( var count_day = 1; count_day <= MaxDay; count_day++){
		
		$("#add_start_quota").append(
			
			"<option value='"+count_day+"'>"+count_day+"</option>"
		)	
		
	}
	
	var addquotaSeMonth = $("#quota_month option:selected").text();
	var addquotaSeYear = $("#quota_year option:selected").text();
	
	$("#add_end_quota option").remove();	
	for( var count_day = 1; count_day <= MaxDay; count_day++){
		
		$("#add_end_quota").append(
			
			"<option value='"+count_day+"'>"+count_day+"</option>"
		)	
		
	}
	
	$("#add_startM_quota").val(addquotaSeMonth+" "+addquotaSeYear);
	$("#add_endM_quota").val(addquotaSeMonth+" "+addquotaSeYear);
				
}

function gen_quotaMinMonth(year,month,minute){
	
	var minmonth_array = [];
	
	MaxofMonth = LastDayOfMonthRe1(year, month);
	for(var genday = 1;  genday <= MaxofMonth; genday++){
		
		
		minmonth_array[ parseInt(genday)-1] = minute;
		
	}
	
	var minmonth_str = minmonth_array.join(',');
	return(minmonth_str);
}

function addQuota(){
	
	var delay = 0;
	var addQStartDay = $("#add_start_quota").val();
	var addQEndDay = $("#add_end_quota").val();
	var addQMinute = $("#add_minute_quota").val();
	var addQAgenID = $("#add_quota_agenname").val();
	
	var quota_select_month = parseInt($("#quota_month").attr('value'));
	var quota_select_year = parseInt($("#quota_year").attr('value'))-543;
	
	var addQStartDate = quota_select_year+"-"+quota_select_month+"-"+addQStartDay;
	var addQEndDate = 	quota_select_year+"-"+quota_select_month+"-"+addQEndDay;
	
	var quotaminMonth = gen_quotaMinMonth(quota_select_year,quota_select_month,addQMinute);
	
	if(quotaminMonth){
	
	
		$.ajaxSetup({
			async: false
		});
		
		$.ajax({
			
			type: "POST",
			url: "?r=quota/addQuotaMinMonth",	
			data:{'start_date':addQStartDate,'end_date':addQEndDate, 'minute':addQMinute, 'agency_id':addQAgenID,'monthly':quotaminMonth},
			
			success: function(data) {
				//alert(data);
			},
			error: function(data){
							
				alert(data);	
			}
			
		});
		
		//console.log("quotaminMonth= "+quotaminMonth);
		showQuotaTable(quota_select_year,quota_select_month)
		//while(delay < 1000){delay++;}
		$(this).dialog("close");
		
	}else {
		
		alert("กรุณาระบุระยะเวลาสำหรับเอเจนซี่");
		
	}
	
}

//----------- End Add quota operation ----------------

</script>
