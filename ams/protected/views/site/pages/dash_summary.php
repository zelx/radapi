<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name . ' - Summary Dasboard';
$this->breadcrumbs=array(
	'Sumary Dasboard',
);
?>
<!--<style>
#ui-datepicker-div {
	width:200px;
	font-size:1.3em;
}
    #toolbar {
        padding: 10px 4px;
    }
</style> -->
<div class="row-fluid">
	<div align="center">
     		<div class="row-fluid" align="center">
                <div class="span2" align="left" style="margin-left:2px" >
					<?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'my_date',
							'value'=>date('d/m/Y'), 
							'id'=>'dash_sum_date',
                            'language'=>Yii::app()->language=='et' ? 'et' : null,
                            'options'=>array(
                                'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
                                'showOn'=>'button', // 'focus', 'button', 'both'
                                'buttonText'=>Yii::t('ui','Select form calendar'),
                                'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
                                'buttonImageOnly'=>true,
								 'dateFormat' => 'dd/mm/yy'
                            ),
                            'htmlOptions'=>array(
                                'style'=>'width:120px;vertical-align:top'
                            )
                        ));   
                    ?>	
                </div>

            </div>
            <div class="row-fluid" align="center">
                <div class="span4" align="left" style="margin-left:2px">
           			<div class="btn-group" data-toggle="buttons-radio">
           			  <button id="dashsum_day0" onClick="dateFilterDay(0);" value="1" type="button" class="btn btn-success active">All</button>
                      <button id="dashsum_day1" onClick="dateFilterDay(1);" value="1" type="button" class="btn btn-success">Mon</button>
                      <button id="dashsum_day2" onClick="dateFilterDay(2);" value="1" type="button" class="btn btn-success">Tue</button>
                      <button id="dashsum_day3" onClick="dateFilterDay(3);" value="1" type="button" class="btn btn-success">Wed</button>
                      <button id="dashsum_day4" onClick="dateFilterDay(4);" value="1" type="button" class="btn btn-success">Thu</button>
                      <button id="dashsum_day5" onClick="dateFilterDay(5);" value="1" type="button" class="btn btn-success">Fri</button>
                      <button id="dashsum_day6" onClick="dateFilterDay(6);" value="1" type="button" class="btn btn-success">Sat</button>
                      <button id="dashsum_day7" onClick="dateFilterDay(7);" value="1" type="button" class="btn btn-success">Sun</button>
                    </div>
                </div>
                <div class="span6" align="center"  style="margin-left:2px">
           			<div class="btn-group" data-toggle="buttons-radio" id="dashsum_datefilter"  value="1month">
                          <!--<button type="button" class="btn btn-info " style=" width:70px" id="dashsum_datefilter_id1">
                          	<span class="dashsum_datefilter_cs"  value="1" id="1day">1 Day</span >
                          </button>-->
                          <button type="button" class="btn  btn-info" style=" width:80px"  id="dashsum_datefilter_id1" name="dashsum_datefilter_id" onClick="dateFilter(1);">
                             <span  class="dashsum_datefilter_cs"  value="1" id="1week">1 Week</span >
                          </button>
                          <button type="button" class="btn  btn-info" style=" width:80px"  id="dashsum_datefilter_id2" name="dashsum_datefilter_id" onClick="dateFilter(2);">
                          	<span  class="dashsum_datefilter_cs" value="2" id="2weeks">2 Weeks</span >
                          </button>
                          <button type="button" class="btn btn-info active" style=" width:80px"  id="dashsum_datefilter_id3" name="dashsum_datefilter_id" onClick="dateFilter(3);">
                          	<span  class="dashsum_datefilter_cs" value="3" id="1month">1 Month</span >
                          </button>
                          <button type="button" class="btn  btn-info" style=" width:90px"  id="dashsum_datefilter_id4" name="dashsum_datefilter_id" onClick="dateFilter(4);">
                           	<span  class="dashsum_datefilter_cs" value="4" id="3months">3 Months</span >
                      	   </button>
                          <button type="button" class="btn  btn-info" style=" width:90px"  id="dashsum_datefilter_id5" name="dashsum_datefilter_id" onClick="dateFilter(5);">
                          	 <span class="dashsum_datefilter_cs" value="5" id="6months">6 Months</span >
                           </button>
                           <div class="btn-group  " align="left"  id="dashsum_datefilter_id7"  value="">
                           	  <a class="btn dropdown-toggle  btn-info" data-toggle="dropdown" href="#" id="ybutton_dashsum"> 
                              <span class="yearname_dashsum">Year</span>
                                <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                      <li ><a onClick="dateFilter(6);" href="#" id="1year" class="year_list_dashsum"  value="1 Year">1 Year</a></li>
                                      <li ><a onClick="dateFilter(7);" href="#" id="2years"  class="year_list_dashsum"  value="2 Years">2 Years</a></li>
                                      <li ><a onClick="dateFilter(8);" href="#" id="3years"  class="year_list_dashsum"  value="3 Years">3 Years</a></li>
                                      <li ><a onClick="dateFilter(9);" href="#" id="4years"  class="year_list_dashsum"  value="4 Years">4 Years</a></li>
                                      <li ><a onClick="dateFilter(10);" href="#" id="5years"  class="year_list_dashsum"  value="5 Years">5 Years</a></li>
                              </ul>
                         </div>
                    </div>
                </div>
			</div>
            <div class="row-fluid" style="margin-top:5px">
				<div class="" align="center" >
                	<div class="container"  id="page" style="width:inherit">
                   			<div class="row-fluid">
								<div  align="center" >
                                    <div class="navbar  ">
                                        <div class="navbar-inner">
                                        
                                            <ul id="dash_sum_select" class="nav" data-toggle="buttons-checkbox">
                                              <li class="active" id="dash_sum_all" > <a href="#">All</a> </li>
                                              <li id="dash_sum_prime" ><a href="#">Prime</a></li>
                                              <li id="dash_sum_nonprime" ><a href="#">Non-Prime</a></li>
                                              <li id="dash_sum_unsold" ><a href="#">Unsold</a></li>
                                              <li id="dash_sum_unsoldnonprime" ><a href="#">Unsold Non Prime</a></li>
<!--                                              <li class="divider-vertical"></li>
                                              <li class="active" id="dash_progown_all"><a href="#">All</a></li>
                                              <li id="dash_progown_bbtv" ><a href="#">CH7</a></li>
                                              <li id="dash_progown_tr" ><a href="#">Time Rental</a></li>
                                              <li class="divider-vertical"></li> -->
                                              <!--<div class="btn-group" align="left">
                                                  <a  class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="agency_dashsum" >Agency</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="agency_dashsum_lis" class="dropdown-menu" >
                                                          <li > <a href="#">Agency 1</a></li>
                                                          <li ><a href="#">AGency 2</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" id="prog_dashsum_butt" value=" ">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="prog_dashsum" > Programs</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="program_dashsum_lis"  class="dropdown-menu">
                                                          <li > <a href="#" >Program 1</a></li>
                                                          <li ><a href="#">Program 2</a></li>  
                                                  </ul>
                                             </div>-->
                                            </ul>
                                        </div>
                                    </div>
                            	</div>
                         	</div>        
                            <div class="row-fluid">
                                    <div  class="span8" align="centert" style="margin-top: 60px">
                                        <div  class="" id="chart1" style="height:400px;width:700px" >
                   						
                                        </div>
                                    </div>
                                    <div  class="span4"  align="left" style="margin-top:80px">
                                        <div  class="" id="chart2" style="height:300px;width:300px" >
                   
                                        </div>
                                    </div>
                           	</div>
                                  
                    </div>
				</div>
            </div>
            
	</div>
</div>   
<script>
$(document).ready(function() {
	 all_chart_dashsum(1,1,1,1,1);
	 //all_piechart_dashsum(0,0);
	 dashsum_agency_list();
	 dashsum_prog_list();
});

var dashsum_datefilterday = 0;
var dashsum_datefilter = 3;
function dateFilter(val){
	dashsum_datefilter = val;
	for(var i=1;i<=5;i++){
		$("#dashsum_datefilter_id"+i).removeClass("active");
	}
	$('#dashsum_datefilter_id'+val).addClass("active");
	$(".yearname_dashsum").removeClass("active");
	$(".yearname_dashsum").text( "Year");
	checkselection();
}

function dateFilterDay(val){
	dashsum_datefilterday = val;
	if(val==0){
		for(var i=1;i<=7;i++){
			$("#dashsum_day"+i).removeClass("active");
		}
		$('#dashsum_day'+val).toggleClass("active");
	}else{
		$('#dashsum_day0').removeClass("active");
		console.log($('#dashsum_datefilter_id'+val).hasClass("active"));
		/*if($('#dashsum_datefilter_id'+val).hasClass('active')) {
			$('#dashsum_day'+val).removeClass("active");
		}else{
			$('#dashsum_day'+val).addClass("active");
		}*/
		$('#dashsum_day'+val).toggleClass("active");
	}
	checkselection();
}

function checkselection(){
	var prime = 0;
	var nonprime = 0;
	var unsold = 0;
	var unsoldnonprime = 0;
	var progbbtv = 0;
	var progtr = 0;
	
	if($("#dash_sum_prime").hasClass("active")){
		prime = 1;
	}
	if($("#dash_sum_nonprime").hasClass("active")){
		nonprime = 1;
	}
	if($("#dash_sum_unsold").hasClass("active")){
		unsold = 1;
	}
	if($("#dash_sum_unsoldnonprime").hasClass("active")){
		unsoldnonprime = 1;
	}
	if($("#prog_own_bbtv").hasClass("active")){
		progbbtv = 1;
	}
	if($("#prog_own_tr").hasClass("active")){
		progtr = 1;
	}
	
	if($("#dash_sum_all").hasClass("active")){
		prime = 1;
		nonprime = 1;
		unsold = 1;
		unsoldnonprime = 1;
	}
	
	all_chart_dashsum(0,prime,nonprime,unsold,unsoldnonprime,progbbtv,progtr);

}

$("#dash_sum_all").click(function(e){
	$(this).addClass("active");
	if($(this).hasClass("active")){
		$("#dash_sum_prime").removeClass("active");
		$("#dash_sum_nonprime").removeClass("active");
		$("#dash_sum_unsold").removeClass("active");
		$("#dash_sum_unsoldnonprime").removeClass("active");
	}
	checkselection();
	
});

/* Prime Time Filter*/
$("#dash_sum_prime").click(function(e){
	$("#dash_sum_all").removeClass("active");
	$(this).toggleClass("active");
//	$("#dash_sum_prime").removeClass("active");
	//alert("dash_sum_prime");
	checkselection();
});
$("#dash_sum_nonprime").click(function(e){
	$("#dash_sum_all").removeClass("active");
	$(this).toggleClass("active");
	checkselection();
	//alert("dash_sum_nonprime");
});
$("#dash_sum_unsold").click(function(e){
	//$("#dash_sum_sold").addClass("active");
	$("#dash_sum_all").removeClass("active");
	$(this).toggleClass("active");
	checkselection();
});
$("#dash_sum_unsoldnonprime").click(function(e){
	//$("#dash_sum_sold").addClass("active");
	$("#dash_sum_all").removeClass("active");
	$(this).toggleClass("active");
	checkselection();
});

/* Program Owner Filter*/
$("#dash_progown_all").click(function(e){
	$(this).addClass("active");
	if($(this).hasClass("active")){
		$("#dash_progown_bbtv").removeClass("active");
		$("#dash_progown_tr").removeClass("active");
	}
	checkselection();
});

$("#dash_progown_bbtv").click(function(e){
	$("#dash_progown_all").removeClass("active");
	$(this).toggleClass("active");
	checkselection();
});
$("#dash_progown_tr").click(function(e){
	$("#dash_progown_all").removeClass("active");
	$(this).toggleClass("active");
	checkselection();
});

$(".dashsum_datefilter_cs").click(function(e){
	$("#dashsum_datefilter").find("button.active").removeClass("active");
	$("#dashsum_datefilter_id7").find("a#ybutton_dashsum").removeClass("active");
	$(".yearname_dashsum").text( "Year");
	$("#dashsum_datefilter_id7").attr('value', " ");
	$("#dashsum_datefilter").find("button#dashsum_datefilter_id"+$(this).attr('value')).addClass("btn btn-info active");
	$("#dashsum_datefilter").attr('value', $(this).attr('id'));
	
	/*if($("#prog_dashsum_butt").attr('value')  != " "){
		progchart_dashsum($("#prog_dashsum_butt").attr('value'))
	}else {*/
		checkselection();
	//}
});

$(".year_list_dashsum").click(function(e){
	$("#dashsum_datefilter").find("button.active").removeClass("active");
	$("#dashsum_datefilter_id7").find("a#ybutton_dashsum").addClass("btn dropdown-toggle  btn-info active");
	$(".yearname_dashsum").text( $(this).attr('value'));
	$("#dashsum_datefilter").attr('value', $(this).attr('id'));
	
	/*if($("#prog_dashsum_butt").attr('value')  != " "){
		progchart_dashsum($("#prog_dashsum_butt").attr('value') )
	}else {*/
		checkselection();
	//}	
} );

$("#dash_sum_date").change(function(){
	
	//if($("#prog_dashsum_butt").attr('value')  != " "){
	//	progchart_dashsum($("#prog_dashsum_butt").attr('value') )
	//}else {
		checkselection();
	//}
});

function dashsum_prog_list() {
		$.ajax('?r=onair/japi&action=progList',{
			type: 'GET',
			dataType: 'json',
			success: function(progList){
			$("#program_dashsum_lis li").remove();			
				$.each(progList,function(k,v){ 
					$.each(v,function(kon,von){	
					var prog_name = von.prog_name;
					prog_name = prog_name.toString() ;
					$("#program_dashsum_lis").append(			
							 "<li >"+
								 "<a id='dashsum_prog_id"+von.prog_id+"' value="+prog_name+"  onclick=select_prog_dashsum("+von.prog_id+"); >"+prog_name+"</a>"+
							 "</li>"
					);
				 });		
				});
			 }
		});
}

function dashsum_agency_list() {
		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agenList){
			$("#agency_dashsum_lis li").remove();			
				$.each(agenList,function(k,v){ 
				var agen_name = v.agency_name;
				agen_name = agen_name.toString() ;
					$("#agency_dashsum_lis").append( 
							 "<li >"+
								 "<a id='dashsum_agen_id"+v.agency_id+"' value="+agen_name+"  onclick=select_agency_dashsum("+v.agency_id+"); >"+agen_name+"</a>"+
							 "</li>"
					);			
				});
			 }
		});
}

function select_prog_dashsum(prog_id){
var prog_name = 0;
prog_name = $("#dashsum_prog_id"+prog_id).attr('value')
$("#prog_dashsum").text(prog_name);
 progchart_dashsum(prog_id);
} 

function select_agency_dashsum(agency_id){
var agency_name = 0;
agency_name = $("#dashsum_agen_id"+agency_id).attr('value')
$("#agency_dashsum").text(agency_name);
} 

function oneday_dashsum(start_date){
	var next_date = 0;
	var min_max_scale =[];

	start_date = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate());
	next_day = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+1);
	min_date = (parseInt(start_date.getMonth())+1)+" "+start_date.getDate()+","+start_date.getFullYear()+" "+"00:00";  
	max_date = (parseInt(next_day.getMonth())+1)+" "+next_day.getDate()+","+next_day.getFullYear()+" "+"00:00"; 
	min_max_scale = [min_date,max_date,"2 hours"];	
	return (min_max_scale);	
}

function oneweek_dashsum( start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
		for(var coun_min= 0;  count_min < 8;  count_min++){
				prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
				if(prevday.getDay() == 1 ){
					break;
				}
		}
		for(var count_max = 0; count_max < 8;  count_max++){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
				if(nextday.getDay() == 0){
					break;
				} 
		}
		var dsdate = $("#dash_sum_date").attr('value').split("/");
		var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
		var startdate = AddDay(datestr,0,1);
		var enddate = AddDay(startdate,7,1);
		//min_date = (parseInt(prevday.getMonth())+1)+" "+prevday.getDate()+","+prevday.getFullYear();  
		//max_date = (parseInt(nextday.getMonth())+1)+" "+nextday.getDate()+","+nextday.getFullYear(); 	
		min_max_scale = [startdate, enddate, "1 day"];
	
	return (min_max_scale);		
}

function twoweek_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
		for(var coun_min= 0;  count_min < 8;  count_min++){
				prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-(count_min+7));
				if(prevday.getDay() == 1 ){
					break;
				}
		}
		for(var count_max = 0; count_max < 8;  count_max++){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+(count_max));
				if(nextday.getDay() == 0){
					break;
				} 
		}
		var dsdate = $("#dash_sum_date").attr('value').split("/");
		var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
		var startdate = AddDay(datestr,0,1);
		var enddate = AddDay(startdate,14,1);
		//min_date = (parseInt(prevday.getMonth())+1)+" "+prevday.getDate()+","+prevday.getFullYear();  
		//max_date = (parseInt(nextday.getMonth())+1)+" "+nextday.getDate()+","+nextday.getFullYear(); 	
		min_max_scale = [startdate, enddate, "2 days"];
	
	return (min_max_scale);		
}

function onemonth_dashsum( start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,30,1);
	
	//min_date = (parseInt(prevday.getMonth())+1)+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+1)+" "+nextday.getDate()+","+nextday.getFullYear(); 	
	//min_date = (parseInt(startdate.getMonth())+1)+" "+startdate.getDate()+","+startdate.getFullYear();  
	//max_date = (parseInt(enddate.getMonth())+1)+" "+enddate.getDate()+","+enddate.getFullYear(); 	
	min_max_scale = [startdate, enddate, "5 days"];
	
	return (min_max_scale);
};	

function threemonth_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,90,1);
	//min_date = (parseInt(prevday.getMonth())+1)+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+3)+" "+nextday.getDate()+","+nextday.getFullYear(); 	
	min_max_scale = [startdate, enddate, "15 days"];	
	
	return (min_max_scale);
};	

function sixmonth_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,180,1);
	//min_date = (parseInt(prevday.getMonth())+1)+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+6)+" "+nextday.getDate()+","+nextday.getFullYear(); 	
	min_max_scale = [startdate, enddate, "30 days"];	
	
	return (min_max_scale);
};	
function oneyear_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,365,1);
	//min_date = (parseInt(prevday.getMonth()))+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+12)+" "+nextday.getDate()+","+parseInt(nextday.getFullYear()+1); 	
	min_max_scale = [startdate, enddate, "60 days"];
	return (min_max_scale);	
}

function  twoyear_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,730,1);
	//min_date = (parseInt(prevday.getMonth()))+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+12)+" "+nextday.getDate()+","+parseInt(nextday.getFullYear()+2); 	
	min_max_scale = [startdate,enddate,"4 months"];	
	return (min_max_scale);	
}

function  threeyear_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,1095,1);
	//min_date = (parseInt(prevday.getMonth()))+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+12)+" "+nextday.getDate()+","+parseInt(nextday.getFullYear()+3); 	
	min_max_scale = [startdate,enddate,"6 months"];	
	return (min_max_scale);	
}

function  fouryear_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,1460,1);
	//min_date = (parseInt(prevday.getMonth()))+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+12)+" "+nextday.getDate()+","+parseInt(nextday.getFullYear()+4); 	
	min_max_scale = [startdate,enddate,"8 months"];	
	return (min_max_scale);	
}

function  fiveyear_dashsum(start_date){
	var min_date = 0;
	var max_date = 0;
	var nextday =  0;
	var prevday = 0;
	var count_min = 0;
	var count_max = 0;
	var min_max_scale = [];
	
	for(var coun_min= 0;  count_min < 32;  count_min++){
			prevday  = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()-count_min);
			if(prevday.getMonth() != start_date.getMonth()){
				prevday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()-count_min)+1);
				break;
			} 
	} 
	prevday  = new Date(prevday.getFullYear(), (parseInt(prevday.getMonth())), 1);
	
	for(var count_max= 0;  count_max < 32;  count_max++){
			nextday = new Date(start_date.getFullYear(), start_date.getMonth(), start_date.getDate()+count_max);
			if(nextday.getMonth() != start_date.getMonth()){
				nextday = new Date(start_date.getFullYear(), start_date.getMonth(), (start_date.getDate()+count_max)-1);
				break;
			}  
	} 
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	var datestr = dsdate[2]+'-'+dsdate[1]+'-'+dsdate[0];
	var startdate = AddDay(datestr,0,1);
	var enddate = AddDay(startdate,1825,1);
	//min_date = (parseInt(prevday.getMonth()))+" "+prevday.getDate()+","+prevday.getFullYear();  
	//max_date = (parseInt(nextday.getMonth())+12)+" "+nextday.getDate()+","+parseInt(nextday.getFullYear()+5); 	
	min_max_scale = [startdate,enddate,"10 months"];	
	return (min_max_scale);	
}

function datescale_dashsum(){
	
	var start_date = 0;
	var min_max_scale =[];
	var dsdate = $("#dash_sum_date").attr('value').split("/");
	console.log("test date");
	console.log(dsdate[1]+'/'+dsdate[0]+'/'+dsdate[2]);
//	var dash_sum_date = new Date(dsdate[1]+'/'+dsdate[0]+'/'+dsdate[2]);

	start_date = new Date(dsdate[1]+'/'+dsdate[0]+'/'+dsdate[2]);	
	if(start_date == "Invalid Date"){
		start_date  =  new Date();
	}
	//switch($("#dashsum_datefilter").attr('value')){
		switch(dashsum_datefilter){
		case 1:
			  min_max_scale =  oneweek_dashsum(start_date);
		  break;
		case 2:
			  min_max_scale = twoweek_dashsum(start_date);
		  break;
		 case 3:
			  min_max_scale =  onemonth_dashsum(start_date);	
		  break;
		case 4:
			  min_max_scale = threemonth_dashsum(start_date);	
		  break;
		case 5:
			  min_max_scale = sixmonth_dashsum(start_date);	
		  break;
		 case 6:
			  min_max_scale = oneyear_dashsum(start_date);	
			// console.log(1+"year");
		  break;
		case 7:
			  min_max_scale = twoyear_dashsum(start_date);	
			 // console.log(2+"year");
		  break;
		case 8:
			  min_max_scale = threeyear_dashsum(start_date);	
			  //console.log(3+"year");
		  break;
		case 9:
			  min_max_scale = fouryear_dashsum(start_date);	
			  //console.log(4+"year");
		  break;
		case 10:
			  min_max_scale = fiveyear_dashsum(start_date);	
			  //console.log(5+"year");
		  break;
		default:
			  min_max_scale = onemonth_dashsum(start_date);	
		 // console.log("prog_id="+min_date+"date_define="+max_date+"step_date"+step_date);
	}
	
	console.log(dashsum_datefilter+' min_max_scale : '+min_max_scale);
	return (min_max_scale);
}


function deter_maximum_value(plot_array){
	
	var array_length = plot_array.length;
	var maxY = -Infinity;
	for(var i = 0; i < array_length; i++){
		maxY = Math.max(plot_array[i][1], maxY);
	}
	
	return(maxY);
}

function deter_minimum_value(plot_array){
	
	var array_length = plot_array.length;
	var minY = -Infinity;
	for(var i = 0; i < array_length; i++){
		minY = Math.min(plot_array[i][1], minY);
	}
	
	return(minY);
}

function progchart_dashsum(prog_id){
	
	var jsonurl = '?r=dashSum/japi&action=progsum&prog_id='+prog_id+' ';								  	

	var  spottime_prog_indiv = [];
	
	var intervalY = 0;
	var scaleY = 10;
	var maxY = 0;
	var minY = 0;
	
	var min_mag = 0;
	var max_mag = 0;
	var maxData = 0;
	var minData = 0;
	var min_max_scale =[];
	
	var min_date = 0;
	var max_date = 0;
	var step_date = 0;
	
	var spot_prog = 0;
	
	min_max_scale = datescale_dashsum();
 	min_date = min_max_scale[0];
 	max_date = min_max_scale[1];
	step_date = min_max_scale[2];
	//console.log("min max date");
	//console.log(min_date);
	//console.log(min_date);
	
	$("#prog_dashsum_butt").attr('value',prog_id);  // ---------->  define program  id---------------
	
	//$.getJSON(jsonurl, function(data){ 
	
	$.ajaxSetup({		
		async: false
	});
	$.ajax(jsonurl,{
		type: 'GET',
		dataType: 'json',
		success: function(data){
	
		$.each(data,function(k,v){ 
		
			spot_prog = parseInt(v.spot_time)/60;
			spottime_prog_indiv[k] =  [v.datetime,spot_prog]; // Spot Time at Individual Program 
			
		}); 
		
		/*
		
		console.log(spottime_prog_indiv);
		
		// Calculate Y axis parameter 
		
		maxData = deter_maximum_value();
		minData = deter_minimum_value();
		intervalY = parseInt(maxData)/parseInt(scaleY);
		maxY = parseInt(maxData)+ parseInt(intervalY);
		minY = parseInt(minData)- parseInt(intervalY);
		
		console.log("maxY= "+maxY+"minY= "+minY);
		// End Calculate Y axis parameter
		
		*/

			//$('#chart1').replot({resetAxes:true});
			$('#chart1').empty(); // ---------->  clear chart  ---------------
			
			var plot3= $.jqplot('chart1', [spottime_prog_indiv], { 
					title:'Summary Chart', 
					gridPadding:{right:35},
					axes:{
						xaxis:{
							renderer:$.jqplot.DateAxisRenderer, 
							tickOptions:{formatString:'%b %#d, %y'},
							min:min_date, 
							max:max_date, 
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickInterval:step_date,
						},
						yaxis: {
							min:0,
							max:100,
							tickInterval:'10',
							tickRenderer: $.jqplot.CanvasAxisTickRenderer,
						}
					},
					highlighter: {
						
						show: true,
						sizeAdjust: 7.5
						
					},
					cursor: {
						
						show: true,
						tooltipLocation:'sw'
								
					},	
					series:[{lineWidth:1}],
					 //legend:{show:true, location:'se'}
					 
			});		
	
		}
		
	});  
	
}

function all_chart_dashsum(dashsum_all,dashsum_prime,dashsum_nonprime,dashsum_unsold,dashsum_unsoldnonprime,progown_bbtv,progown_tr){
	
	console.log('all_chart_dashsum init ==>'+dashsum_prime+','+dashsum_nonprime+','+dashsum_unsold+','+dashsum_unsoldnonprime);
	var min_date = 0;
	var max_date = 0;
	var step_date = 0;
	
	var spot_prog = 0;
	
	min_max_scale = datescale_dashsum();
 	min_date = min_max_scale[0];
 	max_date = min_max_scale[1];
	step_date = min_max_scale[2];
	//console.log("min max date");
	console.log('min_date '+min_date);
	console.log('max_date '+max_date);
	//console.log(step_date);
	var sum_start_date = new Date(min_date);
	var sum_end_date = new Date(max_date);
	//console.log(sum_start_date);
	//console.log(sum_end_date);
	var dsdate = $("#dash_sum_date").attr('value').split("/");
/*	console.log("test date");
	console.log(dsdate[1]+'/'+dsdate[0]+'/'+dsdate[2]);
*/	var dash_sum_date = new Date(dsdate[1]+'/'+dsdate[0]+'/'+dsdate[2]);
	console.log(dash_sum_date);
	
	var query_day = 0;
	if(dashsum_datefilter==1) query_day = 7;
	else if(dashsum_datefilter==2) query_day = 14;
	else if(dashsum_datefilter==3) query_day = 30;
	else if(dashsum_datefilter==4) query_day = 90;
	else if(dashsum_datefilter==5) query_day = 180;
	else if(dashsum_datefilter==6) query_day = 365;
	else if(dashsum_datefilter==7) query_day = 730;
	else if(dashsum_datefilter==8) query_day = 1095;
	else if(dashsum_datefilter==9) query_day = 1460;
	else if(dashsum_datefilter==10) query_day = 1825;

	var year = dash_sum_date.getFullYear();
	var month = (dash_sum_date.getMonth()+1);
	var day = dash_sum_date.getDate();
	var jsonurl = '?r=dashSum/japi&action=dashSummery&year='+year+'&month='+month+'&day='+day+'&progbbtv='+progown_bbtv+'&progtr='+progown_tr+'&period='+query_day;
	var price = [];
	var  price_allbreak = [];
	var  price_allsold = [];
	var  price_prime = [];
	var  price_nonprime = [];
	var  price_unsold = [];
	var  price_unsoldprime = [];
	var  price_zero = [];
	var  prime_sum = 0;
	var  nonprime_sum = 0;
	//$.getJSON(jsonurl, function(data){ 
	
	$.ajaxSetup({		
		async: false
	});
	$.ajax(jsonurl,{
		type: 'GET',
		dataType: 'json',
		success: function(data){	
/*			$.each(data.allsold,function(k,v){ 
				price_allsold [k] =  [v.onairdatetime, parseInt(v.spot_time)/60];
			}); */

			$.each(data.allsold,function(k,v){ 
				
				//spot_prog = parseInt(v.spot_time)/60;
				price_allbreak [k] =  [v.onairdatetime,parseInt(v.spot_time)/60];
				price_allsold[k] = [v.onairdatetime,0];
				price_unsold[k] = [v.onairdatetime,0];
				price_unsoldprime[k] = [v.onairdatetime,0];
				price_prime[k] = [v.onairdatetime,0];
				price_nonprime[k] = [v.onairdatetime,0];
				price_zero[k] = [v.onairdatetime,0];
				
				$.each(data.allsold,function(sold_k,sold_v){
					
					if(v.onairdatetime == sold_v.onairdatetime){
						price_allsold[k] =  [v.onairdatetime, parseInt(sold_v.spot_time)/60];
						price_unsold[k] = [sold_v.onairdatetime,((parseInt(v.spot_time)/60) - (parseInt(sold_v.spot_time)/60))];
						//console.log("unsold:");
						//console.log(price_allunsold[k]);
					}
				});
				//console.log(price_allbreak [k]); 
			}); 
			
			for (i=0; i < price_prime.length; i++){
				$.each(data.soldprime,function(k,v){ 
					//spot_prog = parseInt(v.spot_time)/60;
					if (price_prime[i][0] == v.onairdatetime){
						price_prime[i] =  [v.onairdatetime, parseInt(v.spot_time)/60];
						//console.log(parseInt(v.spot_time)/60);
					}
				}); 
				
				var datesplit = price_prime[i][0].split("-");
				var datdate=parseInt(datesplit[1])+" "+parseInt(datesplit[2])+","+datesplit[0]+'';
				///console.log(datdate);
				var datetestt = new Date(datdate);
				if((datetestt >= sum_start_date ) && (datetestt <= sum_end_date)){
					//console.log("inrenge");
					//console.log(datetestt);
					prime_sum = prime_sum + price_prime[i][1];
				}
				//console.log(price_prime[i][0],price_prime[i][1]);
			}
			//console.log(prime_sum);
			
			for (i=0; i < price_nonprime.length; i++){
				$.each(data.soldnonprime,function(k,v){ 
					//spot_prog = parseInt(v.spot_time)/60;
					if (price_nonprime[i][0] == v.onairdatetime)
						price_nonprime [i] =  [v.onairdatetime, parseInt(v.spot_time)/60];
					
				}); 
				//console.log(price_nonprime[i][0],price_nonprime[i][1]);
					var datesplit = price_nonprime[i][0].split("-");
					var datdate=parseInt(datesplit[1])+" "+parseInt(datesplit[2])+","+datesplit[0]+'';
					//console.log(datdate);
					var datetestt = new Date(datdate);
					//console.log("datetestt");
					//console.log(datetestt);
					if((datetestt >= sum_start_date ) && (datetestt <= sum_end_date)){
						//console.log("inrenge");
						//console.log(datetestt);
						nonprime_sum = nonprime_sum + price_nonprime[i][1];
					}
			}
	
			var total = prime_sum + nonprime_sum;
			var prime_perct = (prime_sum / total)*100;
			var nonprime_perct = (nonprime_sum / total)*100;
			//console.log(prime_perct);
			console.log(nonprime_perct);
			//all_piechart_dashsum(prime_perct,nonprime_perct);
			var advtimechart = [];
				
			if(dashsum_prime || dashsum_nonprime || dashsum_unsold){
				//advtimechart = [price_allbreak,price_prime,price_prime,price_nonprime];
				if(dashsum_prime)
					advtimechart.push(price_prime);
				else
					advtimechart.push(price_zero);

				if(dashsum_nonprime)
					advtimechart.push(price_nonprime);
				else
					advtimechart.push(price_zero);

				if(dashsum_unsold)
					advtimechart.push(price_unsold);
				else
					advtimechart.push(price_zero);
				
				
			}else{
				advtimechart.push(price_prime);
				advtimechart.push(price_nonprime);
				advtimechart.push(price_unsold);
				advtimechart.push(price_unsoldprime);
			}
			//console.log(price_prime);
			
			/*console.log('sinj price_prime '+price_prime);
			console.log('sinj price_nonprime '+price_nonprime);
			console.log('sinj price_unsold '+price_unsold);
			console.log('sinj price_unsoldprime '+price_unsoldprime);*/
			
			
			
			/* Customizestart Here */
			var  new_allsold = [];
			var  new_price_prime = [];
			var  new_price_nonprime = [];
			var  new_price_unsoldprime = [];
			var  new_price_unsoldnoneprime = [];
			var max_param = 0;
			
			//for(var i=1;i<=daysInMonth(month,year);i++){
			var tmp_date = "";
			for(var i=0;i<=query_day+1;i++){
				var dateStr = year+','+month+','+day;
				tmp_date = AddDay(dateStr,i,0);
				new_allsold[i] = tmp_date;
				new_price_prime[i] = [tmp_date,0];
				new_price_nonprime[i] = [tmp_date,0];
				new_price_unsoldprime[i] = [tmp_date,0];
				new_price_unsoldnoneprime[i] = [tmp_date,0];
			}
			
			/*$.each(data.allsold,function(k,v){ 
				new_allsold[k] = [v.onairdatetime,parseInt(v.spot_time)/60];
				new_price_prime[k] = [v.onairdatetime,0];
				new_price_nonprime[k] = [v.onairdatetime,0];
				new_price_unsoldprime[k] = [v.onairdatetime,0];
				new_price_unsoldnoneprime[k] = [v.onairdatetime,0];
			});*/
			
			var sum_soldprime = 0;
			var sum_soldnonprime = 0;
			$.each(data.soldprime,function(k,v){ 
				for (i=0; i < new_allsold.length; i++){
					//$.each(new_allsold[i],function(k2,v2){ 
						//console.log(v.onairdatetime+' '+new_allsold[i]);
						if(v.onairdatetime==new_allsold[i])
						{
							new_price_prime[i] = [v.onairdatetime,parseInt(v.spot_time)/60];
							sum_soldprime += parseInt(v.spot_time);
						}
					//});
				}
				if(max_param<parseInt(v.spot_time)/60)
					max_param = parseInt(v.spot_time)/60; 
			}); 
			
			$.each(data.soldnonprime,function(k,v){ 
				for (i=0; i < new_allsold.length; i++){
					if(v.onairdatetime==new_allsold[i])
					{
						new_price_nonprime[i] = [v.onairdatetime,parseInt(v.spot_time)/60];
						sum_soldnonprime += parseInt(v.spot_time);
					}
				}
				if(max_param<parseInt(v.spot_time)/60)
					max_param = parseInt(v.spot_time)/60;
			}); 
			
			all_piechart_dashsum(sum_soldprime,sum_soldnonprime);
			
			$.each(data.breakprime,function(k,v){ 
				for (i=0; i < new_allsold.length; i++){
					if(v.onairdatetime==new_allsold[i])
					{
						$.each(new_price_prime[i],function(k3,v3){ 
							new_price_unsoldprime[i] = [v.onairdatetime,parseInt(parseInt(v.spot_time)/60 - v3)];
							if(max_param<parseInt(v.spot_time)/60){
								max_param = parseInt(v.spot_time)/60;
							}
						});
					}
				}
				if(max_param<parseInt(v.spot_time)/60)
					max_param = parseInt(v.spot_time)/60;
			});
			
			$.each(data.breaknonprime,function(k,v){ 
				for (i=0; i < new_allsold.length; i++){
					if(v.onairdatetime==new_allsold[i])
					{
						$.each(new_price_nonprime[i],function(k3,v3){ 
							new_price_unsoldnoneprime[i] = [v.onairdatetime,parseInt(parseInt(v.spot_time)/60 - v3)];
							if(max_param<parseInt(v.spot_time)/60){
								max_param = parseInt(v.spot_time)/60;
							}
						});
					}
				}
			}); 
			max_param = parseInt(max_param);
			if(max_param==0)
				max_param = 10;
			 
			console.log('-----------------'+max_param+'---------------');
			//console.log('sinj new_allsold '+new_allsold);
			console.log('sinj new_price_prime '+new_price_prime);
			console.log('sinj new_price_nonprime '+new_price_nonprime);
			console.log('sinj new_price_unsoldprime '+new_price_unsoldprime);
			console.log('sinj new_price_unsoldnoneprime '+new_price_unsoldnoneprime);
			
			//Filter data by Days
			var all_zero = 1;
			for(var i=0;i<=query_day+1;i++){
				all_zero = 1;
				t_date = new Date(Date.parse(new_allsold[i]));
				if(!$("#dashsum_day1").hasClass("active")){
					if(t_date.getDay()==1)
						all_zero = 0;
				}
				if(!$("#dashsum_day2").hasClass("active")){
					if(t_date.getDay()==2)
						all_zero = 0;
				}
				if(!$("#dashsum_day3").hasClass("active")){
					if(t_date.getDay()==3)
						all_zero = 0;
				}
				if(!$("#dashsum_day4").hasClass("active")){
					if(t_date.getDay()==4)
						all_zero = 0;
				}
				if(!$("#dashsum_day5").hasClass("active")){
					if(t_date.getDay()==5)
						all_zero = 0;
				}
				if(!$("#dashsum_day6").hasClass("active")){
					if(t_date.getDay()==6)
						all_zero = 0;
				}
				if(!$("#dashsum_day7").hasClass("active")){
					if(t_date.getDay()==0)
						all_zero = 0;
				}
				
				if($("#dashsum_day0").hasClass("active")){
					all_zero = 1;
				}
				
				if(all_zero==0){
					new_price_prime[i] = [new_allsold[i],0];
					new_price_nonprime[i] = [new_allsold[i],0];
					new_price_unsoldprime[i] = [new_allsold[i],0];
					new_price_unsoldnoneprime[i] = [new_allsold[i],0];
				}
			}
			
			var lebel = [];
			
			var new_advtimechart = [];
			if(dashsum_prime){ 
				lebel.push("Prime"); 
				new_advtimechart.push(new_price_prime);
			}
			//else new_advtimechart.push(price_zero);
			
			if(dashsum_nonprime){ 
				lebel.push("Non Prime");
				new_advtimechart.push(new_price_nonprime);
			}
			//else new_advtimechart.push(price_zero);
			
			if(dashsum_unsold){ 
				lebel.push("Unsold Prime");
				new_advtimechart.push(new_price_unsoldprime);
			}
			//else new_advtimechart.push(price_zero);
			
			if(dashsum_unsoldnonprime){ 
				lebel.push("Unsold Non Prim");
				new_advtimechart.push(new_price_unsoldnoneprime);
			}
			//else new_advtimechart.push(price_zero);
			
			console.log('new_advtimechart '+new_advtimechart);
			
			$('#chart1').empty(); // ---------->  clear chart  ---------------
			var plot3= $.jqplot('chart1', new_advtimechart, {
				title:'Summary Chart', 
				stackSeries: true,
				gridPadding:{right:35},
				seriesDefaults: {
        		   fill: true
				},
				legend: {
						show: true,
					},
				axes:{
					xaxis:{
								renderer:$.jqplot.DateAxisRenderer, 
								tickOptions:{formatString:'%b %#d, %y'},
								min:min_date, 
								max:max_date, 
								labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
								tickInterval:step_date,
					},
					 yaxis: {
								min:0,
								max:max_param + 5 ,
								tickInterval: null,
								tickRenderer: $.jqplot.CanvasAxisTickRenderer,
					}
				},
				highlighter: {
					show: true,
					sizeAdjust: 7.5
				},
				cursor: {
					show: true,
					tooltipLocation:'sw'
				},
														  //series:[{lineWidth:2, markerOptions:{style:'cirlcle'}}]
				series: [
						{
							label: lebel[0]
						},
						{
							label: lebel[1]
						},
						{
							label: lebel[2]
						},
						{
							label: lebel[3]
						}
					],
														 // legend:{show:true, location:'ne',xoffset: 12,yoffset: 12,}
			});		
		
		}
		
	});
// ----------> Line graph plotting	
}

function daysInMonth(month,year) {
    return new Date(year, month, 0).getDate();
}

// AddDay function (format YYY-MM-DD)
function AddDay(strDate,intNum,caseSel)
{
	sdate =  new Date(strDate);
	sdate.setDate(sdate.getDate()+intNum);
	var month_plus = sdate.getMonth()+1;
	var date_plus = sdate.getDate();
	
		
	if(caseSel==1){
		return month_plus+" "+ sdate.getDate() + "," + sdate.getFullYear();
	}else{
		if(month_plus<10) 
			month_plus = '0'+month_plus;
		if(date_plus<10)
			date_plus = '0'+sdate.getDate();
		return sdate.getFullYear()+"-"+ month_plus + "-" + date_plus;	
	}
}



function all_piechart_dashsum(prime,nonprime){
	var min_date = 0;
	var max_date = 0;
	var step_date = 0;
	
	console.log('prime '+prime);
	console.log('nonprime '+nonprime);
	
	var spot_prog = 0;
	min_max_scale = datescale_dashsum();
 	min_date = min_max_scale[0];
 	max_date = min_max_scale[1];
	step_date = min_max_scale[2];	
	var s1 = [['Prime',prime], ['Non-Prime',nonprime]];
	//console.log("prime test");
	//console.log(s1);
	$('#chart2').empty();
	var plot8 = $.jqplot('chart2', [s1], {
		grid: {
			drawBorder: false, 
			drawGridlines: false,
			background: '#ffffff',
			shadow:false
		},
		axesDefaults: {
			 
		},
		seriesDefaults:{
			renderer:$.jqplot.PieRenderer,
			rendererOptions: {
				showDataLabels: true
			}
		},
		legend: {
			show: true,
			rendererOptions: {
				numberRows: 1
			},
			location: 's'
		}
	}); 	
	
}

</script>             