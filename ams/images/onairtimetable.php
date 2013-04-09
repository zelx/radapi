<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Onair';
$this->breadcrumbs=array(
	'Onair',
);
date_default_timezone_set('Asia/Bangkok');
?>
<style>
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
					//console.log("test:");
					//console.log(vpro);
					
					$("#prog_on").append(
												 
							 "<option value='"+von.prog_id+"'>"+von.prog_name+"</option>"
					);
				 });
							
				});
			 }
		});
	});
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
		  var break_seq=0;
		  var adv_seq=0;
		  var timelimit = 0;
		  var totaltime = 0;
		  var totalbreak = 0;
		  var max_seq = 0;
		  var prog_on = $("#prog_on").attr('value');
		  
		$.each(breakcheck[0], function(k,v){
			
			break_id = v.break_id;
			
		});
		
		if(break_id != 0){
			
			showBreak(program,year,month,day);
			
			$("#createbreak").removeAttr("disabled");// Enable Create Break
			
		}else{	
			
			preBreak(program,year,month,day);
		}
		
	}
  });
			
}

function preBreak(program,year,month,day){
		
		var max_seq = 0;
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax( '?r=onair/japi&action=maxSeq&program='+program+'&year='+year+'&month='+month+'&day='+day+'', {
		  type: 'GET',
		  dataType: 'json',
			  success: function(maxSeq) {
			  
				$.each(maxSeq, function(key,value){
					
					max_seq = value.max_seq;
					
				});
			
				if(max_seq < 1){
					
					$("#createbreak").attr("disabled","disabled");// Disable Create Break
					
				}else{
					
					$("#createbreak").removeAttr("disabled");// Enable Create Break
				}
			}
	  	});
	
	
	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax( '?r=onair/japi&action=breakpre&program='+program+'&year='+year+'&month='+month+'&day='+day+'', {
	  type: 'GET',
	  dataType: 'json',
		  success: function(breakpre) {
		  var break_seq=0;
		  var adv_seq=0;
		  var timelimit = 0;
		  var timelength =0;
		  var totaltime = 0;
		  var totalbreak = 0;
		  var prog_on = $("#prog_on").attr('value');

		  $("#breakinglist li").remove();
		  $.each(breakpre[0], function(k,v){
			adv_seq = v.adv_seq;
			
			if(v.adv_time_len != 0){
				
				timelength = v.adv_time_len;
				
			}else{
				
				timelength = v.time_len;
			
			}		
			var mins = Math.floor(parseInt(timelength)/60);
			var secs = parseInt(timelength) % 60;

			//console.log(v);
			if(break_seq != v.break_seq){
				break_seq = v.break_seq;
				timelimit = v.timelimit;
				totaltime = parseInt(timelength);
				totalbreak = timelimit;
				var totalbreak_mins = Math.floor(timelimit/60);
				var totalbreak_secs = timelimit % 60;

				$("#breakinglist").append("<li class='ui-ams-advbrk' id='break"+break_seq+"' ><div class='row-fluid'><div class='span4' align='left'><span class='ui-button-text'>เบรค#"+break_seq+"</span> <button title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+break_seq+"' onclick=modalAdvertiseOpen("+break_seq+","+0+"); return false;><span class='ui-button-text'>เพิ่มโฆษณา</span></button></div><div class='span4' align='left'><a id='totalbreak' style='float:right;margin-right:10px;cursor:pointer;visibility:hidden'>"+totalbreak_mins+":"+totalbreak_secs+"</a><a style='float:right;margin-right:10px;visibility:hidden'>/ </a><a id='breaktime' style='float:right;margin-right:10px;visibility:hidden'>"+mins+":"+secs+"</a></div><div class='span4' align='right'><a title='ลบ' onclick=deleteBreak("+break_seq+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></div></div></li>");
				
			}else{
				totaltime =totaltime+parseInt(timelength);
			}
			var totalbreak_percent = (totaltime / totalbreak)*100;
			
			//console.log("time_len: "+v.time_len+" totalbreak :"+totalbreak+" percent:"+totalbreak_percent+" totaltime:"+totaltime);
			
		  });// end of each(breakofday)
		  $("#breakinglist").append("<li class='ui-ams-advpending' id='pending' align='center'>คิวรอโฆษณา</li>");
		  
		  progressupdate();
  
		  }//Success Function
	  });//AJAX
}

function showBreak(program,year,month,day)
{
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
		$.each(breakshow[0], function(k,v){
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
				if(break_seq != v.break_seq){
					break_seq = v.break_seq;
					timelimit = v.timelimit;
					totaltime = parseInt(timelength);
					totalbreak = timelimit;
					var totalbreak_mins = Math.floor(timelimit/60);
					var totalbreak_secs = timelimit % 60;

					$("#breakinglist").append("<li class='ui-ams-advbrk' id='break"+break_seq+"' ><div class='row-fluid'><div class='span4' align='left'><span class='ui-button-text'>เบรค#"+break_seq+"</span><button title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+break_seq+"' onclick=modalAdvertiseOpen("+break_seq+","+0+"); return false;><span class='ui-button-text'>เพิ่มโฆษณา</span></button></div><div class='span4' align='left'><a id='totalbreak' style='float:right;margin-right:10px;cursor:pointer;visibility:hidden'>"+totalbreak_mins+":"+totalbreak_secs+"</a><a style='float:right;margin-right:10px;visibility:hidden'>/ </a><a id='breaktime' style='float:right;margin-right:10px;visibility:hidden'>"+mins+":"+secs+"</a></div><div class='span4' align='right'><a title='ลบเบรคและชุดโฆษณาในเบรค' onclick=deleteBreak("+break_seq+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></div></div></li>");
					
				}else{
					totaltime = totaltime+ parseInt(timelength);
				}
				
				all_totaltime += parseInt(totaltime); 
				all_totalbreak  += parseInt(totalbreak);
				
				var totalbreak_percent = (totaltime / totalbreak)*100;
				
				var on_breaktype = v.break_type;
				var on_cal_price = v.calc_price;
				var on_break_info = v.break_desc;
				var on_break_discount = v.discount;
				
				//console.log(v.calc_price);
				
				if(on_breaktype == ""){ on_breaktype = " "; }
				if(on_cal_price == "" ){ on_cal_price = 0; }
				if(on_break_info == ""){ on_break_info = " ";}
				if(on_break_discount == ""){ on_break_discount = 0;}
				
				$("#breakinglist").append("<li  list_adv='bk"+break_seq+"advseq"+adv_seq+"' class='ui-state-default ui-ams-adv'  style='width:95%' value='"+adv_id+"' ><div class='adv_each_list' id='break_"+break_seq+"' style='margin-top:2px;max-height:25px'><p><span style='margin-left:3px' ><img src='images/icon-draggable.png' /></span><span id='bk"+break_seq+"prod"+adv_seq+"' class='property_bk' time_bk='"+timelength+"'  bk_type='"+on_breaktype+"' spot_price='"+on_cal_price+"' on_discount='"+on_break_discount+"' bk_info='"+on_break_info+"' prod_id='"+v.prod_id+"' agency_id='"+v.agency_id+"'  style='margin-left:3px;width:25%; max-width:25%; display:inline-block;cursor:move;max-height:20px;overflow:hidden' >"+v.prod_name+"</span><span id='bk"+break_seq+"advname"+adv_seq+"' style='width:25%; max-width:25%;  display:inline-block; cursor:move; max-height:20px;overflow:hidden'>"+v.adv_name+"</span><span id='bk"+break_seq+"agency"+adv_seq+"' style='width:15%; max-width:15%; display:inline-block;cursor:move;max-height:20px;overflow:hidden'>"+v.agency_name+"</span><span id='timelen'  style='width:10%; display:inline-block' align:'right'>"+mins+':'+secs+"</span><span ><a title='ลบชุดโฆษณา'onclick=deleteAdvertise("+break_seq+","+adv_seq+"); ><img src='images/delete_2.png' style='width:20px;margin-right:5px;cursor:pointer' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+break_seq+","+adv_seq+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a title='ดูประวัติ' href='#'><img src='images/clock.png' style='width:20px;margin-right:5px;;visibility:hidden' align='right' /></a></span></p></div></div></li>");
			}else{
				waiting_bk[waiting_bk_cnt++] = v;
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
			  
			$("#breakinglist").append("<li list_adv='bk0advseq"+val.adv_seq+"' class='ui-state-default ui-ams-adv'  style='width:95%' value='"+val.adv_id+"'><div class='adv_each_list' id='break_0' style='margin-top:2px;max-height:25px'><p><span style='margin-left:3px' ><img src='images/icon-draggable.png' /></span><span id='bk"+val.break_seq+"prod"+val.adv_seq+"' class='property_bk' time_bk='"+timelength+"'  bk_type='"+val.break_type+"' spot_price='"+val.calc_price+"' on_discount='"+val.discount+"' bk_info='"+val.break_desc+"' prod_id='"+val.prod_id+"' agency_id='"+val.agency_id+"'  style='margin-left:3px;width:25%; max-width:25%; display:inline-block;cursor:move;max-height:20px;overflow:hidden' >"+val.prod_name+"</span><span id='bk"+val.break_seq+"advname"+val.adv_seq+"' style='width:25%; max-width:25%;  display:inline-block; cursor:move; max-height:20px;overflow:hidden'>"+val.adv_name+"</span><span id='bk"+val.break_seq+"agency"+val.adv_seq+"' style='width:15%; max-width:15%; display:inline-block;cursor:move;max-height:20px;overflow:hidden'>"+val.agency_name+"</span><span id='timelen' style='width:10%; display:inline-block' align:'right'>"+mins+':'+secs+"</span><span ><a title='ลบชุดโฆษณา'onclick=deleteAdvertise(0,"+val.adv_seq+"); ><img src='images/delete_2.png' style='width:20px;margin-right:5px;cursor:pointer' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+val.break_seq+","+val.adv_seq+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a title='ดูประวัติ' href='#'><img src='images/clock.png' style='width:20px;margin-right:5px;;visibility:hidden' align='right' /></a></span></p></div></div></li>");
			  
			 // console.log(v);
		  });
		  //all_totalbreak_percent = (parseInt(all_totaltime) / parseInt(all_totalbreak))*100;
  
		  if(all_totaltime > all_totalbreak){
			  
			var deltabreaksum = all_totaltime - all_totalbreak;
					
			mins = Math.floor(deltabreaksum/60);
			secs = parseInt(deltabreaksum) % 60;
			secs = zeroPad(secs, 2);
	
			var totalbreak_percent = (deltabreaksum / all_totalbreak)*100;
			totalbreak_percent = totalbreak_percent.toFixed(2);
			
			$('#usage_bk').text("-"+mins+":"+secs);
			
			$("#time_bk_lev").css('width',totalbreak_percent+"%");
		  	$("#time_bk_lev").attr("title",totalbreak_percent+"%");	
			$("#time_bk_lev").removeClass();
			$("#time_bk_lev").addClass("bar bar-danger");	
			
		  }else if(all_totaltime == all_totalbreak){
					
			mins = Math.floor(all_totaltime/60);
			secs = parseInt(all_totaltime)% 60;
			secs = zeroPad(secs, 2);
			
			var totalbreak_percent = (all_totaltime / all_totalbreak)*100;
			totalbreak_percent = totalbreak_percent.toFixed(2);
			
			$('#usage_bk').text(mins+":"+secs);
			
			$("#time_bk_lev").css('width',totalbreak_percent+"%");
		  	$("#time_bk_lev").attr("title",totalbreak_percent+"%");	
			$("#time_bk_lev").removeClass();
			$("#time_bk_lev").addClass("bar bar-success");
			
		  }else{
			
			mins = Math.floor(all_totaltime/60);
			secs = parseInt(all_totaltime)% 60;
			secs = zeroPad(secs, 2);
			
			var totalbreak_percent = (all_totaltime / all_totalbreak)*100;
			totalbreak_percent = totalbreak_percent.toFixed(2);
			
			$('#usage_bk').text(mins+":"+secs);
					
			$("#time_bk_lev").css('width',totalbreak_percent+"%");
		  	$("#time_bk_lev").attr("title",totalbreak_percent+"%");
			$("#time_bk_lev").removeClass();
			$("#time_bk_lev").addClass("bar bar-warning");
			  
		  }
		  
		  all_mins = Math.floor(all_totalbreak/60);
		  all_secs = parseInt(all_totalbreak) % 60;
		  all_secs = zeroPad(all_secs, 2);
		  all_mins = zeroPad(all_mins, 2);
		  
		  $('#all_bk').text(all_mins+":"+all_secs);

		  //$("#break"+break_seq+" div.progress div").width(totalbreak_percent+"%");
		  //console.log("allTime="+all_totaltime+"allbk="+all_totalbreak+"Total="+all_totalbreak_percent);
		  
		  progressupdate();
		 
		  
		  }//Success Function
	  
	  });//AJAX
	  
	  show_history();
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
						
					}else{
						
					}
					all_breaksum  += parseInt(breaksum);
					breaksum=0;
					
					//console.log("totalbreak="+totalbreak)
				 }else{
					var x = current.children('div').children('p').children('span#timelen').text().split(':');
					breaksum = breaksum + parseInt(x[0]*60) + parseInt(x[1]);
 
				 }
	
			  if(all_breaksum > all_totalbreak){
				  
				var deltabreaksum = all_breaksum - all_totalbreak;
						
				mins = Math.floor(deltabreaksum/60);
				secs = parseInt(deltabreaksum) % 60;
				secs = zeroPad(secs, 2);
				
				var totalbreak_percent = (deltabreaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text("-"+mins+":"+secs);
				
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
				
				//console.log("Danger")
				//current.children('div').css('color','red');
				current.children('div').css('color', 'blue');	
				
			  }else if(all_breaksum == all_totalbreak &&  all_totalbreak != 0){
						
				mins = Math.floor(all_breaksum/60);
				secs = parseInt(all_breaksum)% 60;
				secs = zeroPad(secs, 2);
				
				var totalbreak_percent = (all_breaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text(mins+":"+secs);
				
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");	
				$("#time_bk_lev").removeClass();
				$("#time_bk_lev").addClass("bar bar-success");
				
				update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
				$("#"+update_daytab).removeClass("ui-ams-inactive");
				$("#"+update_daytab).removeClass("ui-ams-success");
				$("#"+update_daytab).removeClass("ui-ams-warning");
				$("#"+update_daytab).removeClass("ui-ams-danger");
				$("#"+update_daytab).addClass("ui-ams-success");
				
				//current.children('div').css('color', 'green');
				current.children('div').css('color', 'blue');
				
			  }else if ( all_breaksum == 0 && all_totalbreak == 0){
				  
				totalbreak_percent = 0;
				 
				$('#usage_bk').text("00:00");
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");
				  
			  }else {
				
				mins = Math.floor(all_breaksum/60);
				secs = parseInt(all_breaksum)% 60;
				secs = zeroPad(secs, 2);
				
				var totalbreak_percent = (all_breaksum / all_totalbreak)*100;
				totalbreak_percent = totalbreak_percent.toFixed(2);
				
				$('#usage_bk').text(mins+":"+secs);
						
				$("#time_bk_lev").css('width',totalbreak_percent+"%");
				$("#time_bk_lev").attr("title",totalbreak_percent+"%");
				
				$("#time_bk_lev").removeClass();
				$("#time_bk_lev").addClass("bar bar-warning");
				
				update_daytab = $("#ul_daytab").find("li.ui-state-active").attr("id");
				$("#"+update_daytab).removeClass("ui-ams-inactive");
				$("#"+update_daytab).removeClass("ui-ams-success");
				$("#"+update_daytab).removeClass("ui-ams-warning");
				$("#"+update_daytab).removeClass("ui-ams-danger");
				$("#"+update_daytab).addClass("ui-ams-warning");
				  
				current.children('div').css('color', 'blue');
			  }
			  
			  all_mins = Math.floor(all_totalbreak/60);
			  all_secs = parseInt(all_totalbreak) % 60;
			  all_secs = zeroPad(all_secs, 2);
			  all_mins = zeroPad(all_mins, 2);
			  
			  $('#all_bk').text(all_mins+":"+all_secs);
			
			 })
}

readprogList(); //-----> Read and create programs <-------
	
//-------> month function ---------

function month_define() {

	switch($("#onair_mon").attr('value')){
		case "1":
			  var month_name = "ม.ค.";
		  break;
		case "2":
			  var month_name = "ก.พ.";
		  break;
		 case "3":
			  var month_name = "มี.ค.";
		  break;
		case "4":
			  var month_name = "เม.ย.";
		  break;
		case "5":
			  var month_name = "พ.ค.";
		  break;
		 case "6":
			  var month_name = "มิ.ย.";
		  break;
		case "7":
			  var month_name = "ก.ค.";
		  break;
		case "8":
			  var month_name = "ส.ค.";
		  break;
		case "9":
			 var month_name = "ก.ย.";
		  break;
		case "10":
			  var month_name = "ต.ค.";
		  break;
		case "11":
			  var month_name = "พ.ย.";
		  break;
		case "12":
			  var month_name = "ธ.ค.";
		  break;
		default:
			  var month_name = "ม.ค.";	
	}
	
	return(month_name);
}

//<------end of month function ----



$(function() {
	
	var onair_current_date = 0;
	var onair_current_month = 0;
	var onair_current_year = 0;
	
	onair_current_date  =  new Date();
	onair_current_month =  parseInt(onair_current_date.getMonth())+1;	
	onair_current_year = parseInt(onair_current_date.getFullYear())+543;
	
	$("#onair_mon").val(onair_current_month);
	$("#onair_year").val(onair_current_year);
	
	$( "#breakinglist" ).sortable();
	$( "#breakinglist" ).disableSelection();
	$( "#subsort" ).sortable();
	$( "#subsort" ).disableSelection();
	$( "#breakinglist" ).sortable({
		cancel: ".ui-ams-advbrk,.ui-ams-advpending",
		items: "li:not(#break1)",
		update: function( event, ui ) {
			
			progressupdate();
			show_history();
			
		},

	});

	
	for(var i = 1; i < 32; i++){
		$("#ul_daytab").append("<li id='li_daytab"+i+"' class='ui-ams-inactive' ><a id='a_daytab' class='ui-ams-daytab' style='padding:5px 3px 5px 3px;'>"+i+"</a></li>");
		
	}
	
	
	
	$("#li_daytab1").addClass("ui-tabs-selected ui-state-active");
	
	var prog_on = $("#prog_on").attr('value');
	var onair_mon = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	
	checkBreak(prog_on,onair_year,onair_mon,1);
	//alarming_daytab(prog_on,onair_year,onair_mon);

	$("#li_daytab1").ready(function(){
		//alert("test");
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		var day = $("#ul_daytab").find("li.ui-state-active").text();
	
		checkBreak(prog_on,onair_year,onair_mon,1);
	})

	$(".ui-ams-daytab").click(function(e){
		
		//alert("Day "+$(this).text()+" loading..");
		$("#ul_daytab").find("li.ui-state-active").removeClass("ui-tabs-selected ui-state-active");
		$("#ul_daytab").find("li#li_daytab"+$(this).text()).addClass("ui-tabs-selected ui-state-active");
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		checkBreak(prog_on,onair_year,onair_mon,$(this).text());
		//alarming_daytab(prog_on,onair_year,onair_mon);
		
	});

	$("#prog_on").change(function() {
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
	
		checkBreak(prog_on,onair_year,onair_mon,day);
		alarming_daytab(prog_on,onair_year,onair_mon);
		
	});

	$("#onair_mon").change(function() {
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		checkBreak(prog_on,onair_year,onair_mon,day);
		alarming_daytab(prog_on,onair_year,onair_mon);
	});

	$("#onair_year").change(function() {
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		checkBreak(prog_on,onair_year,onair_mon,day);
		alarming_daytab(prog_on,onair_year,onair_mon);
		
	});

	$(".ui-ams-btadvbrk").click(function( e ) {
			alert("test");
	});


	$("#createplan").click(function(e){
	    var reservePlans = $("<button id='breakplan"+parseInt($(".ui-ams-breakplan").size()+1)+"' type='button' class='btn btn-info ui-ams-breakplan' >คิวสำรองที่ "+parseInt($(".ui-ams-breakplan").size()+1)+"</button>");
		 reservePlans.click(function(e){  
		 	$("#programplantitle").text ($(this).text());
		 })
		$("#programplans").append(reservePlans);
		
				 
	});
	
	$("#programplans > button").each( function(index, Element) { 
		 $(Element).click(function(e){  
		 	$("#programplantitle").text ($(this).text());
		 })
		
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
        <div class="span7">
        	<div class="row-fluid">
        		<div class="span3" align="right">
            		<label for="prog_on" style="font-size:1em; margin-top:3px">รายการโทรทัศน์:</label>
                </div>
                <div class="span9" align="left" style="margin-left:10px">
            		<select name="prog_on" id="prog_on" style="font-size:1em;width:100;padding-top:3px;padding-bottom:3px" value=""></select>
        		</div>
            </div>
        </div>
        <div class="span3" style="font-size:1em; margin-left:2px;">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="onair_mon" style="font-size:1em;margin-top:3px"">เดือน:</label>
                </div>
                <div class="span6" align="left">
                    <select class="input-medium" type="text" name="onair_mon" id="onair_mon" style="font-size:1em;width:100;padding-top:3px;padding-bottom:3px" value="">
                            <option value="1" selected="selected">มกราคม</option> 
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
        <div class="span2" style="font-size:1em; margin-left:2px">
        	<div class="row-fluid">
        		<div class="span4" align="right">
            		<label for="onair_year" style="font-size:1em;margin-top:3px"">ปี:</label>
            	</div>
                <div class="span6" align="left">
                    <select class="input-small" type="text" name="onair_year" id="onair_year" style="font-size:1em;width:80;padding-top:3px;padding-bottom:3px" value="">
                          <option value="2553" >2553</option> 
                          <option value="2554" >2554</option> 
                          <option value="2555" >2555</option> 
                          <option value="2556" selected="selected">2556</option> 
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
                                        	<button id="createbreak" type="button" class="btn btn-success" style="font-size:1.8em;width:80px; color:"  data-loading-text="Loading..." onclick="$('#mydialog2').dialog('open'); return false;"><span>เพิ่มเบรค<span></button>
                                        		<button id="addAdvertise" type="button" class="btn btn-success" style="font-size:1.8em;width:100px; color:"  data-loading-text="Loading..."  ><span>เพิ่มโฆษณา<span></button>
                    						<button id="confirm" type="button" class="btn btn-success" style="font-size:1.8em;width:80px"  data-loading-text="Loading..." onclick="$('#confOnairDi').dialog('open'); return false;">ยืนยัน</button>
                   							       
                                     	</li>
                                   		<li class="divider-vertical"></li>
                                   		<li style="float: right;">
                                   			<button id="export" type="button" class="btn btn-success" style="font-size:1.8em;;width:120px"  data-loading-text="Loading..." onclick="$('#ExOnairDi').dialog('open'); return false;">ดาวโหลดไฟล์</button>  
                                   			<button id="export" type="button" class="btn btn-success" style="font-size:1.8em;;width:120px"  data-loading-text="Loading..." >ส่งคิวโฆษณา</button>  
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
                                            <button id="createplan" type="button" class="btn btn-primary" style="font-size:1.8em;width:80px" data-loading-text="Loading...">เพิ่มคิว</button>
                                       		<div id="programplans" class="btn-group" data-toggle="buttons-radio" style="font-size:1.6em;margin-left:3px;">
                                          		<button type="button" class="btn btn-info" style="width:80px">คิวหลัก</button>
                       						</div>
                                                    
                                		</li>
                                    </ul>
                                </div>
                              	<div>
                              		<ul style="font-size:1.2em; margin-left:0px; margin-bottom:0px;">
                                  		<li class='ui-ams-advbrk2'>
                                  			 <span id="programplantitle" class="span4"  style="text-align: left;padding: 5px 10px;"  >คิวหลัก</span>
                                                <button id="cr" type="button" class="btn btn-primary" style="font-size:1em;width:20px; visibility:hidden; margin-left:2px" data-loading-text="Loading..."></button>
                                               <!--     <a id="all_bk" style='float:right;margin-right:10px; color:#FFF'></a>
                                                    <a style='float:right;margin-right:10px;color:#FFF'> / </a>
                                                    <a id="usage_bk" style='float:right;margin-right:10px;cursor:pointer;color:#FFF'></a>     
                                              	-->
                                		<div class='progress' style='margin-bottom:2px'> 
                                      		<div  id='time_bk_lev' class="bar bar-warning" style="width:0%;">
   													<a id="all_bk" style='float:right;margin-right:10px; color:#FFF'></a>
                                                    <a style='float:right;margin-right:10px;color:#FFF'> / </a>
                                                    <a id="usage_bk" style='float:right;margin-right:10px;cursor:pointer;color:#FFF'></a> 	
                                    		</div>
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
                                        <tr style="font-size:1.2em;height:25px;">
                                          <th style="width:30%;text-align:left;padding:6px">ชื่อชุดโฆษณา</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชื่อสินค้า</th>
                                          <th style="width:15%;text-align:center;padding:6px">เอเจนซี่</th>
                                          <th style="width:15%;text-align:center;padding:6px">เวลา(วินาที)</th>
                                          <th style="width:10%;text-align:left;padding:6px">การแก้ไข</th>
                                        </tr>
                                      </thead>
                                      <tbody style="font-size:0.8em">
                                      	<tr style="font-size:1.2em;height:25px;" >
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า A</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด A</th>
                                          <th style="width:15%;text-align:center;padding:6px">Agency A</th>
                                          <th style="width:15%;text-align:center;padding:6px">30</th>
                                          <th style="width:10%;text-align:left;padding:6px">ย้ายไป: สปอร์ตแฟน 5 มีค.</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า B</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด B1</th>
                                          <th style="width:15%;text-align:center;padding:6px">Agency B</th>
                                          <th style="width:15%;text-align:center;padding:6px">30</th>
                                          <th style="width:10%;text-align:left;padding:6px">แยก</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า B</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด B2</th>
                                          <th style="width:15%;text-align:center;padding:6px"></th>
                                          <th style="width:15%;text-align:center;padding:6px">15</th>
                                          <th style="width:10%;text-align:left;padding:6px">อันใหม่</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า C</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด C1</th>
                                          <th style="width:15%;text-align:center;padding:6px"></th>
                                          <th style="width:15%;text-align:center;padding:6px">15</th>
                                          <th style="width:10%;text-align:left;padding:6px">อันใหม่</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้าใหม่ D</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด D1</th>
                                          <th style="width:15%;text-align:center;padding:6px">Agency C</th>
                                          <th style="width:15%;text-align:center;padding:6px">45</th>
                                          <th style="width:10%;text-align:left;padding:6px">รวม</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้าเก่า D</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด D2</th>
                                          <th style="width:15%;text-align:center;padding:6px"></th>
                                          <th style="width:15%;text-align:center;padding:6px">15</th>
                                          <th style="width:10%;text-align:left;padding:6px">รวม</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้าเก่า D</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด D3</th>
                                          <th style="width:15%;text-align:center;padding:6px"></th>
                                          <th style="width:15%;text-align:center;padding:6px">30</th>
                                          <th style="width:10%;text-align:left;padding:6px">รวม</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า C</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด C1</th>
                                          <th style="width:15%;text-align:center;padding:6px">Agency B</th>
                                          <th style="width:15%;text-align:center;padding:6px">15</th>
                                          <th style="width:10%;text-align:left;padding:6px">ย้ายไป: คันปาก 12 มีค.</th>
                                        </tr>
                                        <tr style="font-size:1.2em;height:25px; ">
                                          <th style="width:30%;text-align:left;padding:6px">สินค้า D</th>
                                          <th style="width:30%;text-align:left;padding:6px">ชุด D2</th>
                                          <th style="width:15%;text-align:center;padding:6px">Agency C</th>
                                          <th style="width:15%;text-align:center;padding:6px">15</th>
                                          <th style="width:10%;text-align:left;padding:6px">ย้ายมา: คันปาก 7 มีค.</th>
                                        </tr>
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
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvProduct" id="mAdvProduct" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvTag">Tag:</label>
        <div class="controls">
        	<input name="mAdvTag" id="mAdvTag" style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" class="ui-ams-input text ui-widget-content ui-corner-all " />
         
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName">ชื่อชุดโฆษณา:</label>
        <div class="controls">
            <select name="mAdvName" id="mAdvName" style="font-size:1em;width:50;padding-top:3px;padding-bottom:3px" value="">
            </select>
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvTimelen">เวลา(วินาที):</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvTimelen" id="mAdvTimelen" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvAgency">เอเจนซี่:</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvAgency" id="mAdvAgency" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            <!--<a class="btn" href="#"><i class="icon-search"></i>ค้นหา</a>-->
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px" >
		<label class="control-label" for="mAdvPackage">กิจกรรม:</label>
		<div class="controls">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvPackage" id="mAdvPackage" value="" >
                <option value="none">ไม่กำหนด</option>
				<option value="TSM">TSM</option>
				<option value="FA">FA</option>
				<option value="Pantine">Pantine</option>
				<option value="มวยไทย7สี">มวยไทย7สี</option>
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
                    <label class="radio">ราคาพิเศษ<input class="bk_type" title="ราคาพิเศษ" type="radio" name="bk_type" id="bk_type_2" value="ราคาพิเศษ"></label>
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
                    <label style="margin:1px" class="radio">ปะหัว<input class="bk_type" title="ปะหัว" type="radio" name="bk_type" id="bk_type_6" value="ปะหัว"></label>
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
        <select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvBreak" id="mAdvBreak" value="" >
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


 
<!-- <script type="text/javascript" src="http://ams.bbtvnewmedia.com/ams/assets/258e7330/jquery-autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript" src="http://ams.bbtvnewmedia.com/ams/assets/258e7330/jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="http://ams.bbtvnewmedia.com/ams/assets/258e7330/jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="http://ams.bbtvnewmedia.com/ams/assets/258e7330/jquery-autocomplete/lib/thickbox-compressed.js"></script> -->
<script>


addadv_advList();

function addadv_advList(){
	 
	$.ajaxSetup({
		async: false
	});
	
	$.ajax('?r=onair/japi&action=advOnairList',{
		type: 'GET',
		dataType: 'json',
		success: function(advOnairList){
		$("#mAdvName option").remove();			
		   var _Tags = [];
			$.each(advOnairList,function(kon,von){
		
				$("#mAdvName").append(
										"<option value='"+von.adv_id+"'>"+von.adv_name+"</option>"
				);
				_Tags.push(von.adv_name);
				
			 });
			   // $("#mAdvName").append("<input id='tags' />");
			    $("#mAdvTag").autocomplete({
			      source: _Tags
			    });
         // alert('==>'+_Tags);
		}
	});
}


function get_bktype(){
	
	var onair_bk_type = "ราคาปกติ";
	
	if( $("input[type='radio'][name='bk_type']:checked").attr("id") == "bk_type_2"){
		
		onair_bk_type = $("#mAdvPackage").attr('value');
		
		if(onair_bk_type == "none"){
			
			onair_bk_type = " ";

		}
	
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
	spot_time = $("#mAdvTimelen").attr("value");
	
	
	if($("#bk_type_1").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		//per_discount = $("#mAdvDiscount").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					onair_spot_price = (parseInt(val.minute_price)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice").attr("value", onair_spot_price);
					
				});
			}
		});
		
	}else if($("#bk_type_2").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		//per_discount = $("#mAdvDiscount").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					onair_spot_price = (parseInt(val.pack_price)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice").attr("value", onair_spot_price);
					
				});
			}
		});
		
	}
	
	onair_calprice = $("#mAdvCalPrice").attr("value");
	onair_perdiscount = $("#mAdvDiscount").attr("value");
	
	var onair_netprice = parseInt(onair_calprice)*(1-(parseInt(onair_perdiscount)/100));
	onair_netprice = onair_netprice.toFixed(2);
	
	$("#mAdvNetPrice").val(onair_netprice);
	
}


//addadv_agencyList();

$("#mAdvName").change(function(){
	
	var adv_id = $("#mAdvName").attr('value');
	var add_timelength = 0;
	
	$.ajaxSetup({
		async: false
	});
	$.ajax('?r=onair/japi&action=addadvProd&adv_id='+adv_id+'',{
		type: 'GET',
		dataType: 'json',
		success: function(addadvProd){		
			$.each(addadvProd,function(k,v){
				$.each(v,function(kon,von){
							
					$("#mAdvProduct").attr('value',von.prod_name);
					$("#mAdvProduct").attr('prod_id',von.prod_id);
					$("#mAdvAgency").attr('value',von.agency_name);
					$("#mAdvAgency").attr('agency_id',von.agency_id);
					
					if(von.adv_time_len != 0){
				
						add_timelength = von.adv_time_len;
				
					}else{
				
						add_timelength = von.time_len;
			
					}
					
					$("#mAdvTimelen").attr('value',add_timelength);
						
				});
								
			});
		 	}
		});
});


$("#mAdvPackage").change(function(){
	
	if($("#mAdvPackage").attr('value') != "none"){
		
		$("#bk_type_2").attr("checked","checked");
		
	}else {
		
		$("#bk_type_1").attr("checked","checked");
	}
	
	cal_spotprice();	
	
});


$(".bk_type").click(function(){
	
	if($("#bk_type_2").attr("checked") != "checked"){
		
		$("#mAdvPackage").val("none")
		
	}	
	
	cal_spotprice();	
});

$("#onair_inputDi").click(function(){ // Calculate Spot price
	
	cal_spotprice();
	get_bktype();	
	
});

</script>

<!-- zii dialog Advertise modal UPDATE BREAK-->
<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'updateOnairDi',
        'options'=>array(
            'title'=>'แก้ไขโฆษณา',
			'width'=>500,
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
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvProduct_edit" id="mAdvProduct_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
    </div>
    <div class="control-group" style="margin-bottom:5px">
		<label class="control-label" for="mAdvName_edit">ชื่อชุดโฆษณา:</label>
        <div class="controls">
            <select name="mAdvName_edit" id="mAdvName_edit" style="font-size:1em;width:50;padding-top:3px;padding-bottom:3px" value="">
            </select>
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvTimelen_edit">เวลา(วินาที):</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvTimelen_edit" id="mAdvTimelen_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
	</div>
    <div class="control-group" style="margin-bottom:5px">
        <label class="control-label" for="mAdvAgency_edit">เอเจนซี่:</label>
		<div class="controls">
            <input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="mAdvAgency_edit" id="mAdvAgency_edit" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            <!--<a class="btn" href="#"><i class="icon-search"></i>ค้นหา</a>-->
        </div>
	</div>
    <div class="control-group" style="margin-bottom:5px" >
		<label class="control-label" for="mAdvPackage_edit">กิจกรรม:</label>
		<div class="controls">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="mAdvPackage_edit" id="mAdvPackage_edit" value="" >
                <option value="none">ไม่กำหนด</option>
				<option value="TSM">TSM</option>
				<option value="FA">FA</option>
				<option value="Pantine">Pantine</option>
				<option value="มวยไทย7สี">มวยไทย7สี</option>
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
                    <label class="radio">ราคาพิเศษ<input class="bk_type_edit" title="ราคาพิเศษ" type="radio" name="bk_type_edit" id="bk_type_edit_2" value="ราคาพิเศษ"></label>
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
                    <label style="margin:1px" class="radio">ปะหัว<input class="bk_type_edit" title="ปะหัว" type="radio" name="bk_type_edit" id="bk_type_edit_6" value="ปะหัว"></label>
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

function addadv_advList_edit(){
	
	var edit_list = 0;
	var edit_timelength = 0;
	var edit_calc_price = 0;
	var edit_bk_type = " ";
	var edit_bk_discount = 0;
	var edit_bk_info = " "; 
	
	var edit_on_product = 0;
	var edit_on_agency = 0;
	 
	$.ajaxSetup({
		async: false
	});
	
	$.ajax('?r=onair/japi&action=advOnairList',{
		type: 'GET',
		dataType: 'json',
		success: function(advOnairList){
		$("#mAdvName_edit option").remove();			
		
			$.each(advOnairList,function(kon,von){
		
				$("#mAdvName_edit").append(
				
					"<option value='"+von.adv_id+"'>"+von.adv_name+"</option>"
										
				);
			 });

		}
	});
	
	
	edit_list = $('#breakinglist').find('li[list_adv=bk'+$('#onair_input_editDi').attr("value")+'advseq'+$('#adv_seq_list').attr("value")+']').attr("value");
	edit_list = parseInt(edit_list);
	
	$("#mAdvName_edit").val(edit_list);
	
	edit_on_product = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).text();
	edit_on_agency = $("#bk"+$('#onair_input_editDi').attr("value")+"agency"+$('#adv_seq_list').attr("value")).text();
	$("#mAdvProduct_edit").attr("value",edit_on_product);
	$("#mAdvAgency_edit").attr("value",edit_on_agency);
	
	
	edit_timelength = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('time_bk');
	edit_calc_price = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('spot_price');
	edit_bk_type = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('bk_type');
	edit_bk_discount = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('on_discount');
	edit_bk_info = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('bk_info');
	
	
	$("#mAdvTimelen_edit").attr("value",edit_timelength);
	$("#mAdvCalPrice_edit").attr("value",edit_calc_price );	
	$("#mAdvNote_edit").attr("value",edit_bk_info);
	edit_bk_discount = parseInt(edit_bk_discount);
	$("#mAdvDiscount_edit").val(edit_bk_discount);
	
	//----------> BREAK TYPE ------->
	
	if(edit_bk_type == "ราคาปกติ"){
		
		$("#mAdvPackage_edit").val("none");
		$("#bk_type_edit_1").attr("checked","checked");
		
		
	}else if(edit_bk_type == " "){
		
		$("#mAdvPackage_edit").val("none");
		$("#bk_type_edit_2").attr("checked","checked");
		
	}else if(edit_bk_type == "บาเตอร์"){
		
		$("#mAdvPackage_edit").val("0");
		$("#bk_type_edit_3").attr("checked","checked");
		
	}else if(edit_bk_type == "อัตราพิเศษ"){
		
		$("#mAdvPackage_edit").val("none");
		$("#bk_type_edit_4").attr("checked","checked");
		
	}else if(edit_bk_type == "แถม"){
		
		$("#mAdvPackage_edit").val("none");
		$("#bk_type_edit_5").attr("checked","checked");
		
	}else if(edit_bk_type == "ปะหัว"){
		
		$("#mAdvPackage_edit").val("none");
		$("#bk_type_edit_6").attr("checked","checked");
		
	}else{
		
		$("#mAdvPackage_edit").val(edit_bk_type);
		$("#bk_type_edit_2").attr("checked","checked");
	
	}
	
	//----------> BREAK TYPE ------->
	
	cal_spotprice_edit();

}

function get_bktype_edit(){
	
	var onair_bk_type = "ราคาปกติ";	
	
	if( $("input[type='radio'][name='bk_type_edit']:checked").attr("id") == "bk_type_edit_2"){
		
		onair_bk_type = $("#mAdvPackage_edit").attr('value');
		
		if(onair_bk_type == "none"){
			
			onair_bk_type = " ";

		}
	
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
	spot_time =  $("#mAdvTimelen_edit").attr("value");
	
	if($("#bk_type_edit_1").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					onair_spot_price = (parseInt(val.minute_price)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice_edit").attr("value", onair_spot_price);
					
				});
			}
		});
		
	}else 	if($("#bk_type_edit_2").attr("checked") == "checked"){
		
		prog_id_price = $("#prog_on").attr("value");
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+prog_id_price+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					onair_spot_price = (parseInt(val.pack_price)/60)*parseInt(spot_time);
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
							
					$("#mAdvCalPrice_edit").attr("value", onair_spot_price);
					
				});
			}
		});
		
	}
	
	onair_calprice = $("#mAdvCalPrice_edit").attr("value");
	onair_perdiscount = $("#mAdvDiscount_edit").attr("value");
	
	var onair_netprice = parseInt(onair_calprice)*(1-(parseInt(onair_perdiscount)/100));
	onair_netprice = onair_netprice.toFixed(2);
	
	$("#mAdvNetPrice_edit").val(onair_netprice);
	
}


//addadv_agencyList();

$("#mAdvName_edit").change(function(){
	
	var adv_id = $("#mAdvName_edit").attr('value');
	var add_timelength = 0;
	
	$.ajaxSetup({
		async: false
	});
	$.ajax('?r=onair/japi&action=addadvProd&adv_id='+adv_id+'',{
		type: 'GET',
		dataType: 'json',
		success: function(addadvProd){		
			$.each(addadvProd,function(k,v){
				$.each(v,function(kon,von){
							
					$("#mAdvProduct_edit").attr('value',von.prod_name);
					$("#mAdvAgency_edit").attr('value',von.agency_name);
					
					if(von.adv_time_len != 0){
				
						add_timelength = von.adv_time_len;
				
					}else{
				
						add_timelength = von.time_len;
			
					}
					
					$("#mAdvTimelen_edit").attr('value',add_timelength);
						
				});
								
			});
		 	}
		});
});

$("#mAdvPackage_edit").change(function(){
		
	
	if($("#mAdvPackage_edit").attr('value') != "none"){
		
		$("#bk_type_edit_2").attr("checked","checked");
		
	}else {
		
		$("#bk_type_edit_1").attr("checked","checked");
	}
	
	cal_spotprice_edit();	
	
});

$(".bk_type_edit").click(function(){
	
	if($("#bk_type_edit_2").attr("checked") != "checked"){
		
		$("#mAdvPackage_edit").val("none")
		
	}	
	
	cal_spotprice_edit();	
	
});

$("#onair_input_editDi").click(function(){ // Calculate Spot price
	
	cal_spotprice_edit();
	get_bktype_edit();
	
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
		
		var edit_onair_product = $('#mAdvProduct_edit').attr("value");
		var edit_onair_advID = $('#mAdvName_edit').attr("value");
		var edit_onair_advname = $('#mAdvName_edit option:selected').text();
		var edit_onair_timelen = $('#mAdvTimelen_edit').attr("value");
		var edit_onair_agency = $('#mAdvAgency_edit').attr("value");
		
		var edit_onair_break = get_bktype_edit();
		var edit_on_calc_price = $("#mAdvCalPrice_edit").attr("value");
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
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("on_discount",edit_break_discount);
		$("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr("bk_info",edit_break_info);
		
		
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
            'title'=>'ยืนยันการปรับเปลี่ยน',
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
			
			var bk_desc = [];
			var on_bk_type = [];
			var on_spot_price = [];
			var on_discount = [];
			
			var onair_mon = parseInt($("#onair_mon").attr('value'));
			var onair_year = parseInt($("#onair_year").attr('value'))-543;
			var prog_id = $("#prog_on").attr('value');
			var onair_day = $("#ul_daytab").find("li.ui-state-active").text();
		

			//console.log($("#breakinglist"));
			 $("#breakinglist").find('li').each(function(){
			 	var current = $(this);
				var mins;
				var secs;
				var adv_current;
				
				if(current.attr('id')){
					
					var tb = current.children('div').children('div').children('a#totalbreak').text().split(':');
					if(tb == ""){
						
						totalbk[cnt_time++]  = 0;
					}else {
						totalbk[cnt_time++]  = parseInt(tb[0]*60) + parseInt(tb[1]);	
					}
					
					break_read[adv_seq] = current.attr('id');

				 }else{
					
					break_read[adv_seq] = current.attr('value');
					
					
					on_bk_type[adv_seq] = current.children('div').children('p').children('span.property_bk').attr("bk_type");
					on_spot_price[adv_seq] =  current.children('div').children('p').children('span.property_bk').attr("spot_price");
					on_discount[adv_seq] = current.children('div').children('p').children('span.property_bk').attr("on_discount");
					bk_desc[adv_seq] =  current.children('div').children('p').children('span.property_bk').attr("bk_info");
					
				 } 
				adv_seq++;
				
			 }); 
			
			//console.log("break_read="+break_read+"  totalbreak="+totalbk);
			//console.log("onair_year"+onair_year+"onair_mon"+onair_mon+"onair_day"+onair_day+"prog_id"+prog_id);
	
			$.ajaxSetup({
				async: false
			});
			$.ajax({
				type: "POST",
				url: "?r=onair/addonair",
				data:{'prog_id':prog_id, 'year':onair_year, 'month':onair_mon,'day':onair_day, 'break_read':break_read,'totalbk':totalbk,'break_desc':bk_desc,'break_type':on_bk_type,'calc_price':on_spot_price,'discount':on_discount},
					success: function(data) {
							
						//alert("success");
					},
					error: function(data){
							
						alert("มีข้อผิดพลาดเกิดขึ้นระหว่างเพิ่มคิวโฆษณา กรุณาเลือกคิวโฆษณาใหม่อีกครั้ง");
						
					}
										   
			});	 

	 while(delay < 1000){delay++;}
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
			   'ตกลง'=>'js:showreport',
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
 //--------------- Start of Dialog for spliting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'onair_move_Di',
        'options'=>array(
            'title'=>'การย้ายโฆษณา',
			'width'=>450,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:onair_split_cf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input">
 <form class="form-horizontal" style="font-size:1em">
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
						'id'=>'onair_move_date_next',
		                'language'=>Yii::app()->language=='et' ? 'et' : null,
		                'options'=>array(
		                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
		                	'showOn'=>'button', // 'focus', 'button', 'both'
		                	'buttonText'=>Yii::t('ui','Select form calendar'),
		                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
							//'dateFormat'=>'dd/mm/yy',
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

<?php
 //--------------- Start of Dialog for spliting Advertise  --------------
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'onair_split_Di',
        'options'=>array(
            'title'=>'การแยกโฆษณา',
			'width'=>1000,
            'autoOpen'=>false,
            'modal'=>true,
            'buttons'=>array(
                'ตกลง'=>'js:onair_split_cf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input">
 <form class="form-horizontal" style="font-size:1em">
	<div class="row-fluid" align="center">
      <div class="span5" align="left">
      	<div class="row-fluid">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_advname" style="margin-top:4px" >ชุดโฆษณา:</label>
            </div>
            <div class="span8" align="left">
                <select name="onair_split_advname" id="onair_split_advname" style="font-size:0.8em;width:210px; margin-top:1px" value="" >                
                </select>  
            </div>
            
      	</div>
        <div class="row-fluid" align="center" style="margin-top:2px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_product" style="margin-top:4px" >สินค้า:</label>
            </div>
            <div class="span8" align="left">
                <select name="onair_split_product" id="onair_split_product" style="font-size:0.8em;width:210px; margin-top:1px" value="">                
                </select>  
            </div>
            
      	</div>
        <div class="row-fluid" align="center" style="margin-top:2px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_agency" style="margin-top:4px" >บริษัท:</label>
            </div>
            <div class="span8" align="left">
           		<input style="font-size:0.8em;width:200px; margin-top:1px" type="text" name="onair_split_agency" id="onair_split_agency" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
            
      	</div>
        <div class="row-fluid" align="center" style="margin-top:2px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_timeorg" style="margin-top:4px" >เวลา (วินาที):</label>
            </div>
            <div class="span8" align="left">
           		<input style="font-size:0.8em;width:200px; margin-top:1px" type="text" name="onair_split_timeorg" id="onair_split_timeorg" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
            
      	</div>
        <div class="row-fluid" align="center" style="margin-top:2px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_bksq" style="margin-top:4px" >ลำดับเบรก:</label>
            </div>
            <div class="span8" align="left">
           		<input style="font-size:0.8em;width:200px; margin-top:1px" type="text" name="onair_split_bksq" id="onair_split_bksq" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
            
      	</div>
        <div class="row-fluid" align="center" style="margin-top:2px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_advsq" style="margin-top:4px" >ลำดับโฆษณา:</label>
            </div>
            <div class="span8" align="left">
           		<input style="font-size:0.8em;width:200px; margin-top:1px" type="text" name="onair_split_advsq" id="onair_split_advsq" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
            </div>
            
      	</div>
        
      	<div class="row-fluid" align="center" style="margin-top:3px">
        
      		<div class="span4" align="right">
           	 	<label for="onair_split_number" style="margin-top:4px" >จำนวน:</label>
            </div>
            <div class="span8" align="left">
            	<select  style="font-size:0.8em;width:120px; margin-top:1px" id="onair_split_number" value="" class="input-small" >
                    <option selected="selected">2</option>
                    <option>3</option>            	
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>10</option>
                </select>
            </div>
      	</div>
      </div>
      <div class="span7">
		<div class="row-fluid">
        <div class="">
		<div class="container" id="page" style="width:inherit; margin-top:1px" align="center">
             <div class="row-fluid">
                <div class="">
                    <table align="center" class="table table-striped" id="onair_split_table">
                      <thead thead align="center">
                        <tr style="font-size:1em;height:25px;">
                          <th style="width:80%;text-align:left;padding:6px">ชื่อชุดโฆษณา</th>
                          <th style="width:20%;text-align:right;padding:6px">เวลา(วินาที)</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:1em"  class="onair_time_each_sp">
                      	<tr style='height:25px;padding-top:4px;padding-bottom:4px'>
                          <td style='text-align:left'>กรุณาเลือกชุดโฆษณา</td>
                          <td style='text-align:right'></td>
                        </tr>
                      </tbody>
                    </table>
                </div>
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

function onair_show_ProdAgency(){
	
	var onair_prod_name = 0;
	var onair_agency_name = 0;
	var onair_time_org = 0;
	var onair_adv_id = 0;
	
	var onair_prod_id_org = 0;
	
	onair_adv_id = $("#onair_split_advname").attr('value');
	
	$.ajaxSetup({
		async: false
	});
	
	$.ajax('?r=onair/japi&action=readProdAgency&adv_id='+onair_adv_id+'',{
		type: 'GET',
		dataType: 'json',
		success: function(readProdAgency){		
			$.each(readProdAgency,function(k,v){
				
				onair_prod_id_org = v.prod_id;	
				onair_prod_name = v.prod_name;
				onair_agency_name = v.agency_name;
				onair_time_org = v.time_len;
		
			});
			
			$("#onair_split_product").val(onair_prod_id_org);
			$("#onair_split_agency").attr('value',onair_agency_name);
			$("#onair_split_timeorg").attr('value',onair_time_org);
			
			//console.log("onair_prod_name= "+onair_prod_name);
		 }
	});	
}

function onair_split_table(){
	
	var ini_time_sp = 0;
	var onair_num_split =0;
	var onair_totaltime_sp = 0;
	
	onair_num_split =$("#onair_split_number").attr('value');
	
	$("#onair_split_table tbody tr").remove();
	for(var sp=1; sp <= onair_num_split; sp++ ){
			
		$("#onair_split_table tbody").append(
			"<tr >"+ 
						"<td style='text-align:left;height:15px;padding:2px'>"+
						"<input type='text' name='onair_num_sp' id='onair_num_sp_"+sp+"' class='input-mini' value='"+$("#onair_split_advname option:selected").html()+" Part"+sp+"' style='font-size:0.8em;height:20px;width:440px' />"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<input type='text' name='onair_time_sp' id='onair_time_sp_"+sp+"' class='input-mini' value='"+ini_time_sp+"' style='height:20px;width:50px' />"+
                  
						"</td>"+
			"</tr>"
		); 
			
			//var time_bk = $("#num_break_"+br+"").attr('value'); 
	}
		
	for(var time_sp=1; time_sp <= onair_num_split; time_sp++ ){
			
		onair_totaltime_sp = onair_totaltime_sp + parseInt($("#onair_time_sp_"+time_sp+"").attr('value'));	
		
	}
		
	$("#onair_split_table tbody").append(
		"<tr>"+ 
						"<td style='font-size:1em;text-align:center;height:15px;padding:2px'>เวลารวม</td>"+
						"<td style='font-size:1em;text-align:center;height:15px;padding:2px'><a title='เวลารวม'>"+onair_totaltime_sp+"</a></td>"+
		"</tr>"
	);
	
}

function onair_split_table_change(){

	var onair_cg_num_split =0;
	var onair_cg_time_sp = [];
	var onair_cg_totaltime_sp = 0;
	
	onair_cg_num_split = $("#onair_split_number").attr('value');
	
	for(var tsp=1; tsp <= onair_cg_num_split; tsp++ ){	
	
		onair_cg_time_sp[tsp] = $("#onair_time_sp_"+tsp+"").attr('value');
		
		if(onair_cg_time_sp[tsp] == "undefined"){
			onair_cg_time_sp[tsp] = 0;
		}
		
	}
		
	$("#onair_split_table tbody tr").remove();	
	for(var sp=1; sp<= onair_cg_num_split; sp++ ){ 
		
		$("#onair_split_table tbody").append(
			"<tr >"+ 
						"<td style='text-align:left;height:15px;padding:2px'>"+
						"<input type='text' name='onair_num_sp' id='onair_num_sp_"+sp+"' class='input-mini' value='"+$("#onair_split_advname option:selected").html()+" Part"+sp+"' style='font-size:0.8em;height:20px;width:440px' />"+
						"</td>"+
						"<td style='font-size:0.8em;text-align:right;height:15px;padding:2px'>"+
						"<input type='text' name='onair_time_sp' id='onair_time_sp_"+sp+"' class='input-mini' value='"+onair_cg_time_sp[sp]+"' style='height:20px;width:50px' />"+
                  
						"</td>"+
			"</tr>"
		);
			
			//var time_bk = $("#num_break_"+br+"").attr('value'); 
	}
		
	for(var time_sp=1; time_sp<= onair_cg_num_split; time_sp++ ){
			
		onair_cg_totaltime_sp = onair_cg_totaltime_sp + parseInt($("#onair_time_sp_"+time_sp+"").attr('value'));	
		
	}
		
	$("#onair_split_table tbody").append(
		"<tr>"+ 
						"<td style='font-size:1em;text-align:center;height:15px;padding:2px'>เวลารวม</td>"+
						"<td style='font-size:1em;text-align:center;height:15px;padding:2px'><a title='เวลารวม'>"+onair_cg_totaltime_sp+"</a></td>"+
		"</tr>"
	);
	
	if(onair_cg_totaltime_sp > $("#onair_split_timeorg").attr('value')){
		
		alert("เวลารวมของโฆษณาที่ถูกแยกมากกว่าเวลาโฆษณาเดิม");
		
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
	
	var onair_day = $("#ul_daytab").find("li.ui-state-active").text();
	var onair_prog_id = $("#prog_on").attr('value');
	var onair_month = parseInt($("#onair_mon").attr('value'));
	var onair_year = parseInt($("#onair_year").attr('value'))-543;
	
	onair_split_timeorg = $("#onair_split_timeorg").attr('value');
	onair_org_adv_id = $("#onair_split_advname").attr('value');
	onair_split_prod_id =  $("#onair_split_product").attr('value');
	onair_split_bk_seq =  $("#onair_split_bksq").attr('value');
	onair_split_adv_seq =  $("#onair_split_advsq").attr('value');
	
	console.log("onair_month= "+onair_org_adv_id);
	
	for(var on_sp_cnt = 1; on_sp_cnt <= $("#onair_split_number").attr('value'); on_sp_cnt++ ){
			
		onair_split_adv_name[on_sp_cnt-1] = $("#onair_num_sp_"+on_sp_cnt).attr('value');
		onair_split_adv_timelen[on_sp_cnt-1] = $("#onair_time_sp_"+on_sp_cnt).attr('value');
	}
	
	console.log("onair_org_adv_id= "+onair_org_adv_id+"  onair_split_adv_name= "+onair_split_adv_name+"  onair_split_adv_timelen= "+onair_split_adv_timelen)
		
	if($("#onair_num_sp_1").attr('value')){ //---> At least, the fisrt splited adv must define  
	
		$.ajaxSetup({
			async: false
		});
		$.ajax({
			type: "POST",
			url: "?r=onair/onSplitadv",
			data:{'org_adv_id':onair_org_adv_id, 'split_adv_name':onair_split_adv_name, 'split_adv_timelen':onair_split_adv_timelen,'split_prod_id':onair_split_prod_id,'prog_id':onair_prog_id,'day':onair_day,'month':onair_month,'year':onair_year,'time_org':onair_split_timeorg,'split_bk_seq':onair_split_bk_seq,'split_adv_seq':onair_split_adv_seq},
			
				success: function(data) {									
					//alert("การแยกชุดโฆษณาเสร็จสมบูรณ์");
				},
				error: function(data){				
					alert("มีข้อผิดพลาดเกิดขึ้นระหว่างการดำเนินการ กรุณาทำการแยกชุดโฆษณาใหม่อีกครั้ง");		
				}	
											   
		});	
	
	
	}else{
		alert("กรุณาเลือกโฆษณาในการแยกชุดโฆษณาและกำหนดเวลาโฆษณาหรือคลิกปุ่มยกเลิกเพื่อออกจากหน้าต่างนี้");
	}
	
	while(delay < 100){delay++;}
	
	checkBreak(onair_prog_id,onair_year,onair_month,onair_day);
	$(this).dialog("close");
}

function open_move_adv_onair(){
	
	var split_break_seq_org = 0;
	var split_adv_seq_org = 0;
	var split_adv_id_org = 0;
	
	//confirm_onair(); //confirm database first
	
	/*split_break_seq_org = $("#onair_input_editDi").attr('value');
	split_adv_seq_org = $("#adv_seq_list").attr('value');
	split_adv_id_org = $("#mAdvName_edit").attr('value');*/
	
	
		
	$.ajax('?r=onair/japi&action=advOnairList',{
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
	
	/*$("#onair_split_advname").val(split_adv_id_org );
	$("#onair_split_bksq").val(split_break_seq_org);
	$("#onair_split_advsq ").val(split_adv_seq_org);
	
	onair_show_ProdAgency();
	onair_split_table();*/
		
	//Attribute to break sequence and adv sequence input
	
	return false;	
}

function open_split_adv_onair(){
	
	var split_break_seq_org = 0;
	var split_adv_seq_org = 0;
	var split_adv_id_org = 0;
	
	//confirm_onair(); //confirm database first
	
	split_break_seq_org = $("#onair_input_editDi").attr('value');
	split_adv_seq_org = $("#adv_seq_list").attr('value');
	split_adv_id_org = $("#mAdvName_edit").attr('value');
	
	
	$(this).dialog("close");
	
	$('#onair_split_Di').dialog('open');
	
	$("#onair_split_advname").val(split_adv_id_org );
	$("#onair_split_bksq").val(split_break_seq_org);
	$("#onair_split_advsq ").val(split_adv_seq_org);
	
	onair_show_ProdAgency();
	onair_split_table();
		
	//Attribute to break sequence and adv sequence input
	
	return false;	
}


$.ajaxSetup({
	async: false
});
$.ajax('?r=adver/japi&action=advList',{
	type: 'GET',
	dataType: 'json',
	success: function(advList){
		$("#onair_split_advname option").remove();			
		$.each(advList,function(k,v){	
				$("#onair_split_advname").append( 
							"<option value="+v.adv_id+">"+v.adv_name+"</option>"
				);
							
		});
	}
	
		 
});

$.ajaxSetup({
	async: false
});

$.ajax('?r=adver/japi&action=prodList',{
	type: 'GET',
	dataType: 'json',
	success: function(prodList){
		$("#onair_split_product option").remove();			
			$.each(prodList,function(k,v){ // each of break
					
				$("#onair_split_product").append( 
							 "<option value="+v.prod_id+">"+v.prod_name+"</option>"
				);
							
			});
	}
});


$("#onair_split_advname").change(function(){
							  
	onair_show_ProdAgency();
	onair_split_table();						  
									  
});


$("#onair_split_number").change(function() {
	
	onair_show_ProdAgency();
	onair_split_table();
								  
});


$(".onair_time_each_sp").change(function() {
	
	onair_split_table_change();					  
									  
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
                //'ตกลง'=>'js:onair_merge_adv_cf',
                'ยกเลิก'=>'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
?>

<div class="dialog_input">
 <form class="form-horizontal" style="font-size:1em">
 	 <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_prog">รายการ:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prog" id="onair_merge_prog" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="onair_merge_prog_next" id="onair_merge_prog_next" >
        	</select> 
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_date">วันที่:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_date" id="onair_merge_date" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        
       		<?php
            	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name'=>'my_date',
				'id'=>'onair_merge_date_next',
                'language'=>Yii::app()->language=='et' ? 'et' : null,
                'options'=>array(
                	'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                	'showOn'=>'button', // 'focus', 'button', 'both'
                	'buttonText'=>Yii::t('ui','Select form calendar'),
                	'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
					//'dateFormat'=>'dd/mm/yy',
                	'buttonImageOnly'=>true,),
                'htmlOptions'=>array(
                	'style'=>'width:175px;vertical-align:top'),
                ));   
        	?>	
             
		</div>
    </div>
 	<div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label  for="onair_merge_prod">สินค้า:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prod" id="onair_merge_prod" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prod_next" id="onair_merge_prod_next"  class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_prod_final" id="onair_merge_prod_final"  class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_adv">ชื่อโฆษณา:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:0.8em;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_adv" id="onair_merge_adv" class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="onair_merge_adv_next" id="onair_merge_adv_next" ></select>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:0.8em;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_adv_final" id="onair_merge_adv_final" class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
    </div>
 	<div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_agency">เอเจนซี่:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_agency" id="onair_merge_agency"  class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_agency" id="onair_merge_agency_next"  class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_agency_final" id="onair_merge_agency_final"  class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label  for="onair_merge_timelen">เวลา(วินาที):</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_timelen" id="onair_merge_timelen" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_timelen_next" id="onair_merge_timelen_next" class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_timelen_final" id="onair_merge_timelen_final" class="ui-ams-input text ui-widget-content ui-corner-all "  readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label  for="onair_merge_timelen">ลำดับเบรกและโฆษณา:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
            <div class="row-fluid">
                <div class="span6" align="left" style="margin-top:5px">
                    <input style="font-size:1em;width:80px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_bkseq" id="onair_merge_bkseq" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>
                </div>
				<div class="span6" align="left" style="margin-top:5px">
                    <input style="font-size:1em;width:80px;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_advseq" id="onair_merge_advseq" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>
                </div>
            </div>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
            <div class="row-fluid">
                <div class="span6" align="left" style="margin-top:5px">
                    <select style="padding:2px 6px 2px 6px;margin-bottom:7px" class="input-small" name="onair_merge_bkseq_next" id="onair_merge_bkseq_next" >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select> 
                </div>
                <div class="span6" align="left" style="margin-top:5px">
                    <select style="padding:2px 6px 2px 6px;margin-bottom:7px" class="input-small" name="onair_merge_advseq_next" id="onair_merge_advseq_next" >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                    </select> 
                </div>
            </div>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
            <div class="row-fluid">
                <div class="span6" align="left" style="margin-top:5px">
                    <select style="padding:2px 6px 2px 6px;margin-bottom:7px" class="input-small" name="onair_merge_bkseq_final" id="onair_merge_bkseq_final" >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select> 
                </div>
                <div class="span6" align="left" style="margin-top:5px">
                    <select style="padding:2px 6px 2px 6px;margin-bottom:7px" class="input-small" name="onair_merge_advseq_final" id="onair_merge_advseq_final" >
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                    </select> 
                </div>
            </div> 
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_pack">กิจกรรม:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_pack" id="onair_merge_pack" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="onair_merge_pack_next" id="onair_merge_pack_next" >
                <option value="none">ไม่กำหนด</option>
				<option value="TSM">TSM</option>
				<option value="FA">FA</option>
				<option value="Pantine">Pantine</option>
				<option value="มวยไทย7สี">มวยไทย7สี</option>
        	</select>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<select style="padding:2px 6px 2px 6px;margin-bottom:7px" name="onair_merge_pack_final" id="onair_merge_pack_final" >
                <option value="none">ไม่กำหนด</option>
				<option value="TSM">TSM</option>
				<option value="FA">FA</option>
				<option value="Pantine">Pantine</option>
				<option value="มวยไทย7สี">มวยไทย7สี</option>
        	</select>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_bktype"></label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px;margin-left:25px">
        	<div class="row-fluid">
  				<div class="span6" align="left">
                    <label class="radio">ราคาปกติ<input class="onair_merge_bktype" title="ราคาปกติ" type="radio" name="onair_merge_bktype" id="onair_merge_bktype1" value="ราคาปกติ" checked="checked" onclick="return false;"></label>
                </div>
                <div class="span6" align="left">
                    <label class="radio">ราคาพิเศษ<input class="onair_merge_bktype" title="ราคาพิเศษ" type="radio" name="onair_merge_bktype" id="onair_merge_bktype2" value="ราคาพิเศษ" onclick="return false;"></label>
                </div>
             </div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
        	<div class="row-fluid">
  				<div class="span6" align="left">
                    <label class="radio">ราคาปกติ<input class="onair_merge_bktype_next" title="ราคาปกติ" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next1" value="ราคาปกติ" checked="checked"></label>
                </div>
                <div class="span6" align="left">
                    <label class="radio">ราคาพิเศษ<input class="onair_merge_bktype_next" title="ราคาพิเศษ" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next2" value="ราคาพิเศษ"></label>
                </div>
             </div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
        	<div class="row-fluid">
  				<div class="span6" align="left">
                    <label class="radio">ราคาปกติ<input class="onair_merge_bktype_final" title="ราคาปกติ" type="radio" name="onair_merge_bktype_final" id="onair_merge_bktype_final1" value="ราคาปกติ" checked="checked"></label>
                </div>
                <div class="span6" align="left">
                    <label class="radio">ราคาพิเศษ<input class="onair_merge_bktype_final" title="ราคาพิเศษ" type="radio" name="onair_merge_bktype_final" id="onair_merge_bktype_final2" value="ราคาพิเศษ"></label>
                </div>
             </div> 
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_bktype"></label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px;margin-left:25px">
			 <div class="row-fluid">
             	<div class="span6" align="left">
                    <label class="radio">บาเตอร์<input class="onair_merge_bktype" title="บาเตอร์" type="radio" name="onair_merge_bktype" id="onair_merge_bktype3" value="บาเตอร์" onclick="return false;"></label>
                </div>
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">อัตราพิเศษ<input class="onair_merge_bktype" title="อัตราพิเศษ" type="radio" name="onair_merge_bktype" id="onair_merge_bktype4" value="อัตราพิเศษ" onclick="return false;"></label>
                </div>
            </div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
			 <div class="row-fluid">
             	<div class="span6" align="left">
                    <label class="radio">บาเตอร์<input class="onair_merge_bktype_next" title="บาเตอร์" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next3" value="บาเตอร์"></label>
                </div>
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">อัตราพิเศษ<input class="onair_merge_bktype_next" title="อัตราพิเศษ" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next4" value="อัตราพิเศษ"></label>
                </div>
            </div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
			 <div class="row-fluid">
             	<div class="span6" align="left">
                    <label class="radio">บาเตอร์<input class="onair_merge_bktype_final" title="บาเตอร์" type="radio" name="onair_merge_bktype_final" id="bonair_merge_bktype_final3" value="บาเตอร์"></label>
                </div>
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">อัตราพิเศษ<input class="onair_merge_bktype_final" title="อัตราพิเศษ" type="radio" name="onair_merge_bktype_final" id="onair_merge_bktype_final4" value="อัตราพิเศษ"></label>
                </div>
             </div>
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_bktype"></label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px;margin-left:25px">
           	<div class="row-fluid">
                <div class="span6" align="left">
                    <label class="radio">แถม<input class="onair_merge_bktype" title="แถม" type="radio" name="onair_merge_bktype" id="onair_merge_bktype5" value="แถม" onclick="return false;"></label>
                </div> 
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">ปะหัว<input class="onair_merge_bktype" title="ปะหัว" type="radio" name="onair_merge_bktype" id="onair_merge_bktype6" value="ปะหัว" onclick="return false;"></label>
                </div>          
			</div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
           	<div class="row-fluid">
                <div class="span6" align="left">
                    <label class="radio">แถม<input class="onair_merge_bktype_next" title="แถม" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next5" value="แถม"></label>
                </div> 
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">ปะหัว<input class="onair_merge_bktype_next" title="ปะหัว" type="radio" name="onair_merge_bktype_next" id="onair_merge_bktype_next6" value="ปะหัว"></label>
                </div>          
			</div>
		</div>
        <div class="span3" align="left" style="margin-top:5px;margin-left:25px">
           	<div class="row-fluid">
                <div class="span6" align="left">
                    <label class="radio">แถม<input class="onair_merge_bktype_final" title="แถม" type="radio" name="onair_merge_bktype_final" id="onair_merge_bktype_final5" value="แถม"></label>
                </div> 
                <div class="span6" align="left">
                    <label style="margin:1px" class="radio">ปะหัว<input class="onair_merge_bktype_final" title="ปะหัว" type="radio" name="onair_merge_bktype_final" id="onair_merge_bktype_final6" value="ปะหัว"></label>
                </div>          
			</div>
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label   for="onair_merge_progcomp">บริษัทสั่งจ่าย:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_progcomp" id="onair_merge_progcomp" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_progcomp_next" id="onair_merge_progcomp_next" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_progcomp_final" id="onair_merge_progcomp_final" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_spotprice">ราคาต่อSpot:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_spotprice" id="onair_merge_spotprice" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_spotprice_next" id="onair_merge_spotprice_next" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_spotprice_final" id="onair_merge_spotprice_final" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_discount">ส่วนลด:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
			<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_discount" id="onair_merge_discount" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
            <select id="onair_merge_discount_next" class="input-small" >
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
        <div class="span3" align="left" style="margin-top:5px">
            <select id="onair_merge_discount_final"  value="" class="input-small" >
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
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label for="onair_merge_netprice">ราคาสุทธิ:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_netprice" id="onair_merge_netprice" class="ui-ams-input text ui-widget-content ui-corner-all " readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_netprice_next" id="onair_merge_netprice_next" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<input style="font-size:1em;width:50;padding-top:3px;padding-bottom:7px" type="text" name="onair_merge_netprice_final" id="onair_merge_netprice_final" class="ui-ams-input text ui-widget-content ui-corner-all " value="" readonly="readonly"/>  
		</div>
    </div>
    <div class="row-fluid">
    	<div class="span2" align="right" style="margin-top:5px">
			<label  for="onair_merge_info">หมายเหตุ:</label>
        </div>
       	<div class="span3" align="left" style="margin-top:5px">
        	<textarea rows="3" id="onair_merge_info"></textarea>
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<textarea rows="3" id="onair_merge_info_next"></textarea>
		</div>
        <div class="span3" align="left" style="margin-top:5px">
        	<textarea rows="3" id="onair_merge_info_final"></textarea> 
		</div>
    </div>
 </form>   
</div>

<?php
    $this->endWidget('zii.widgets.jui.CJuiDialog');
	
	//--------------- End of Dialog for merging Advertise  --------------
?>

<script>
// -------> Main Advertise for Merging --------->

function open_merge_adv_onair(){
	
	var onair_break_seq_org = 0;
	var onair_adv_seq_org = 0;
	var onair_adv_id_org = 0;
	var onair_adv_name_org = 0;
	
	onair_break_seq_org = $("#onair_input_editDi").attr('value');
	onair_adv_seq_org = $("#adv_seq_list").attr('value');
	
	$(this).dialog("close");
	
	$('#onair_merge_Di').dialog('open');
	
	$("#onair_merge_bkseq").val(onair_break_seq_org);
	$("#onair_merge_advseq").val(onair_adv_seq_org);
	
	read_main_adv_merge();
	
	Read_Mereg_Prog(); //---->Show program list for seccend advertise
}

function read_main_adv_merge(){
	
	var main_merge_progid = 0;
	var main_merge_progname = 0;
	var main_merge_prodid = 0;
	var main_merge_prodname = 0;
	var main_merge_agencyid =0;
	var main_merge_agencyname =0;

	var main_merge_advid = 0;
	var main_merge_advname = 0;
	var main_merge_timelen = 0;
	var main_merge_calprice = 0;
	var main_merge_bktype = 0;
	var main_merge_discount = 0;
	var main_merge_info = 0;
	
	main_merge_progid = $("#prog_on").attr('value');
	main_merge_progname = $("#prog_on option:selected").text();
	main_merge_agencyid = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('agency_id');
	main_merge_agencyname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"agency"+$('#onair_merge_advseq').attr("value")).text()

	main_merge_advid = $('#breakinglist').find('li[list_adv=bk'+$('#onair_merge_bkseq').attr("value")+'advseq'+$('#onair_merge_advseq').attr("value")+']').attr("value");
	main_merge_advname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"advname"+$('#onair_merge_advseq').attr("value")).text();
	main_merge_prodid = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('prod_id');
	main_merge_prodname = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).text();
	main_merge_timelen = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('time_bk');
	main_merge_calprice = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('spot_price');
	main_merge_bktype = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('bk_type');
	main_merge_discount = $("#bk"+$('#onair_merge_bkseq').attr("value")+"prod"+$('#onair_merge_advseq').attr("value")).attr('on_discount');
	main_merge_info = $("#bk"+$('#onair_input_editDi').attr("value")+"prod"+$('#adv_seq_list').attr("value")).attr('bk_info');
	
	read_merge_maindate();//----> Date 
	
	$("#onair_merge_prog").attr("merge_prog_id",main_merge_progid);
	$("#onair_merge_prog").val(main_merge_progname);
	$("#onair_merge_agency").val(main_merge_agencyname);
	$("#onair_merge_agency").attr("merge_agencyid",main_merge_agencyid);
	$("#onair_merge_adv").attr("merge_adv_id",main_merge_advid);
	$("#onair_merge_adv").val(main_merge_advname);
	$("#onair_merge_prod").val(main_merge_prodname);
	$("#onair_merge_prod").attr("merge_prodid",main_merge_prodid);
	$("#onair_merge_timelen").val(main_merge_timelen);
	
	read_merge_progprice(main_merge_progid)// -------> Program price per minute and program company
	
	$("#onair_merge_spotprice").val(main_merge_calprice);
	$("#onair_merge_discount").val(main_merge_discount);
	$("#onair_merge_info").val(main_merge_info);

	//----------> BREAK TYPE ------->
	
	if(main_merge_bktype == "ราคาปกติ"){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype1").attr("checked","checked");
		
		
	}else if(main_merge_bktype == " "){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype2").attr("checked","checked");
		
	}else if(main_merge_bktype == "บาเตอร์"){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype3").attr("checked","checked");
		
	}else if(main_merge_bktype == "อัตราพิเศษ"){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype4").attr("checked","checked");
		
	}else if(main_merge_bktype == "แถม"){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype5").attr("checked","checked");
		
	}else if(main_merge_bktype == "ปะหัว"){
		
		$("#onair_merge_pack").val("ไม่กำหนด");
		$("#onair_merge_bktype6").attr("checked","checked");
		
	}else{
		
		$("#onair_merge_bktype2").attr("checked","checked");
		$("#onair_merge_pack").val(main_merge_bktype);
	
	}
	
	//----------> BREAK TYPE ------->
	
	
	var onair_merge_netprice = parseInt(main_merge_calprice)*(1-(parseInt(main_merge_discount)/100));
	onair_merge_netprice = onair_merge_netprice.toFixed(2);
	
	$("#onair_merge_netprice").val(onair_merge_netprice);
	
}

function read_merge_maindate(){
	
	var main_merge_month = parseInt($("#onair_mon").attr('value'));
	var main_merge_year = parseInt($("#onair_year").attr('value'))-543;
	var main_merge_day = $("#ul_daytab").find("li.ui-state-active").text();
	var main_merge_date = 0;
	
	main_merge_date = main_merge_month+"/"+main_merge_day+"/"+main_merge_year;	
	$("#onair_merge_date").val(main_merge_date);
}

function read_merge_progprice(main_merge_progid){
	
		var onair_spot_price = 0;
		var onair_pack_price = 0;

	
		$.ajaxSetup({
			async: false
		});
		
		$.ajax('?r=onair/japi&action=getPrice&prog_id='+main_merge_progid+'',{
			type: 'GET',
			dataType: 'json',
			success: function(getPrice){
		
				$.each(getPrice,function(key,val){
					
					//console.log("minute_price= "+val.minute_price+" pack_price= "+val.pack_price)
					onair_spot_price = parseInt(val.minute_price)/60;
					onair_spot_price = onair_spot_price.toFixed(2);// Round up
					onair_pack_price = parseInt(val.pack_price)/60;
					onair_pack_price = onair_pack_price.toFixed(2);// Round up
							
					$("#onair_merge_progcomp").attr("spotprice", onair_spot_price);
					$("#onair_merge_progcomp").attr("packprice", onair_pack_price);
					$("#onair_merge_progcomp").attr("comp_id", val.company_id);
					
					$.ajaxSetup({
						async: false
					});
			
					$.ajax('?r=program/japi&action=readUpdateComp&company_id='+val.company_id+'',{
						type: 'GET',
						dataType: 'json',
						success: function(readUpdateComp){
								
							$.each(readUpdateComp,function(key,value){
								
								$("#onair_merge_progcomp").val(value.name);

							});
						}
					});
					
				});
			}
		});	

}

// <------- Main Advertise for Merging <---------


//--------> Seccond Advertise for Merging ------->

function Read_Mereg_Prog(){ 

//merge_prog_list
	var merge_comp_id = 0;
	var merge_comp_name = 0;
	var merge_prog_id = 0;
	
	merge_comp_id = $("#onair_merge_progcomp").attr("comp_id");
	merge_prog_id = $("#onair_merge_prog").attr("merge_prog_id");
	
	console.log("merge_comp_id= "+merge_comp_id+"merge_prog_id= "+merge_prog_id);
	
	$.ajaxSetup({
		async: false
	});

	$.ajax('?r=onair/japi&action=readMeregProg&comp_id='+merge_comp_id+'&prog_id='+merge_prog_id+'',{
		type: 'GET',
		dataType: 'json',
		success: function(readMeregProg){

			$("#onair_merge_prog_next option").remove();			
			$.each(readMeregProg,function(k,v){ 
							
					$("#onair_merge_prog_next").append(
														 
						"<option value='"+v.prog_id+"'>"+v.prog_name+"</option>"
					);	
					
					merge_comp_name = v.name;		
			});
			
			$("#onair_merge_progcomp_next").val(merge_comp_name);
			
		}
	});
}

function show_sec_merge_adv(sm_prog_id,sm_year,sm_month,sm_day){
	
	$.ajaxSetup({
		async: false
	});
	$.ajax( '?r=onair/japi&action=secMergeProgShow&program='+sm_prog_id+'&year='+sm_year+'&month='+sm_month+'&day='+sm_day+'', {
	  type: 'GET',
	  dataType: 'json',
		success: function(secMergeProgShow) {
			  
			$("#onair_merge_adv_next option").remove();			
			$.each(secMergeProgShow,function(k,v){
				$("#onair_merge_adv_next").append(
												 
					"<option value='"+v.adv_id+"'>"+v.adv_name+"</option>"
				);
							
			});
			
		}
	});

}

function show_sec_merge_prodagen(){
	
	var sec_merge_advid = 0;
	var sec_merge_timelength = 0;
	
	sec_merge_advid = $("#onair_merge_adv_next").attr("value");

	$.ajaxSetup({
		async: false
	});
	$.ajax('?r=onair/japi&action=mergeAdvProd&adv_id='+sec_merge_advid+'',{
		type: 'GET',
		dataType: 'json',
		success: function(mergeAdvProd){		
				$.each(mergeAdvProd,function(k,v){
							
					$("#onair_merge_prod_next").attr('value',v.prod_name);
					$("#onair_merge_prod_next").attr('prod_id',v.prod_id);
					$("#onair_merge_agency_next").attr('value',v.agency_name);
					$("#onair_merge_agency_next").attr('agency_id',v.agency_id);
					
					if(v.adv_time_len != 0){
				
						sec_merge_timelength = v.adv_time_len;
				
					}else{
				
						sec_merge_timelength = v.time_len;
			
					}
					
					$("#onair_merge_timelen_next").attr('value',sec_merge_timelength);
						
				});
		 	}
		});	
}

function show_sec_merge_Bkseq(sm_prog_id,sm_year,sm_month,sm_day){
	
	var sec_merge_advid = 0;
	var sec_merge_timelength = 0;
	var sec_merge_bkseq = 0;
	var sec_merge_advseq = 0;

	
	sec_merge_advid = $("#onair_merge_adv_next").attr("value");

	$.ajaxSetup({
		async: false
	});
	$.ajax( '?r=onair/japi&action=secMergeBkseqShow&program='+sm_prog_id+'&year='+sm_year+'&month='+sm_month+'&day='+sm_day+'&adv_id='+sec_merge_advid+'', {
	  type: 'GET',
	  dataType: 'json',
		success: function(secMergeBkseqShow) {
			  
			$("#onair_merge_bkseq_next option").remove();			
			$.each(secMergeBkseqShow,function(k,v){
				
				if(sec_merge_bkseq != v.break_seq){
					
					sec_merge_bkseq = v.break_seq;
					$("#onair_merge_bkseq_next").append(
											 
						"<option value='"+sec_merge_bkseq+"'>"+sec_merge_bkseq+"</option>"
						
					);	
					
				}
		
			});
			
		}
	});	
} 

function show_sec_merge_Advseq(sm_prog_id,sm_year,sm_month,sm_day){
	
	var sec_merge_advid = 0;
	var sec_merge_timelength = 0;
	var sec_merge_bkseq = 0;
	var sec_merge_advseq = 0;
	var sec_merge_bkseqid = 0

	sec_merge_advid = $("#onair_merge_adv_next").attr("value");
	sec_merge_bkseqid = $("#onair_merge_bkseq_next").attr("value")

	$.ajaxSetup({
		async: false
	});
	$.ajax( '?r=onair/japi&action=SecMergeAdvseqShow&program='+sm_prog_id+'&year='+sm_year+'&month='+sm_month+'&day='+sm_day+'&adv_id='+sec_merge_advid+'&break_seq='+sec_merge_bkseqid+'', {
	  type: 'GET',
	  dataType: 'json',
		success: function(SecMergeAdvseqShow) {
			  
			$("#onair_merge_advseq_next option").remove();			
			$.each(SecMergeAdvseqShow,function(k,v){
				
				if(sec_merge_advseq != v.adv_seq){
					
					sec_merge_advseq = v.adv_seq;
					$("#onair_merge_advseq_next").append(
											 
						"<option value='"+sec_merge_advseq+"'>"+sec_merge_advseq+"</option>"
						
					);	
				}
				
		
			});
			
		}
	});
	
	// ----------> Determine break type 
	sec_merge_bktype(sm_prog_id,sm_year,sm_month,sm_day,sec_merge_advid,sec_merge_bkseqid)
}

function sec_merge_bktype(sm_prog_id,sm_year,sm_month,sm_day,sm_advid,sm_bkseqid){
	
	var merge_advseq_next = 0;

	merge_advseq_next = $("#onair_merge_advseq_next").attr("value");

		
	$.ajaxSetup({
		async: false
	});
	$.ajax( '?r=onair/japi&action=SecMergeBktypeShow&program='+sm_prog_id+'&year='+sm_year+'&month='+sm_month+'&day='+sm_day+'&adv_id='+sm_advid+'&break_seq='+sm_bkseqid+'&adv_seq='+merge_advseq_next+'', {
	  type: 'GET',
	  dataType: 'json',
		success: function(SecMergeBktypeShow) {
			  		
			$.each(SecMergeBktypeShow,function(k,v){
				
				console.log("bktype= "+v.break_type);
			
				//----------> BREAK TYPE ------->
				
				if(v.break_type == "ราคาปกติ"){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next1").attr("checked","checked");
					
					
				}else if(v.break_type == " "){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next2").attr("checked","checked");
					
				}else if(v.break_type == "บาเตอร์"){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next3").attr("checked","checked");
					
				}else if(v.break_type == "อัตราพิเศษ"){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next4").attr("checked","checked");
					
				}else if(v.break_type == "แถม"){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next5").attr("checked","checked");
					
				}else if(v.break_type == "ปะหัว"){
					
					$("#onair_merge_pack_next").val("ไม่กำหนด");
					$("#onair_merge_bktype_next6").attr("checked","checked");
					
				}else{
					
					$("#onair_merge_bktype_next2").attr("checked","checked");
					$("#onair_merge_pack_next").val(v.break_type);
				
				}
				
				//----------> BREAK TYPE ------->

					
			});
			//console.log(SecMergeBktypeShow);
		}
	});	
	
}


$("#onair_merge_advseq_next").change(function(){
	
	var sec_merge_prog = 0;
	var sec_merge_date = 0;
	var sec_merge_curdate = 0;
	var sec_merge_curday = 0;
	var sec_merge_curmonth = 0;
	var sec_merge_curyear = 0;
	var sec_merge_bkseqid =0;
	var sec_merge_advid = 0;

	
	sec_merge_prog = $("#onair_merge_prog_next").attr("value");
	sec_merge_bkseqid = $("#onair_merge_bkseq_next").attr("value");
	sec_merge_advid = $("#onair_merge_adv_next").attr("value");
	
	if($("#onair_merge_date_next").attr("value")){
		
		sec_merge_curdate =  new Date($("#onair_merge_date_next").attr("value"));
		
	}else {
		
		sec_merge_curdate = new Date();
	}
	
	sec_merge_curday = parseInt(sec_merge_curdate.getDate());
	sec_merge_curmonth =  parseInt(sec_merge_curdate.getMonth())+1;	
	sec_merge_curyear = parseInt(sec_merge_curdate.getFullYear());
	
	//console.log("breakSeq= "+sec_merge_bkseqid);

	sec_merge_bktype(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday,sec_merge_advid,sec_merge_bkseqid);	
	
});

$("#onair_merge_bkseq_next").change(function(){

	var sec_merge_prog = 0;
	var sec_merge_date = 0;
	var sec_merge_curdate = 0;
	var sec_merge_curday = 0;
	var sec_merge_curmonth = 0;
	var sec_merge_curyear = 0;
	
	sec_merge_prog = $("#onair_merge_prog_next").attr("value");
	
	if($("#onair_merge_date_next").attr("value")){
		
		sec_merge_curdate =  new Date($("#onair_merge_date_next").attr("value"));
		
	}else {
		
		sec_merge_curdate = new Date();
	}
	
	sec_merge_curday = parseInt(sec_merge_curdate.getDate());
	sec_merge_curmonth =  parseInt(sec_merge_curdate.getMonth())+1;	
	sec_merge_curyear = parseInt(sec_merge_curdate.getFullYear());

	show_sec_merge_Advseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
		
});

$("#onair_merge_adv_next").change(function(){
	
	var sec_merge_prog = 0;
	var sec_merge_date = 0;
	var sec_merge_curdate = 0;
	var sec_merge_curday = 0;
	var sec_merge_curmonth = 0;
	var sec_merge_curyear = 0;
	
	sec_merge_prog = $("#onair_merge_prog_next").attr("value");
	
	if($("#onair_merge_date_next").attr("value")){
		
		sec_merge_curdate =  new Date($("#onair_merge_date_next").attr("value"));
		
	}else {
		
		sec_merge_curdate = new Date();
	}
	
	sec_merge_curday = parseInt(sec_merge_curdate.getDate());
	sec_merge_curmonth =  parseInt(sec_merge_curdate.getMonth())+1;	
	sec_merge_curyear = parseInt(sec_merge_curdate.getFullYear());

	show_sec_merge_prodagen();	
	show_sec_merge_Bkseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	show_sec_merge_Advseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);

});

$("#onair_merge_date_next").change(function(){
	
	var sec_merge_prog = 0;
	var sec_merge_date = 0;
	var sec_merge_curdate = 0;
	var sec_merge_curday = 0;
	var sec_merge_curmonth = 0;
	var sec_merge_curyear = 0;
	
	sec_merge_prog = $("#onair_merge_prog_next").attr("value");
	
	if($("#onair_merge_date_next").attr("value")){
		
		sec_merge_curdate =  new Date($("#onair_merge_date_next").attr("value"));
		
	}else {
		
		sec_merge_curdate = new Date();
	}
	
	sec_merge_curday = parseInt(sec_merge_curdate.getDate());
	sec_merge_curmonth =  parseInt(sec_merge_curdate.getMonth())+1;	
	sec_merge_curyear = parseInt(sec_merge_curdate.getFullYear());
	
	show_sec_merge_adv(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	
	show_sec_merge_prodagen();
	show_sec_merge_Bkseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	show_sec_merge_Advseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	
	//console.log("prog= "+sec_merge_prog+"day= "+sec_merge_curday+"month= "+sec_merge_curmonth+"year= "+sec_merge_curyear);
	
});

$("#onair_merge_prog_next").change(function(){
	
	var sec_merge_prog = 0;
	var sec_merge_date = 0;
	var sec_merge_curdate = 0;
	var sec_merge_curday = 0;
	var sec_merge_curmonth = 0;
	var sec_merge_curyear = 0;
	
	sec_merge_prog = $("#onair_merge_prog_next").attr("value");
	
	if($("#onair_merge_date_next").attr("value")){
		
		sec_merge_curdate =  new Date($("#onair_merge_date_next").attr("value"));
		
	}else {
		
		sec_merge_curdate = new Date();
	}
	
	sec_merge_curday = parseInt(sec_merge_curdate.getDate());
	sec_merge_curmonth =  parseInt(sec_merge_curdate.getMonth())+1;	
	sec_merge_curyear = parseInt(sec_merge_curdate.getFullYear());
	
	show_sec_merge_adv(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	
	show_sec_merge_prodagen();
	show_sec_merge_Bkseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	show_sec_merge_Advseq(sec_merge_prog,sec_merge_curyear,sec_merge_curmonth,sec_merge_curday);
	
	//console.log("prog= "+sec_merge_prog+"day= "+sec_merge_curday+"month= "+sec_merge_curmonth+"year= "+sec_merge_curyear);
	
});





//<-------- Seccond Advertise for Merging <-------

</script>






<script>
 
	 function show_history(){
	
		var count_adv = 0;
		var all_break_read = [];
	
		$("#breakinglist").find('li').each(function(){
			var current = $(this);
			if(current.attr('id')){
	
			}else{
					
				all_break_read[count_adv++] = current.attr('value');
						
			} 			
		}); 
		
		//console.log("all_break_read= "+all_break_read)
		/*
		$("#breakinglist li").remove();
		$.each(breakpre[0], function(k,v){
			
		});
		*/
	}

//------------>Start Alarming DayTab --------------->

	function calculate_usagetime(prog_on,onair_year,onair_mon,on_day){
		
		var usage_break_time = 0;
		var total_break_time = 0;
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax( '?r=onair/japi&action=sumUTime&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+on_day+'', {
		  type: 'GET',
		  dataType: 'json',
			  success: function(sumUTime) {
			  
				$.each(sumUTime, function(key,value){
					
					usage_break_time = parseInt(value.tape_time) + parseInt(value.adv_time);

				});
			}
	  	});
		
		$.ajaxSetup({
			async: false
		});
		
		$.ajax( '?r=onair/japi&action=sumTotalTime&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+on_day+'', {
		  type: 'GET',
		  dataType: 'json',
			  success: function(sumTotalTime) {
			  
				$.each(sumTotalTime, function(k,v){

					total_break_time = v.total_time;
					
				});
			}
	  	});
		
		if(usage_break_time > total_break_time){
			
			$("#li_daytab"+on_day).removeClass("ui-ams-inactive");
			$("#li_daytab"+on_day).removeClass("ui-ams-success");
			$("#li_daytab"+on_day).removeClass("ui-ams-warning");
			$("#li_daytab"+on_day).removeClass("ui-ams-danger");
			$("#li_daytab"+on_day).addClass("ui-ams-danger");	
							
		}else if(usage_break_time == total_break_time){
			
			$("#li_daytab"+on_day).removeClass("ui-ams-inactive");
			$("#li_daytab"+on_day).removeClass("ui-ams-success");
			$("#li_daytab"+on_day).removeClass("ui-ams-warning");
			$("#li_daytab"+on_day).removeClass("ui-ams-danger");
			$("#li_daytab"+on_day).addClass("ui-ams-success");
			
		}else {
			
			$("#li_daytab"+on_day).removeClass("ui-ams-inactive");
			$("#li_daytab"+on_day).removeClass("ui-ams-success");
			$("#li_daytab"+on_day).removeClass("ui-ams-warning");
			$("#li_daytab"+on_day).removeClass("ui-ams-danger");
			$("#li_daytab"+on_day).addClass("ui-ams-warning");
		}
		
	}


	function alarming_daytab(prog_on,onair_year,onair_mon){
		
		var on_program = 0;
		var max_seq = 0;
	
		for(var on_day = 1; on_day < 32; on_day++){

			$.ajaxSetup({
				async: false
			});
			
			$.ajax( '?r=onair/japi&action=breakcheck&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+on_day+'', {
			  type: 'GET',
			  dataType: 'json',
				  success: function(breakcheck) {
					  
				var break_id = 0; 
				
				$.each(breakcheck[0], function(k,v){
					
					break_id = v.break_id;
					
				});
				
				if(break_id != 0){
					
					calculate_usagetime(prog_on,onair_year,onair_mon,on_day);
					
				}else{	

					$.ajaxSetup({
						async: false
					});
					
					$.ajax( '?r=onair/japi&action=maxSeq&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+on_day+'', {
					  type: 'GET',
					  dataType: 'json',
						  success: function(maxSeq) {
						  
							$.each(maxSeq, function(key,value){
								
								max_seq = value.max_seq;
								
							});
							
							if(max_seq < 1){
								
								$("#li_daytab"+on_day).removeClass("ui-ams-inactive");
								$("#li_daytab"+on_day).removeClass("ui-ams-success");
								$("#li_daytab"+on_day).removeClass("ui-ams-warning");
								$("#li_daytab"+on_day).removeClass("ui-ams-danger");
								$("#li_daytab"+on_day).addClass("ui-ams-inactive");
								
							}else{
								
								$("#li_daytab"+on_day).removeClass("ui-ams-inactive");
								$("#li_daytab"+on_day).removeClass("ui-ams-success");
								$("#li_daytab"+on_day).removeClass("ui-ams-warning");
								$("#li_daytab"+on_day).removeClass("ui-ams-danger");
								$("#li_daytab"+on_day).addClass("ui-ams-warning");

							}
						}
					});
					
				}	
			}
		  });		
		}
	}

//<------------End of Alarming DayTab <--------------
 
 
 
//------------> Start to Export function ----------->
    
	function showreport(){
		
		var cell=0;
		var delay =0;
		var report_timelength = 0;
		
		var day = $("#ul_daytab").find("li.ui-state-active").text();
		var prog_on = $("#prog_on").attr('value');
		var onair_mon = parseInt($("#onair_mon").attr('value'));
		var onair_year = parseInt($("#onair_year").attr('value'))-543;
		
		$.ajaxSetup({
			async: false
		});
		$.ajax('?r=onair/japi&action=breakshow&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'',{
			type: 'GET',
			dataType: 'json',
			success: function(breakshow){
			//var breakid=0;
			$("#report_table tbody tr").remove();			
				$.each(breakshow[0], function(k,v){
					
					if(v.adv_time_len != 0){
				
						report_timelength = v.adv_time_len;
				
					}else{
				
						report_timelength = v.time_len;
			
					}
					 
					$("#report_table tbody").append(
					
						"<tr style='height:25px;padding-top:4px;padding-bottom:4px'>"+ 
							"<td style='text-align:left'>date</td>" + 
							"<td style='text-align:left'>เทป</td>" +
							"<td style='text-align:left'>"+v.prod_name+" ("+v.adv_name+")</td>" +
							"<td style='text-align:right'>"+report_timelength+"</td>" + 
							"<td style='text-align:left'></td>" +
							"<td style='text-align:left'>"+v.agency_name+"</td>" +
							"<td style='text-align:left'></td>" +
							"<td style='text-align:left'>"+v.prog_name+"</td>" + 
							"<td style='text-align:left'>Break"+v.break_seq+"</td>" +
							"<td style='text-align:left'></td>" +
						"</tr>" 
					);	
				}); 					
				//console.log(prod_adv);
			 }
		});
		
		//console.log("prog;"+prog_on+"Y:"+onair_year+"M:"+onair_mon+"D:"+day);
		
		$.ajaxSetup({
			async: false
		});
		
		window.location = '?r=onair/excel&program='+prog_on+'&year='+onair_year+'&month='+onair_mon+'&day='+day+'';

	while(delay < 10000000){delay++;}
	$(this).dialog("close");	

	}
//<------------End of Export function -------------


	function modalAdvertiseNote(){
		//alert(bk);
		
		$('#noteDialog').dialog('open');
	}
	
	function modalAdvertiseOpen(bk,adv){
		
		if (adv == 0){
			$("#mAdvName").attr('value','');
			$("#mAdvProduct").attr('value','');
			$("#mAdvAgency").attr('value','');
		}else{
			$("#mAdvName").attr('value',$("#bk"+bk+"advname"+adv).text());
			$("#mAdvProduct").attr('value',$("#bk"+bk+"prod"+adv).text());
			$("#mAdvAgency").attr('value',$("#bk"+bk+"agency"+adv).text());
		}
		$("#mAdvBreak").children().remove();
		for (var i=1;i<=$(".ui-ams-advbrk").size();i++)
			$("#mAdvBreak").append("<option>"+i+"</option>");
		$("#mAdvBreak").attr('value',bk);
		$('#modalAdvertise').dialog('open'); 
	}
	
    function addBreak(){
		
        $(this).dialog("close");
		var breakid=$(".ui-ams-advbrk").size()+1;
		var totalbreak_percent=0;
		var totalbreak_mins = Math.floor($("#createbreaktime").val()/60);
		var totalbreak_secs = $("#createbreaktime").val() % 60;
		
        //alert( $("#createbreaktime").val() +" / "+ breakid +" has been added");

		$("<li class='ui-ams-advbrk' id='break"+breakid+"'><div class='row-fluid'><div class='span4' align='left'><span class='ui-button-text'>เบรค#"+breakid+"</span><button  title='เพิ่มชุดโฆษณา' class='ui-ams-btadvbrk' id='btbreak"+breakid+"' onclick=modalAdvertiseOpen("+breakid+","+0+"); return false;><span class='ui-button-text'>เพิ่มโฆษณา</span></button></div><div class='span4' align='right'><a id='totalbreak' style='float:right;margin-right:10px;visibility:hidden'' >"+totalbreak_mins+":"+totalbreak_secs+"</a><a style='float:right;margin-right:10px;visibility:hidden''>/ </a><a id='breaktime' style='float:right;margin-right:10px;visibility:hidden''>"+0+":"+0+"</a></div><div class='span4' align='right'><a title='ลบเบรคและชุดโฆษณาในเบรค' onclick=deleteBreak("+breakid+","+0+");return false;><img src='images/delete_2.png' style='width:22px;margin-right:4px;margin-top:4px;cursor:pointer'/></div></div></li>").insertBefore("#pending");
		
		progressupdate();
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
		
		$("#break"+$('#delete_advID').attr("del_breakID")).remove();
		$("li div#break_"+$('#delete_advID').attr("del_breakID")).parent().remove();
		progressupdate();
		$(this).dialog("close");
	}
	
	function addAdvertise(){
		
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
		var break_info = $('#onair_beak_info').attr('value');
		var break_discount = $("#mAdvDiscount").attr("value");

		if(onair_breaktype == ""){ 	onair_breaktype = " "; }
		if(on_calc_price == "" ){ on_calc_price = 0; }
		if(break_info == ""){ break_info = " ";}
		if(break_discount == ""){ break_discount = 0;}
		
		if($('#mAdvBreak').attr('value') == list_size){
			
			$("<li list_adv='bk"+currbreak+"advseq"+all_adv_size+"' class='ui-state-default ui-ams-adv' style='width:95%' value='"+$("#mAdvName").attr('value')+"'  ><div id='break_"+currbreak+"' class='adv_each_list' style='margin-top: 2px; max-height: 25px; color: blue;'><p><span style='margin-left:3px;' ><img src='images/icon-draggable.png' /></span><span id='bk"+currbreak+"prod"+all_adv_size+"' class='property_bk' bk_type='"+onair_breaktype+"' spot_price='"+on_calc_price+"' on_discount='"+break_discount+"' bk_info='"+break_info+"' time_bk='"+mAdvTimelen+"' prod_id='"+$("#mAdvProduct").attr('prod_id')+"' agency_id='"+$("#mAdvAgency").attr('agency_id')+"' style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move'>"+$("#mAdvProduct").attr('value')+"</span><span  id='bk"+currbreak+"advname"+all_adv_size+"' style='width:20%; max-width:20%;  display:inline-block; cursor:move' >"+$("#mAdvName option:selected").html()+"</span><span  id='bk"+currbreak+"agency"+all_adv_size+"' style='width:20%; max-width:20%; display:inline-block; cursor:move'>"+$("#mAdvAgency").attr('value')+"</span><span id='timelen' style='width:10%; display:inline-block' align:'center'>"+mins+':'+secs+"</span><span ><a title='ลบชุดโฆษณา' onclick=deleteAdvertise("+currbreak+","+all_adv_size+");><img src='images/delete_2.png' style='width:20px;margin-right:5px;' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+currbreak+","+all_adv_size+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a href='#'><img src='images/pen.png' style='width:20px;margin-right:5px;visibility:hidden' align='right' /></a></span></p></div></li>").insertBefore("#pending");
			
		}else{
			$("<li list_adv='bk"+currbreak+"advseq"+all_adv_size+"'  class='ui-state-default ui-ams-adv' style='width:95%' value='"+$("#mAdvName").attr('value')+"' ><div id='break_"+currbreak+"' class='adv_each_list' style='margin-top: 2px; max-height: 25px; color: blue;'><p><span style='margin-left:3px;' ><img src='images/icon-draggable.png' /></span><span  id='bk"+currbreak+"prod"+all_adv_size+"' class='property_bk' bk_type='"+onair_breaktype+"' spot_price='"+on_calc_price+"' on_discount='"+break_discount+"' bk_info='"+break_info+"' time_bk='"+mAdvTimelen+"' prod_id='"+$("#mAdvProduct").attr('prod_id')+"' agency_id='"+$("#mAdvAgency").attr('agency_id')+"' style='margin-left:3px;width:30%; max-width:30%; display:inline-block;cursor:move'>"+$("#mAdvProduct").attr('value')+"</span><span  id='bk"+currbreak+"advname"+all_adv_size+"' style='width:20%; max-width:20%;  display:inline-block; cursor:move' >"+$("#mAdvName option:selected").html()+"</span><span id='bk"+currbreak+"agency"+all_adv_size+"' style='width:20%; max-width:20%; display:inline-block; cursor:move'>"+$("#mAdvAgency").attr('value')+"</span><span id='timelen' style='width:10%; display:inline-block' align:'center'>"+mins+':'+secs+"</span><span ><a title='ลบชุดโฆษณา' onclick=deleteAdvertise("+currbreak+","+all_adv_size+");><img src='images/delete_2.png' style='width:20px;margin-right:5px;' align='right' /></a></span><span ><a title='แก้ไข'onclick=updateOnair("+currbreak+","+all_adv_size+");><img src='images/pen.png' style='width:20px;margin-right:5px;cursor:pointer;' align='right' /></a></span><span ><a href='#'><img src='images/pen.png' style='width:20px;margin-right:5px;visibility:hidden' align='right' /></a></span></p></div></li>").insertBefore("#break"+nextbreak);
		}
			
		progressupdate();
		//confirm_onair(); //confirm database first
		$(this).dialog("close");
	}
	
</script>

            </div>
        
    </div>
</div>
