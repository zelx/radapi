<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<div id="yw0_tab_1" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
 <div class="row-fluid">
  </div>
    <h2>Reconcile Queue</h2>
    <div class="row-fluid" id="reconcile_queue"></div>
    
	<h4>ToDay Program</h4>
    <div class="row-fluid"  align="center">
	<div class="">
            <div class="container" id="page" style="width:inherit">
              <div class="row-fluid"  align="left">
                <div class="">
                    <table align="center" class="table table-striped" id="todayprogram" >
                      <thead align="center">
                        <tr style="font-size:1em;height:25px;">
                          <th style="width:70%;text-align:left;padding:6px">รายการ</th>
                          <th style="width:10%;text-align:left;padding:6px">จัดคิว</th>
                          <th style="width:10%;text-align:left;padding:6px">รอคิว</th>
                          <th style="width:10%;text-align:left;padding:6px">เวลาทั้งหมด</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:0.8em;height:25px">
                      </tbody>
                    </table>
                </div>
             </div>
       		</div>
	</div>
    </div> 
  </div>
 </div>
    

<?php
 //--------------- Start of Dialog for update Agency  --------------
/*	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'confirmReconDi',
        'options'=>array(
            'title'=>'ลบผู้ติดต่อ',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:confirmReconDb',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
	
	*/
?>

<!---
<div class="dialog_input" >
        <div class="row-fluid" style="margin-top:30px" align="center" id="dlete_cont_id" value="">
       ยืนยันการ Reconcile Queue
        </div>
</div>   
--->
<?php

/*
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for update Agency  --------------
	
*/
?>   
    
</dv> <!---  End of Panel Tab --->

<script>

function format_datetime_recon(date)
{
	var d  =  new Date(date);
	var year = output = d.getFullYear()+543;
	year = year%100;
	
	var month = d.getMonth()+1;
	var day = d.getDate();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();

	var output = day+" "+month_define_recon(month)+" "+year;
		//console.log("Date:"+month);
		return output;
}

function month_define_recon(month) {
	
	switch(month){
		
		case 1:
			  var month_name = "มค.";
		  break;
		case 2:
			  var month_name = "กพ.";
		  break;
		 case 3:
			  var month_name = "มีค.";
		  break;
		case 4:
			  var month_name = "เมย.";
		  break;
		case 5:
			  var month_name = "พค.";
		  break;
		 case 6:
			  var month_name = "มิย.";
		  break;
		case 7:
			  var month_name = "กค.";
		  break;
		case 8:
			  var month_name = "สค.";
		  break;
		case 9:
			 var month_name = "กย.";
		  break;
		case 10:
			  var month_name = "ตค.";
		  break;
		case 11:
			  var month_name = "พย.";
		  break;
		case 12:
			  var month_name = "ธค.";
		  break;	
		  
		default:
			  var month_name = "มค.";
	}
	
	return(month_name);
	
	
}

function openconfirmDi(break_id,prog_id){
	
	$('#confirmReconDi').dialog('open');
		
	return false;	
}


function confirmReconDb(break_id,prog_name){
	
		
	var todaydate = new Date().toString("dd-MM-yyyy");
	var todaytime = new Date().toString("HH:mm");
	var prog_id = 0;

	$.ajaxSetup({
		async: false
	});

	$.ajax('?r=reconcile/japi&action=updateRecocileq&break_id='+break_id+'&date='+todaydate+'&time='+todaytime+'',{
			
		type: 'GET',
		dataType: 'json',
		success: function(updateRecocileq){
			$("#reconcile_queue div").remove();
			$.each(updateRecocileq,function(k,v){ 
			
				if(prog_id != v.prog_id){
					
					prog_id = v.prog_id;
					//console.log("ProgramName="+v.prog_id);
					
					$("#reconcile_queue").append("<div id='reconProg"+prog_id+"' class='row-fluid'><h4>"+v.prog_name+"</h4></div>");
					$("#reconProg"+prog_id).append("<div class='span2' align='left' style='margin-left:10px'>"+format_datetime_recon(v.datetime)+"</div><div class='span10' align='right' style='margin-left:2px'><a hef='' onclick='confirmReconDb("+v.break_id+")'>Reconcile Queue</a></div>");
					
				}else {
					
					$("#reconProg"+prog_id).append("<div class='span2' align='left' style='margin-left:10px'>"+format_datetime_recon(v.datetime)+"</div><div class='span10' align='right' style='margin-left:2px'><a hef='' onclick='confirmReconDb("+v.break_id+")'>Reconcile Queue</a></div>");
					
				}
				
			});
		}
	});	
	
}


//------------> Read and Generate Reconcile Queue ------------>

function generate_reconcileq(){
	
	var todaydate = new Date().toString("dd-MM-yyyy");
	var todaytime = new Date().toString("HH:mm");
	//console.log("today= "+todaydate);
	
	var prog_id = 0;
	
	$.ajaxSetup({
		async: false
	});

	$.ajax('?r=reconcile/japi&action=reconcileQueue&date='+todaydate+'&time='+todaytime+'',{
			
		type: 'GET',
		dataType: 'json',
		success: function(reconcileQueue){
			$("#reconcile_queue div").remove();
			$.each(reconcileQueue,function(k,v){ 	
			
				//console.log("ProgramName= "+v.prog_name+" break_id= "+v.break_id+" Datetime= "+v.datetime);
				if(prog_id != v.prog_id){
					
					prog_id = v.prog_id;
					//console.log("ProgramName="+v.prog_id);
					
					$("#reconcile_queue").append("<div id='reconProg"+prog_id+"' class='row-fluid'><h4>"+v.prog_name+"</h4></div>");
					$("#reconProg"+prog_id).append("<div class='span2' align='left' style='margin-left:10px'>"+format_datetime_recon(v.datetime)+"</div><div class='span10' align='right' style='margin-left:2px'><a hef='' onclick='confirmReconDb("+v.break_id+")'>Reconcile Queue</a></div>");
					
				}else {
					
					$("#reconProg"+prog_id).append("<div class='span2' align='left' style='margin-left:10px'>"+format_datetime_recon(v.datetime)+"</div><div class='span10' align='right' style='margin-left:2px'><a hef='' onclick='confirmReconDb("+v.break_id+")'>Reconcile Queue</a></div>");
					
				}
			});
		}
	});
	
}

function today_program(){
//todayprogram
	var todaydate = new Date().toString("dd-MM-yyyy");
	//console.log("today= "+todaydate);
	
	var prog_id = 0;
	
	$.ajaxSetup({
		async: false
	});

	$.ajax('?r=reconcile/japi&action=todayProgram&date='+todaydate+'',{
			
		type: 'GET',
		dataType: 'json',
		success: function(todayProgram){
			$("#todayProgram tbody tr").remove();
			$.each(todayProgram,function(k,v){ 
			
				v.total_advqueue_time;
				v.total_advpending_time;
				v.total_break_time;
			
				$("#todayprogram tbody").append(
					"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
						"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
						"</td>"+
						"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
						"</td>"+
						"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
						"</td>"+
						"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
						"</td>"+
					"</tr>"
				);
			});
		}
	});
	
}


$(document).ready(function() {
	
	
	 generate_reconcileq();
	
});



//--------> End of Read and Generate Reconcile Queue ---------> 


</script> 
