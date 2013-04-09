<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Onair';
$this->breadcrumbs=array(
	'Onair',
);
date_default_timezone_set('Asia/Bangkok');
?>
<style>

#onair_prodlist_sp{ position: absolute; border: 1px solid #e3e3e3; }
div.toolTip{ padding-left: 10px; padding-right: 10px; }
div.toolTip:hover{ border: 1px solid #e3e3e3; background-color: blue; color:black; }


#breakinglist { list-style-type: none; margin: 0; padding: 0; width:auto; }
#breakinglist li { margin: 3px 3px 3px 0; padding: 1px;font-size: 3em; text-align:left; }
#advplan li {font-size:3em;}
#history { list-style-type: none; margin: 0; padding: 0; width:auto; }
#history li { margin: 3px 3px 3px 0; padding: 1px;font-size: 1em; text-align:left; }
#break1 {font-size: 3em;}
/*
#ui-datepicker-div {
	width:200px;
	font-size:0.3em;
}
*/

<!--- Start ToolTip -------!>

.bubbleInfo {
    position: relative;
}

.popup {
	border-radius: 5px;
	background: #fff;
	box-shadow: 0px 0px 14px rgba(0,0,0,0.3);
	color: #2c2c2c;
	width:600px;
    position: absolute;
	margin-left:-680px;
    display: none; /* keeps the popup hidden if no JS available */
}

<!--- End ToolTip -------!>


.tooltipster-shadow {
	border-radius: 5px;
	background: #fff;
	box-shadow: 0px 0px 14px rgba(0,0,0,0.3);
	color: #2c2c2c;
}
.tooltipster-shadow .tooltipster-content {
	font-family: 'Arial', sans-serif;
	font-size: 14px;
	line-height: 16px;
	padding: 8px 10px;
}



.ui-ams-advbrk {font-size: 1.6em;  color: #ffffff;
  background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}

.ui-ams-advbrk2 {font-size: 1.6em;  color: #ffffff;
  background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}
.progress {
	background-color: #0088cc;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;
}

.ui-ams-adv {
	margin: 0;
	height: 25px;
/*    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px;*/ 
}

.ui-ams-advpending{font-size: 5em;  color: #ffffff;
  background-color:#F90;
    -webkit-border-radius: 5px;
     -moz-border-radius: 5px;
          border-radius: 5px; 
}

.ui-ams-warning{font-size: 0.95em;  color: #ffffff;
  background-color:#F90;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
		text-decoration: underline;
}

.ui-ams-success{font-size: 0.95em;  color: #ffffff;
  background-color:#0C0;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}

.ui-ams-danger{font-size: 0.95em;  color: #ffffff;
  background-color:#F03;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}

.ui-ams-inactive{font-size: 0.95em;  color: #ffffff;
  background-color:#CCC;
    -webkit-border-radius: 2px;
     -moz-border-radius: 2px;
          border-radius: 2px; 
}


.ui-ams-disable-link{   

pointer-events: none;
   cursor: default;
   
}




#toolbar {
 	padding: 10px 4px;
}

#breakinglist > .ui-ams-advbrk > .row-fluid > .span4 > .ui-button-text {
	color:#fff;
	padding: 1px 2px;
}

</style>

 
<script>


function readprogList(){ 

	$.ajaxSetup({
		async: false
	});

	$(document).ready(function() {
		$.ajax('?r=onair/japi&action=progList',{
			type: 'GET',
			dataType: 'json',
			success: function(progList){
			//var breakid=0;
			$("#prog_on option").remove();			
				$.each(progList,function(k,v){ // each of break
					$.each(v,function(kon,von){
					$("#prog_on").append(
												 
							 "<option value='"+von.prog_id+"'>"+von.prog_name+"</option>"
					);
				 });
							
				});
			 }
		});
	});
}

function read_bkplan(program,year,month,day){
	
	var break_plan = 0;
	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=onair/japi&action=readBKPlan&program='+program+'&year='+year+'&month='+month+'&day='+day+'', {
	  type: 'GET',
	  dataType: 'json',
		  success: function(readBKPlan){
			  
			$("#programplans div").remove();  
			$.each(readBKPlan, function(k,v){
				
				break_plan = v.break_plan;
				create_bkplanButton(break_plan);
				
			});				
		  }  
	});
}

function create_bkplanButton(break_plan){ // Create Break Plan Button
	
	if(break_plan){
				
		if(break_plan == 0){  // Create Plan "0"
			
			
			var reservePlans = $("<div id='breakplan0'  class='btn-group' data-toggle='buttons-radio' style='font-size:1.6em;margin-left:3px; margin-top:0px'><button onclick='enable_bkplan(0)' id='onair_plan0' type='button' class='btn ui-ams-breakplan' style='width:100px' value='0'>คิวจริง</button></div>"); 
			
			$("#programplans").append(reservePlans);	
				
					 
		}else{  // Create Other Plans
			
			var cnt_plan = 0;
			var maxPlan = 0;
			var onair_planId = [];
						
			$(".ui-ams-breakplan").each(function() {
							
				onair_planId[cnt_plan++] =  $(this).attr("value");
							
			});
						
			maxPlan = Math.max.apply( Math, onair_planId ); //Maximum plan value for creating NEW plan id
				
			var reservePlans = $("<div id='breakplan"+parseInt(break_plan)+"' class='btn-group' style='font-size:1.6em;margin-top:0px' data-toggle='buttons-radio'><button onclick='enable_bkplan("+parseInt(break_plan)+")' class='btn ui-ams-breakplan' value='"+parseInt(break_plan)+"' id='onair_plan"+parseInt(break_plan)+"'>คิวสำรองที่ "+parseInt(break_plan)+"</button><button id='onair_plan_sub' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret' style='height:8px'></span></button><ul class='dropdown-menu' style='width:80px'><li><a onclick='delete_plan("+parseInt(break_plan)+")'>ลบคิวสำรองที่ "+parseInt(break_plan)+"</a></li></ul></div> ");
			
			$("#programplans").append(reservePlans);	
			
		}
			
	}else {
				
		$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");	
	}

}


function adv_history(history_adv_id,history_orgadv_id,history_operation,history_orgprog_id,history_break_id,history_datetime){
	
		var prev_history_datetime = 0;
		var prev_history_orgadv_id = 0;
		var prev_history_adv_id = 0;
		var history_table = "";
		
		
		if(history_operation == "moved"){ //ย้ายมา
		
		var movedmainadv_prodname = 0;
		var movedmainadv_advname = 0;
		var movedmainadv_timelenght = 0;
		var movedmainadv_agencyname = 0;
		var movedmainadv_datetime = 0;
		var movedmainadv_progname = 0;
										
		$.ajaxSetup({
			async: false
		});
		$.ajax( '?r=onair/japi&action=deterMainAdv&adv_id='+history_adv_id+'', {
			type: 'GET',
			dataType: 'json',
				success: function(deterMainAdv) {
					$.each(deterMainAdv, function(k_main,v_main){
													
						movedmainadv_prodname = v_main.prod_name;
						movedmainadv_advname = v_main.tape_name;
						movedmainadv_timelenght = v_main.adv_time_len;
						movedmainadv_agencyname = v_main.agency_name;
													
					});
				}
		});
									
									
		$.ajaxSetup({
			async: false
		});
		$.ajax( '?r=onair/japi&action=deterOrigProg&adv_id='+history_adv_id+'', {
			type: 'GET',
			dataType: 'json',
				success: function(deterOrigProg) {
										  
					$.each(deterOrigProg, function(k_mainProg,v_mainProg){
						movedmainadv_datetime = v_mainProg.datetime;
						movedmainadv_progname = v_mainProg.prog_name;
						
						history_table += "<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left'>"+movedmainadv_prodname+"</td>" + 
											"<td style='text-align:left'>"+movedmainadv_advname+"</td>" +
											"<td style='text-align:right'>"+movedmainadv_timelenght+"</td>" +
											"<td style='text-align:left'>"+movedmainadv_agencyname+"</td>" +
											"<td style='text-align:left'>ย้ายมาจาก "+movedmainadv_progname+" "+format_datetime(movedmainadv_datetime)+"</td>" +
																		
										"</tr>";
									
											//console.log(v_mainProg.prog_name);
					});
				}
		});					

									
		history_table += "<tr style='height:10px;padding-top:4px;padding-bottom:4px'>"+ 
							"<td style='text-align:left'></td>" + 
							"<td style='text-align:left'></td>" +
							"<td style='text-align:left'></td>" +
							"<td style='text-align:left'></td>" +
							"<td style='text-align:left'></td>" +
						"</tr>" ;
								
		}
	
	return (history_table);
}


function checkBreak(program,year,month,day){

	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=onair/japi&action=breakcheck&program='+program+'&year='+year+'&month='+month+'&day='+day+'', {
	  type: 'GET',
	  dataType: 'json',
		  success: function(breakcheck) {
			  
			var break_id = 0;
			var active_plan = 0; 
			var reconsile = 0;
			var onair_prof_id = 0;
			var dayWeekNum = 0;
			var timeStart = 0;
			
			$.each(breakcheck, function(k,v){
				
				break_id = v.break_id;
				active_plan = v.active_plan;
				reconsile = v.reconsile;

			});
			
			$("#prog_on").attr("break_id",break_id); // HIDDEN BREAK ID

			
			if(break_id != 0){
				
				showBreak(program,year,month,day);
				$("#createbreak").removeAttr("disabled");// Enable Create Break		
				
				read_bkplan(program,year,month,day); //Read and show plan	
				$("#programplans").children("div").find("button.active").removeClass("btn-inverse active"); // Enable breakplan
				$("#onair_plan"+active_plan).addClass("btn-inverse active"); // Enable breakplan
				$("#programplantitle").text($("#onair_plan"+active_plan).text()); // Enable breakplan			
				
			}else{	
			
				$("#createbreak").attr("disabled","disabled");// Disable Create Break
				$("#time_bk_lev").css('width',0+"%");
				$("#time_bk_lev").attr("title",0+"%");	
				$('#usage_bk').text("00:00");
				$('#all_bk').text("00:00");
				$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
				
				$("#breakinglist li").remove();
				$("#history_table tbody tr").remove();
				//preBreak(program,year,month,day);
				
			}
			
			// Control Advertise List by detecting RECONSILE
			//console.log("reconsile= "+reconsile);			
			
	}
  });
			
}


function showBreak(program,year,month,day){
	
	//console.log("year:"+year+" month:"+month);
	var waiting_bk = [];
	var waiting_bk_cnt = 0;

	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=onair/japi&action=breakshow&program='+program+'&year='+year+'&month='+month+'&day='+day+'', {
	  type: 'GET',
	  dataType: 'json',
		  success: function(breakshow) {
		  var break_seq=0;
		  var adv_seq=0;
		  var timelimit = 0;
		  var totaltime = 0;
		  var totalbreak = 0;
		  var timelength = 0;
		  var adv_id =0;
		  var prog_on = $("#prog_on").attr('value');
		  
		  //------ All break time --->
		  
		  var all_totaltime = 0;
		  var all_totalbreak = 0;
		  var all_totalbreak_percent = 0;
		  var all_mins = 0;
		  var all_secs = 0;
		  
		  //<------------------------

		  $("#breakinglist li").remove();
		$.each(breakshow, function(k,v){
			adv_seq = v.adv_seq;
			adv_id = v.adv_id;
			
			if(v.adv_time_len != 0){
				
				timelength = v.adv_time_len;
				
			}else{
				
				timelength = v.time_len;
			
			}
			
			var mins = Math.floor(parseInt(timelength)/60);
			var secs = parseInt(timelength) % 60;

			//console.log(v);
			if(v.break_seq != 0){
				
				var onair_prof_id = v.onair_prof_id;
				var dayWeekNum = v.dayweek_num;
				var timeStart = v.time_start;
				$("#prog_on").attr("onair_prof_id",onair_prof_id); // HIDDEN Onair Prof ID
				$("#prog_on").attr("dayweek_num",dayWeekNum); // HIDDEN Onair Prof ID
				$("#prog_on").attr("time_start",timeStart); // HIDDEN Onair Prof ID
				
				if(break_seq != v.break_seq){
					break_seq = v.break_seq;
					timelimit = v.time_len;
					totaltime = parseInt(timelength);
					totalbreak = timelimit;
					var totalbreak_mins = Math.floor(timelimit/60);
					var totalbreak_secs = timelimit % 60;
					
					var onairTime = v.onairtime.split(':');
					var onairTimeMin = onairTime[0];
					var onairTimeSec = onairTime[1];
					
					//console.log("onairtime= "+v.onairtime+" break_type_name= "+v.break_type_name);
					//console.log("totalbreak_mins= "+totalbreak_mins+" totalbreak_secs= "+totalbreak_secs);
					
					$("#breakinglist").append("<li class='ui-ams-advbrk' id='break"+break_seq+"' >"+
						"<div class='row-fluid'>"+
							"<div class='span8' align='left'>"+
								"<button title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+break_seq+"' onclick=modalAdvertiseOpen("+break_seq+","+0+"); return false;>"+
									"<span class='ui-button-text'>เพิ่มโฆษณา</span>"+
								"</button>"+
								"<span class='ui-button-text' style='font-size:1em;color:white;'>เบรคที่ "+break_seq+"</span>"+
								"<span id='breakType' breakTypeID='"+v.break_type_id+"' class='ui-button-text' style='font-size:1em;color:white;'>"+
									"<a style='font-size:1em;color:white;cursor:pointer;visibility:visible' onclick=changeBreakPropOpen('"+v.break_type_id+"','"+v.break_type_name+"','"+onairTimeMin+":"+onairTimeSec+"',"+break_seq+",'"+ v.time_len+"');return false;>("+v.break_type_name+")  </a>"+
								"</span>"+
								"<span id='breakOnairTime' onairTime='"+onairTimeMin+":"+onairTimeSec+"' class='ui-button-text' style='font-size:1em;color:white;'>"+
									"<a style='font-size:1em;color:white;cursor:pointer;visibility:visible'  onclick=changeBreakPropOpen('"+v.break_type_id+"','"+v.break_type_name+"','"+onairTimeMin+":"+onairTimeSec+"',"+break_seq+",'"+ v.time_len+"');return false;>เวลาออกอากาศ: "+onairTimeMin+":"+onairTimeSec+"</a>"+
								"</span>"+
							"</div>"+
							"<div class='span2' align='left'>"+
								"<a id='totalbreak' style='float:right;margin-right:10px;cursor:pointer;visibility:hidden'>"+totalbreak_mins+":"+totalbreak_secs+"</a>"+
								"<a style='float:right;margin-right:10px;visibility:hidden'>/</a>"+
							"</div>"+
							"<div class='span2' align='right'>"+
								"<span id='breaktime' style='font-size:1em;color:white;float:left;margin-left:80px;margin-top:5px'>เวลารวม: "+mins+":"+secs+"</span>"+
								"<a title='ลบเบรค' onclick=deleteBreak("+break_seq+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></a>"+
							"</div>"+
						"</div></li>"
					);
					
/*
					$("#breakinglist").append("<li class='ui-ams-advbrk' id='break"+break_seq+"' ><div class='row-fluid'><div class='span4' align='left'><span class='ui-button-text'>เบรคที่ "+break_seq+"</span><button title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+break_seq+"' onclick=modalAdvertiseOpen("+break_seq+","+0+"); return false;><span class='ui-button-text'>เพิ่มโฆษณา</span></button></div><div class='span6' align='left'><a id='totalbreak' style='float:right;margin-right:10px;cursor:pointer;visibility:hidden'>"+totalbreak_mins+":"+totalbreak_secs+"</a><a style='float:right;margin-right:10px;visibility:hidden'>/ </a></div><div class='span2' align='right'><span id='breaktime' style='font-size:1em;color:white;float:left;margin-left:80px;margin-top:5px'>"+mins+":"+secs+"  </span><a title='ลบเบรคและชุดโฆษณาในเบรค' onclick=deleteBreak("+break_seq+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></a></div></div></li>");

*/		
				}else{
					
					totaltime = totaltime+ parseInt(timelength);
				}
				
				all_totaltime += parseInt(totaltime); 
				all_totalbreak  += parseInt(totalbreak);
				
				var totalbreak_percent = (totaltime / totalbreak)*100;
				
				var on_breaktype = v.break_type;
				var on_cal_price = v.price;
				var on_break_info = v.break_desc;
				var on_break_discount = v.discount;
				var on_net_price = v.net_price;
				var on_pkg_id = v.pkg_id;
				
				if(on_breaktype == ""){ on_breaktype = " "; }
				
				if(on_cal_price == "" ){ 
				
					on_cal_price = 0;
					on_net_price = 0;
				 }
				 
				if(on_break_info == ""){ on_break_info = " ";}
				if(on_break_discount == ""){ on_break_discount = 0;}
				if(on_pkg_id == ""){on_pkg_id = 0;}
				if(v.adv_seq > 0){
					
				$("#breakinglist").append("<li  list_adv='bk"+break_seq+"advseq"+adv_seq+"' class='ui-state-default ui-ams-adv'  style='width:95%' value='"+adv_id+"' >"+
				"<div class='adv_each_list' id='break_"+break_seq+"' style='margin-top:2px;max-height:25px'>"+
					"<p><span style='margin-left:3px' ><img src='images/icon-draggable.png' /></span>"+
					"<span id='bk"+break_seq+"prod"+adv_seq+"' class='property_bk' time_bk='"+timelength+"'  bk_type='"+on_breaktype+"' spot_price='"+on_cal_price+"' on_discount='"+on_break_discount+"' net_price='"+on_net_price+"' pkg_id='"+on_pkg_id+"' bk_info='"+on_break_info+"' tape_id='"+v.tape_id+"' prod_id='"+v.prod_id+"' agency_id='"+v.agency_id+"'  style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move;max-height:20px;overflow:hidden' >"+v.prod_name+"</span>"+
					"<span id='bk"+break_seq+"advname"+adv_seq+"' style='width:30%; max-width:30%;  display:inline-block; cursor:move; max-height:20px;overflow:hidden'>"+v.tape_name+"</span>"+
					"<span id='timelen'  style='width:10%; display:inline-block' align:'right'>"+mins+':'+secs+"</span>"+
					"<span id='bk"+break_seq+"agency"+adv_seq+"' style='width:15%; max-width:15%; display:inline-block;cursor:move;max-height:20px;overflow:hidden'>"+v.agency_name+"</span>"+
					"<span style='display:inline-block;'  align='right' >"+
						"<span id='tooltipAdvId"+adv_id+"' style='display:inline-block;visibility:hidden' class='bubbleInfo'  rel='"+adv_id+"'><a class='trigger'><img src='images/clock.png' style='width:20px;margin-right:5px;' align='right' /></a><a class='popup'></a></span>"+
						"<span style='display:inline-block;' align='right'><a title='แก้ไข'onclick=updateOnair("+break_seq+","+adv_seq+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span>"+
						"<span style='display:inline-block;' align='right'><a title='ลบชุดโฆษณา'onclick=deleteAdvertise("+break_seq+","+adv_seq+"); ><img src='images/delete_2.png' style='width:20px;margin-right:5px;cursor:pointer' align='right' /></a></span>"+
					"</span></p>"+
					"</div></li>");
				}
			}else{
				
				if(v.adv_seq){
					
					waiting_bk[waiting_bk_cnt++] = v;
					
				}
			};
			
		  });// end of each(breakofday)
		  
		  //console.log(waiting_bk);
		  $("#breakinglist").append("<li class='ui-ams-advpending' id='pending' align='center'>คิวรอโฆษณา</li>");
		  
		  $.each(waiting_bk, function(key,val){
			  
			if(val.adv_time_len != 0){
				
				timelength = val.adv_time_len;
				
			}else{
				
				timelength = val.time_len;
			
			}
			
			var mins = Math.floor(parseInt(timelength)/60);
			var secs = parseInt(timelength) % 60;
			
			
			var on_breaktype = val.break_type;
			var on_cal_price = val.price;
			var on_break_info = val.break_desc;
			var on_break_discount = val.discount;
			var on_net_price = val.net_price;
			var on_pkg_id = val.pkg_id;
				
			if(on_breaktype == ""){ on_breaktype = " "; }
				
			if(on_cal_price == "" ){ 
				
					on_cal_price = 0;
					on_net_price = 0;
			}
				 
			if(on_break_info == ""){ on_break_info = " ";}
			if(on_break_discount == ""){ on_break_discount = 0;}
			if(on_pkg_id == ""){on_pkg_id = 0;}
			
			  
			$("#breakinglist").append("<li list_adv='bk0advseq"+val.adv_seq+"' class='ui-state-default ui-ams-adv'  style='width:95%' value='"+val.adv_id+"'>"+
				"<div class='adv_each_list' id='break_0' style='margin-top:2px;max-height:25px'>"+
					"<p><span style='margin-left:3px' ><img src='images/icon-draggable.png' /></span>"+
						"<span id='bk"+val.break_seq+"prod"+val.adv_seq+"' class='property_bk' time_bk='"+timelength+"'  bk_type='"+on_breaktype+"' spot_price='"+on_cal_price +"' on_discount='"+on_break_discount+"' net_price='"+on_net_price+"' bk_info='"+on_break_info+"'  tape_id='"+val.tape_id+"' prod_id='"+val.prod_id+"' pkg_id='"+on_pkg_id+"' agency_id='"+val.agency_id+"'  style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move;max-height:20px;overflow:hidden' >"+val.prod_name+"</span>"+
						"<span id='bk"+val.break_seq+"advname"+val.adv_seq+"' style='width:30%; max-width:30%;  display:inline-block; cursor:move; max-height:20px;overflow:hidden'>"+val.tape_name+"</span>"+
						"<span id='timelen' style='width:10%; display:inline-block' align:'right'>"+mins+':'+secs+"</span>"+
						"<span id='bk"+val.break_seq+"agency"+val.adv_seq+"' style='width:15%; max-width:15%; display:inline-block;cursor:move;max-height:20px;overflow:hidden'>"+val.agency_name+"</span>"+
						"<span style='display:inline-block;'  align='right' >"+
							"<span id='tooltipAdvId"+val.adv_id+"' style='display:inline-block;visibility:hidden' class='bubbleInfo'  rel='"+val.adv_id+"'><a class='trigger'><img src='images/clock.png' style='width:20px;margin-right:5px;' align='right' /></a><a class='popup'></a></span>"+
							"<span style='display:inline-block;'><a title='แก้ไข'onclick=updateOnair("+val.break_seq+","+val.adv_seq+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span>"+
							"<span style='display:inline-block;' ><a title='ลบชุดโฆษณา'onclick=deleteAdvertise("+val.break_seq+","+val.adv_seq+"); ><img src='images/delete_2.png' style='width:20px;margin-right:5px;cursor:pointer' align='right' /></a></span>"+
						"</span>"+
					"</p>"+
				"</div></li>");
			  
			 // console.log(v);
		  });
		  
		  progressupdate();
		  show_history(); // ------ > update  HISTORY 
		  
		  }//Success Function
	  
	  });//AJAX
	  	  
			$('.tooltip').tooltipster({
				theme: '.tooltipster-shadow',
				content: 'Loading...',
				functionBefore: function(origin, continueTooltip) {
				
				  // we'll make this function asynchronous and allow the tooltip to go ahead and show the loading notification while fetching our data
				  continueTooltip();
					//tooltip_id = $this.attr("id");
					var tooltip_a = origin.find('a')['context'];
					var adv_id_tooltip = $(tooltip_a).text();

					//console.log(tooltip_a);
					//console.log();
				  // next, we want to check if our data has already been cached
				  if (origin.data('ajax') !== 'cached') {
					  
					 $.ajax({
						type: 'GET',
						url: '?r=onair/japi&action=tooltipHistory&adv_id='+adv_id_tooltip+'',
						dataType:'json',
						success: function(tooltipHistory) {
							
							var tooltiptable = "<table id='tooltipHistory' align='center' class='table table-striped' id='history_table'>"+
                                    "<thead>"+
										"<tr style='font-size:1em;height:25px;'>"+
											"<th style='width:30%;text-align:left;padding:6px'>ชื่อสินค้า</th>"+
                                         	"<th style='width:25%;text-align:left;padding:6px'>ชื่อโฆษณา</th>"+
                                            "<th style='width:10%;text-align:right;padding:6px'>เวลา(วินาที)</th>"+
                                         	"<th style='width:15%;text-align:left;padding:6px'>เอเจนซี่</th>"+
                                          	"<th style='width:20%;text-align:left;padding:6px'>การแก้ไข</th>"+
                                        "</tr>"+
                                      "</thead>"+
                                      "<tbody style='font-size:1em'>";
									  
							var check_advid = 0;		  
									  
							$.each(tooltipHistory, function(k,val){
								
								
								var history_operation = val.operation;
								var history_orgadv_id = val.orig_adv_id;
								var history_adv_id = val.adv_id;
								var history_orgprog_id = val.orig_prog_id;
								var history_break_id = val.orig_break_id;
								var history_datetime = val.timestamp; 
														
								if((history_operation == "moved") && check_advid != history_adv_id){
								
									console.log("check_advid= "+check_advid);
									check_advid = history_adv_id;
									tooltiptable += adv_history(check_advid,history_orgadv_id,history_operation,history_orgprog_id,history_break_id,history_datetime);//console.log(adv_history(history_adv_id,history_orgadv_id,history_operation,history_orgprog_id,history_break_id,history_datetime))
								
								}
								//console.log("adv_id_tooltip="+adv_id_tooltip+"operation= "+val.operation);	
							
							});	  
                         	
							tooltiptable += "</tbody></table>";
									
						   // update our tooltip content with our returned data and cache it
						   origin.tooltipster('update', tooltiptable).data('ajax', 'cached');
						   
						},
						error: function(data){				
							origin.tooltipster('update', "มีข้อผิดพลาด "+$(tooltip_a).text()).data('ajax', 'cached');
						}	
						
					 });
				  }
				}
		});	  
		
		

			$('.bubbleInfo').each(function () {
			    // options
			    var distance = 10;
			    var time =150;
			    var hideDelay = 100;

			    var hideDelayTimer = null;
			
			    // tracker
			    var beingShown = false;
			    var shown = false;
			    var getvalue = $(this).attr('rel');
			    var trigger = $('.trigger', this);
			    var popup = $('.popup', this).css('opacity', 0);
				
			    // set the mouseover and mouseout on both element
			    $([trigger.get(0), popup.get(0)]).mouseover(function (e) {
			      // stops the hide event if we move from the trigger to the popup element
			      if (hideDelayTimer) clearTimeout(hideDelayTimer);
				  
			      var tooltipHtml;
					
			      $.ajax({
						type: 'GET',
						url: '?r=onair/japi&action=tooltipHistory&adv_id='+getvalue+'',
						dataType:'json',
						success: function(tooltipHistory) {
							
							var tooltiptable = "<table id='tooltipHistory' align='center' class='table table-striped' id='history_table'>"+
                                  "<thead>"+
										"<tr style='font-size:1em;height:25px;'>"+
											"<th style='width:30%;text-align:left;padding:6px'>ชื่อสินค้า</th>"+
                                       	"<th style='width:25%;text-align:left;padding:6px'>ชื่อโฆษณา</th>"+
                                          "<th style='width:10%;text-align:right;padding:6px'>เวลา(วินาที)</th>"+
                                       	"<th style='width:15%;text-align:left;padding:6px'>เอเจนซี่</th>"+
                                        	"<th style='width:20%;text-align:left;padding:6px'>การแก้ไข</th>"+
                                      "</tr>"+
                                    "</thead>"+
                                    "<tbody style='font-size:1em'>";
									  
							var check_advid = 0;		  
									  
							$.each(tooltipHistory, function(k,val){
								
								
								var history_operation = val.operation;
								var history_orgadv_id = val.orig_adv_id;
								var history_adv_id = val.adv_id;
								var history_orgprog_id = val.orig_prog_id;
								var history_break_id = val.orig_break_id;
								var history_datetime = val.timestamp; 
														
								if((history_operation == "moved") && check_advid != history_adv_id){
								
									
									check_advid = history_adv_id;
									tooltiptable += adv_history(check_advid,history_orgadv_id,history_operation,history_orgprog_id,history_break_id,history_datetime);//console.log(adv_history(history_adv_id,history_orgadv_id,history_operation,history_orgprog_id,history_break_id,history_datetime))
								
								}
								//console.log("adv_id_tooltip="+adv_id_tooltip+"operation= "+val.operation);	
							
							});
							tooltiptable += "</tbody></table>";
							tooltipHtml=tooltiptable;
														
							},
							error: function(data){				
								
							}	
						 });
					  	
						
					  
					$(".popup").html(tooltipHtml);
			     
				  // don't trigger the animation again if we're being shown, or already visible
			      if (beingShown || shown) {
			        return;
			      } else {
			        beingShown = true;
					
						var mouseX=0;
						var mouseY=0;
						$(document).mousemove( function(e) {
   						mouseX = e.pageX; 
   						mouseY = e.pageY;
						});
															        // reset position of popup box
			        popup.css({
					  top: e.pageY-340,
			          left: e.pageX,
			          display: 'block' // brings the popup back in to view
			        })
			        // (we're using chaining on the popup) now animate it's opacity and position
			        .animate({
			          top: '-=' + distance + 'px',
			          opacity: 1
			        }, time, 'swing', function() {
			          // once the animation is complete, set the tracker variables
			          beingShown = false;
			          shown = true;
			        });
			      }
			    }).mouseout(function () {
			      // reset the timer if we get fired again - avoids double animations
			      if (hideDelayTimer) clearTimeout(hideDelayTimer);
			      
			      // store the timer so that it can be cleared in the mouseover if required
			      hideDelayTimer = setTimeout(function () {
			        hideDelayTimer = null;
			        popup.animate({
			          top: '-=' + distance + 'px',
			          opacity: 0
			        }, 100, 'swing', function () {
			          // once the animate is complete, set the tracker variables
			          shown = false;
			          // hide the popup entirely after the effect (opacity alone doesn't do the job)
			          popup.css('display', 'none');
			        });
			      }, hideDelay);
			    });
			  });
			  
}


function breakplanopen(bk){
	//alert("break plan "+bk);
	$("#advplan").find('li.active.ui-ams-breakplan').removeClass('active');
	$("#advplan").children("li#breakplan"+bk).addClass('active');
	//$(".ui-ams-breakplan").removeClass('active');
	
}

function zeroPad(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}

function progressupdate(){
	
			var breakid;
			var breaker;
			var breaksum=0;
			var totalbreak=0;
			
			var all_totalbreak = 0;
			var all_breaksum = 0;
			var all_breaksum_pen = 0;
			var all_mins = 0;
			var all_secs = 0;
			
			var update_daytab = 0;
			var pending_advtime = 0;
			
			//console.log($("#breakinglist"));
			 $("#breakinglist").find('li').each(function(){
				var current = $(this);
				var mins;
				var secs;
				//console.log(current);
				 if(current.attr('id')){
					breakid = current.attr('id');
					breaker = current;
					var tb = breaker.children('div').children('div').children('a#totalbreak').text().split(':');
					totalbreak = parseInt(tb[0]*60) + parseInt(tb[1]);
					
					if(current.attr('id') != "pending"){
						
						all_totalbreak += parseInt(totalbreak);
						
					}else if(current.attr('id') == "pending"){
						
						pending_advtime = 0;
						
					}
					all_breaksum  += parseInt(breaksum);
					breaksum=0;
					//console.log("totalbreak="+totalbreak)
				 }else{
					 
					var x = current.children('div').children('p').children('span#timelen').text().split(':');
					breaksum = breaksum + parseInt(x[0]*60) + parseInt(x[1]);
					pending_advtime = breaksum;
 
				 }
				 
				 //console.log("pending_advtime= "+pending_advtime);
	
			  if(all_breaksum > all_totalbreak){
				  
				var deltabreaksum = all_breaksum - all_totalbreak;
						
				mins = Math.floor(deltabreaksum/60);
				secs = parseInt(deltabreaksum) % 60;
				mins = zeroPad(mins, 2);
				secs = zeroPad(secs, 2); 
				
				var totalbreak_percent = (deltabreaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text("-"+mins+":"+secs);
				
				$("#time_bk_lev").css('width',"100%");
				$("#time_bk_lev").attr("title","Over 100%");	
				$("#time_bk_lev").removeClass();
				$("#time_bk_lev").addClass("bar bar-warning");

				update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
				$("#"+update_daytab).removeClass("ui-ams-inactive");
				$("#"+update_daytab).removeClass("ui-ams-success");
				$("#"+update_daytab).removeClass("ui-ams-warning");
				$("#"+update_daytab).removeClass("ui-ams-danger");
				$("#"+update_daytab).addClass("ui-ams-warning");

				
				
				var minBreak = Math.floor(breaksum/60);
				var secBreak = parseInt(breaksum) % 60;
				minBreak = zeroPad(minBreak, 2);
				secBreak = zeroPad(secBreak, 2);
				breaker.children('div').children('div').children('span#breaktime').text(minBreak+":"+secBreak);
				
				//console.log("Danger")
				//current.children('div').css('color','red');
				current.children('div').css('color', 'blue');	
				
			  }else if(all_breaksum == all_totalbreak &&  all_totalbreak != 0){
						
				mins = Math.floor(all_breaksum/60);
				secs = parseInt(all_breaksum)% 60;
				mins = zeroPad(mins, 2);
				secs = zeroPad(secs, 2);
				
				var totalbreak_percent = (all_breaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text(mins+":"+secs);
				
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");	
				$("#time_bk_lev").removeClass();
				$("#time_bk_lev").addClass("bar bar-success");
				
				if(pending_advtime == 0){
					
					update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
					$("#"+update_daytab).removeClass("ui-ams-inactive");
					$("#"+update_daytab).removeClass("ui-ams-success");
					$("#"+update_daytab).removeClass("ui-ams-warning");
					$("#"+update_daytab).removeClass("ui-ams-danger");
					$("#"+update_daytab).addClass("ui-ams-success");
					
				}else {
					
					update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
					$("#"+update_daytab).removeClass("ui-ams-inactive");
					$("#"+update_daytab).removeClass("ui-ams-success");
					$("#"+update_daytab).removeClass("ui-ams-warning");
					$("#"+update_daytab).removeClass("ui-ams-danger");
					$("#"+update_daytab).addClass("ui-ams-warning");	
					
				}
				
				var minBreak = Math.floor(breaksum/60);
				var secBreak = parseInt(breaksum) % 60;
				minBreak = zeroPad(minBreak, 2);
				secBreak = zeroPad(secBreak, 2);
				breaker.children('div').children('div').children('span#breaktime').text(minBreak+":"+secBreak);
				
				//current.children('div').css('color', 'green');
				current.children('div').css('color', 'blue');
				
			  }else if ( all_breaksum == 0 && all_totalbreak == 0){
				  
				totalbreak_percent = 0;
				 
				$('#usage_bk').text("00:00");
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");
				
				var minBreak = Math.floor(breaksum/60);
				var secBreak = parseInt(breaksum) % 60;
				minBreak = zeroPad(minBreak, 2);
				secBreak = zeroPad(secBreak, 2);
				breaker.children('div').children('div').children('span#breaktime').text(minBreak+":"+secBreak);
				  
			  }else {
				
				mins = Math.floor(all_breaksum/60);
				secs = parseInt(all_breaksum)% 60;
				mins = zeroPad(mins, 2);
				secs = zeroPad(secs, 2);
				
				var totalbreak_percent = (all_breaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text(mins+":"+secs);
						
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");
				
				$("#time_bk_lev").removeClass();
				$("#time_bk_lev").addClass("bar bar-danger");
				
				update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
				$("#"+update_daytab).removeClass("ui-ams-inactive");
				$("#"+update_daytab).removeClass("ui-ams-success");
				$("#"+update_daytab).removeClass("ui-ams-warning");
				$("#"+update_daytab).removeClass("ui-ams-danger");
				$("#"+update_daytab).addClass("ui-ams-danger");
				
				var minBreak = Math.floor(breaksum/60);
				var secBreak = parseInt(breaksum) % 60;
				minBreak = zeroPad(minBreak, 2);
				secBreak = zeroPad(secBreak, 2);
				breaker.children('div').children('div').children('span#breaktime').text(minBreak+":"+secBreak);
				
				  
				current.children('div').css('color', 'blue');
			  } 
			  
			  all_mins = Math.floor(all_totalbreak/60);
			  all_secs = parseInt(all_totalbreak) % 60;
			  all_secs = zeroPad(all_secs, 2);
			  all_mins = zeroPad(all_mins, 2);
			  
			  $('#all_bk').text(all_mins+":"+all_secs);
			
			})

}

//-------> month function ---------

//-------> month function ---------

function format_datetime(date) //swordzoro add
{
	var d  =  new Date(date);
	var year = output = d.getFullYear()+543;
	year = year%100;
	
	var month = d.getMonth()+1;
	var day = d.getDate();
	var hour = d.getHours();
	var minute = d.getMinutes();
	var second = d.getSeconds();

	var output = day+" "+month_define(month)+" "+year;
		//console.log("Date:"+month);
		return output;
}

function month_define(month) { //swordzoro datetime

	//switch($("#onair_mon").attr('value')){ //swordzoro comment
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


//<------end of month function ----


function create_new_plan(){
	
	var cnt_plan = 0;
	var maxPlan = 0;
	var onair_planId = [];
		
	$(".ui-ams-breakplan").each(function() {
			
		onair_planId[cnt_plan++] =  $(this).attr("value");
			
	});
		
	maxPlan = Math.max.apply( Math, onair_planId ); //Maximum plan value for creating NEW plan id

	var reservePlans = $("<div id='breakplan"+parseInt(maxPlan+1)+"' class='btn-group' style='font-size:1.6em;margin-top:0px' data-toggle='buttons-radio'><button onclick='enable_bkplan("+parseInt(maxPlan+1)+")' class='btn ui-ams-breakplan' value='"+parseInt(maxPlan+1)+"' id='onair_plan"+parseInt(maxPlan+1)+"'>คิวสำรองที่ "+parseInt(maxPlan+1)+"</button><button id='onair_plan_sub' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret' style='height:8px'></span></button><ul class='dropdown-menu' style='width:80px'><li><a onclick='delete_plan("+parseInt(maxPlan+1)+")'>ลบคิวสำรองที่ "+parseInt(maxPlan+1)+"</a></li></ul></div> ");

	$("#programplans").append(reservePlans);	
	
}

function update_activeplan(program,year,month,day,plan){
	
	$.ajaxSetup({
		async: false
	});
	$.ajax({
		type: "POST",
		url: "?r=onair/updateActivePlan",
		data:{'prog_id':program,'year':year,'month':month,'day':day,'plan':plan},
			
			success: function(data) {									
					//alert(data);
					
			},
			error: function(data){				
				alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ");		
			}	
											   
	});		

}


function backtoPrevPlan(){
	
	var previous_plan = $('#createQueueDi').attr("prevQ");
	
	var prog_on = $("#prog_on").attr('value');
	var onair_mon = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	var day = $("#ul_daytab").find("li.ui-state-active").text();
	
	update_activeplan(prog_on,onair_year,onair_mon,day,previous_plan);
	checkBreak(prog_on,onair_year,onair_mon,day);
	
	$("#programplantitle").text($("#onair_plan"+previous_plan).text());
	$(this).dialog("close");
	//console.log("BackToQ "+previous_plan)
		
}

function check_activeplan(plan,previous_plan){
	
	var num_plan = 0;
	
	var prog_on = $("#prog_on").attr('value');
	var onair_mon = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	var day = $("#ul_daytab").find("li.ui-state-active").text();
	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=onair/japi&action=checkPlan&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'&plan='+plan+'', {
	  type: 'GET',
	  dataType: 'json',
		  success: function(checkPlan){
			  
			$.each(checkPlan, function(k,v){
				
				num_plan = v.num_plan;
				
			});				
		  }  
	});
	
	if(num_plan > 0){ // Already have PLAN in breaktime
		
		update_activeplan(prog_on,onair_year,onair_mon,day,plan);
		checkBreak(prog_on,onair_year,onair_mon,day);
		//console.log("Update Active Plan "+plan);
		
	}else { // Don't have PLAN in breaktime --> Create BREAK PLAN first

		$('#createQueueDi').attr("prevQ",previous_plan);
		$('#createQueueDi').dialog('open'); return false;
		//console.log("Add and Update Plan "+plan);
	}
}

function enable_bkplan(plan){
	
	var previous_plan = $("#programplans").children("div").find("button.active").attr("value");
	
	$("#onair_plan"+plan).attr("value");
	$("#programplantitle").text($("#onair_plan"+plan).text());
	
	$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
	$("#onair_plan"+plan).addClass("btn-inverse active");  
	//console.log("activePlan= "+$("#onair_plan"+plan).attr("value"));
	// Update Active Plan in Onair_schedule Table	
	check_activeplan(plan,previous_plan);
}

function delete_plan(plan){


	var prog_on = $("#prog_on").attr('value');
	var onair_mon = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	var day = $("#ul_daytab").find("li.ui-state-active").text();
	
	var previous_plan = $("#programplans").children("div").find("button.active").attr("value");
	
	if(plan == previous_plan){
		
		previous_plan = 0;
		
	}

	$("#breakplan"+plan).remove();
	
	$.ajaxSetup({
		async: false
	});
	$.ajax({
		type: "POST",
		url: "?r=onair/deletePlan",
		data:{'prog_id':prog_on,'year':onair_year,'month':onair_mon,'day':day,'plan':plan,'prev_plan':previous_plan},
			
			success: function(data) {									
					
					//alert(data);
					
			},
			error: function(data){	
						
				alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ");
						
			}	
											   
	});	
	 
	 checkBreak(prog_on,onair_year,onair_mon,day);
	//check_activeplan(0); 
}

function default_daytab(){
	
	for(var i = 1; i < 32; i++){
		
		$("#li_daytab"+parseInt(i)).children("a#a_daytab").addClass("ui-ams-daytab ui-ams-disable-link");
				
	}
}
		

$(function() {
	
	
	var onair_current_date = 0;
	var onair_current_month = 0;
	var onair_current_year = 0;
	var onair_current_day = 0;
	
	onair_current_date  =  new Date();
	onair_current_month =  parseInt(onair_current_date.getMonth())+1;	
	onair_current_year = parseInt(onair_current_date.getFullYear())+543;
	onair_current_day = onair_current_date.getDate();
	
	readprogList(); //-----> Read and create programs <-------
	
	$("#onair_mon").val(onair_current_month);
	$("#onair_year").val(onair_current_year);
	
	$( "#breakinglist" ).sortable();
	$( "#breakinglist" ).disableSelection();
	$( "#subsort" ).sortable();
	$( "#subsort" ).disableSelection();
	$( "#breakinglist" ).sortable({
		cancel: ".ui-ams-advbrk,.ui-ams-advpending", // When reconsile queue add class of advertise lists
		items: "li:not(#break1)",
		update: function( event, ui ) {
			
			progressupdate();
			confirm_onair();
		},
	});
	
	for(var i = 1; i < 32; i++){
		
		$("#ul_daytab").append("<li id='li_daytab"+i+"' class='disable' ><a id='a_daytab' class='ui-ams-daytab ui-ams-disable-link' style='padding:5px 3px 5px 3px;'>"+i+"</a></li>");
			
	}

	$(".ui-ams-daytab").click(function(e){
		
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		//default_daytab(); // Default Class for Daytab
		//alarming_daytab(prog_on,onair_year,onair_mon);		
		
		$("#ul_daytab").find("li.ui-state-active").removeClass("ui-tabs-selected ui-state-active");
		$("#ul_daytab").find("li#li_daytab"+$(this).text()).addClass("ui-tabs-selected ui-state-active");
		
		checkBreak(prog_on,onair_year,onair_mon,$(this).text());
		show_history(); 
		
	});

	$("#prog_on").change(function() {	
		
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		default_daytab(); // Default Class for Daytab
		alarming_daytab(prog_on,onair_year,onair_mon);
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day);
	});

	$("#onair_mon").change(function() {
		
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		default_daytab(); // Default Class for Daytab
		alarming_daytab(prog_on,onair_year,onair_mon);
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day);
	});

	$("#onair_year").change(function() {
		
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;	
		default_daytab(); // Default Class for Daytab	
		alarming_daytab(prog_on,onair_year,onair_mon);

		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day); 
		
	});

	$(".ui-ams-btadvbrk").click(function( e ) {
			alert("test");
	});
	
	
	
	$("#createplan").click(function(e){
		
		create_new_plan();
				 
	});
	
	$("#programplans > button").each( function(index, Element) { 
		 $(Element).click(function(e){  
		 	$("#programplantitle").text ($(this).text());
		 })
		
	});
	
	
	$(document).ready(function() {
		
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		alarming_daytab(prog_on,onair_year,onair_mon);
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		checkBreak(prog_on,onair_year,onair_mon,day);


	});
	
	
});

</script>
<!--
<h1>Onair</h1>

<p>This is a "static" page. You may change the content of this page
by updating the file <code><?php echo __FILE__; ?></code>.</p>
-->
    <div class="row-fluid">
    <div class="row-fluid" style="margin-top:10px">
        <div class="span8" style="margin-left:20px">
        	<div class="row-fluid">
        		<div class="span1" align="right">
            		<label for="prog_on" style="font-size:1em; margin-top:3px">รายการ:</label>
                </div>
                <div class="span11" align="left" style="margin-left:10px">
            		<select name="prog_on" id="prog_on" style="font-size:1em;width:100;padding-top:3px;padding-bottom:3px" value=""></select>
        		</div>
            </div>
        </div>
        <div class="span2" style="font-size:1em; margin-left:2px;" align="right">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="onair_mon" style="font-size:1em;margin-top:3px"">เดือน:</label>
                </div>
                <div class="span6" align="left">
                    <select class="input-small" type="text" name="onair_mon" id="onair_mon" style="font-size:1em;width:120px;padding-top:3px;padding-bottom:3px" value="">
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
        <div class="span2" style="font-size:1em; margin-left:2px" align="right">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="onair_year" style="font-size:1em;margin-top:3px"">ปี:</label>
            	</div>
                <div class="span6" align="left">
                    <select class="input-small" type="text" name="onair_year" id="onair_year" style="font-size:1em;width:120px;padding-top:3px;padding-bottom:3px" value="">
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
    </div>

<div style="font-size:0.5em;" id="div_daytab" class="ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible" >
	<ul id="ul_daytab" class="nav ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" style="padding:2px 2px 0px 2px; height:32px" >
    </ul>
    <div id="breaking" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
    	<link rel="stylesheet" type="text/css" href="/ams/assets/caf735ba/jui/css/base/jquery-ui.css"  />
            <div class="row-fluid">
            <div class="span12" align="center">
                <div class="row-fluid">      
                	<div class="span12">
                    	<div class="container"  id="page" style="width:inherit">
                        	<div class="navbar">
                            	<div class="navbar-inner">
                                	<ul class="nav" style="width: 100%;">
                                    	<li>
                                        	<button id="createbreak" type="button" class="btn btn-success" style="font-size:1.8em;width:80px; color:"  data-loading-text="Loading..." onclick="addBreakOpen();"><span>เพิ่มเบรค<span></button>
                                        		<button id="addAdvertise" type="button" class="btn btn-success" style="font-size:1.8em;width:100px; color:" onclick="modalAdvertiseOpen(0,0);"  data-loading-text="Loading..."  ><span>เพิ่มโฆษณา<span></button>
                   							       
                                     	</li>
                                   		<li class="divider-vertical"></li>
                                   		<li style="float: right;">
                                   			<button id="download_excel" type="button" class="btn btn-success" style="font-size:1.8em;;width:120px"  data-loading-text="Loading..." onclick="download_layout();">ดาวโหลดไฟล์</button>  
                                   			<button id="export" type="button" class="btn btn-success" style="font-size:1.8em;;width:120px"  data-loading-text="Loading..." onclick="$('#ExOnairMail').dialog('open'); return false;">ส่งคิวโฆษณา</button>  
                                   		</li>
                                   <!-- 	<li >
                                            <button id="createplan" type="button" class="btn btn-primary" style="font-size:1.8em;width:80px" data-loading-text="Loading...">เพิ่มคิว</button>
                                       		<div id="programplans" class="btn-group" data-toggle="buttons-radio" style="font-size:1.6em;margin-left:3px;">
                                          		<button type="button" class="btn btn-info" style="width:80px">คิวหลัก</button>
                       						</div>
                                                    
                                		</li>
     								-->
                          			</ul>
                          		
                              	</div>
                              	<div class="navbar-inner">
                                    <ul class="nav">
                                    		<li >
                                            <div class="row-fluid">
        										<div>
                                                    <button id="createplan" type="button" class="btn btn-primary" style="font-size:1.8em;width:80px; margin-top:6px" data-loading-text="Loading...">เพิ่มคิว</button>
                                                    
                                                    <div id="programplans" class="btn-group" data-toggle="buttons-radio" style="font-size:1.6em;margin-left:3px; margin-top:6px">
                                                        <div id="breakplan0"  class="btn-group" data-toggle="buttons-radio" style="font-size:1.6em;margin-left:3px; margin-top:0px">
                                                        <button onclick='enable_bkplan(0)' id="onair_plan0" type="button" class="btn ui-ams-breakplan" style="width:80px" value="0">คิวจริง</button>
                                                        </div>
                                                         
                                                    </div>
                                      			</div>
                                        	</div>
                                		</li>
                                    </ul>
                                </div>
                              	<div>
                              		<ul style="font-size:1.2em;margin-left:0px; margin-bottom:0px;">
                                  		<li class='ui-ams-advbrk2'>
                                  			 <span id="programplantitle" class="span4"  style="text-align: left;padding: 5px 10px;"  >คิวจริง</span>
                                                <button id="cr" type="button" class="btn btn-primary" style="font-size:1em;width:20px; visibility:hidden; margin-left:2px" data-loading-text="Loading..."></button>
                                                    <a id="all_bk" style='float:right;margin-right:10px; color:#FFF'></a>
                                                    <a style='float:right;margin-right:10px;color:#FFF'> / </a>
                                                    <a id="usage_bk" style='float:right;margin-right:10px;cursor:pointer;color:#FFF'></a>     
                                              	
                                		<div class='progress' style='margin-bottom:2px'> 
                                      		<div  id='time_bk_lev' class="bar bar-warning" style="width:0%;">
   													
                                    		</div><!--
                                    		<a id="all_bk" style='float:right;margin-right:10px; color:#000'></a>
                                                    <a style='float:right;margin-right:10px;color:#000'> / </a>
                                                    <a id="usage_bk" style='float:right;margin-right:10px;cursor:pointer;color:#000'></a> 
                                                    -->	
                               			</div>
                                           
                                        </li>
                                 	</ul>
                                </div>
                          	</div>
                          	<div>
                    			<ul id="breakinglist" style="font-size:0.5em; margin-left:20px;margin-right:20px;"></ul>
                      		</div>
               			</div> 
                	</div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                    
                   		<p style="font-size:2.5em">ประวัติการแก้ไข</p>
                        <ul id="history" style="font-size:1.2em;">
                        
                            <div class="container" id="page" style="width:inherit; margin-left:20px; margin-right:20px"> 
                             <div class="row-fluid">
                                <div class="">
                                    <table align="center" class="table table-striped" id="history_table">
                                      <thead>
                                        <tr style="font-size:1.5em;height:25px;">
											<th style="width:30%;text-align:left;padding:6px">ชื่อสินค้า</th>
                                         	<th style="width:25%;text-align:left;padding:6px">ชื่อโฆษณา</th>
                                            <th style="width:10%;text-align:right;padding:6px">เวลา(วินาที)</th>
                                         	<th style="width:15%;text-align:left;padding:6px">เอเจนซี่</th>
                                          	<th style="width:20%;text-align:left;padding:6px">การแก้ไข</th>
                                        </tr>
                                      </thead>
                                      <tbody style="font-size:1.2em">
                                      </tbody>
                                    </table>
                                </div>
                             </div>
                             
                        </ul> 
     
                    </div>
            	</div>
            </div>
            
<!-- zii dialog Advertise modal ADD BREAK-->

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'modalAdvertise',
        'options'=>array(
            'title'=>'โฆษณา',
			'width'=>500,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'เพิ่มโฆษณา'=>'js:addAdvertise',
				//'ลบเบรค'=>'js:removeBreak',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>


<div class="dialog_input" id="onair_inputDi">
 <form class="form-horizontal" style="font-size:1em" >

    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvProduct">สินค้า:</label>
		<div class="controls">
            <input style="font-size:1em;padding-top:3px;padding-bottom:7px" type="text" name="mAdvProduct" id="mAdvProduct" class="ui-ams-input text ui-widget-content ui-corner-all " autocomplete="off"/>  
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName_auto">ชื่อโฆษณา:</label>
        <div class="controls">
        	<input name="mAdvName_auto" id="mAdvName_auto" style="font-size:1em;padding-top:3px;padding-bottom:7px" type="text" class="ui-ams-input text ui-widget-content ui-corner-all " />
         
        </div>
	</div>
   <!--  <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName">ชื่อชุดโฆษณา:</label>
        <div class="controls">
            <select name="mAdvName" id="mAdvName" style="font-size:1em;width:50;padding-top:3px;padding-bottom:3px" value="">
            </select>
        </div>
	</div> -->
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvTimelen">เวลา(วินาที):</label>
		<div class="controls">
            <input style="font-size:1em;padding-top:3px;padding-bottom:7px" type="text" name="mAdvTimelen" id="mAdvTimelen" class="ui-ams-input text ui-widget-content ui-corner-all" />  
		</div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvAgency">เอเจนซี่:</label>
		<div class="controls">
            <select name="mAdvAgency" id="mAdvAgency" style="font-size:1em;padding-top:3px;padding-bottom:3px" value="">
            </select>  
            <!--<a class="btn" href="#"><i class="icon-search"></i>ค้นหา</a>-->
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px" >
		<label class="control-label" for="mAdvPackage">กิจกรรม:</label>
		<div class="controls">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvPackage" id="mAdvPackage" value="" >
        	</select>
		</div>
	</div>
	<div class="control-group" style="margin-bottom:5px">
		<div class="controls">
        	<div class="row-fluid">
  				<div class="span4" align="left">
                    <label class="radio">ราคาปกติ<input class="bk_type" title="ราคาปกติ" type="radio" name="bk_type" id="bk_type_1" value="ราคาปกติ" checked="checked"></label>
                </div>
                <div class="span4" align="left">
                    <label class="radio">ราคาแพค<input class="bk_type" title="ราคาพิเศษ" type="radio" name="bk_type" id="bk_type_2" value="ราคาพิเศษ"></label>
                </div>
             </div>       
			 <div class="row-fluid">
             	<div class="span4" align="left">
                    <label class="radio">บาเตอร์<input class="bk_type" title="Brother" type="radio" name="bk_type" id="bk_type_3" value="บาเตอร์"></label>
                </div>
                <div class="span4" align="left">
                    <label style="margin:1px" class="radio">อัตราพิเศษ<input class="bk_type" title="อัตราพิเศษ" type="radio" name="bk_type" id="bk_type_4" value="อัตราพิเศษ"></label>
                </div>
            </div>
           	<div class="row-fluid">
                <div class="span4" align="left">
                    <label class="radio">แถม<input class="bk_type" title="แถม" type="radio" name="bk_type" id="bk_type_5" value="แถม"></label>
                </div> 
                <div class="span4" align="left">
                    <label style="margin:1px" class="radio">อื่นๆ<input class="bk_type" title="ปะหัว" type="radio" name="bk_type" id="bk_type_6" value="ปะหัว"></label>
                </div>          
			</div>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvCalPrice">ราคาต่อ Spot:</label>
		<div class="controls">
			<input style="margin-bottom:7px" type="text" name="mAdvCalPrice" id="mAdvCalPrice" class="ui-ams-input text ui-widget-content ui-corner-all " value=""/>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvDiscount">ส่วนลด(%):</label>
		<div class="controls">
            <select id="mAdvDiscount"  value="" class="input-small" >
            	<option value="0">0</option>
         		<option value="5">5</option>
      	 		<option value="10">10</option>
             	<option value="15">15</option>            	
             	<option value="20">20</option>
             	<option value="25">25</option>
              	<option value="30">30</option>
              	<option value="35">35</option>
              	<option value="40">40</option>
             	<option value="45">45</option>
              	<option value="50">50</option>
        	</select>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvNetPrice">ราคาสุทธิ</label>
		<div class="controls">
			<input style="margin-bottom:7px" type="text" name="mAdvNetPrice" id="mAdvNetPrice" class="ui-ams-input text ui-widget-content ui-corner-all " value=""/>
		</div>
    </div>
	<div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvBreak">เบรค:</label>
		<div class="controls">
        <select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvBreak" id="mAdvBreak">
        </select>
		</div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvNote">หมายเหตุ:</label>
		<div class="controls" >
			<textarea rows="3" id="onair_beak_info"></textarea>
		</div>
    </div>
 </form>   
</div>
    
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- zii dialog Advertise modal ADD BREAK-->


<script>

	//addadv_advList();
	function searchOnairProduct(){
		
		var sentData = $("#mAdvProduct").val();
		var p = $("#mAdvProduct").position();
	
			
		var add_OnairProdName = [];
		var add_OnairProdNameID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairProdName&prod_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairProdName){
				$.each(autoOnairProdName,function(k,v){ 
				
					add_OnairProdNameID.push(v.prod_name+":"+v.prod_id);
					add_OnairProdName.push(v.prod_name);
					
					//console.log("prod_id="+v.prod_id);
					
				});
				
				$("#mAdvProduct").autocomplete({
					
					source:add_OnairProdName,
					select: function (event, ui) {
						
						$("#mAdvProduct").val(ui.item.label); // display the selected text
						
						
						
						for (var i=0;i < add_OnairProdNameID.length ;i++){
									
							var n = add_OnairProdNameID[i].split(":"); 
							if (n[0]== $("#mAdvProduct").val()) {
								
								$("#mAdvProduct").attr("prod_name", n[0]);
								$("#mAdvProduct").attr("prod_id", n[1]);
									
							}
						}	
						
					},
					search: function() {
						
						$("#mAdvProduct").attr("prod_id","");
						
					}
				});
			}
		});	
	}
	
	function searchOnairTape(){
		
		var sentData = $("#mAdvName_auto").val();
		var p = $("#mAdvName_auto").position();
		
		var autoProdID = $("#mAdvProduct").attr("prod_id");
	
		var add_OnairTapeName = [];
		var add_OnairTapeNameID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairTapeName&prod_id='+autoProdID+'&tape_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairTapeName){
				$.each(autoOnairTapeName,function(k,v){ 
				
					add_OnairTapeNameID.push(v.tape_name+":"+v.tape_id);
					add_OnairTapeName.push(v.tape_name);
					
				});
				
				$("#mAdvName_auto").autocomplete({
					
					source:add_OnairTapeName,
					select: function (event, ui) {
						
						$("#mAdvName_auto").val(ui.item.label); // display the selected text
						for (var i=0;i < add_OnairTapeNameID.length ;i++){
									
							var n = add_OnairTapeNameID[i].split(":"); 
							if (n[0]== $("#mAdvName_auto").val()) {
											
								$("#mAdvName_auto").attr("tape_name", n[0]);
								$("#mAdvName_auto").attr("tape_id", n[1]);
								
								searchOnairTimelenght(); // Determine Time Lenght
									
							}
						}	
						
					},
					search: function() {
						
						$("#mAdvName_auto").attr("tape_id","");
						
					}
				});
			}
		});			
		
	}
	
	function searchOnairTimelenght(){
		
		var autoTapeID = $("#mAdvName_auto").attr("tape_id");
		var autoProdID = $("#mAdvProduct").attr("prod_id");
		var autoTimelen = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairTapeTime&prod_id='+autoProdID+'&tape_id='+autoTapeID+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairTapeName){
				
				$.each(autoOnairTapeName,function(k,v){ 
				
					autoTimelen = v.time_len;
			
				});
				
			}
		});	
		
		$("#mAdvTimelen").attr('value',autoTimelen);
		cal_spotprice();
		cal_netprice();
		
	
	}
	
	function showOnairAgency(){ // Show Agency and Package
			
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agenList){
			//var breakid=0;
			$("#mAdvAgency option").remove();			
				$.each(agenList,function(k,v){
					
					$("#mAdvAgency").append(
					 
						"<option value="+v.agency_id+">"+v.agency_name+"</option>"
							 
					);
							
				});
			 }
		});	
		
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=onair/japi&action=OnairPackageList',{
			type: 'GET',
			dataType: 'json',
			success: function(OnairPackageList){
				
				$("#mAdvPackage option").remove();
				$("#mAdvPackage").append( 
						"<option pkg_id='0'  value='none'>ไม่กำหนด</option>"
				);			
				$.each(OnairPackageList,function(k,v){
					
					$("#mAdvPackage").append( 
							 "<option pkg_id="+v.pkg_id+"  value="+v.pkg_name+">"+v.pkg_name+"</option>"
					);
							
				});
			 }
		});		

	}


	$("#mAdvProduct").keyup(function(event){
	
		searchOnairProduct();
		
	});
	
	$("#mAdvName_auto").keyup(function(event){
	
		searchOnairTape();		
		
	});


function get_bktype(){
	
	var onair_bk_type = "ราคาปกติ";
	
	if( $("input[type='radio'][name='bk_type']:checked").attr("id") == "bk_type_2"){
		
		onair_bk_type = $("#mAdvPackage").attr('value');
		
		if(onair_bk_type == "none"){
			
			onair_bk_type = " ";

		}
	
	} else if( $("input[type='radio'][name='bk_type']:checked").attr("id") == "bk_type_6"){
		
			onair_bk_type =$("#mAdvNote").val();
		
	}else {

		 onair_bk_type = $("input[type='radio'][name='bk_type']:checked").val();
		
	}	
	
return(onair_bk_type);

}

function cal_spotprice(){
	
	var prog_id_price = 0;
	var onair_spot_price = 0;
	var onair_calprice = 0 ;
	var onair_perdiscount = 0;
	var spot_time = 0;
	
	var onair_prof_id = $("#prog_on").attr("onair_prof_id");
	var dayweek_num = $("#prog_on").attr("dayweek_num"); // HIDDEN Onair Prof ID
	var time_start = $("#prog_on").attr("time_start"); // HIDDEN Onair Prof ID
	
	console.log("onair_prof_id= "+onair_prof_id+" dayweek_num= "+dayweek_num+" time_start="+time_start);	
	
	spot_time = $("#mAdvTimelen").attr("value");
	
	if($("#bk_type_2").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		//per_discount = $("#mAdvDiscount").attr("value");
		
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					if(parseInt(val.minute_pack) != 0){ // Must define minute of PACK
						
						onair_spot_price = (parseInt(val.price_pack)/(60*parseInt(val.minute_pack)))*parseInt(spot_time);
					}else {
						
						onair_spot_price = 0;
						
					}
					
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice").attr("value", money_forchange(onair_spot_price));
					//$("#mAdvCalPrice").attr("calprice",onair_spot_price);
					
				});
			}
		});
		
	} else {
		
		prog_id_price = $("#prog_on").attr("value");
		//per_discount = $("#mAdvDiscount").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
//		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&year='+onair_year+'&month='+onair_mon+'&day='+onair_day+'',{
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				
		
				$.each(getPrice,function(key,val){
					
					console.log("getPrice= "+val.price_net);
					
					//onair_spot_price = (parseInt(val.price_net)/60)*parseInt(spot_time);
					onair_spot_price = (parseInt(val.price_minute)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice").attr("value", money_forchange(onair_spot_price));
					//$("#mAdvCalPrice").attr("calprice",onair_spot_price);
					
				});
			}
		});
		
		
	}

	
}

function keyinCalprice(){
	
	var live_change = $("#mAdvCalPrice").attr("value");
	live_change = parseFloat(live_change.replace(/,/g, '')).toFixed(2);
	
	$("#mAdvCalPrice").attr("value",money_forchange(live_change));
}


function keyinNetprice(){
	
	var live_change = $("#mAdvNetPrice").attr("value");
	live_change = parseFloat(live_change.replace(/,/g, '')).toFixed(2);
	
	$("#mAdvNetPrice").attr("value",money_forchange(live_change));		
};


function cal_netprice(){
	
	if($("#mAdvCalPrice").attr("value")){
		
		var onair_calprice = $("#mAdvCalPrice").attr("value");
		onair_calprice = parseFloat(onair_calprice.replace(/,/g, ''));
	
		var onair_perdiscount = $("#mAdvDiscount").attr("value");
		var onair_netprice = parseInt(onair_calprice)*(1-(parseInt(onair_perdiscount)/100));
		onair_netprice = onair_netprice.toFixed(2);
		
		$("#mAdvNetPrice").val(money_forchange(onair_netprice));		
			
	}
	//console.log("onair_calprice="+onair_calprice);
}

function addTapetoAdvertise(){
	
	
	// Add and return ADV_ID  ----- Attribute to $("#mAdvName_auto").attr("att_id"); and  $("#mAdvName_auto").attr("att_name");
	//$("#mAdvProduct").attr("prod_name");
	//$("#mAdvProduct").attr("prod_id");
	var onairAdvName = $("#mAdvName_auto").attr("value");
	var onairTapeID = $("#mAdvName_auto").attr("tape_id");	
	var onairTimeLen = $("#mAdvTimelen").val();
	var onairPackageID = $("#mAdvPackage").attr("pkg_id");
	var onairAgencyID = $("#mAdvAgency").attr('value');
	var onairTimeLen = $("#mAdvTimelen").val();

	var onairCalcPrice = $("#mAdvCalPrice").attr("value"); // Attribute price  Please Manage about NetPrice
	onairCalcPrice = parseFloat(onairCalcPrice.replace(/,/g, ''));
	
	var onairNetPrice = $("#mAdvNetPrice").attr("value");
	onairNetPrice = parseFloat(onairNetPrice.replace(/,/g, ''));
	//console.log("webPrice="+onairCalcPrice);
	
	var onairDiscont = $("#mAdvDiscount").val();
	
	var onairPriceType = get_bktype();	//----------------------> Advertise still not support
	var onairBreakinfo = $("#onair_beak_info").val();//----------------------> Advertise still not support
	//$("#mAdvNetPrice").val();//----------------------> Advertise still not support
	
	var onairAdvID = 0;	
	
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			
			type: "POST",
			url: "?r=onair/addAdverOnair",
			data:{'adv_name':onairAdvName,'tape_id':onairTapeID,'adv_time_len':onairTimeLen,'pkg_id':onairPackageID,'price_type':onairPriceType,'agency_id':onairAgencyID,'calc_price':onairCalcPrice,'discount':onairDiscont,'net_price':onairNetPrice},
			
				success: function(data) {	
				
					onairAdvID = data;
				
				},
				error: function(data){				
					alert("มีข้อผิดพลาด");		
				}	
											   
		});	

	console.log("onairAdvID= "+onairAdvID)

	$('#mAdvName_auto').attr('att_id',onairAdvID);
	$('#mAdvName_auto').attr('att_name',onairAdvName);	

}

$("#mAdvPackage").change(function(){
	
	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
		
	if($("#mAdvPackage").attr('value') != "none"){
			
		$("#bk_type_2").attr("checked","checked");
			
	}else {
			
		$("#bk_type_1").attr("checked","checked");
	}
	
	cal_spotprice();
	cal_netprice();
	
});

$("#mAdvTimelen").keyup(function(event){
	
	cal_spotprice();
	cal_netprice();
		
});


$(".bk_type").click(function(){
	
	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
		
	if($("#bk_type_2").attr("checked") != "checked"){
			
		$("#mAdvPackage").val("none")
			
	}	
	
	cal_spotprice();
	cal_netprice();
});


$("#mAdvCalPrice").focusout(function(e) {
	
	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
	keyinCalprice();
	cal_netprice();
	
	
});



$("#mAdvNetPrice").focusout(function(e) {
	
	keyinNetprice();
	
});

$("#mAdvDiscount").change(function(e) {
	
	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
	cal_netprice();
	
});

/*

$("#onair_inputDi").click(function(){ // Calculate Spot price
	
	get_bktype();
	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
	cal_spotprice();
	
});

$("#mAdvCalPrice").focusin(function(e) {

	if($("#mAdvTimelen").attr("value") == ""){$("#mAdvTimelen").attr("value",0)};
	keyinCalprice();
	cal_netprice();
	
	
});

*/

</script>

<!-- zii dialog Advertise modal UPDATE BREAK-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'updateOnairDi',
        'options'=>array(
            'title'=>'แก้ไขโฆษณา',
			'width'=>600,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
            	'ย้ายโฆษณา'=>'js:open_move_adv_onair',
				'แยกโฆษณา'=>'js:open_split_adv_onair',
				'รวมโฆษณา'=>'js:open_merge_adv_onair',
                'ตกลง'=>'js:updateOnairDb',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
<div class="dialog_input" id="onair_input_editDi" value="">
<form class="form-horizontal" style="font-size:1em" id="adv_seq_list">

    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvProduct_edit">สินค้า:</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvProduct_edit" id="mAdvProduct_edit" class="ui-ams-input text ui-widget-content ui-corner-all " />  
		</div>
    </div>

	    <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName_edit_auto">ชื่อโฆษณา:</label>
        <div class="controls">
        	<input name="mAdvName_edit_auto" id="mAdvName_edit_auto" style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" class="ui-ams-input text ui-widget-content ui-corner-all " />
         
        </div>
	</div>
<!--     <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName_edit">ชื่อชุดโฆษณา:</label>
        <div class="controls">
            <select name="mAdvName_edit" id="mAdvName_edit" style="font-size:1em;width:50;padding-top:3px;padding-bottom:3px" value="">
            </select>
        </div>
	</div> -->
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvTimelen_edit">เวลา(วินาที):</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvTimelen_edit" id="mAdvTimelen_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            
		</div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvAgency_edit">เอเจนซี่:</label>
		<div class="controls">
            <select name="mAdvAgency_edit" id="mAdvAgency_edit" style="font-size:1em;padding-top:3px;padding-bottom:3px" value="">
            </select>  
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px" >
		<label class="control-label" for="mAdvPackage_edit">กิจกรรม:</label>
		<div class="controls">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvPackage_edit" id="mAdvPackage_edit" value="" >
        	</select>
           
		</div> 
	</div>
	<div class="control-group" style="margin-bottom:5px">
		<div class="controls">
        	<div class="row-fluid">
  				<div class="span4" align="left">
                    <label class="radio">ราคาปกติ<input class="bk_type_edit" title="ราคาปกติ" type="radio" name="bk_type_edit" id="bk_type_edit_1" value="ราคาปกติ" checked="checked"></label>
                </div>
                <div class="span4" align="left">
                    <label class="radio">ราคาแพค<input class="bk_type_edit" title="ราคาแพค" type="radio" name="bk_type_edit" id="bk_type_edit_2" value="ราคาพิเศษ"></label>
                </div>
             </div>       
			 <div class="row-fluid">
             	<div class="span4" align="left">
                    <label class="radio">บาเตอร์<input class="bk_type_edit" title="บาเตอร์" type="radio" name="bk_type_edit" id="bk_type_edit_3" value="บาเตอร์"></label>
                </div>
                <div class="span4" align="left">
                    <label style="margin:1px" class="radio">อัตราพิเศษ<input class="bk_type_edit" title="อัตราพิเศษ" type="radio" name="bk_type_edit" id="bk_type_edit_4" value="อัตราพิเศษ"></label>
                </div>
            </div>
           	<div class="row-fluid">
                <div class="span4" align="left">
                    <label class="radio">แถม<input class="bk_type_edit" title="แถม" type="radio" name="bk_type_edit" id="bk_type_edit_5" value="แถม"></label>
                </div> 
                <div class="span4" align="left">
                    <label style="margin:1px" class="radio">อื่นๆ<input class="bk_type_edit" title="อื่นๆ" type="radio" name="bk_type_edit" id="bk_type_edit_6" value="อื่นๆ"></label>
                </div>          
			</div>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvCalPrice_edit">ราคาต่อ Spot:</label>
		<div class="controls">
			<input style="margin-bottom:7px" type="text" name="mAdvCalPrice_edit" id="mAdvCalPrice_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value=""/>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvDiscount_edit">ส่วนลด(%):</label>
		<div class="controls">
            <select id="mAdvDiscount_edit"  value="" class="input-small" >
            	<option value="0">0</option>
         		<option value="5">5</option>
      	 		<option value="10">10</option>
             	<option value="15">15</option>            	
             	<option value="20">20</option>
             	<option value="25">25</option>
              	<option value="30">30</option>
              	<option value="35">35</option>
              	<option value="40">40</option>
             	<option value="45">45</option>
              	<option value="50">50</option>
        	</select>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvNetPrice_edit">ราคาสุทธิ:</label>
		<div class="controls">
			<input style="margin-bottom:7px" type="text" name="mAdvNetPrice_edit" id="mAdvNetPrice_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value=""/>
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label  class="control-label" for="mAdvNote_edit">หมายเหตุ:</label>
		<div class="controls" >
			<textarea rows="3" id="mAdvNote_edit"></textarea>
		</div>
    </div>
</form>
</div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- zii dialog Advertise modal UPDATE -->

<script>

	function showOnairAgency_edit(){ // Show Agency and Package  USE THE SAME CONROLLER with ADD FUNCTION
			
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agenList){
			//var breakid=0;
			$("#mAdvAgency_edit option").remove();			
				$.each(agenList,function(k,v){
					
					$("#mAdvAgency_edit").append(
					 
						"<option value="+v.agency_id+">"+v.agency_name+"</option>"
							 
					);
							
				});
			 }
		});	
		
		$.ajaxSetup({
			async: false
		});

		$.ajax('?r=onair/japi&action=OnairPackageList',{
			type: 'GET',
			dataType: 'json',
			success: function(OnairPackageList){
				
				$("#mAdvPackage_edit option").remove();
				$("#mAdvPackage_edit").append( 
						"<option pkg_id='0'  value='none'>ไม่กำหนด</option>"
				);			
				$.each(OnairPackageList,function(k,v){
					
					$("#mAdvPackage_edit").append( 
							 "<option pkg_id="+v.pkg_id+"  value="+v.pkg_name+">"+v.pkg_name+"</option>"
					);
							
				});
			 }
		});		

	}

	function searchOnairProduct_edit(){  // Use the same controller with adding advertise
		
		var sentData = $("#mAdvProduct_edit").val();
		var p = $("#mAdvProduct_edit").position();
	
			
		var add_OnairProdName = [];
		var add_OnairProdNameID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairProdName&prod_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairProdName){
				$.each(autoOnairProdName,function(k,v){ 
				
					add_OnairProdNameID.push(v.prod_name+":"+v.prod_id);
					add_OnairProdName.push(v.prod_name);
					
					//console.log("prod_id="+v.prod_id);
					
				});
				
				$("#mAdvProduct_edit").autocomplete({
					
					source:add_OnairProdName,
					select: function (event, ui) {
						
						$("#mAdvProduct_edit").val(ui.item.label); // display the selected text
						
						
						
						for (var i=0;i < add_OnairProdNameID.length ;i++){
									
							var n = add_OnairProdNameID[i].split(":"); 
							if (n[0]== $("#mAdvProduct_edit").val()) {
								
								$("#mAdvProduct_edit").attr("prod_name", n[0]);
								$("#mAdvProduct_edit").attr("prod_id", n[1]);
									
							}
						}	
						
					},
					search: function() {
						
						$("#mAdvProduct_edit").attr("prod_id","");
						
					}
				});
			}
		});	
	}
	
	
	function searchOnairTape_edit(){ // Use the same controller with adding TAPE
		
		var sentData = $("#mAdvName_edit_auto").val();
		var p = $("#mAdvName_edit_auto").position();
		
		var autoProdID = $("#mAdvProduct_edit").attr("prod_id");
	
		var add_OnairTapeName = [];
		var add_OnairTapeNameID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairTapeName&prod_id='+autoProdID+'&tape_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairTapeName){
				$.each(autoOnairTapeName,function(k,v){ 
				
					add_OnairTapeNameID.push(v.tape_name+":"+v.tape_id);
					add_OnairTapeName.push(v.tape_name);
					
				});
				
				$("#mAdvName_edit_auto").autocomplete({
					
					source:add_OnairTapeName,
					select: function (event, ui) {
						
						$("#mAdvName_edit_auto").val(ui.item.label); // display the selected text
						for (var i=0;i < add_OnairTapeNameID.length ;i++){
									
							var n = add_OnairTapeNameID[i].split(":"); 
							if (n[0]== $("#mAdvName_edit_auto").val()) {
											
								$("#mAdvName_edit_auto").attr("tape_name", n[0]);
								$("#mAdvName_edit_auto").attr("tape_id", n[1]);
								
								searchOnairTimelenght(); // Determine Time Lenght
									
							}
						}	
						
					},
					search: function() {
						
						$("#mAdvName_edit_auto").attr("tape_id","");
						
					}
				});
			}
		});			
		
	}
	


function addadv_advList_edit(){
	
	var edit_list = 0;
	var edit_timelength = 0;
	var edit_calc_price = 0;
	var edit_bk_type = " ";
	var edit_bk_discount = 0;
	var edit_bk_info = " "; 
	
	var edit_on_product = 0;
	var edit_on_agency = 0;
    var edit_Tags = [];
    var edit_Tmp = []; 
	
	
	showOnairAgency_edit();
	
	edit_list = $('#breakinglist').find('li[list_adv=bk'+$('#onair_input_editDi').attr("value")+'advseq'+$('#adv_seq_list').attr("value")+']').attr("value");
	edit_list = parseInt(edit_list);
	
	edit_on_product = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).text();
	edit_on_agency = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("agency_id");
	
	var edit_on_productID = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("prod_id");
	var edit_on_tapeID = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("tape_id");
	var edit_on_advname = $("#bk"+$('#onair_input_editDi').attr("value")+"advname"+$('#adv_seq_list').attr("value")).text();
	var edit_on_pkgid = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("pkg_id");
	
	
	$("#mAdvProduct_edit").attr("value",edit_on_product);
	$("#mAdvProduct_edit").attr("prod_id",edit_on_productID);
	$("#mAdvProduct_edit").attr("old_prod_id",edit_on_productID);
	
	$('#mAdvName_edit_auto').attr("value",edit_on_advname);
	$('#mAdvName_edit_auto').attr("tape_id",edit_on_tapeID);
	$('#mAdvName_edit_auto').attr("old_tape_id",edit_on_tapeID);
	$('#mAdvName_edit_auto').attr("adv_id",edit_list);
	
	$("#mAdvAgency_edit").attr("value",edit_on_agency);  
	
	edit_timelength = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('time_bk');
	edit_calc_price = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('spot_price');
	edit_bk_type = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('bk_type');
	edit_bk_discount = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('on_discount');
	edit_bk_info = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('bk_info');

	
	$("#mAdvTimelen_edit").attr("value",edit_timelength);
	$("#mAdvCalPrice_edit").attr("value",money_forchange(edit_calc_price));	
	$("#mAdvNote_edit").attr("value",edit_bk_info);
	edit_bk_discount = parseInt(edit_bk_discount);
	
	$("#mAdvDiscount_edit").val(edit_bk_discount);
	
	
	var edit_bknet_price =  $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('net_price');
	$("#mAdvNetPrice_edit").val(money_forchange(edit_bknet_price));
	
	//----------> BREAK TYPE ------->
	
	if((edit_on_pkgid == 0) || (edit_on_pkgid == "")){
		
		if(edit_bk_type == "ราคาปกติ"){
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_1").attr("checked","checked");
			
			
		}else if(edit_bk_type == " "){
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_2").attr("checked","checked");
			
		}else if(edit_bk_type == "บาเตอร์"){
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_3").attr("checked","checked");
			
		}else if(edit_bk_type == "อัตราพิเศษ"){
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_4").attr("checked","checked");
			
		}else if(edit_bk_type == "แถม"){
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_5").attr("checked","checked");
			
		}else { // Default for อื่นๆ
			
			$("#mAdvPackage_edit").val("none");
			$("#bk_type_edit_6").attr("checked","checked");
			
		}
		
	}else {
		
		if(edit_bk_type){
			
			$("#mAdvPackage_edit").attr("value",edit_bk_type);
			$("#bk_type_edit_2").attr("checked","checked");
			
		}else {
			
			$("#mAdvPackage_edit").attr("value","none");
			$("#bk_type_edit_2").attr("checked","checked");						

		}
	}
	
	//----------> BREAK TYPE ------->
	
	cal_spotprice_edit();
	//cal_netprice_edit();
}

function get_bktype_edit(){
	
	var onair_bk_type = "ราคาปกติ";	
	
	if( $("input[type='radio'][name='bk_type_edit']:checked").attr("id") == "bk_type_edit_2"){
		
		onair_bk_type = $("#mAdvPackage_edit").attr('value');
		
		if(onair_bk_type == "none"){
			
			onair_bk_type = " ";

		}
	
	} else if( $("input[type='radio'][name='bk_type_edit']:checked").attr("id") == "bk_type_edit_6"){
		
			onair_bk_type =$("#mAdvNote_edit").val();
		
	}else {

			onair_bk_type = $("input[type='radio'][name='bk_type_edit']:checked").val();
		
	}	
	
return(onair_bk_type);

}

function cal_spotprice_edit(){
	
	var prog_id_price = 0;
	var onair_spot_price = 0;
	var onair_calprice = 0;;
	var onair_perdiscount = 0;
	
	var spot_time = 0;
	
	var onair_prof_id = $("#prog_on").attr("onair_prof_id");
	var dayweek_num = $("#prog_on").attr("dayweek_num"); // HIDDEN Onair Prof ID
	var time_start = $("#prog_on").attr("time_start"); // HIDDEN Onair Prof ID
			
	//var onair_mon = parseInt($("#onair_mon").attr('value'));
	//var onair_year = parseInt($("#onair_year").attr('value'))-543;
	//var onair_day = $("#ul_daytab").find("li.ui-state-active").text();	

	spot_time =  $("#mAdvTimelen_edit").attr("value");
	
	if($("#bk_type_edit_2").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		
		$.ajaxSetup({
			async: false
		});

		//$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&year='+onair_year+'&month='+onair_mon+'&day='+onair_day+'',{
			
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					if(parseInt(val.minute_pack) != 0){ // Must define minute of PACK
						
						onair_spot_price = (parseInt(val.price_pack)/(60*parseInt(val.minute_pack)))*parseInt(spot_time);
					}else {
						
						onair_spot_price = 0;
						
					}
					
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice_edit").attr("value",money_forchange(onair_spot_price));
					
				});
			}
		});
		
	}else {
		
		prog_id_price = $("#prog_on").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
	//	$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&year='+onair_year+'&month='+onair_mon+'&day='+onair_day+'',{
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{		
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					//onair_spot_price = (parseInt(val.price_net)/60)*parseInt(spot_time);
					onair_spot_price = (parseInt(val.price_minute)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice_edit").attr("value",money_forchange(onair_spot_price));
					
				});
			}
		});
		
	}
	
}

function keyinCalprice_edit(){
	
	if($("#mAdvCalPrice_edit").attr("value")){
		
		var real_change = $("#mAdvCalPrice_edit").attr("value");
		real_change = parseFloat(real_change.replace(/,/g, '')).toFixed(2);
		
		$("#mAdvCalPrice_edit").attr("value",money_forchange(real_change));
	}
	
}

function keyinNetprice_edit(){
	
	if($("#mAdvNetPrice_edit").attr("value")){
	
		var live_change = $("#mAdvNetPrice_edit").attr("value");
		live_change = parseFloat(live_change.replace(/,/g, '')).toFixed(2);
		
		$("#mAdvNetPrice_edit").attr("value",money_forchange(live_change));		
	
	}
};


function cal_netprice_edit(){
	
		
	if($("#mAdvCalPrice_edit").attr("value")){
		
		var onair_calprice = $("#mAdvCalPrice_edit").attr("value");
		onair_calprice = parseFloat(onair_calprice.replace(/,/g, ''));
		
		var onair_perdiscount = $("#mAdvDiscount_edit").attr("value");
		var onair_netprice = parseInt(onair_calprice)*(1-(parseInt(onair_perdiscount)/100));
		onair_netprice = onair_netprice.toFixed(2);
			
		$("#mAdvNetPrice_edit").val(money_forchange(onair_netprice));		
			
	}
	//console.log("onair_calprice="+onair_calprice);
}


$("#mAdvProduct_edit").keyup(function(event){
	
	searchOnairProduct_edit();
		
});

$("#mAdvName_edit_auto").keyup(function(event){
	
	searchOnairTape_edit();		
		
});

$("#mAdvPackage_edit").change(function(){
		
	
	if($("#mAdvPackage_edit").attr('value') != "none"){
		
		$("#bk_type_edit_2").attr("checked","checked");
		
	}else {
		
		$("#bk_type_edit_1").attr("checked","checked");
	}
	
	cal_spotprice_edit();
	cal_netprice_edit();	
	
});

$(".bk_type_edit").click(function(){
	
	if($("#bk_type_edit_2").attr("checked") != "checked"){
		
		$("#mAdvPackage_edit").val("none")
		
	}	
	
	cal_spotprice_edit();
	cal_netprice_edit();	
	
});


$("#mAdvCalPrice_edit").focusout(function(e) {
	
	if($("#mAdvTimelen_edit").attr("value") == ""){$("#mAdvTimelen_edit").attr("value",0)};
	keyinCalprice_edit();
	cal_netprice_edit();
	
	
});

$("#mAdvDiscount_edit").change(function(e) {
	
	if($("#mAdvTimelen_edit").attr("value") == ""){$("#mAdvTimelen_edit").attr("value",0)};
	cal_netprice_edit();
	
});



$("#mAdvNetPrice_edit").focusout(function(e) {
	
	keyinNetprice_edit();
	
});


function updateOnair(breakid, adv_order){
		
	$("#onair_input_editDi").attr('value',breakid);
	$("#adv_seq_list").attr('value',adv_order);
	$('#updateOnairDi').dialog('open');
		
	addadv_advList_edit();		
	return false;	
} 
	
	function updateOnairDb(){
		
		$('#onair_input_editDi').attr("value");
		$('#adv_seq_list').attr("value");
		
		var edit_tapeid = $('#mAdvName_edit_auto').attr("tape_id");
		
		if(edit_tapeid == ""){
			
			edit_tapeid = $('#mAdvName_edit_auto').attr("old_tape_id");
			
		}
		
		var edit_onair_product = $('#mAdvProduct_edit').attr("value");
		//var edit_onair_advID = $('#mAdvName_edit').attr("value");
		//var edit_onair_advname = $('#mAdvName_edit option:selected').text();
		 //$('#mAdvName_auto').attr('att_id');
		var edit_onair_advID = $('#mAdvName_edit_auto').attr("adv_id");
		var edit_onair_advname = $('#mAdvName_edit_auto').attr("value");
		var edit_onair_timelen = $('#mAdvTimelen_edit').attr("value");
		var edit_onair_agency = $('#mAdvAgency_edit option:selected').text();
		
		//console.log("edit_onair_agency= "+edit_onair_agency)
		
		var edit_onair_break = get_bktype_edit();
		var edit_on_calc_price = $("#mAdvCalPrice_edit").attr("value");
			edit_on_calc_price = parseFloat(edit_on_calc_price.replace(/,/g, ''));
			
		var edit_on_net_price = $("#mAdvNetPrice_edit").val();
			edit_on_net_price = parseFloat(edit_on_net_price.replace(/,/g, ''));
		
		
		var edit_break_discount = $("#mAdvDiscount_edit").attr("value");
		var edit_break_info = $('#mAdvNote_edit').attr("value");
		
		var edit_mins = Math.floor(edit_onair_timelen/60);
		var edit_secs = edit_onair_timelen % 60;
		edit_secs = zeroPad(edit_secs, 2);
		
		if(edit_onair_break == ""){ edit_onair_break = " "; }
		if(edit_on_calc_price == "" ){ edit_on_calc_price = 0; }
		if(edit_break_discount == ""){ edit_break_discount = 0;}
		if(edit_break_info == ""){ edit_break_info = " ";}

		
		$('#breakinglist').find('li[list_adv=bk'+$('#onair_input_editDi').attr("value")+'advseq'+$('#adv_seq_list').attr("value")+']').attr("value",edit_onair_advID);
		
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).text(edit_onair_product);
		$("#bk"+$('#onair_input_editDi').attr("value")+"advname"+$('#adv_seq_list').attr("value")).text(edit_onair_advname);
		$("#bk"+$('#onair_input_editDi').attr("value")+"agency"+$('#adv_seq_list').attr("value")).text(edit_onair_agency);
		
		// ------> Time length ------->
			
		$('#breakinglist').find('li[list_adv=bk'+$('#onair_input_editDi').attr("value")+'advseq'+$('#adv_seq_list').attr("value")+']').children('div').children('p').children('span#timelen').text(edit_mins+":"+edit_secs);	
		
		// ------> 
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("time_bk",edit_onair_timelen);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("bk_type",edit_onair_break);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("spot_price",edit_on_calc_price);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("on_discount",$("#mAdvDiscount_edit").val());
		
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("net_price",edit_on_net_price);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("pkg_id",$("#mAdvPackage_edit option:selected").attr("pkg_id"));
		
		
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("on_discount",edit_break_discount);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("bk_info",edit_break_info);

			
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("tape_id",edit_tapeid);

		
		var insert_pkg_id = $("#mAdvPackage_edit option:selected").attr("pkg_id");
		var insert_agency_id = $("#mAdvAgency_edit").attr("value");
	
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			
			type: "POST",
			url: "?r=onair/updateAdverOnair",
			data:{'adv_id':edit_onair_advID,'adv_name':edit_onair_advname,'tape_id':edit_tapeid,'adv_time_len':edit_onair_timelen,'pkg_id':insert_pkg_id,'price_type':edit_onair_break,'agency_id':insert_agency_id,'calc_price':edit_on_calc_price,'discount':edit_break_discount,'net_price':edit_on_net_price},
			
				success: function(data) {	
				
					//onairAdvID = data;
				
				},
				error: function(data){		
						
					alert("มีข้อผิดพลาด");		
					
				}	
											   
		});	
		
		confirm_onair(); 
		
		$(this).dialog("close");
	}

</script>

<!-- zii dialog delete advertise -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'delete_advDi',
        'options'=>array(
            'title'=>'ลบชุดโฆษณา',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
				'ใช่'=>'js:delete_adv',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

    <div class="dialog_input"  style="margin-top:30px" id="delete_advID" breakSQ="" advSQ="" align="center" > 
    <form class="form-horizontal" >คุณต้องการลบโฆษณานี้ใช่หรือไม่</form>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- zii dialog delete advertise --> 

<!-- zii dialog delete break sequence -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'delete_breakseqDi',
        'options'=>array(
            'title'=>'ลบเบรคและชุดโฆษณา',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
				'ใช่'=>'js:delete_breakseq',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

    <div class="dialog_input"  style="margin-top:30px" id="delete_breakseqID" del_breakID="" align="center" > 
    <form class="form-horizontal" >คุณต้องการลบเบรคและชุดโฆษณานี้ใช่หรือไม่</form>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<!-- zii dialog delete break sequence --> 





<!-- zii dialog Break modal -->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'mydialog2',
        'options'=>array(
            'title'=>'เพิ่มเบรค',
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'เพิ่ม'=>'js:addBreak',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input">
    <form class="form-horizontal" >
    <div class="control-group" align="center" style="margin-top:10px">เวลา (วินาที):
	<select name="createbreaktime" id="createbreaktime" value="" style="width:100px">
          <option>15</option>
          <option>30</option>
          <option>45</option>
          <option>60</option>
          <option>75</option>
          <option>90</option>
          <option>105</option>
          <option>120</option>
          <option>135</option>
          <option>150</option>
          <option>165</option>
          <option>180</option>
	</select>
	</div>
    </form>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'backOnairDi',
        'options'=>array(
            'title'=>'ยกเลิกการปรับเปลี่ยน',
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                //'เพิ่ม'=>'js:addBreak',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input">
    <form class="form-horizontal" >
    <div class="control-group" >
    <label class="control-label" for="createbreaktime" >Sorry! This feature is not available yet.</label>
    <div class="controls" style="width:50px">
    </div>
	</div>
    </form>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'confOnairDi',
        'options'=>array(
            'title'=>'บันทึกคิวโฆษณา',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ใช่'=>'js:confirm_onair',
                'ไม่'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input" align="center">
    <div class="controls" style="margin-top:30px" align="center">
   คุณต้องการบันทึกคิวโฆษณานี้ใช่หรือไม่
    
	</div>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>


<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'createQueueDi',
        'options'=>array(
            'title'=>'บันทึกคิวโฆษณาสำรอง',
			'width'=>400,
			'height'=>200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'สร้างคิวสำรอง'=>'js:confirm_onair',
                'ไม่'=>'js:backtoPrevPlan',
            ),
        ),
    ));
?>
    <div class="dialog_input" align="center">
    <div class="controls" style="margin-top:30px" align="center">
   คิวโฆษณานี้ยังไม่มีในระบบ
   คลิกสร้างคิวสำรองเพื่อสร้างคิวโฆษณาสำรองนี้ คลิกไม่เพื่อกลับไปคิวโฆษณาล่าสุด
    
	</div>
    </div>
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<script>

function confirm_onair(){
		
		//console.log($('.ui-ams-advbrk').size());
		
			var breakid;
			var breaker;
			var delay =0;
			var breaksum=0;
			var totalbreak=0;
			var adv_seq = 0;
			var cnt_time = 0;
			var break_read = [];
			var totalbk = [];
			var breakType_ID = [];
			var breakOnair_Time = [];
			
			var bk_desc = [];
			var on_bk_type = [];
			var on_spot_price = [];
			var on_discount = [];
			
			var onair_mon = parseInt($("#onair_mon").attr('value'));
			var onair_year = parseInt($("#onair_year").attr('value'))-543;
			var prog_id = $("#prog_on").attr('value');
			var onair_day = $("#ul_daytab").find("li.ui-state-active").text();
			
			
			var break_plan = $("#programplans").children("div").find("button.active").attr("value");
			 
			if(break_plan){
				
				break_plan = break_plan;
				
			}else{
				
				$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
				$("#onair_plan0").addClass("btn-inverse active")  	
				break_plan = 0;			
				
			}
			
			//console.log("CreatBreakPlan="+break_plan); 
			
			//console.log($("#breakinglist"));
			 $("#breakinglist").find('li').each(function(){
			 	var current = $(this);
				var mins;
				var secs;
				var adv_current;
				
				if(current.attr('id')){
					
					if(current.attr('id') != "pending"){
					
						var tb = current.children('div').children('div').children('a#totalbreak').text().split(':');
						
						
						if(tb == ""){
							
							totalbk[cnt_time]  = "00:00";
							
						}else {
							
							totalbk[cnt_time]  = parseInt(tb[0]*60) + parseInt(tb[1]);	
						
						}
	
	
						var break_Type_ID = current.children('div').children('div').children('span#breakType').attr("breakTypeID");
						
						breakType_ID[cnt_time]  = parseInt(break_Type_ID);
						
						var breakOnair_Time_tb = current.children('div').children('div').children('span#breakOnairTime').attr("onairTime");
						
						//console.log("breakOnair_Time_tb= "+breakOnair_Time_tb);
						if(breakOnair_Time_tb == ""){
							
							breakOnair_Time[cnt_time]  = "00:00";
							
						}else {
							
							breakOnair_Time[cnt_time]  = breakOnair_Time_tb+":00";
						}	
					
					cnt_time = cnt_time+1; 
					
					}
					
					
					break_read[adv_seq] = current.attr('id');
					

				 }else{
					
					break_read[adv_seq] = current.attr('value');
					
					
					// Start change for Migrating to use only adv_id
					
					on_bk_type[adv_seq] = current.children('div').children('p').children('span.property_bk').attr("bk_type");
					on_spot_price[adv_seq] =  current.children('div').children('p').children('span.property_bk').attr("spot_price");
					on_discount[adv_seq] = current.children('div').children('p').children('span.property_bk').attr("on_discount");
					bk_desc[adv_seq] =  current.children('div').children('p').children('span.property_bk').attr("bk_info");
					// End change for Migrating to use only adv_id
					
				 } 
				 
				adv_seq++;
				
			 }); 
			
			//console.log("break_read="+break_read+"  totalbreak="+totalbk+" CreatBreakPlan="+break_plan );
			//console.log("onair_year"+onair_year+"onair_mon"+onair_mon+"onair_day"+onair_day+"prog_id"+prog_id);
			//console.log("breakOnair_Time= "+breakOnair_Time+" breakType_ID= "+breakType_ID+" totalbreak= "+totalbk);
			
		
			$.ajaxSetup({
				async: false
			});
			$.ajax({
				type: "POST",
				url: "?r=onair/addonair",
				data:{'prog_id':prog_id, 'year':onair_year, 'month':onair_mon,'day':onair_day, 'break_read':break_read,'totalbk':totalbk,'break_desc':bk_desc,'break_type':on_bk_type,'calc_price':on_spot_price,'discount':on_discount,'break_plan':break_plan,'breakOnairTime':breakOnair_Time,'breakTypeID':breakType_ID},
					success: function(data) {
							
						//alert("success");
					},
					error: function(data){
							
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มคิวโฆษณา กรุณาเลือกคิวโฆษณาใหม่อีกครั้ง");
						
					}
										   
			});	 
		
	
	 update_activeplan(prog_id,onair_year,onair_mon,onair_day,break_plan);
	 checkBreak(prog_id,onair_year,onair_mon,onair_day);	 
	 $(this).dialog("close");
} 

</script>

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'ExOnairDi',
        'options'=>array(
            'title'=>'Export',
			'width'=>400,
			'height'=>300,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
			   //'ตกลง'=>'js:showreport',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input">
        <div class="controls" style=" margin-top:30px" align="center">
        	<label class="control-label" for="createbreaktime" >ตารางการจัดคิวโฆษณาจะถูกแสดงในหน้ารายงาน</label>
        </div>
    </div>
    
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'ExOnairMail',
        'options'=>array(
            'title'=>'Export',
			'width'=>400,
			'height'=>300,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
			   'ตกลง'=>'js:sendEmail',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input">
        <div class="controls" style=" margin-top:30px" align="center">
        	<label class="control-label" for="createbreaktime" >ตารางการจัดคิวโฆษณาจะถูกส่งไปทาง Email</label>
        </div>
    </div>
    
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
?>

<?php
 //--------------- Start of Dialog for spliting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'onair_move_Di',
        'options'=>array(
            'title'=>'การย้ายโฆษณา',
			'width'=>450,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:save_move',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" id="move_breakSEQ">
 <form class="form-horizontal" style="font-size:1em" id="move_advSEQ">
	<div class="row-fluid" align="center">
      <div class="span12" align="left">
      	
	      	<div class="row-fluid">
		    	<div class="span2" align="right" style="margin-top:5px; width:50px">
					<label for="onair_merge_prog">รายการ:</label>
		        </div>
		        <div class="span3" align="right" style="margin-left:50px">
		        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="onair_move_prog_next" id="onair_move_prog_next" >
		        	</select> 
				</div>
		    </div>
		    <div class="row-fluid">
		    	<div class="span2" align="right" style="margin-top:5px; width:50px">
					<label for="onair_merge_date">วันที่:</label>
		        </div>
		        <div class="span3" align="right" style="margin-left:0px; width:270px">
		        
		       		<?php
		            	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		                'name'=>'my_date',
						//'value'=>date('d/m/Y'),
						'id'=>'onair_move_date_next',
		                'language'=>Yii::app()->language=='et' ? 'et' : null,
		                'options'=>array(
		                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
		                	'showOn'=>'button', // 'focus', 'button', 'both'
		                	'buttonText'=>Yii::t('ui','Select form calendar'),
		                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
							'dateFormat'=>'dd/mm/yy',
							//'dateFormat'=>'mm-dd-yy',
							//'beforeShowDay' => 'js:$.datepicker.setHoliDays ',
		                	'buttonImageOnly'=>true,),
		                'htmlOptions'=>array(
		                	'style'=>'width:175px;vertical-align:top'),
		                ));   
		        	?>	
		             
				</div>
		  </div>
		  <div class="row-fluid">
		    	<div class="span2" align="right" style="margin-top:5px; width:50px">
					<label for="onair_merge_date">วันที่:</label>
		        </div>
		        <div class="span3" align="right" style="margin-left:0px; width:270px">
		        
		       		<?php
		            	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		                'name'=>'my_date',
						//'value'=>date('d/m/Y'),
						'id'=>'onair_move_date_next2',
		                'language'=>Yii::app()->language=='et' ? 'et' : null,
		                'options'=>array(
		                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
		                	'showOn'=>'button', // 'focus', 'button', 'both'
		                	'buttonText'=>Yii::t('ui','Select form calendar'),
		                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
							'dateFormat'=>'dd/mm/yy',
							//'dateFormat'=>'mm-dd-yy',
							'beforeShowDay' => '',
		                	'buttonImageOnly'=>true,),
		                'htmlOptions'=>array(
		                	'style'=>'width:175px;vertical-align:top'),
		                ));   
		        	?>	
		             
				</div>
		  </div>
      </div>
    </div>
 </form>    
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for spliting Advertise  --------------
?>

<script>

function unavailable(date) {
		
	 //console.log("debug date")	
		
	  var unavailableDates = ["9-3-2013","14-3-2013","15-3-2013"];
		
		
	  var dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
	  if ($.inArray(dmy, unavailableDates) < 0) {
		
		return [false,"","วันนี้ไม่อยู่ในกำหนดการออกอากาศ"];
	  
	  } else {
		
		return [true,"",""];
	  
	  }
}


function open_move_adv_onair(){
	 
	
	var prog_id = $("#prog_on").val(); 
		 
	var d = parseInt( $("#ul_daytab").find("li.ui-state-active").text());
	var m =  parseInt($("#onair_mon").val());
	var y = parseInt($("#onair_year").val())-543;
	var move_break_seq_org = $("#onair_input_editDi").attr('value');
	var move_adv_seq_org = $("#adv_seq_list").attr('value');
	
	$.ajaxSetup({
			async: false
	});
 
	$.ajax('?r=onair/japi&action=progListForMove&program_id='+prog_id+'&onair_year='+y+'&onair_month='+m+'&onair_day='+d+'',{
		type: 'GET',
		dataType: 'json',
		success: function(programList){
		$("#onair_move_prog_next option").remove();			
		
			$.each(programList,function(kon,von){
		
				$("#onair_move_prog_next").append(
				
					"<option value='"+von.prog_id+"'>"+von.prog_name+"</option>"
										
				);
			 });

		}
	});
 
	$(this).dialog("close");
	
	$('#onair_move_Di').dialog('open');	
	$("#onair_move_date_next").attr("value","");
	$("#move_breakSEQ").attr('value',move_break_seq_org);
	$("#move_advSEQ").attr('value',move_adv_seq_org);
	
	$('#onair_move_date_next2').datepicker("beforeShowDay","js:unavailable");

/*
	$(document).ready(function() {
		$('#onair_move_date_next').datepicker("beforeShowDay",disableAllTheseDays);
	});
		
			var date = new Date();
	var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
	
	$('#onair_move_date_next').datepicker({
			minDate: new Date(y, m, d),
			dateFormat: 'mm-dd-yy',
	});

	$(function() {
		
			$( "#onair_move_date_next" ).datepicker({ minDate: -20, maxDate: "+1M +10D" });
	
	});
		
	var queryDate = '2013-03-23';
	
	var parsedDate = $.datepicker.parseDate('yy-mm-dd', queryDate);
	
	$('#onair_move_date_next').datepicker('setDate', parsedDate);
	*/
	
	/*$("#onair_split_advname").val(split_adv_id_org );
	$("#onair_split_bksq").val(split_break_seq_org);
	$("#onair_split_advsq ").val(split_adv_seq_org);
	
	onair_show_ProdAgency();
	onair_split_table();*/
		
	//Attribute to break sequence and adv sequence input
	
	return false;	
}


function save_move(){
	
	var delay = 0;
	var old_move_progId = $("#prog_on").val(); 
		 
	var old_moveDay = parseInt( $("#ul_daytab").find("li.ui-state-active").text());
	var old_moveMonth =  parseInt($("#onair_mon").val());
	old_moveDay = zeroPad(old_moveDay, 2);
	old_moveMonth = zeroPad(old_moveMonth, 2);
	
	var old_moveYear = parseInt($("#onair_year").val())-543;
 	var ori_adv_id_org = $('#breakinglist').find('li[list_adv=bk'+$("#move_breakSEQ").attr('value')+'advseq'+$("#move_advSEQ").attr('value')+']').attr("value");
	var ori_break_plan =0 ;
	var ori_break_seq = $("#move_breakSEQ").attr('value');
	var ori_adv_seq = $("#move_advSEQ").attr('value');
	
	var new_moveProgramid = $("#onair_move_prog_next").val();
	//var new_moveDate = new Date($('#onair_move_date_next').val());   
	//new_moveDate = Date.parseExact(new_moveDate,"d/M/yyyy");
	var new_moveDate = Date.parseExact($('#onair_move_date_next').val(),"d/M/yyyy");
	new_moveDate = new Date(new_moveDate);
	
	//console.log("Date= "+new_moveDate);
	
	var new_moveDay = zeroPad(new_moveDate.getDate(), 2);
	var new_moveMonth = parseInt(new_moveDate.getMonth())+1;
	new_moveMonth = zeroPad(new_moveMonth, 2);
	var new_moveYear = new_moveDate.getFullYear();

		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=onair/onMoveConfirm",
			data:{'old_move_progid':old_move_progId,'old_move_day':old_moveDay,'old_move_month':old_moveMonth,'old_move_year':old_moveYear,'ori_adv_id':ori_adv_id_org,'break_plan':ori_break_plan,'ori_break_seq':ori_break_seq,'ori_adv_seq':ori_adv_seq,'new_move_progid':new_moveProgramid,'new_move_day':new_moveDay,'new_move_month':new_moveMonth,'new_move_year':new_moveYear},
			
				success: function(data) {									
					//alert(data);
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการย้ายชุดโฆษณาใหม่อีกครั้ง");		
				}	
											   
		});	
	
	//while(delay < 100){delay++;}
	checkBreak(old_move_progId,old_moveYear,old_moveMonth,old_moveDay);
	$(this).dialog("close");
}
</script>


<?php
 //--------------- Start of Dialog for spliting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'onair_split_Di',
        'options'=>array(
            'title'=>'การแยกโฆษณา',
			'width'=>1200,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:onair_split_cf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" id="onair_split_bksq">
 <form class="form-horizontal" style="font-size:1em" id="onair_split_advsq">
	<div class="row-fluid" align="center">
    	<div class="row-fluid">
    		<div class="span2" align="left" style="margin-left:5px">
           	 	<label for="onair_split_number" style="margin-top:4px" >จำนวนโฆษณาที่ต้องการแยก</label>
            	<select  style="font-size:1em;width:160px; margin-top:1px;" id="onair_split_number" value="" class="input-small" >
                    <option selected="selected">2</option>
                    <option>3</option>            	
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>10</option>
                    <option>11</option>
                    <option>12</option>
                    <option>13</option>
                    <option>14</option>
                    <option>15</option>
                    <option>16</option>
                </select>
            </div>
      		<div class="span2" align="left" style="margin-left:5px">
           	 	<label for="onair_split_timetotal" style="margin-top:4px" >เวลารวมของโฆษณาที่ถูกแยก</label> 
                
                <input style="font-size:1em;width:160px; margin-top:1px" type="text" name="onair_split_timetotal" id="onair_split_timetotal" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/> 
            </div> 
            <div class="span2" align="left" style="margin-left:5px; margin-top:27px; visibility:hidden" id="splittime_caution">
                	<span><a><img src='images/warning.png' style='width:35px;margin-right:2px;' align='left' /></a></span>
            </div> 
        </div>
      	<div class="row-fluid" style="margin-top:20px">
      		<div class="span3" align="left" style="margin-left:5px">
           	 	<label for="onair_split_product" style="margin-top:4px" >ชื่อสินค้า</label> 
                <input style="font-size:0.8em;width:260px; margin-top:1px" type="text" name="onair_split_product" id="onair_split_product" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/> 
            </div>        
      		<div class="span3"  align="left" style="margin-left:10px">
           	 	<label for="onair_split_advname" style="margin-top:4px" >ชื่อโฆษณา</label>
                <input style="font-size:0.8em;width:260px; margin-top:1px" type="text" name="onair_split_advname" id="onair_split_advname" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/> 
            </div>
      		<div class="span1" align="left" style="margin-left:12px">
           	 	<label for="onair_split_timeorg" style="margin-top:4px" >เวลา(วินาที)</label>
           		<input style="font-size:0.8em;width:60px; margin-top:1px;" type="text" name="onair_split_timeorg" id="onair_split_timeorg" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
      		<div class="span2" align="left" style="margin-left:12px">
           	 	<label for="onair_split_agency" style="margin-top:4px" >ชื่อบริษัท</label>
           		<input style="font-size:0.8em;width:160px; margin-top:1px" type="text" name="onair_split_agency" id="onair_split_agency" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
      		<div class="span1" align="left" style="margin-left:12px">
           	 	<label for="onair_split_price" style="margin-top:4px" >ราคา</label>
           		<input style="font-size:0.8em;width:60px; margin-top:1px" type="text" name="onair_split_price" id="onair_split_price" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
      		<div class="span1" align="left" style="margin-left:10px">
           	 	<label for="onair_split_discount" style="margin-top:4px" >ส่วนลด(%)</label>
           		<input style="font-size:0.8em;width:60px; margin-top:1px" type="text" name="onair_split_discount" id="onair_split_discount" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
      		<div class="span1" align="left" style="margin-left:12px">
           	 	<label for="onair_split_netprice" style="margin-top:4px" >ราคาสุทธิ</label>
           		<input style="font-size:0.8em;width:60px;margin-top:1px;" type="text" name="onair_split_netprice" id="onair_split_netprice" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>    
      	</div>
		<div class="row-fluid" style="margin-top:10px">
        <div class="">
		<div class="container" id="page" style="width:inherit; margin-top:1px" align="center">
             <div class="row-fluid">
                <div class="">
                    <table align="center" class="table table-striped" id="onair_split_table">
                      <thead thead align="center">
                        <tr style="font-size:0.9em;height:25px;">
                          <th style="width:25%;text-align:left;padding:4px">สินค้า</th>
                          <th style="width:25%;text-align:left;padding:4px">ชื่อโฆษณา</th>
                          <th style="width:10%;text-align:right;padding:4px">เวลา(วินาที)</th>
                          <th style="width:15%;text-align:left;padding:4px">เอเจนซี่</th>
                          <th style="width:10%;text-align:right;padding:4px">ราคาต่อ Spot</th>
                          <th style="width:5%;text-align:right;padding:4px">ส่วนลด(%)</th>
                          <th style="width:10%;text-align:right;padding:4px">ราคาสุุทธิ</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:1em"  class="onair_time_each_sp">
                      </tbody>
                    </table>
                </div>
             </div>
         </div> 
         </div>
         </div>       
    </div>
 </form>    
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for spliting Advertise  --------------
?>

<script>

function read_main_adv_split(){

	var main_split_agencyid = 0;
	var main_split_agencyname = 0;
	var main_split_advid = 0;
	var main_split_advname = 0;
	var main_split_prodid = 0;
	var main_split_prodname = 0;
	var main_split_timelen = 0;
	
	var main_split_spotprice = 0;
	var main_split_discount = 0;
	var main_split_netprice = 0;
	
	main_split_agencyid = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).attr('agency_id');
	main_split_agencyname = $("#bk"+$('#onair_split_bksq').attr("value")+"agency"+$('#onair_split_advsq').attr("value")).text()

	main_split_advid = $('#breakinglist').find('li[list_adv=bk'+$('#onair_split_bksq').attr("value")+'advseq'+$('#onair_split_advsq').attr("value")+']').attr("value");
	main_split_advname = $("#bk"+$('#onair_split_bksq').attr("value")+"advname"+$('#onair_split_advsq').attr("value")).text();
	main_split_prodid = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).attr('prod_id');
	main_split_prodname = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).text();
	main_split_timelen = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).attr('time_bk');
	main_split_spotprice = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).attr('spot_price');
	main_split_discount = $("#bk"+$('#onair_split_bksq').attr("value")+"prod"+$('#onair_split_advsq').attr("value")).attr('on_discount');
	
	main_split_netprice = parseInt(main_split_spotprice)*(1-(parseInt(main_split_discount)/100));
	main_split_netprice = main_split_netprice.toFixed(2);
	
	$("#onair_split_product").val(main_split_prodname);
	$("#onair_split_product").attr("split_prodid",main_split_prodid);
	$("#onair_split_advname").val(main_split_advname);
	$("#onair_split_advname").attr("split_advid",main_split_advid);
	$("#onair_split_agency").val(main_split_agencyname);
	$("#onair_split_agency").attr("split_agencyid",main_split_agencyid);
	$("#onair_split_timeorg").val(main_split_timelen);
	
	$("#onair_split_price").val(main_split_spotprice);
	$("#onair_split_discount").val(main_split_discount);
	$("#onair_split_netprice").val(main_split_netprice);
	
	
}



function get_bktype_split(){
	
	var onair_bk_type = "ราคาปกติ";
	
	if( $("input[type='radio'][name='bk_type_edit']:checked").attr("id") == "bk_type_edit_2"){
		
			onair_bk_type = "1"; // Pack price'
			
	
	}else {
		
			onair_bk_type = "0"; // Normal price and other price
		
	}
	
	$("#onair_split_price").attr("price_type",onair_bk_type);
	
//return(onair_bk_type);
}

function cal_spotprice_split(spot_time_split){
	
	var prog_id_price_split = 0;
	var onair_spot_price_sp = 0;
	var onair_calprice = 0 ;
	var onair_perdiscount = 0;
	
	prog_id_price_split = $("#prog_on").attr("value");

	var onair_prof_id = $("#prog_on").attr("onair_prof_id");
	var dayweek_num = $("#prog_on").attr("dayweek_num"); // HIDDEN Onair Prof ID
	var time_start = $("#prog_on").attr("time_start"); // HIDDEN Onair Prof ID	
	
	//var onair_mon = parseInt($("#onair_mon").attr('value'));
	//var onair_year = parseInt($("#onair_year").attr('value'))-543;
	//var onair_day = $("#ul_daytab").find("li.ui-state-active").text();	
	
	if($("#onair_split_price").attr("price_type") == "1"){ // PACK PRICE

		//per_discount = $("#mAdvDiscount").attr("value");
		
		$.ajaxSetup({
			async: false
		});	
	
//		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&year='+onair_year+'&month='+onair_mon+'&day='+onair_day+'',{
	
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					if(parseInt(val.minute_pack) != 0){ // Must define minute of PACK
						
						onair_spot_price = (parseInt(val.price_pack)/(60*parseInt(val.minute_pack)))*parseInt(spot_time);
					}else {
						
						onair_spot_price = 0;
						
					}
					onair_spot_price_sp = onair_spot_price_sp.toFixed(2);// Round up
							
					
				});
			}
		});		

		
	}else{
		
		
		$.ajaxSetup({
			async: false
		});
		
	
//		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&year='+onair_year+'&month='+onair_mon+'&day='+onair_day+'',{
	
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'&onair_prof_id='+onair_prof_id+'&dayweek_num='+dayweek_num+'&time_start='+time_start+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					//onair_spot_price_sp = (parseInt(val.price_net)/60)*parseInt(spot_time_split);
					onair_spot_price = (parseInt(val.price_minute)/60)*parseInt(spot_time);
					onair_spot_price_sp = onair_spot_price_sp.toFixed(2);// Round up
					
				});
			}
		});
		
	}
	
	return(onair_spot_price_sp);
	
}

function onair_split_table(){
	
	var ini_time_sp = 0;
	var onair_num_split =0;
	var onair_totaltime_sp = 0;
	var spot_price_split = 0;
	var spot_discount_split = 0;
	var net_price_split = 0;
	var spot_time = 0;
	
	onair_num_split =$("#onair_split_number").attr('value');
	
	$("#onair_split_table tbody tr").remove();
	for(var sp=1; sp <= onair_num_split; sp++ ){
			
		$("#onair_split_table tbody").append(
			"<tr >"+ 
						"<td style='font-size:0.8em;text-align:left;height:15px;padding:2px'>"+
						"<input type='text' name='onair_prod_sp' id='onair_prod_sp_"+sp+"' spID='"+sp+"' class='onair_prod_sp' style='height:20px;width:276px' autocomplete='off' />"+	
						"</td>"+
						"<td style='font-size:0.8em;text-align:left;height:15px;padding:2px'>"+
						"<input type='text' name='onair_adv_sp' id='onair_adv_sp_"+sp+"' class='onair_adv_sp' style='font-size:1em;height:20px;width:276px;' />"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<select class='onair_splittime' style='height:30px;width:90px' name='onair_time_sp' id='onair_time_sp_"+sp+"' >"+
							"<option value='15'>15</option>"+
							"<option value='30'>30</option>"+
							"<option value='45'>45</option>"+
							"<option value='60'>60</option>"+
							"<option value='75'>75</option>"+
							"<option value='90'>90</option>"+
							"<option value='105'>105</option>"+
							"<option value='120'>120</option>"+
							"<option value='135'>135</option>"+
							"<option value='150'>150</option>"+
							"<option value='165'>165</option>"+
							"<option value='180'>180</option>"+
							"<option value='195'>195</option>"+
							"<option value='210'>210</option>"+
							"<option value='125'>125</option>"+
							"<option value='240'>240</option>"+
						"</select>"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:left;height:15px;padding:2px'>"+
						"<input type='text' name='onair_agency_sp' id='onair_agency_sp_"+sp+"'  class='ui-ams-input text ui-widget-content ui-corner-all'  style='height:20px;width:160px' value='"+$("#onair_split_agency").val()+"' readonly='readonly' />"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<input type='text' name='onair_spotprice_sp' id='onair_spotprice_sp_"+sp+"' class='input-mini' value='' style='height:20px;width:84px' />"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<select  name='onair_discount_sp' style='height:30px;width:90px'  id='onair_discount_sp_"+sp+"' class='onair_discount_sp' >"+
							"<option value='0'>0</option>"+
							"<option value='5'>5</option>"+
							"<option value='10'>10</option>"+
							"<option value='15'>15</option>"+            	
							"<option value='20'>20</option>"+
							"<option value='25'>25</option>"+
							"<option value='30'>30</option>"+
							"<option value='35'>35</option>"+
							"<option value='40'>40</option>"+
							"<option value='45'>45</option>"+
							"<option value='50'>50</option>"+
						"</select>"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<input type='text' name='onair_netprice_sp' id='onair_netprice_sp_"+sp+"' class='onair_netprice_sp' style='height:20px;width:84px' />"+
						"</td>"+
			"</tr>"
		); 
		
		$("#onair_discount_sp_"+sp).val($("#onair_split_discount").val()); // Default the old discount
		
		spot_time = $("#onair_time_sp_"+sp).val();
		spot_price_split = cal_spotprice_split(spot_time);		
		spot_discount_split = $("#onair_discount_sp_"+sp).val();

		net_price_split = parseInt(spot_price_split)*(1-(parseInt(spot_discount_split)/100));
		net_price_split = net_price_split.toFixed(2);
		
		$("#onair_spotprice_sp_"+sp).val(spot_price_split);
		$("#onair_netprice_sp_"+sp).val(net_price_split);
		
	}
		
	for(var time_sp=1; time_sp <= onair_num_split; time_sp++ ){
			
		onair_totaltime_sp = onair_totaltime_sp + parseInt($("#onair_time_sp_"+time_sp+"").attr('value'));		
	}
	
	$("#onair_split_timetotal").val(onair_totaltime_sp);
	
	if(onair_totaltime_sp > $("#onair_split_timeorg").attr('value')){
		
		$("#splittime_caution").css('visibility','visible');
		
	}else {
		
		$("#splittime_caution").css('visibility','hidden');
		
	}
}

function split_timelen_change(){
	
	var onair_cg_num_split = 0;
	var spot_time = 0;
	var spot_price_split = 0;
	var spot_discount_split = 0;
	var net_price_split = 0;
	var onair_cg_totaltime_sp= 0;
	
	onair_cg_num_split = $("#onair_split_number").attr('value');
	
	
	for(var tsp=1; tsp <= onair_cg_num_split; tsp++ ){	
	
		
		spot_time = $("#onair_time_sp_"+tsp).val();
		spot_price_split = cal_spotprice_split(spot_time);		
		spot_discount_split = $("#onair_discount_sp_"+tsp).val();

		net_price_split = parseInt(spot_price_split)*(1-(parseInt(spot_discount_split)/100));
		net_price_split = net_price_split.toFixed(2);
		
		$("#onair_spotprice_sp_"+tsp).val(spot_price_split);
		$("#onair_netprice_sp_"+tsp).val(net_price_split);
		
		onair_cg_totaltime_sp = onair_cg_totaltime_sp + parseInt($("#onair_time_sp_"+tsp+"").attr('value'));
	
	}
	
	$("#onair_split_timetotal").val(onair_cg_totaltime_sp);
	
	if(onair_cg_totaltime_sp > $("#onair_split_timeorg").attr('value')){
		
		$("#splittime_caution").css('visibility','visible');
		
	}else {
		
		$("#splittime_caution").css('visibility','hidden');
		
	}

}


function onair_split_cf(){
	
	var delay = 0;	
	var onair_org_adv_id = 0;	
	var onair_split_adv_name = [];
	var onair_split_adv_timelen = [];
	var onair_split_prod_id = 0;
	var onair_split_bk_seq = 0;
	var onair_split_adv_seq = 0;
	var onair_split_timeorg =0;
	
	var onprod_id_array = [];
	var onprod_name_array = [];	
	var onadv_name_array = [];
	var ontime_length_array = [];
	var onspot_price_array = [];
	var ondiscount_array = [];	
	
	var onair_day = $("#ul_daytab").find("li.ui-state-active").text();
	var onair_prog_id = $("#prog_on").attr('value');
	var onair_month = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	
	
	
	//----------- Should Send BREAK PLAN ---------->
	var break_plan = $("#programplans").children("div").find("button.active").attr("value");
			 
		if(break_plan){
				
			break_plan = break_plan;
				
		}else{
				
			$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
			$("#onair_plan0").addClass("btn-inverse active")  	
			break_plan = 0;			
				
	}
	
	var reserveBKID = $("#prog_on").attr("break_id");	
	
	onair_split_timeorg = $("#onair_split_timeorg").attr('value');
	
	onair_org_adv_id = $("#onair_split_advname").attr('split_advid');
	onair_split_prod_id =  $("#onair_split_product").attr('split_prodid');
	onair_split_bk_seq =  $("#onair_split_bksq").attr('value');
	onair_split_adv_seq =  $("#onair_split_advsq").attr('value');
	
	//console.log("onair_month= "+onair_org_adv_id);
	
	for(var on_sp_cnt = 1; on_sp_cnt <= $("#onair_split_number").attr('value'); on_sp_cnt++ ){
		
		onprod_id_array[on_sp_cnt-1] = $("#onair_prod_sp_"+on_sp_cnt).attr("sp_id");
		onprod_name_array[on_sp_cnt-1] = $("#onair_prod_sp_"+on_sp_cnt).val();		
		onadv_name_array[on_sp_cnt-1] = $("#onair_adv_sp_"+on_sp_cnt).val();
		ontime_length_array[on_sp_cnt-1] = $("#onair_time_sp_"+on_sp_cnt).val();
		onspot_price_array[on_sp_cnt-1] = $("#onair_spotprice_sp_"+on_sp_cnt).val();
		ondiscount_array[on_sp_cnt-1] = $("#onair_discount_sp_"+on_sp_cnt).val();
		
	}
	
	//console.log("onair_org_adv_id= "+onair_org_adv_id+" pro_id= "+onprod_id_array+"  onair_split_adv_timelen= "+onair_split_adv_timelen)
	//console.log("pro_id= "+onprod_id_array+"onadv_name_array= "+onadv_name_array)
		
	
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=onair/onSplitadv",
			data:{'org_adv_id':onair_org_adv_id,'split_prod_id':onprod_id_array,'split_prod_name':onprod_name_array, 'split_adv_name':onadv_name_array, 'split_adv_timelen':ontime_length_array,'split_spot_price':onspot_price_array,'split_discount':ondiscount_array,'org_prod_id':onair_split_prod_id,'prog_id':onair_prog_id,'day':onair_day,'month':onair_month,'year':onair_year,'time_org':onair_split_timeorg,'split_bk_seq':onair_split_bk_seq,'split_adv_seq':onair_split_adv_seq,'break_plan':break_plan, 'break_id':reserveBKID},
			
				success: function(data) {									
					//alert("การแยกชุดโฆษณาเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการแยกชุดโฆษณาใหม่อีกครั้ง");		
				}	
											   
		});	
		
		//while(delay < 100){delay++;}
		
		checkBreak(onair_prog_id,onair_year,onair_month,onair_day);
		$(this).dialog("close");
	
}


function showTip_split_onadv(input_adv_id){
	
	var sentData = $("#"+input_adv_id).val();
	var p = $("#"+input_adv_id).position();
	
	var prodID =  $("#"+input_adv_id).attr("prodID");

	console.log("prodID= "+prodID);
		
	var add_OnairTapeNameID = [];
	var add_OnairTapeName = [];
	var count_prod = 0;

	$.ajaxSetup({
		async: false
	});
			
	$.ajax('?r=onair/japi&action=autoOnairTapeName&prod_id='+prodID+'&tape_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairTapeName){
				$.each(autoOnairTapeName,function(k,v){ 
				
					add_OnairTapeNameID.push(v.tape_name+":"+v.tape_id);
					add_OnairTapeName.push(v.tape_name);
					
				});
				
				
				
				$("#"+input_adv_id).autocomplete({
					
					source:add_OnairTapeName,
					select: function (event, ui) {
						
						$("#"+input_adv_id).val(ui.item.label); // display the selected text
						
					}
					
				});
				
			}
		});


}


function showTip_split_onproduct(input_product_id){
	
	var sentData = $("#"+input_product_id).val();
	var p = $("#"+input_product_id).position();

		
	var prod_split_name = [];
	var prod_split_all = [];
	var count_prod = 0;

	$.ajaxSetup({
			async: false
	});
		
	$.ajax('?r=onair/japi&action=autoMergeProd&prod_name='+sentData+'',{
		
		type: 'GET',
		dataType: 'json',
		success: function(autoMergeProd){
			$.each(autoMergeProd,function(k,v){ 
			
				prod_split_all.push(v.prod_name+":"+v.prod_id);
				prod_split_name.push(v.prod_name);
				
			});
			
			//console.log("prod_split_all.length="+prod_split_all.length);
			
			$("#"+input_product_id).autocomplete({
				
				source:prod_split_name,
				select: function (event, ui) {
					
					//console.log("start to separate");
					
					$("#"+input_product_id).val(ui.item.label); // display the selected text
					//$("#onair_prod_sp_2").val(ui.item.value);
					//$("#txtAllowSearchID").val(ui.item.value); // save selected id to hidden input
					for (var i=0;i < prod_split_all.length ;i++){
								
						var n = prod_split_all[i].split(":"); 
						if (n[0]== $("#"+input_product_id).val()) {
										
								$("#"+input_product_id).attr("sp_name", n[0]);
								$("#"+input_product_id).attr("sp_id", n[1]);
								
								var splValue = $("#"+input_product_id).attr("spID");
								
									$("#onair_adv_sp_"+splValue).attr("prodID",n[1]);
	
								
								//console.log("check= "+n[1]+"  "+n[0] );
										
						}
					}	
					
				},
				search: function() {
					
					$("#"+input_product_id).attr("sp_id","");
					
				}

								
			});
		}
	});	
}


function open_split_adv_onair(){
	
	var split_break_seq_org = 0;
	var split_adv_seq_org = 0;
	var split_adv_id_org = 0;
	
	//confirm_onair(); //confirm database first
	split_break_seq_org = $("#onair_input_editDi").attr('value');
	split_adv_seq_org = $("#adv_seq_list").attr('value');
	
	get_bktype_split()// Detect Price 
	
	//split_adv_id_org = $("#mAdvName_edit").attr('value');
	
	$(this).dialog("close");
	
	$('#onair_split_Di').dialog('open');
	
	//$("#onair_split_advname").val(split_adv_id_org );
	$("#onair_split_bksq").val(split_break_seq_org);
	$("#onair_split_advsq").val(split_adv_seq_org);
	
	read_main_adv_split();
	onair_split_table();
		
	//Attribute to break sequence and adv sequence input
	
	return false;	
}



// Autocomplete split function



$('.onair_prod_sp').live('keyup', 'input[name=onair_prod_sp]', function() {
	
	var current_inprod_sp = $(this).closest('tr').find("input:[name=onair_prod_sp]").attr("id");
	showTip_split_onproduct(current_inprod_sp);
	

	
});


$('.onair_adv_sp').live('keyup', 'input[name=onair_adv_sp]', function() {
	
	var current_inadv_sp = $(this).closest('tr').find("input:[name=onair_adv_sp]").attr("id");
	
	showTip_split_onadv(current_inadv_sp);
});


// End Autocomplete split function 

$('.onair_splittime').live('change', 'input[name=onair_time_sp]', function() {
	
	split_timelen_change();
	
});


$('.onair_discount_sp').live('change', 'input[name=onair_discount_sp]', function() {
	
 	split_timelen_change();;
	
});


$("#onair_split_number").change(function() {
	
	onair_split_table();
								  
});

//----------> End of Split -------------->
</script>

<?php
 //--------------- Start of Dialog for merging Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'onair_merge_Di',
        'options'=>array(
            'title'=>'การรวมโฆษณา',
			'width'=>1000,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:onair_merge_adv_cf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input" id="merge_adv_dialog" >
 <form class="form-horizontal" style="font-size:1em" >
 	 <div class="row-fluid" align="center">
    	<div class="span3" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_prodname">สินค้า</label>
			<input style="font-size:0.8em;width:200px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prodname" id="onair_merge_prodname" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>
    	<div class="span3" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_advname">ชื่อโฆษณา</label>
			<input style="font-size:0.8em;width:200px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_advname" id="onair_merge_advname" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>
    	<div class="span2" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_agencyname">เอเจนซี่</label>
			<input style="font-size:0.8em;;width:120px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_agencyname" id="onair_merge_agencyname" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>
    	<div class="span2" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_timelen">เวลา(วินาที)</label>
			<input style="font-size:0.8em;width:120px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_timelen" id="onair_merge_timelen" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>
    	<div class="span1" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_bkseq">เบรก</label>
			<input style="font-size:0.8em;width:40px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_bkseq" id="onair_merge_bkseq" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>
    	<div class="span1" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_advseq">โฆษณา</label>
			<input style="font-size:0.8em;width:40px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_advseq" id="onair_merge_advseq" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
        </div>  
     </div>
 	 <div class="row-fluid" align="center" style="margin-top:10px;">
		<div class="container" id="page" style="width:98%"  style="margin-right:5px">
        	<div class="row-fluid">
                <div class="">
                    <table align="center" class="table table-striped" id="merge_table">
                      <thead>
                        <tr style="font-size:0.8em;height:35px;">
                          <th style="width:5%;text-align:center;padding:6px"></th>
                          <th style="width:25%;text-align:left;padding:6px">สินค้า</th>
                          <th style="width:25%;text-align:left;padding:6px">ชื่อโฆษณา</th>
                          <th style="width:15%;text-align:left;padding:6px">เอเจนซี่</th>
                          <th style="width:10%;text-align:right;padding:6px">เวลา(วินาที)</th>
                          <th style="width:10%;text-align:right;padding:6px">เบรก</th>
                          <th style="width:10%;text-align:right;padding:6px">โฆษณา</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:0.8em">
                      </tbody>
                    </table>
                </div>
         	</div>
        </div>
   	 </div> 

     <div class="row-fluid" align="center">
    	<div class="span3" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_prodname_new">สินค้า</label>
			<input style="font-size:0.8em;width:200px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prodname_new" id="onair_merge_prodname_new" autocomplete="off" class="ui-ams-input text ui-widget-content ui-corner-all " />	
            <div id="merge_prodname_newlist"></div>  				  
        </div>
    	<div class="span3" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_advname_new">ชื่อโฆษณา</label>
			<input style="font-size:0.8em;width:200px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_advname_new" id="onair_merge_advname_new" class="ui-ams-input text ui-widget-content ui-corner-all " />  
        </div>

    	<div class="span1" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_timelen_new">เวลา(วินาที)</label>
			<input style="font-size:0.8em;width:60px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_timelen_new" id="onair_merge_timelen_new" class="ui-ams-input text ui-widget-content ui-corner-all " />  
        </div>
    	<div class="span2" align="left" style="margin-top:5px;margin-left:35px">
        	<label for="onair_merge_price_new">ราคาต่อ Spot</label>
			<input style="font-size:0.8em;;width:120px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_price_new" id="onair_merge_price_new" class="ui-ams-input text ui-widget-content ui-corner-all " />  
        </div>
    	<div class="span1" align="left" style="margin-top:5px;margin-left:10px">
        	<label for="onair_merge_discount_new">ส่วนลด(%)</label>
           	<select id="onair_merge_discount_new"   class="input-small" style="font-size:0.8em;width:60px;padding-top:3px;padding-bottom:7px" >
            	<option value="0">0</option>
         		<option value="5">5</option>
      	 		<option value="10">10</option>
             	<option value="15">15</option>            	
             	<option value="20">20</option>
             	<option value="25">25</option>
              	<option value="30">30</option>
              	<option value="35">35</option>
              	<option value="40">40</option>
             	<option value="45">45</option>
              	<option value="50">50</option>
        	</select>  
        </div>
    	<div class="span2" align="left" style="margin-top:5px;margin-left:20px">
        	<label for="onair_merge_netprice_new">ราคาสุทธิ</label>
			<input style="font-size:0.8em;width:120px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_netprice_new" id="onair_merge_netprice_new" class="ui-ams-input text ui-widget-content ui-corner-all " />  
        </div>  
     </div>    
 </form>
</div>

  
<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for merging Advertise  --------------
?>

<script>

function open_merge_adv_onair(){
	
	var onair_break_seq_org = 0;
	var onair_adv_seq_org = 0;
	var onair_adv_id_org = 0;
	var onair_adv_name_org = 0;
	
	
	$("#onair_merge_prodname_new").val('');
	$("#onair_merge_advname_new").val('');
	$("#onair_merge_timelen_new").val('');
	$("#onair_merge_price_new").val('');
	$("#onair_merge_discount_new").val('');
	$("#onair_merge_netprice_new").val('');	
	
	onair_break_seq_org = $("#onair_input_editDi").attr('value');
	onair_adv_seq_org = $("#adv_seq_list").attr('value');
	
	$(this).dialog("close");
	
	$('#onair_merge_Di').dialog('open');
	
	$("#onair_merge_bkseq").val(onair_break_seq_org);
	$("#onair_merge_advseq").val(onair_adv_seq_org);
	
	read_main_adv_merge();
	check_merge_prop();
	read_check_merge_adv();
	
	//Read_Mereg_Prog(); //---->Show program list for seccend advertise
}

function read_main_adv_merge(){
	
	var main_merge_agencyid = 0;
	var main_merge_agencyname = 0;
	var main_merge_advid = 0;
	var main_merge_advname = 0;
	var main_merge_prodid = 0;
	var main_merge_prodname = 0;
	var main_merge_timelen = 0;
	

	
	main_merge_agencyid = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('agency_id');
	main_merge_agencyname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"agency"+$('#onair_merge_advseq').attr("value")).text()

	main_merge_advid = $('#breakinglist').find('li[list_adv=bk'+$('#onair_merge_bkseq').attr("value")+'advseq'+$('#onair_merge_advseq').attr("value")+']').attr("value");
	main_merge_advname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"advname"+$('#onair_merge_advseq').attr("value")).text();
	main_merge_prodid = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('prod_id');
	main_merge_prodname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).text();
	main_merge_timelen = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('time_bk');
	
	
	$("#onair_merge_prodname").val(main_merge_prodname);
	$("#onair_merge_prodname").attr("merge_prodid",main_merge_prodid);
	$("#onair_merge_advname").val(main_merge_advname);
	$("#onair_merge_advname").attr("merge_advdid",main_merge_advid);
	$("#onair_merge_agencyname").val(main_merge_agencyname);
	$("#onair_merge_agencyname").attr("merge_agencyid",main_merge_agencyid);
	$("#onair_merge_timelen").val(main_merge_timelen);
}

function check_merge_prop(){
	
	var main_merge_progid = 0;
	var main_merge_progname = 0;
	var main_merge_bktype = 0;
	var adv_timelen = 0;
	var main_merge_agencyid =0;

	
	var day = $("#ul_daytab").find("li.ui-state-active").text();
	var prog_on = $("#prog_on").attr('value');
	var onair_mon = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	
	var main_merge_bkseq = 0;
	var main_merge_advseq =0;
		
	var break_plan = $("#programplans").children("div").find("button.active").attr("value");
			 
		if(break_plan){
				
			break_plan = break_plan;
				
		}else{
				
			$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
			$("#onair_plan0").addClass("btn-inverse active")  	
			break_plan = 0;			
				
	}
	
	var reserveBKID = $("#prog_on").attr("break_id");	
		
		
	main_merge_progid = $("#prog_on").attr('value');
	main_merge_progname = $("#prog_on option:selected").text();
	main_merge_agencyid = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('agency_id');
	
	main_merge_bktype = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('bk_type');
	
	main_merge_bkseq = $("#onair_merge_bkseq").attr("value");
	main_merge_advseq = $("#onair_merge_advseq").attr("value");

	$.ajaxSetup({
		async: false
	});
	//console.log("main_merge_bktype="+main_merge_bktype);
	
	$.ajax('?r=onair/japi&action=checkMeregProp&prog_id='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'&price_type='+main_merge_bktype+'&agency_id='+main_merge_agencyid+'&break_seq='+main_merge_bkseq+'&adv_seq='+main_merge_advseq+',&break_plan='+break_plan+'&break_id='+reserveBKID+'',{
		
		type: 'GET',
		dataType: 'json',
		success: function(checkMeregProp){

			//$("#onair_merge_prog_next option").remove();
			$("#merge_table tbody tr").remove();			
			$.each(checkMeregProp,function(k,v){ 
			
			 $("#onair_merge_bkseq").attr("break_id",v.break_id);

				if(v.adv_time_len != 0){
					
					adv_timelen = v.adv_time_len;
					
				}else{
					
					adv_timelen = v.time_len;
				
				}
			
				if(v.break_seq == main_merge_bkseq && v.adv_seq == main_merge_advseq ){
					
					
				}else{
					
						$("#merge_table tbody").append(
							"<tr style='height:25px;padding-top:4px;padding-bottom:4px' class='onair_merge_adv' >"+ 
								"<td style='text-align:center;padding:4px'><label class='checkbox inline'>"+
								"<input name='onair_mergeadv' style='margin-top:2px' type='checkbox' id='check"+v.adv_id+"' adv_id='"+v.adv_id+"' adv_time='"+adv_timelen+"' bk_seq='"+v.break_seq+"' adv_seq='"+v.adv_seq+"' calprice='"+v.calc_price+"' break_id='"+v.break_id+"' break_type='"+v.break_type+"' ></label></td>"+
								"<td style='text-align:left;padding-top:8px'>"+v.prod_name+"</td>" + 
								"<td style='text-align:left;padding-top:8px'>"+v.adv_name+"</td>" +
								"<td style='text-align:left;padding-top:8px'>"+v.agency_name+"</td>" + 
								"<td style='text-align:right;padding-top:8px'>"+adv_timelen+"</td>" + 
								"<td style='text-align:right;padding-top:8px'>"+v.break_seq+"</td>" + 
								"<td style='text-align:right;padding-top:8px'>"+v.adv_seq+"</td>" +
							"</tr>"  
						);
					
					//console.log("bktype= "+v.price_type+"calc_price= "+v.calc_price+"adv_id= "+v.adv_id+"adv_name= "+v.adv_name);
				}
			});
			
			//console.log("max_advmerge="+advmerge_count)

		}
	});
	
}

function read_check_merge_adv(){
	
	var advmerge_count = 0;
	var merge_adv_titaltime = 0;
	var price_per_min = 0;
	var merge_adv_newprice = 0;
	var main_merge_calprice = 0;
	
	
	$(".onair_merge_adv").find("input[type='checkbox']:checked").each(function() {
			
			//console.log("main_merge_calprice="+$(this).attr("calprice"))
			
			merge_adv_newprice = parseInt(merge_adv_newprice) + parseInt($(this).attr("calprice"));
			merge_adv_titaltime = parseInt(merge_adv_titaltime) + parseInt($(this).attr("adv_time"));
			
	});

	main_merge_calprice = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('spot_price');
	
	
	merge_adv_titaltime = parseInt(merge_adv_titaltime) + parseInt($("#onair_merge_timelen").attr("value"));
	merge_adv_newprice = parseInt(merge_adv_newprice) + parseInt(main_merge_calprice);
	merge_adv_newprice = merge_adv_newprice.toFixed(2);
	

	$("#onair_merge_timelen_new").val(merge_adv_titaltime);
	$("#onair_merge_price_new").val(merge_adv_newprice);
	
	var merge_discount_new = $("#onair_merge_discount_new").attr("value");
	var merge_calprice_new = $("#onair_merge_price_new").attr("value");
	
	
	var merge_netprice_new = parseInt(merge_calprice_new)*(1-(parseInt(merge_discount_new)/100));
	merge_netprice_new = merge_netprice_new.toFixed(2);
	
	$("#onair_merge_netprice_new").val(merge_netprice_new);
	
	
}

//"input[type='checkbox']

$('#merge_table').live('change', 'input[name=onair_mergeadv]', function() {
	
	read_check_merge_adv();
	
});


$("#onair_merge_discount_new").click(function(){
	
	var merge_discount_new = $("#onair_merge_discount_new").attr("value");
	var merge_calprice_new = $("#onair_merge_price_new").attr("value");
	
	var merge_netprice_new = parseInt(merge_calprice_new)*(1-(parseInt(merge_discount_new)/100));
	merge_netprice_new = merge_netprice_new.toFixed(2);
	
	$("#onair_merge_netprice_new").val(merge_netprice_new);
	
})

//----- Auto complete moerge PRODUCT


//----- Auto complete moerge PRODUCT



function autocom_tapeprodMerge(){
	
		var sentData = $("#onair_merge_advname_new").attr("value");
		
		var p = $("#onair_merge_advname_new").position();
		var autoProdID = $("#onair_merge_prodname_new").attr("merged_prod_id");
		
		
		//console.log("autoProdID= "+autoProdID);
		
		var add_OnairTapeNameID = [];
		var add_OnairTapeName = [];
		var count_prod = 0;

		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairTapeName&prod_id='+autoProdID+'&tape_name='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairTapeName){
				$.each(autoOnairTapeName,function(k,v){ 
				
					add_OnairTapeNameID.push(v.tape_name+":"+v.tape_id);
					add_OnairTapeName.push(v.tape_name);
					
				});
				
				$("#onair_merge_advname_new").autocomplete({
					
					source:add_OnairTapeName,
					select: function (event, ui) {
						
						$("#onair_merge_advname_new").val(ui.item.label); // display the selected text
						for (var i=0;i < add_OnairTapeNameID.length ;i++){
									
							var n = add_OnairTapeNameID[i].split(":"); 
							if (n[0]== $("#onair_merge_advname_new").val()) {
											
								$("#onair_merge_advname_new").attr("tape_name", n[0]);
								$("#onair_merge_advname_new").attr("tape_id", n[1]);
								
								//searchOnairTimelenght(); // Determine Time Lenght
									
							}
						}	
						
					},
					search: function() {
						
						$("#onair_merge_advname_new").attr("tape_id","");
						
					}
				});
			}
		});			
	
}


function autocom_prodMerge(){
	
	var sentData = $("#onair_merge_prodname_new").val();
	var p = $("#onair_merge_prodname_new").position();

		
	var prod_split_name = [];
	var prod_split_all = [];
	var count_prod = 0;

	$.ajaxSetup({
			async: false
	});
		
	$.ajax('?r=onair/japi&action=autoMergeProd&prod_name='+sentData+'',{
		
		type: 'GET',
		dataType: 'json',
		success: function(autoMergeProd){
			$.each(autoMergeProd,function(k,v){ 
			
				prod_split_all.push(v.prod_name+":"+v.prod_id);
				prod_split_name.push(v.prod_name);
				
			});
			
			//console.log("prod_split_all.length="+prod_split_all.length);
			
			$("#onair_merge_prodname_new").autocomplete({
				
				source:prod_split_name,
				select: function (event, ui) {
					
					//console.log("start to separate");
					
					$("#onair_merge_prodname_new").val(ui.item.label); // display the selected text
					//$("#onair_prod_sp_2").val(ui.item.value);
					//$("#txtAllowSearchID").val(ui.item.value); // save selected id to hidden input
					for (var i=0;i < prod_split_all.length ;i++){
								
						var n = prod_split_all[i].split(":"); 
						if (n[0]== $("#onair_merge_prodname_new").val()) {
										
								$("#onair_merge_prodname_new").attr("sp_name", n[0]);
								$("#onair_merge_prodname_new").attr("merged_prod_id", n[1]);
								
								//console.log("check= "+n[1]+"  "+n[0] );
										
						}
					}	
					
				},
				search: function() {
					
					$("#onair_merge_prodname_new").attr("merged_prod_id","");
					
				}

								
			});
		}
	});	
}



function onair_merge_adv_cf(){
	
	var delay = 0;
	
	var del_merge_break_seq = [];
	var del_merge_adv_seq = [];
	var del_merge_adv = [];
	var del_merge_bkid = [];
	
	var merged_count = 0;
	
	if($("#onair_merge_prodname_new").attr('value') != "" && $("#onair_merge_advname_new").attr('value') != ""){ 
		
		//merge_break_type_new
		
		var new_merge_prodid = $('#onair_merge_prodname_new').attr("merged_prod_id"); // new prodid
		var new_merge_prodname = $('#onair_merge_prodname_new').attr("value"); // new prodname
		var new_merge_advname = $('#onair_merge_advname_new').attr("value");
		var new_merge_timelen = $('#onair_merge_timelen_new').attr("value");
		
		var new_merge_bkseq = $('#onair_merge_bkseq').attr("value");
		var new_merge_advseq = $('#onair_merge_advseq').attr("value");
		var new_merge_price = $('#onair_merge_price_new').attr("value");
		var new_merge_discount = $('#onair_merge_discount_new').attr("value");
		
		var old_merge_advid = $("#onair_merge_advname").attr("merge_advdid");
		var old_prog_id = $("#prog_on").attr('value');
		var old_breakid = $("#onair_merge_bkseq").attr("break_id");
		
		//console.log("old_merge_advid="+new_merge_price);
		
		$(".onair_merge_adv").find("input[type='checkbox']:checked").each(function() {
		
			del_merge_break_seq[merged_count++] =  $(this).attr("bk_seq");
			del_merge_adv_seq[merged_count++] =  $(this).attr("adv_seq");
			del_merge_adv[merged_count++] = $(this).attr("adv_id");
			del_merge_bkid[merged_count++] = $(this).attr("break_id");
			
			//-------> Plan of BREAK Next Progress
			
		});
		
		
		var break_plan = $("#programplans").children("div").find("button.active").attr("value");
				 
			if(break_plan){
					
				break_plan = break_plan;
					
			}else{
					
				$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
				$("#onair_plan0").addClass("btn-inverse active")  	
				break_plan = 0;			
					
		}
		
		del_merge_break_seq = del_merge_break_seq.filter(function(e){return e});
		del_merge_adv_seq = del_merge_adv_seq.filter(function(e){return e});
		del_merge_adv = del_merge_adv.filter(function(e){return e});
		del_merge_bkid = del_merge_bkid.filter(function(e){return e});
		
		//console.log("del_merge_adv="+del_merge_adv);
		
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=onair/confirmMergeAdv",
			data:{'break_seq':new_merge_bkseq,'adv_seq':new_merge_advseq,'break_id':old_breakid,'calc_price':new_merge_price,'discount':new_merge_discount,'prod_id':new_merge_prodid,'prod_name':new_merge_prodname,'adv_name':new_merge_advname,'timelen':new_merge_timelen,'adv_id':old_merge_advid,'old_prog_id':old_prog_id,'m_bkseq':del_merge_break_seq,'m_advseq':del_merge_adv_seq,'m_advid':del_merge_adv,'break_plan':break_plan},
				
				success: function(data){
														
					//alert("การแยกชุดโฆษณาเสร็จสมบูรณ์");
					
					//alert(data);
				},
				error: function(data){	
				
					//alert(data);
								
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการรวมชุดโฆษณาใหม่อีกครั้ง");		
				}	
												   
		});	
	
		var onair_day = $("#ul_daytab").find("li.ui-state-active").text();
		var onair_prog_id = $("#prog_on").attr('value');
		var onair_month = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		//while(delay < 100){delay++;}
		checkBreak(onair_prog_id,onair_year,onair_month,onair_day);
		$(this).dialog("close");
	
	}else{
		
		alert("กรุณากำหนดชื่อโฆษณาและชื่อสินค้าหรือคลิกปุ่มยกเลิกเพื่อออกจากหน้าต่างนี้");
	}

}

$("#onair_merge_advname_new").keyup(function(event){
	
		autocom_tapeprodMerge();		
		
});

$("#onair_merge_prodname_new").keyup(function(event){
	
		autocom_prodMerge();		
		
});



</script>


<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'changeBreakPropDi',
        'options'=>array(
            'title'=>'แก้ไขชนิดเบรคและเวลาออกอากาศ',
			'width'=>500,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'เพิ่มเบรค'=>'js:addBreak',
				'บันทึกการแก้ไขเบรค'=>'js:changeBreakPropConf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>
    <div class="dialog_input">
    <form class="form-horizontal" >
	<div class="row-fluid" style="margin-top:20px" >
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="updateOnairBreakType">ชนิดเบรค:</label>
       	</div>
  		<div class="span6" align="left">
            <input type="text" name="updateOnairBreakType" id="updateOnairBreakType" class="ui-ams-input text ui-widget-content ui-corner-all "/> 
       	</div>        
        <div class="span3" align="left" style="margin-top:5px" >
       		<label id="updateOnairBreakType_alert" style="visibility:hidden"><a title='ชนิดเบรคใหม่';><img src='images/new_warning.png' style='width:25px;margin-right:5px;cursor:pointer' align='center' /></a></label>
    	</div>
  	</div> 

    <div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="updateOnairOnairTime">เวลาออกอากาศ:</label>
       	</div>
  		<div class="span6" align="left">
            <input type="text" name="updateOnairOnairTime" id="updateOnairOnairTime" class="text ui-widget-content ui-corner-all"/>
       	</div>        
        <div class="span3" align="left" style="margin-top:3px" >

    	</div>
  	</div>
    
    <div class="row-fluid">
    	<div class="span3" align="right" style="margin-top:5px">
        	<label for="updateOnairTotalTime">เวลา(วินาที):</label>
       	</div>
  		<div class="span6" align="left">
			<select class='text ui-widget-content ui-corner-all' style="font-size:1em;width:220px; "name="updateOnairTotalTime" id="updateOnairTotalTime" >
				<option value='15'>15</option>
				<option value='30'>30</option>
				<option value='45'>45</option>
				<option value='60'>60</option>
				<option value='75'>75</option>
				<option value='90'>90</option>
				<option value='105'>105</option>
				<option value='120'>120</option>
				<option value='135'>135</option>
				<option value='150'>150</option>
				<option value='165'>165</option>
				<option value='180'>180</option>
				<option value='195'>195</option>
				<option value='210'>210</option>
				<option value='125'>125</option>
				<option value='240'>240</option>
			</select>
       	</div>        
        <div class="span3" align="left" style="margin-top:3px" >

    	</div>
  	</div>
      
    </form>
    </div>
    
<?php

    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
?>

<script>
	
	//TUMBreakheader
	
	function addBreakOpen(){
		
		$('#changeBreakPropDi').dialog('open');
		//$('#changeBreakPropDi').attr('breakSeq',BreakSeq);
		$("#updateOnairBreakType").val('');
		$("#updateOnairBreakType").attr("bkTypeID",'');
		$("#updateOnairOnairTime").val('');
		$("#updateOnairTotalTime").val('60');
		
		$("#updateOnairBreakType_alert").css("visibility", "hidden"); // Disapear the alert NEW BreakType icon
		
	}
	
	function changeBreakPropOpen(BreakTypeID,BreakTypeName,BreakOnairTime,BreakSeq,BreakTotalTime){
		
		$('#changeBreakPropDi').dialog('open');
		$('#changeBreakPropDi').attr('breakSeq',BreakSeq);
				
		$("#updateOnairBreakType").val(BreakTypeName);
		$("#updateOnairBreakType").attr("bkTypeID",BreakTypeID);
		$("#updateOnairOnairTime").val(BreakOnairTime);
		$("#updateOnairTotalTime").val(BreakTotalTime);
		
		$("#updateOnairBreakType_alert").css("visibility", "hidden"); // Disapear the alert NEW BreakType icon
		
		//console.log("BreakTypeID= "+BreakTypeID+" BreakTypeName= "+BreakTypeName+" BreakOnairTime= "+BreakOnairTime+" BreakSeq= "+BreakSeq);
		
	}
	
	function autoBreakType(){
		
		var sentData = $("#updateOnairBreakType").val();
		var p = $("#updateOnairBreakType").position();
	
			
		var updateOnairBkTypeName = [];
		var updateOnairBkTypeID = [];
		var count_prod = 0;
	
		$.ajaxSetup({
				async: false
		});
			
		$.ajax('?r=onair/japi&action=autoOnairBkTypeName&bkTypeName='+sentData+'',{
			
			type: 'GET',
			dataType: 'json',
			success: function(autoOnairBkTypeName){
				$.each(autoOnairBkTypeName,function(k,v){ 
				
					updateOnairBkTypeID.push(v.break_type_name+":"+v.break_type_id);
					updateOnairBkTypeName.push(v.break_type_name);
					//console.log("prod_id="+v.prod_id);
					
				});
				
				$("#updateOnairBreakType").autocomplete({
					
					source:updateOnairBkTypeName,
					select: function (event, ui) {
						
						$("#updateOnairBreakType").val(ui.item.label); // display the selected text
						
						for (var i=0;i < updateOnairBkTypeID.length ;i++){
									
							var n = updateOnairBkTypeID[i].split(":"); 
							if (n[0]== $("#updateOnairBreakType").val()) {
								
								$("#updateOnairBreakType").attr("bkTypeName", n[0]);
								$("#updateOnairBreakType").attr("bkTypeID", n[1]);
								$("#updateOnairBreakType_alert").css("visibility", "hidden"); // Disapear the alert NEW BreakType icon
		
									
							}
						}	
						
					},
					search: function() {
						
						$("#updateOnairBreakType").attr("bkTypeID","");
						$("#updateOnairBreakType_alert").css("visibility", "visible"); // Show the alert NEW BreakType icon						
						
					}
				});
			}
		});		
		
	}
	
	
	$("#updateOnairBreakType").keyup(function(event){
	
		autoBreakType();
		
	});
	

	function changeBreakPropConf(){
		
		var bkSEQ = $('#changeBreakPropDi').attr('breakSeq');
		
		if($("#updateOnairBreakType").val()){
			
			if(!$("#updateOnairBreakType").attr("bkTypeID")){
				
				var bkTypeName = $("#updateOnairBreakType").val();
				
				$.ajaxSetup({
					async: false
				});
				
				$.ajax( '?r=onair/japi&action=insertNewBkType&bkTypeName='+bkTypeName+'', {
					type: 'GET',
					dataType: 'json',
					success: function(insertNewBkType) {
											  
						$.each(insertNewBkType, function(k_type,v_type){
							
							$("#break"+bkSEQ).children('div').children('div').children('span#breakType').attr("breaktypeid",v_type.break_type_id);
							$("#break"+bkSEQ).children('div').children('div').children('span#breakType').children('a').text("("+v_type.break_type_name+")");
												
						});
					}
				});
				
			}else {
				
				
				var onairBkTypeName = $("#updateOnairBreakType").val();
				var onairBkTypeID = $("#updateOnairBreakType").attr("bkTypeID");
				$("#break"+bkSEQ).children('div').children('div').children('span#breakType').attr("breaktypeid",onairBkTypeID);
				$("#break"+bkSEQ).children('div').children('div').children('span#breakType').children('a').text("("+onairBkTypeName+")");
				//console.log("Type= "+$("#updateOnairBreakType").val());
				
			}
			
			var updateOnairTimeFull =  $("#updateOnairOnairTime").val();
			var updateOnairTime = updateOnairTimeFull.split(':');
			var updateOnairTimeMin = updateOnairTime[0];
			var updateOnairTimeSec = updateOnairTime[1];
			
			$("#break"+bkSEQ).children('div').children('div').children('span#breakOnairTime').attr("onairtime",updateOnairTimeFull);
			$("#break"+bkSEQ).children('div').children('div').children('span#breakOnairTime').children('a').text("เวลาออกอากาศ: "+updateOnairTimeMin+":"+updateOnairTimeSec);	
			
			
			var updateTotalTime = $("#updateOnairTotalTime").val();
			var updateTimeMin = Math.floor(updateTotalTime/60);
			var updateTimesec = parseInt(updateTotalTime)% 60;
			updateTimeMin = zeroPad(updateTimeMin, 2);
			updateTimesec = zeroPad(updateTimesec, 2);
			//console.log("updateTimeMin= "+updateTimeMin+" updateTimesec="+updateTimesec);
			$("#break"+bkSEQ).children('div').children('div').children('a#totalbreak').text(updateTimeMin+":"+updateTimesec);
			
		}
		
		confirm_onair(); //confirm database
		$(this).dialog("close");
		
	}	
	
</script>


<script>
 
 //------------show History ----------->
 
	 function show_history(){
	
		var count_adv = 0;
		var all_break_read = [];
		var history_operation = [];
		var history_orgadv_id = [];
		var history_adv_id = 0;
		var history_orgprog_id = 0;
		var history_datetime = 0; 
		
		var prev_history_datetime = 0;
		var prev_history_orgadv_id = 0;
		var prev_history_adv_id = 0;
		var history_break_id = 0;
		
		var history_prog_on = $("#prog_on").attr('value');
		var history_day = $("#ul_daytab").find("li.ui-state-active").text();
		var history_onair_mon = parseInt($("#onair_mon").attr('value'));
		var history_onair_year = parseInt($("#onair_year").attr('value'))-543;	
		
	
		$("#history_table tbody tr").remove();
	
	/*
		$("#breakinglist").find('li').each(function(){
			var current = $(this);
			if(current.attr('id')){
	
			}else{
					
				all_break_read[count_adv++] = current.attr('value');
			
			} 			
		}); 
	*/	
		
		$.ajaxSetup({
			async: false
		});
				
		$.ajax( '?r=onair/japi&action=historyReader&onair_year='+history_onair_year+'&onair_month='+history_onair_mon+'&onair_day='+history_day+'&onair_progid='+history_prog_on+'', {
			type: 'GET',
			dataType: 'json',
				success: function(historyReader) {
					$.each(historyReader, function(k,val){
						
						history_operation = val.operation;
						history_orgadv_id = val.orig_adv_id;
						history_adv_id = val.adv_id;
						history_orgprog_id = val.orig_prog_id;
						history_break_id = val.orig_break_id;
						history_datetime = val.timestamp; 
			
							if(history_operation != ""){
								
								if(history_operation == "merged" && prev_history_datetime != history_datetime && prev_history_adv_id != history_adv_id ){
			
									
									//console.log(history_orgadv_id);
									prev_history_datetime = history_datetime;
									prev_history_adv_id = history_adv_id;
									//first_history_adv_id = history_adv_id;
									
									var mainadv_prodname = 0;
									var mainadv_advname = 0;
									var mainadv_timelenght = 0;
									var mainadv_agencyname = 0;
									var mainadv_datetime = 0; 
									
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterMainAdv&adv_id='+prev_history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterMainAdv) {
										  
											$.each(deterMainAdv, function(k_main,v_main){
												
												mainadv_prodname = v_main.prod_name;
												//mainadv_advname = v_main.adv_name;
												mainadv_advname = v_main.tape_name;
												mainadv_timelenght = v_main.adv_time_len;
												mainadv_agencyname = v_main.agency_name;
												
											});
										}
									});
										
									
									$("#history_table tbody").append(
												
										"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left'>"+mainadv_prodname+"</td>" + 
											"<td style='text-align:left'>"+mainadv_advname+"</td>" +
											"<td style='text-align:right'>"+mainadv_timelenght+"</td>" +
											"<td style='text-align:left'>"+mainadv_agencyname+"</td>" +
											"<td style='text-align:left'>รวมใหม่</td>" +
														
										"</tr>" 
									);
									
									
									
									$.ajaxSetup({
										async: false
									});
									
									$.ajax( '?r=onair/japi&action=deterAdvHisParentM&adv_id='+prev_history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										  success: function(deterAdvHisParentM) {
											 // console.log("deterAdvHisParentM");
										  	//console.log(deterAdvHisParentM);
											
											var merged_advID = 0;
											$.each(deterAdvHisParentM, function(kadv,valadv){
												
												//console.log("prev_history_adv_id="+valadv.adv_id);
												
												if(merged_advID != parseInt(valadv.adv_id))
												
													merged_advID = parseInt(valadv.adv_id);
														
													$("#history_table tbody").append(
													
														"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
															"<td style='text-align:left;padding-left:30px'>"+valadv.prod_name+"</td>" + 
															"<td style='text-align:left'>"+valadv.tape_name+"</td>" +
															"<td style='text-align:right'>"+valadv.adv_time_len+"</td>" +
															"<td style='text-align:left'>"+valadv.agency_name+"</td>" +
															"<td style='text-align:left'>รวม</td>" +
															
														"</tr>" 
													);
														
											});
										}
									});
									
									$("#history_table tbody").append(
												
										"<tr style='height:10px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left'></td>" + 
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
														
										"</tr>" 
									);
			
								}else if(history_operation == "splited" && prev_history_datetime != history_datetime && prev_history_orgadv_id != history_orgadv_id ){
									
									//console.log(history_orgadv_id);
									prev_history_datetime = history_datetime;
									prev_history_orgadv_id = history_orgadv_id;
									//first_history_adv_id = history_adv_id;
									
									var mainadv_prodname = 0;
									var mainadv_advname = 0;
									var mainadv_timelenght = 0;
									var mainadv_agencyname = 0;
									var mainadv_datetime = 0; 
									
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterMainAdv&adv_id='+prev_history_orgadv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterMainAdv) {
										  
											$.each(deterMainAdv, function(k_main,v_main){
												
												mainadv_prodname = v_main.prod_name;
												mainadv_advname = v_main.tape_name;
												mainadv_timelenght = v_main.adv_time_len;
												mainadv_agencyname = v_main.agency_name;
												
											});
										}
									});
										
									
									$("#history_table tbody").append(
												
										"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left'>"+mainadv_prodname+"</td>" + 
											"<td style='text-align:left'>"+mainadv_advname+"</td>" +
											"<td style='text-align:right'>"+mainadv_timelenght+"</td>" +
											"<td style='text-align:left'>"+mainadv_agencyname+"</td>" +
											"<td style='text-align:left'>แยก</td>" +
														
										"</tr>" 
									);
									
									$.ajaxSetup({
										async: false
									});
									
									$.ajax( '?r=onair/japi&action=deterAdvHisParent&adv_id='+prev_history_orgadv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										  success: function(deterAdvHisParent) {
										  
										  	var splitHadvID = 0;
											$.each(deterAdvHisParent, function(kadv,valadv){
												
												//console.log("prev_history_adv_id="+valadv.adv_id);
												
												if(splitHadvID != parseInt(valadv.adv_id)){
													
													splitHadvID = parseInt(valadv.adv_id);
													
													$("#history_table tbody").append(
													
														"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
															"<td style='text-align:left;padding-left:30px'>"+valadv.prod_name+"</td>" + 
															"<td style='text-align:left'>"+valadv.tape_name+"</td>" +
															"<td style='text-align:right'>"+valadv.adv_time_len+"</td>" +
															"<td style='text-align:left'>"+valadv.agency_name+"</td>" +
															"<td style='text-align:left'>อันใหม่</td>" +
															
														"</tr>" 
													);
												}
														
											});
										}
									});
									
									$("#history_table tbody").append(
												
										"<tr style='height:10px;padding-top:4px;padding-bottom:4px'>"+ 
											"<td style='text-align:left'></td>" + 
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
											"<td style='text-align:left'></td>" +
										"</tr>" 
									);
									
									
								}else if(history_operation == "move"){ //ถูกย้ายไป
									
									var movemainadv_prodname = 0;
									var movemainadv_advname = 0;
									var movemainadv_timelenght = 0;
									var movemainadv_agencyname = 0;
									
									var movemainadv_progname = 0;
									var movemainadv_datetime = 0;
									
									
										
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterMainAdv&adv_id='+history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterMainAdv) {
										  
											$.each(deterMainAdv, function(k_main,v_main){
												
												movemainadv_prodname = v_main.prod_name;
												movemainadv_advname = v_main.tape_name;
												movemainadv_timelenght = v_main.adv_time_len;
												movemainadv_agencyname = v_main.agency_name;
												
											});
										}
									});
									
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterDefProg&adv_id='+history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterDefProg) {
										  
											$.each(deterDefProg, function(k_mainProg,v_mainProg){
												
												movemainadv_datetime = v_mainProg.datetime;
												movemainadv_progname = v_mainProg.prog_name;
												
												$("#history_table tbody").append(
															
													"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
														"<td style='text-align:left'>"+movemainadv_prodname+"</td>" + 
														"<td style='text-align:left'>"+movemainadv_advname+"</td>" +
														"<td style='text-align:right'>"+movemainadv_timelenght+"</td>" +
														"<td style='text-align:left'>"+movemainadv_agencyname+"</td>" +
														"<td style='text-align:left'>ย้ายไป "+movemainadv_progname+" "+format_datetime(movemainadv_datetime)+"</td>" +
																	
													"</tr>" 
												);
												
												//console.log(v_mainProg.prog_name);
												
											});
										}
									});					

									$("#history_table tbody").append(
													
											"<tr style='height:10px;padding-top:4px;padding-bottom:4px'>"+ 
												"<td style='text-align:left'></td>" + 
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
											"</tr>" 
									);
									
									
								}else if(history_operation == "moved"){ //ย้ายมา
								
									
									var movedmainadv_prodname = 0;
									var movedmainadv_advname = 0;
									var movedmainadv_timelenght = 0;
									var movedmainadv_agencyname = 0;
									var movedmainadv_datetime = 0;
									var movedmainadv_progname = 0;
									
									$("#tooltipAdvId"+history_adv_id).css('visibility','visible');
									$("#tooltipAdvId"+history_adv_id).children('a').removeClass("ui-ams-disable-link");
									
										
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterMainAdv&adv_id='+history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterMainAdv) {
										  
											$.each(deterMainAdv, function(k_main,v_main){
												
												movedmainadv_prodname = v_main.prod_name;
												movedmainadv_advname = v_main.tape_name;
												movedmainadv_timelenght = v_main.adv_time_len;
												movedmainadv_agencyname = v_main.agency_name;
												
											});
										}
									});
									
									
									$.ajaxSetup({
										async: false
									});
									$.ajax( '?r=onair/japi&action=deterOrigProg&adv_id='+history_adv_id+'', {
									  type: 'GET',
									  dataType: 'json',
										success: function(deterOrigProg) {
										  
											$.each(deterOrigProg, function(k_mainProg,v_mainProg){
												
												movedmainadv_datetime = v_mainProg.datetime;
												movedmainadv_progname = v_mainProg.prog_name;
												
												//console.log(v_mainProg.prog_name);
									
													$("#history_table tbody").append(
																
														"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
															"<td style='text-align:left'>"+movedmainadv_prodname+"</td>" + 
															"<td style='text-align:left'>"+movedmainadv_advname+"</td>" +
															"<td style='text-align:right'>"+movedmainadv_timelenght+"</td>" +
															"<td style='text-align:left'>"+movedmainadv_agencyname+"</td>" +
															"<td style='text-align:left'>ย้ายมาจาก "+movedmainadv_progname+" "+format_datetime(movedmainadv_datetime)+"</td>" +
																		
														"</tr>" 
													);	
												
											});
										}
									});														
									
									$("#history_table tbody").append(
													
											"<tr style='height:10px;padding-top:4px;padding-bottom:4px'>"+ 
												"<td style='text-align:left'></td>" + 
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
												"<td style='text-align:left'></td>" +
											"</tr>" 
									);				
								
								
								}
								
							}
							
						});	
				}
		});
		
		//console.log("all_break_read= "+all_break_read)
		//console.log("Num_array= "+all_break_read.length);
		
	}

//<------------show History -----------

//------------>Start Alarming DayTab --------------->

	function alarming_daytab(prog_on,onair_year,onair_mon){
		
		var on_program = 0;
		var max_seq = 0;
		var first_day_active = 0;
		
		
		
			$.ajaxSetup({
				async: false
			});
			
			$.ajax( '?r=onair/japi&action=DailyUsageTimeofMonth&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day=1', {
			  type: 'GET',
			  dataType: 'json',
				  success: function(DailyUsageTimeofMonth) {
					
					$("#ul_daytab li").removeClass();
					$.each(DailyUsageTimeofMonth, function(k,v){
						
						$("#li_daytab"+parseInt(v.day)).removeClass();
						$("#li_daytab"+parseInt(v.day)).children("a#a_daytab").removeClass("ui-ams-disable-link");
						
						if(parseInt(k) == 0){
							
							$("#li_daytab"+parseInt(v.day)).addClass("ui-tabs-selected ui-state-active");
							
						}
						
						if(parseInt(v.total_advqueue_time) > parseInt(v.total_break_time)){
							
							$("#li_daytab"+parseInt(v.day)).addClass("ui-ams-warning");
							
						}else if((parseInt(v.total_advqueue_time) == parseInt(v.total_break_time)) && (!parseInt(v.total_advpending_time))){
							
							$("#li_daytab"+parseInt(v.day)).addClass("ui-ams-success");
							
						}else {
							
							$("#li_daytab"+parseInt(v.day)).addClass("ui-ams-danger");
							
						}
						
					});
				}
		  });		
	}

//<------------End of Alarming DayTab <--------------
 
 
 
//------------> Start to Export function ----------->
    function sendEmail(){
    	var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
    	//window.open('?r=mail/excel&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'');
    	//window.location = '?r=mail/excel&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'';
    	$.ajax('?r=mail/excel&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'',{
    		type: 'GET',
			dataType: 'json',
			success: function(breakshow){
				alert("ระบบส่งเมลล์ เรียบร้อยแล้วค่ะ");
			}
		});
		
		//var delay =0;
		//while(delay < 10000000){delay++;}
		$(this).dialog("close");	
	
		
    }
	
		
	// -----------> Download Layout (Excel) ------------>
	
	function download_layout(){
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		window.location.href = '?r=onair/downloadExcel&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'';
	
	}
	// <----------- End of Download Layout <------------- 


	function modalAdvertiseNote(){
		//alert(bk);
		
		$('#noteDialog').dialog('open');
	}
	
	function modalAdvertiseOpen(bk,adv){

		if (adv == 0){
			
			$("#mAdvName_auto").attr('value','');
			$("#mAdvProduct").attr('value','');
			$("#mAdvAgency").attr('value','');
			$("#mAdvCalPrice").attr('value',"0.00");
			$("#mAdvNetPrice").attr('value',"0.00");
			$("#onair_beak_info").attr('value','');
			$('#mAdvTimelen').attr('value',0)
			
		}else{
			$("#mAdvName").attr('value',$("#bk"+bk+"advname"+adv).text());
			$("#mAdvProduct").attr('value',$("#bk"+bk+"prod"+adv).text());
			$("#mAdvAgency").attr('value',$("#bk"+bk+"agency"+adv).text());
		}
		$("#mAdvBreak").children().remove();
		for (var i=1;i<=$(".ui-ams-advbrk").size();i++)
			$("#mAdvBreak").append("<option value='"+i+"'>"+i+"</option>");
		$("#mAdvBreak").attr('value',bk);
		$('#modalAdvertise').dialog('open');
		
		if(bk == 0){
			
			$("#mAdvBreak").val(1);
			
		}
		showOnairAgency();
	}
	
    function addBreak(){
		
        
		var breakid = $(".ui-ams-advbrk").size()+1;
		
		if($("#updateOnairBreakType").val()){
			
			if(!$("#updateOnairBreakType").attr("bkTypeID")){
				
				var bkTypeName = $("#updateOnairBreakType").val();
				
				$.ajaxSetup({
					async: false
				});
				
				$.ajax( '?r=onair/japi&action=insertNewBkType&bkTypeName='+bkTypeName+'', {
					type: 'GET',
					dataType: 'json',
					success: function(insertNewBkType) {
											  
						$.each(insertNewBkType, function(k_type,v_type){
							
							var onairBkTypeName = v_type.break_type_name;
							var onairBkTypeID = v_type.break_type_id;							
												
						});
					}
				});
				
			}else {
				
				
				var onairBkTypeName = $("#updateOnairBreakType").val();
				var onairBkTypeID = $("#updateOnairBreakType").attr("bkTypeID");
				//console.log("Type= "+$("#updateOnairBreakType").val());
				
			}
			
			var updateOnairTimeFull =  $("#updateOnairOnairTime").val();
			var updateOnairTime = updateOnairTimeFull.split(':');
			var updateOnairTimeMin = updateOnairTime[0];
			var updateOnairTimeSec = updateOnairTime[1];	
			
			var updateTotalTime = $("#updateOnairTotalTime").val();
			var updateTimeMin = Math.floor(updateTotalTime/60);
			var updateTimeSec = parseInt(updateTotalTime)% 60;
			updateTimeMin = zeroPad(updateTimeMin, 2);
			updateTimeSec = zeroPad(updateTimeSec, 2);
		}		

        //alert( $("#createbreaktime").val() +" / "+ breakid +" has been added");
		
		$("<li class='ui-ams-advbrk' id='break"+breakid+"'>"+
			"<div class='row-fluid'>"+
				"<div class='span8' align='left'>"+
				
				
					"<button  title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+breakid+"' onclick=modalAdvertiseOpen("+breakid+","+0+"); return false;>"+
						"<span class='ui-button-text'>เพิ่มโฆษณา</span>"+
					"</button>"+
					"<span class='ui-button-text'  style='font-size:1em;color:white;'>เบรคที่ "+breakid+"</span>"+
					"<span id='breakType' breakTypeID='"+onairBkTypeID+"' class='ui-button-text' style='font-size:1em;color:white;'>"+
						"<a style='font-size:1em;color:white;cursor:pointer;visibility:visible' onclick=changeBreakPropOpen('"+onairBkTypeID+"','"+onairBkTypeName+"','"+updateOnairTimeMin+":"+updateOnairTimeSec+"',"+breakid+",'"+ updateTotalTime+"');return false;>("+onairBkTypeName+")  </a>"+
					"</span>"+
					"<span id='breakOnairTime' onairTime='"+updateOnairTimeMin+":"+updateOnairTimeSec+"' class='ui-button-text' style='font-size:1em;color:white;'>"+
						"<a style='font-size:1em;color:white;cursor:pointer;visibility:visible'onclick=changeBreakPropOpen('"+onairBkTypeID+"','"+onairBkTypeName+"','"+updateOnairTimeMin+":"+updateOnairTimeSec+"',"+breakid+",'"+updateTotalTime+"') ;return false;>เวลาออกอากาศ: "+updateOnairTimeMin+":"+updateOnairTimeSec+"</a>"+
					"</span>"+		
					
				"</div>"+
				"<div class='span2' align='right'>"+
					"<a id='totalbreak' style='float:right;margin-right:10px;visibility:hidden'' >"+updateTimeMin+":"+updateTimeSec+"</a>"+
					"<a style='float:right;margin-right:10px;visibility:hidden''>/</a>"+
				"</div>"+
				"<div class='span2' align='right'>"+
					"<span id='breaktime' style='font-size:1em;color:white;float:left;margin-left:80px;margin-top:5px'>"+00+":"+00+"</span>"+
					"<a title='ลบเบรค' onclick=deleteBreak("+breakid+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></a>"+
				"</div>"+
			"</div></li>"
		).insertBefore("#pending");
		

/*
		$("<li class='ui-ams-advbrk' id='break"+breakid+"'>"+
			"<div class='row-fluid'><div class='span4' align='left'><span class='ui-button-text'>เบรคที่ "+breakid+"</span><button  title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+breakid+"' onclick=modalAdvertiseOpen("+breakid+","+0+"); return false;><span class='ui-button-text'>เพิ่มโฆษณา</span></button></div><div class='span6' align='right'><a id='totalbreak' style='float:right;margin-right:10px;visibility:hidden'' >"+totalbreak_mins+":"+totalbreak_secs+"</a><a style='float:right;margin-right:10px;visibility:hidden''>/ </a></div><div class='span2' align='right'><span id='breaktime' style='font-size:1em;color:white;float:left;margin-left:80px;margin-top:5px'>"+00+":"+00+"  </span><a title='ลบเบรคและชุดโฆษณาในเบรค' onclick=deleteBreak("+breakid+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></a></div></div></li>").insertBefore("#pending");
	
*/	
		//progressupdate();
		confirm_onair(); //confirm database first
		$(this).dialog("close");
    }
	
	function deleteAdvertise(bk_seq,adv_seq_li){
		
		$('#delete_advDi').dialog('open');
		$('#delete_advID').attr("breakSQ",bk_seq);
		$('#delete_advID').attr("advSQ",adv_seq_li);

	}
	
	function delete_adv(){
		
		$('#breakinglist').find('li[list_adv=bk'+$('#delete_advID').attr("breakSQ")+'advseq'+$('#delete_advID').attr("advSQ")+']').remove();
		
		progressupdate();
		$(this).dialog("close");
	}
	
	function deleteBreak(del_break_id){
		
		$('#delete_breakseqDi').dialog('open');
		$('#delete_advID').attr("del_breakID",del_break_id);
		
	}
	
	function delete_breakseq(){
		
		//$("#break"+$('#delete_advID').attr("del_breakID")).remove();
		//$("li div#break_"+$('#delete_advID').attr("del_breakID")).parent().remove();
		
		change_delete_bkadv($('#delete_advID').attr("del_breakID"));
		
		progressupdate();
		$(this).dialog("close");
	}
	
	function change_delete_bkadv(delbk_seq){
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		
		var break_plan = $("#programplans").children("div").find("button.active").attr("value");
			 
		if(break_plan){
				
			break_plan = break_plan;
				
		}else{
				
			$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
			$("#onair_plan0").addClass("btn-inverse active")  	
			break_plan = 0;			
				
		}
		
		var reserveBKID = $("#prog_on").attr("break_id");		
		//console.log("delbk_seq= "+delbk_seq);
		
		$('#delete_advID').attr("del_breakID");
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			
			type: "POST",
			url: "?r=onair/changeBreakSeq",
			data:{'break_seq':delbk_seq,'prog_id':prog_on,'year':onair_year,'month':onair_mon,'day':day,'bkplan':break_plan,'break_id':reserveBKID},
			
				success: function(data) {	
												
					//alert("การแยกชุดโฆษณาเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการอาจทำให้การลบไม่สมบูรณ์ ถ้าหากชุดโฆษณายังไม่ถูกลบกรุณาทำรายการใหม่อีกครั้ง");		
				}	
											   
		});	
		
		$("#break"+$('#delete_advID').attr("del_breakID")).remove();
		
		//console.log("break_seq= "+$('#delete_advID').attr("del_breakID"))
		
		
		checkBreak(prog_on,onair_year,onair_mon,day);
		
	}
	
	
	function add_another_plan(reserveBKID,reserveADVID,onair_breaktype,on_calc_price,break_discount,break_info){
		
		
		var break_plan = $("#programplans").children("div").find("button.active").attr("value");
			 
		if(break_plan){
				
			break_plan = break_plan;
				
		}else{
				
			$("#programplans").children("div").find("button.active").removeClass("btn-inverse active");
			$("#onair_plan0").addClass("btn-inverse active")  	
			break_plan = 0;			
				
		}
		
		
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			
			type: "POST",
			url: "?r=onair/addReserveBkPlan",
			data:{'break_id':reserveBKID,'adv_id':reserveADVID,'bktype':onair_breaktype,'calprice':on_calc_price,'bkdiscount':break_discount,'bkinfo':break_info,'break_plan':break_plan},
			
				success: function(data) {	
												
					//alert("การแยกชุดโฆษณาเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการเพิ่มชุดโฆษณาในคิวสำรอง");		
				}	
											   
		});	

	}
	
	function new_adv_onair(){
		
		var delay =0;
		var add_advprod = 0;
		var add_advname = 0;
		var add_advtime = 0;
		var add_advinfo = 0;
		
		add_advprod =  $("#mAdvProduct").val();
		add_advname = $('#mAdvName_auto').val();
		add_advtime = $("#mAdvTimelen").val()
		add_advprodID = $("#mAdvProduct").attr('prod_id');
		
		var NewTapeId = 0;
		var NewProdId = 0;
		
		//console.log("add_advprodID= "+add_advprodID+"add_advprod= "+add_advprod)

		if((add_advprod != "") && (add_advname != "") && (add_advtime != "") ){
			
			if(add_advprodID != ""){
			
				//console.log("add_advagency= "+add_advagency+" add_advprod="+add_advprod+" add_advname="+add_advname+" add_advtime"+add_advtime+" add_advinfo="+add_advinfo);
				
				$.ajaxSetup({
					async: false
				});
				$.ajax({
					type: "POST",
					url: "?r=onair/addUpdateProd",	
					data:{'prod_id':add_advprodID,'prod_name':add_advprod, 'tape_name':add_advname, 'timelen':add_advtime},
					success: function(data) {
						
						NewTapeId = data;
						
					},
					error: function(data){
						
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการเนื่องจากชื่อชุดโฆษณานี้อาจจะมีอยู่แล้ว กรุณาทำการเพิ่มชุดโฆษณาใหม่อีกครั้ง");	
					}
				});
				//while(delay < 1000){delay++;}
				//$(this).dialog("close");
				
			}else {
				
				
				$.ajaxSetup({
					async: false
				});
				$.ajax({
					type: "POST",
					url: "?r=onair/add",	
					data:{'prod_name':add_advprod, 'tape_name':add_advname, 'timelen':add_advtime},
					success: function(data) {
						
						NewTapeId = data;
						
					},
					error: function(data){
						
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ(ควรคลิกเลือกสินค้าที่มีอยู่ในรายการ) กรุณาทำการเพิ่มชุดโฆษณาใหม่อีกครั้ง");	
					}
				});
				//while(delay < 1000){delay++;}
				//$(this).dialog("close");
	
			}
			
			
			$("#mAdvName_auto").attr("tape_id",NewTapeId);	
			
				
		}else{
			
			alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาตรวจสอบการกรอกชื่อสินค้าและชุดโฆษณา");	
		}
	
	}
	
	
	function addAdvertise(){
		
		if($("#mAdvProduct").attr('prod_id') == "" || $('#mAdvName_auto').attr('tape_id') == ""){
			
			
			//$("#mAdvProduct").attr('prod_id',0);
			$('#mAdvName_auto').attr('tape_id',0);
			
			new_adv_onair();

		}
		
		addTapetoAdvertise(); // Add advertise property into advertise TABLE
		 
		 //console.log("onairAddAdvID="+onairAddAdvID);
		var currbreak = parseInt($('#mAdvBreak').attr('value'));
		var nextbreak = currbreak + 1;
		var mAdvTimelen = parseInt($('#mAdvTimelen').attr('value'));
		
		var mins = Math.floor(mAdvTimelen/60);
		var secs = mAdvTimelen % 60;
		secs = zeroPad(secs, 2);
		
		var list_size = parseInt($('.ui-ams-advbrk').size());
		var all_adv_size = 0;
		all_adv_size = parseInt($('.adv_each_list').size())+1;
		
		var onair_breaktype = get_bktype();
		
		var on_calc_price = $("#mAdvCalPrice").attr("value");
			on_calc_price = parseFloat(on_calc_price.replace(/,/g, ''));
			
		var break_info = $('#onair_beak_info').attr('value');
		var break_discount = $("#mAdvDiscount").attr("value");

		if(onair_breaktype == ""){ 	onair_breaktype = " "; }
		if(on_calc_price == "" ){ on_calc_price = 0; }
		if(break_info == ""){ break_info = " ";}
		if(break_discount == ""){ break_discount = 0;}
		
		// ----- > Reserve Plan adding 
		
		var reserveBKID = $("#prog_on").attr("break_id");
		var reserveADVID = $('#mAdvName_auto').attr('att_id');
		
		add_another_plan(reserveBKID,reserveADVID,onair_breaktype,on_calc_price,break_discount,break_info); // Add another plan
		
		//<-------End Reserve plan
		
		var pkg_adv_add = $("#mAdvPackage").attr("pkg_id");
		
		if(!pkg_adv_add){ pkg_adv_add = 0;}
		
		if($('#mAdvBreak').attr('value') == list_size){
			
			$("<li list_adv='bk"+currbreak+"advseq"+all_adv_size+"' class='ui-state-default ui-ams-adv' style='width:95%' value='"+$('#mAdvName_auto').attr('att_id')+"'  ><div id='break_"+currbreak+"' class='adv_each_list' style='margin-top: 2px; max-height: 25px; color: blue;'><p><span style='margin-left:3px;' ><img src='images/icon-draggable.png' /></span><span id='bk"+currbreak+"prod"+all_adv_size+"' class='property_bk'  net_price='"+$("#mAdvNetPrice").val()+"' pkg_id='"+pkg_adv_add+"'  tape_id='"+$('#mAdvName_auto').attr('tape_id')+"' bk_type='"+onair_breaktype+"' spot_price='"+on_calc_price+"' on_discount='"+break_discount+"' bk_info='"+break_info+"' time_bk='"+mAdvTimelen+"' prod_id='"+$("#mAdvProduct").attr('prod_id')+"' agency_id='"+$("#mAdvAgency").attr('value')+"' style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move'>"+$("#mAdvProduct").attr('value')+"</span><span  id='bk"+currbreak+"advname"+all_adv_size+"' style='width:30%; max-width:30%;  display:inline-block; cursor:move' >"+$('#mAdvName_auto').attr('value')+"</span><span id='timelen' style='width:10%; display:inline-block' align:'center'>"+mins+':'+secs+"</span><span  id='bk"+currbreak+"agency"+all_adv_size+"' style='width:20%; max-width:20%; display:inline-block; cursor:move'>"+$("#mAdvAgency option:selected").text()+"</span><span ><a title='ลบชุดโฆษณา' onclick=deleteAdvertise("+currbreak+","+all_adv_size+");><img src='images/delete_2.png' style='width:20px;margin-right:5px;' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+currbreak+","+all_adv_size+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a href='#'><img src='images/pen.png' style='width:20px;margin-right:5px;visibility:hidden' align='right' /></a></span></p></div></li>").insertBefore("#pending");
			
		}else{
			
			$("<li list_adv='bk"+currbreak+"advseq"+all_adv_size+"'  class='ui-state-default ui-ams-adv' style='width:95%' value='"+$('#mAdvName_auto').attr('att_id')+"' ><div id='break_"+currbreak+"' class='adv_each_list' style='margin-top: 2px; max-height: 25px; color: blue;'><p><span style='margin-left:3px;' ><img src='images/icon-draggable.png' /></span><span  id='bk"+currbreak+"prod"+all_adv_size+"' class='property_bk' bk_type='"+onair_breaktype+"'net_price='"+$("#mAdvNetPrice").val()+"' pkg_id='"+pkg_adv_add+"'  tape_id='"+$('#mAdvName_auto').attr('tape_id')+"' spot_price='"+on_calc_price+"' on_discount='"+break_discount+"' bk_info='"+break_info+"' time_bk='"+mAdvTimelen+"' prod_id='"+$("#mAdvProduct").attr('prod_id')+"' agency_id='"+$("#mAdvAgency").attr('value')+"' style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move'>"+$("#mAdvProduct").attr('value')+"</span><span  id='bk"+currbreak+"advname"+all_adv_size+"' style='width:30%; max-width:30%;  display:inline-block; cursor:move' >"+$('#mAdvName_auto').attr('value')+"</span><span id='timelen' style='width:10%; display:inline-block' align:'center'>"+mins+':'+secs+"</span><span id='bk"+currbreak+"agency"+all_adv_size+"' style='width:20%; max-width:20%; display:inline-block; cursor:move'>"+$("#mAdvAgency option:selected").text()+"</span><span ><a title='ลบชุดโฆษณา' onclick=deleteAdvertise("+currbreak+","+all_adv_size+");><img src='images/delete_2.png' style='width:20px;margin-right:5px;' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+currbreak+","+all_adv_size+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a href='#'><img src='images/pen.png' style='width:20px;margin-right:5px;visibility:hidden' align='right' /></a></span></p></div></li>").insertBefore("#break"+nextbreak);
			
		}	
		// NewProdct and Tape condition
		
		confirm_onair(); //confirm database first
		$(this).dialog("close");
		
	}

</script>

  </div>
        
    </div>
</div>
