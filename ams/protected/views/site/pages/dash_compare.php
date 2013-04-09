<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Compare Dashboard';
$this->breadcrumbs=array(
	'Compare Dashboard',
);
?>
<div class="row-fluid">
	<div align="center">
     		<div class="row-fluid" align="center">
            	<div class="span1" align="left" >
					<label for="dateA_title" style="font-size:1em">A:</label>
                </div>
                <div class="span4" align="left" style="margin-left:2px" >
					<?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'dash_comp_date_A',
							'value'=>date('d/m/Y'), 
							'id'=>'dash_comp_date_A',
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
            	<div class="span1" align="left" >
					<label for="dateA_title" style="font-size:1em">B:</label>
                </div>
                <div class="span4" align="left" style="margin-left:2px" >
					<?php
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'name'=>'dash_comp_date_B',
							'value'=>date('d/m/Y'), 
							'id'=>'dash_comp_date_B',
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
           			<div id="dash_comp_weekday" class="btn-group" data-toggle="buttons-radio">
           			  <button id="dash_comp_day0"   value="" type="button" class="btn btn-success active">All</button>
                      <button id="dash_comp_day1"   value="2" type="button" class="btn btn-success">Mon</button>
                      <button id="dash_comp_day2"   value="3" type="button" class="btn btn-success">Tue</button>
                      <button id="dash_comp_day3"   value="4" type="button" class="btn btn-success">Wed</button>
                      <button id="dash_comp_day4"   value="5" type="button" class="btn btn-success">Thu</button>
                      <button id="dash_comp_day5"   value="6" type="button" class="btn btn-success">Fri</button>
                      <button id="dash_comp_day6"   value="7" type="button" class="btn btn-success">Sat</button>
                      <button id="dash_comp_day7"   value="1" type="button" class="btn btn-success">Sun</button>
                    </div>
                </div>
                <div class="span6" align="center"  style="margin-left:2px">
           			<div class="btn-group" data-toggle="buttons-radio" id="dash_comp_datefilter"  value="30">
                          <!--<button type="button" class="btn btn-info " style=" width:70px" id="dashsum_datefilter_id1">
                          	<span class="dashsum_datefilter_cs"  value="1" id="1day">1 Day</span >
                          </button>-->
                          <button type="button" class="btn  btn-info active" style=" width:80px"  id="dash_comp_datefilter_id1" name="dash_comp_datefilter_id"  value="7" >
                             <span  class="dashsum_datefilter_cs"  value="1" id="1week">1 Week</span >
                          </button>
                          <button type="button" class="btn  btn-info" style=" width:80px"  id="dash_comp_datefilter_id2" name="dash_comp_datefilter_id" value="14" >
                          	<span  class="dashsum_datefilter_cs" value="2" id="2weeks">2 Weeks</span >
                          </button>
                          <button type="button" class="btn btn-info" style=" width:80px"  id="dash_comp_datefilter_id3" name="dash_comp_datefilter_id" value="30" >
                          	<span  class="dashsum_datefilter_cs" value="3" id="1month">1 Month</span >
                          </button>
                          <button type="button" class="btn  btn-info" style=" width:90px"  id="dash_comp_datefilter_id4" name="dash_comp_datefilter_id" value="90" >
                           	<span  class="dashsum_datefilter_cs" value="4" id="3months">3 Months</span >
                      	   </button>
                          <button type="button" class="btn  btn-info" style=" width:90px"  id="dash_comp_datefilter_id5" name="dash_comp_datefilter_id" value="180" >
                          	 <span class="dashsum_datefilter_cs" value="5" id="6months">6 Months</span >
                           </button>
                           <div class="btn-group  " align="left"  id="dash_comp_datefilter_id7"  value="">
                           	  <a class="btn dropdown-toggle  btn-info" data-toggle="dropdown" href="#" id="ybutton_dash_comp"> 
                              <span class="yearname_dashsum">Year</span>
                                <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu">
                                      <li > <a href="#" id="1year" class="year_list_dashsum"  value="1 Year">1 Year</a></li>
                                      <li ><a href="#" id="2years"  class="year_list_dashsum"  value="2 Years">2 Years</a></li>
                                      <li ><a href="#" id="3years"  class="year_list_dashsum"  value="3 Years">3 Years</a></li>
                                      <li ><a href="#" id="4years"  class="year_list_dashsum"  value="4 Years">4 Years</a></li>
                                      <li ><a href="#" id="5years"  class="year_list_dashsum"  value="5 Years">5 Years</a></li>
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
                                            <ul class="nav">
                                              <li id="dash_comp_all" class="active"> <a href="#">All</a> </li>
                                              <li id="dash_comp_prime" ><a href="#">Prime</a></li>
                                              <li id="dash_comp_nonprime" ><a href="#">Non-Prime</a></li>
                                              <li id="dash_comp_unsold_prime" ><a href="#">Unsold Prime</a></li>
                                              <li id="dash_comp_unsold_nonprime" ><a href="#">Unsold Non-Prime</a></li>
                                              <li class="divider-vertical"></li>
                                              <li id="dash_comp_prog_all" ><a href="#">All</a></li>
                                              <li id="dash_comp_prog_bbtv"><a href="#">CH7</a></li>
                                              <li id="dash_comp_prog_tr"><a href="#">Time Rental</a></li>
                                             <!-- <li class="divider-vertical"></li>
                                              
                                              <div class="btn-group" align="left">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   Agency
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul class="dropdown-menu">
                                                          <li > <a href="#">Agency 1</a></li>
                                                          <li ><a href="#">AGency 2</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   Client
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul class="dropdown-menu">
                                                          <li > <a href="#">Client 1</a></li>
                                                          <li ><a href="#">Client 2</a></li>  
                                                  </ul>
                                             </div>-->
                                            </ul>
                                        </div>
                                    </div>
                            	</div>
                         	</div>        
                            <div class="row-fluid">
                                    <div  class="span8" align="center">
								<div  align="center"  id="dash_comp_chart"  class="" style="height:400px;width:700px">
                                </div>
                              </div>
                                    <div  class="span4"  align="left" style="margin-top:80px">
                                <div  align="center"  id="dash_comp_pie"  class="" style="height:300px;width:300px">
                                </div>
                                </div>
                           	</div>
<script>
var dashcomp_data=[];
var dashcomp_A = [];
var dashcomp_B = [];
var dashcomp_pie_A = 0;
var dashcomp_pie_B = 0;

$(document).ready(function(){ 
	chartCompRetrive('2013-03-01','2013-03-01',7,1,1,1,1,'');
	chartCompShow('2013-03-01',"1 day",250);
}); 
function getdata(){
	
};
function checkCompSelection(){  
	var datepk_A = $("#dash_comp_date_A").attr('value').split("/");
	var datepk_B = $("#dash_comp_date_B").attr('value').split("/");
	var date_A = datepk_A[2]+'-'+datepk_A[1]+'-'+datepk_A[0];
	var date_B = datepk_B[2]+'-'+datepk_B[1]+'-'+datepk_B[0];
	
	var soldprime = 1;
	var soldnonprime = 1;
	var unsoldprime =1;
	var unsoldnonprime =1;
	if (!$("#dash_comp_all").hasClass("active")) {
	  soldprime = $("#dash_comp_prime").hasClass("active") ?1 :0;
	  soldnonprime =  $("#dash_comp_nonprime").hasClass("active") ?1 :0;
	  unsoldprime = $("#dash_comp_unsold_prime").hasClass("active") ?1 :0;
	  unsoldnonprime = $("#dash_comp_unsold_nonprime").hasClass("active") ?1 :0;
		
	};

  	var inday = '';
  	 
	 $("#dash_comp_weekday button").each(function(i,el){
	 	if (i != 0){  
	 	 if (   $(el).hasClass("active") ){ 
	 	 	inday += ','+ $(el).attr("value");
	 	 }
	 	}
 	});
 	 if (inday.length > 0){
 	 	inday = inday.substring(1,inday.length);
 	 }
 	 var sample =  parseInt(   $("#dash_comp_datefilter button.active").val() );
 	
 
	chartCompRetrive(date_A,date_B,sample,soldprime,soldnonprime,unsoldprime,unsoldnonprime,inday);
	chartCompShow(date_A,"1 day",250);

};

$("#dash_comp_all").click(function(e){
	$(this).addClass("active");
	if($(this).hasClass("active")){
		$("#dash_comp_prime").removeClass("active");
		$("#dash_comp_nonprime").removeClass("active");
		$("#dash_comp_unsold_prime").removeClass("active");
		$("#dash_comp_unsold_nonprime").removeClass("active");
	}
	 checkCompSelection();
	
});

/* Prime Time Filter*/
$("#dash_comp_prime").click(function(e){ 
	$("#dash_comp_all").removeClass("active");
	$(this).toggleClass("active");
//	$("#dash_comp_prime").removeClass("active");
	//alert("dash_comp_prime");
	checkCompSelection();
});
$("#dash_comp_nonprime").click(function(e){
	$("#dash_comp_all").removeClass("active");
	$(this).toggleClass("active");
	checkCompSelection();
	//alert("dash_comp_nonprime");
});
$("#dash_comp_unsold_prime").click(function(e){
	//$("#dash_sum_sold").addClass("active");
	$("#dash_comp_all").removeClass("active");
	$(this).toggleClass("active");
	checkCompSelection();
});
$("#dash_comp_unsold_nonprime").click(function(e){
	//$("#dash_sum_sold").addClass("active");
	$("#dash_comp_all").removeClass("active");
	$(this).toggleClass("active");
	checkCompSelection();
});
/* Program Owner Filter*/
$("#dash_comp_prog_all").click(function(e){
	$(this).addClass("active");
	if($(this).hasClass("active")){
		$("#dash_comp_prog_bbtv").removeClass("active");
		$("#dash_comp_prog_tr").removeClass("active");
	}
	checkCompSelection();
});

$("#dash_comp_prog_bbtv").click(function(e){
	$("#dash_comp_prog_all").removeClass("active");
	$(this).toggleClass("active");
	checkCompSelection();
});
$("#dash_comp_prog_tr").click(function(e){
	$("#dash_comp_prog_all").removeClass("active");
	$(this).toggleClass("active");
	checkCompSelection();
});

$("#dash_comp_date_A").change(function(){
	
	console.log($(this).attr('value'));
	checkCompSelection();
});

$("#dash_comp_date_B").change(function(){
	
	console.log($(this).attr('value'));
	checkCompSelection();
});
 
 $("#dash_comp_weekday button").each(function(i,el){
 	if (i == 0) {
 		$(el).click(function(e){
 			$("#dash_comp_weekday button").removeClass("active");
 			$(this).addClass("active"); 
  checkCompSelection();
 		});
 	} else{
 		 $(el).click(function(e){
 			$("#dash_comp_weekday button:first").removeClass("active");
			$(this).toggleClass("active"); 
 checkCompSelection();
 		});
 	};

 });
  $("#dash_comp_datefilter button").each(function(i,el){
 	 
 		 $(el).click(function(e){
 				$("#dash_comp_datefilter button").removeClass("active");
 			$(this).addClass("active");
 			checkCompSelection();
 		});
  

 });
 
 

function chartCompRetrive(date_A,date_B,sample,soldprime,soldnonprime,unsoldprime,unsoldnonprime,inday){
	
	
   if(typeof(date_A)==='undefined') date_A = '2013-03-01';
   if(typeof(date_B)==='undefined') date_B = '2013-03-15';
	//date_B = '2013-03-15';
   if(typeof(sample)==='undefined') sample = 7;
   
   
    
	var jsonurl = '?r=dashSum/japi&action=dashCompare&date_A='+date_A+'&date_B='+date_B+'&sample='+sample+'&soldprime='+soldprime
	+'&soldnonprime='+soldnonprime
	+'&unsoldprime='+unsoldprime
	+'&unsoldnonprime='+unsoldnonprime 
	+'&inday='+ inday;
	
	$.ajaxSetup({		
		async: false
	});
	console.log(jsonurl);
	$.ajax(jsonurl,{
		type: 'GET',
		dataType: 'json',
		success: function(data){
			 
			dashcomp_data = data;	
			var cnt_A = 0;
			var cnt_B = 0;
			var idx_A = [];
			var idx_B = [];
			var dashcomp_A_buff = [];
			var dashcomp_B_buff = [];
			    dashcomp_pie_A   = 0;
			  dashcomp_pie_B = 0;
			console.log(Date.parse(date_A).add(cnt_A).day());
			$.each(dashcomp_data.comp_a,function(k,v){ 
				 
				idx_A[k] = v.onairdatetime;
				dashcomp_A_buff[k] =  [k, parseInt(v.spot_time)/60];
				dashcomp_pie_A +=  parseInt(v.spot_time)/60;
			}); 
		
			$.each(dashcomp_data.comp_b,function(k,v){ 
				idx_B[k] = v.onairdatetime;
				dashcomp_B_buff[k] =  [k, parseInt(v.spot_time)/60];
				dashcomp_pie_B +=  parseInt(v.spot_time)/60;
			}); 
			dashcomp_A  =new Array();
			dashcomp_B  =new Array();
			for(i = 0; i<sample; i++){
				//zero_A[i] = [Date.parse(date_A).add(i).toString('d/M/yyyy'),0];
				//zero_B[i] = [Date.parse(date_B).add(i).toString('d/M/yyyy'),0];
//				console.log("tet");
//				console.log(idx_A[i]);
//				console.log(Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'));
//				console.log(idx_A.indexOf(Date.parse(date_A).add(i).day().toString('yyyy-MM-dd')));
//				console.log(idx_B.indexOf(Date.parse(date_B).add(i).day().toString('yyyy-MM-dd')));
				idx = idx_A.indexOf(Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'));
				if( idx != -1){
					console.log (idx);
					dashcomp_A[i] = [Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'),dashcomp_A_buff[idx][1] ];					
				}else{
					dashcomp_A[i] = [Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'),0];
				}
				
				idx = idx_B.indexOf(Date.parse(date_B).add(i).day().toString('yyyy-MM-dd'));
				if( idx != -1){
					console.log (idx);
					dashcomp_B[i] = [Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'),dashcomp_B_buff[idx][1] ];					
				}else{
					dashcomp_B[i] = [Date.parse(date_A).add(i).day().toString('yyyy-MM-dd'),0];
				}

			} 
		 
			console.log(dashcomp_A);
			console.log(dashcomp_B);
//			console.log(dashcomp_B_buff);
		
		}
		
	});
}

function chartCompShow(startdate,interval,valmax){

//		console.log(dashcomp_A);
//		console.log(dashcomp_B);
		$('#dash_comp_chart').empty(); // ---------->  clear chart  ---------------
  var plot2 = $.jqplot('dash_comp_chart', [dashcomp_A,dashcomp_B], {
				title:'Summary Chart', 
	 
	 axes:{
        xaxis:{
          renderer:$.jqplot.DateAxisRenderer,
          tickOptions:{
          		showLabel:false,
            formatString:'%#d %b %y'
          } 
          , autoscale:true
        },
        yaxis:{
        	min:0,
        	 
          tickOptions:{
          
            formatString:''
            }
        }
        , autoscale:true
      },
      highlighter: {
        show: true,
        sizeAdjust: 7.5
      },
		legend: {
			show: true,
		},
		cursor: {
			show: false 
		},
		series: [
			{
				label: 'A' 
        
			},
			{
				label: 'B' 
			}],

      //series:[{lineWidth:4, markerOptions:{style:'square'}}]
  });


		var s1 = [['A',dashcomp_pie_A], ['B',dashcomp_pie_B]];
			 
		var plot8 = $.jqplot('dash_comp_pie', [s1], {
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
                    </div>
				</div>
            </div>
            
	</div>
</div>                