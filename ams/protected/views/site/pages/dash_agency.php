<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Agency Dashboard';
$this->breadcrumbs=array(
	'Agency Dashboard',
);
?>
<!--<style>
#ui-datepicker-div {
	width:200px;
	font-size:0.3em;
}
    #toolbar {
        padding: 10px 4px;
    }
</style> -->
<div class="row-fluid">
	<div align="center">
            <div class="row-fluid" style="margin-top:10px">
				<div class="" align="center" >
                	<div class="container"  id="page" style="width:inherit">
                			<div class="row-fluid">
                				<div class="span5" style="float:left">
									<div class="span3" align="left" >
										<label for="dateA_title" style="font-size:1em">From:</label>
					                </div>
					                <div class="span9" align="left" style="margin-left:2px" >
										<?php
					                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					                            'name'=>'dash_agency_date_from',
												'value'=>date('01/m/Y'), 
												'id'=>'dash_agency_date_from',
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
				                <div class="span5" style="float:left">
									<div class="span3" align="left" >
										<label for="dateA_title" style="font-size:1em">To:</label>
					                </div>
					                <div class="span9" align="left" style="margin-left:2px" >
										<?php
					                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
					                            'name'=>'dash_agency_date_to',
												'value'=>date('t/m/Y'), 
												'id'=>'dash_agency_date_to',
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
				                
							</div>
                   			<div class="row-fluid">
								<div  align="center" >
                                    <div class="navbar "  >
                                        <div class="navbar-inner ">
                                            <ul class="nav">
                                              <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanagency1" > Agencys</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listagency1" class="dropdown-menu">
                                                          <li > <a href="#">Agency A</a></li>
                                                          <li ><a href="#">Agency B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanagency2" > Agencys</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listagency2" class="dropdown-menu">
                                                          <li > <a href="#">Agency A</a></li>
                                                          <li ><a href="#">Agency B</a></li>  
                                                  </ul>
                                             </div>
                                            <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanagency3" > Agencys</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listagency3" class="dropdown-menu">
                                                          <li > <a href="#">Agency A</a></li>
                                                          <li ><a href="#">Agency B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanagency4" > Agencys</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listagency4" class="dropdown-menu">
                                                          <li > <a href="#">Agency A</a></li>
                                                          <li ><a href="#">Agency B</a></li>  
                                                  </ul>
                                             </div>
                                              <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanagency5" > Agencys</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listagency5" class="dropdown-menu">
                                                          <li > <a href="#"> Production House A</a></li>
                                                          <li ><a href="#"> Production House B</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="left" style="margin-bottom:8px">
                                                  <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                                   <span id="dashagency_spanunit" > Unit</span>
                                                    <span class="caret"></span>
                                                  </a>
                                                  <ul id="dashagency_listunit" class="dropdown-menu">
                                                          <li > <a href="money"> ดูราคา</a></li>
                                                          <li ><a href="time"> นาที</a></li>  
                                                  </ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li ></li></ul>
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li class="divider-vertical"></li></ul>
                                             </div>
                                             <div class="btn-group" align="right" >
                                                  <?php/*
														$this->widget('zii.widgets.jui.CJuiDatePicker', array(
															'name'=>'my_date_agency1',
															'language'=>Yii::app()->language=='et' ? 'et' : null,
															'options'=>array(
																'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
																'showOn'=>'button', // 'focus', 'button', 'both'
																'buttonText'=>Yii::t('ui','Select form calendar'),
																'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
																'buttonImageOnly'=>true,
															),
															'htmlOptions'=>array(
																'style'=>'width:120px;vertical-align:top'
															),
														)); */  
													?>	
                                             </div>
                                             <div class="btn-group" align="center" style="margin-bottom:6px">
                                              	 <ul  class="nav"><li class="divider-vertical"></li></ul>
                                             </div>
                                             <div class="btn-group" align="right">
                                                  <?php/*
														$this->widget('zii.widgets.jui.CJuiDatePicker', array(
															'name'=>'my_date_agency2',
															'language'=>Yii::app()->language=='et' ? 'et' : null,
															'options'=>array(
																'showAnim'=>'show', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
																'showOn'=>'button', // 'focus', 'button', 'both'
																'buttonText'=>Yii::t('ui','Select form calendar'),
																'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar_green.png',
																'buttonImageOnly'=>true,
															),
															'htmlOptions'=>array(
																'style'=>'width:120px;vertical-align:top'
															),
														));  */ 
													?>	
                                             </div>
                                            </ul>
                                        </div>
                                    </div>
                            	</div>
                         	</div>        
                            <div class="row-fluid">
								<div  align="center"  id="dash_agency_chart1"   style="height:400px;width:700px">
                            
                                </div>
                                <script>

var dashagencysel = [0,0,0,0,0];
var dataType = "time";
function dashagency_select(agencyli,agency_id){
	var agency_name = 0;
	agency_name = $("#dashagency"+agencyli+"_id"+agency_id).attr('value')
	$("#dashagency_spanagency"+agencyli).text(agency_name);
	console.log(agencyli+" "+agency_id+" "+agency_name);
	// progchart_dashsum(agency_id);
	dashagencysel[agencyli-1] = agency_id;
	console.log(dashagencysel);
	dashagency_chartshow();
} 

function dashagency_agency_list() {
		$.ajax('?r=agency/japi&action=agenList',{
			type: 'GET',
			dataType: 'json',
			success: function(agencyList){
			$("#dashagency_listagency1 li").remove();
			$("#dashagency_listagency2 li").remove();
			$("#dashagency_listagency3 li").remove();
			$("#dashagency_listagency4 li").remove();
			$("#dashagency_listagency5 li").remove();
//			$.each(agencyList,function(k,v){ 
				$.each(agencyList,function(kon,von){	
					var agency_name = von.agency_name;
					console.log(von);
					agency_name = agency_name.toString() ;
					$("#dashagency_listagency1").append(
							 "<li >"+
								 "<a id='dashagency1_id"+von.agency_id+"' value="+agency_name+"  onclick=dashagency_select("+1+","+von.agency_id+"); >"+agency_name+"</a>"+
							 "</li>"
					);
					$("#dashagency_listagency2").append(
							 "<li >"+
								 "<a id='dashagency2_id"+von.agency_id+"' value="+agency_name+"  onclick=dashagency_select("+2+","+von.agency_id+"); >"+agency_name+"</a>"+
							 "</li>"
					);
					$("#dashagency_listagency3").append(
							 "<li >"+
								 "<a id='dashagency3_id"+von.agency_id+"' value="+agency_name+"  onclick=dashagency_select("+3+","+von.agency_id+"); >"+agency_name+"</a>"+
							 "</li>"
					);
					$("#dashagency_listagency4").append(
							 "<li >"+
								 "<a id='dashagency4_id"+von.agency_id+"' value="+agency_name+"  onclick=dashagency_select("+4+","+von.agency_id+"); >"+agency_name+"</a>"+
							 "</li>"
					);
					$("#dashagency_listagency5").append(
							 "<li >"+
								 "<a id='dashagency5_id"+von.agency_id+"' value="+agency_name+"  onclick=dashagency_select("+5+","+von.agency_id+"); >"+agency_name+"</a>"+
							 "</li>"
					);
				});		
//			});
			 }
		});
}

function dashagency_chartshow(){
	var agencylist = "";
	var agencylistcnt = 1;
	$.each(dashagencysel, function(k,v){
		aglist = k + 1;
		agencylist += "&agency"+aglist+"="+v+"";
	});
	console.log(agencylist);
		var dStart = $("#dash_agency_date_from").val().split("/");
		var dEnd = $("#dash_agency_date_to").val().split("/");
		var fromDate = dStart[2]+"-"+dStart[1]+"-"+dStart[0];
		var toDate = dEnd[2]+"-"+dEnd[1]+"-"+dEnd[0];
		$.ajax( '?r=dashSum/japi&action=DashAgency'+agencylist+'&data_type='+dataType+'&date_start='+fromDate+'&date_end='+toDate, 
	{
	type: 'GET',
	dataType: 'json',

	success: function(DashAgency){
		var tickstr = new Array();
		var line1 = [0,0,0,0,0];//[10,20,30];
		var line2 = [0,0,0,0,0];//[30,20,10];
		//var line1 = new Array();
		//var line2 = new Array();
	
		$.each(DashAgency, function(k,v){
			console.log("itme k "+k+" v "+v.spot_time+" v.prog_nam "+v.break_time);
			if (typeof(v.agency_name) != "undefined")
			{
				tickstr.push(v.agency_name);
				//line1.push(parseInt(v.spot_time));
				//line1.push(20); 
				//line2.push(parseInt(v.break_time)-parseInt(v.spot_time)); 
				line1[k]=parseInt(v.spot_time);
				//line2[k]=parseInt(v.break_time)-parseInt(v.spot_time);
				line2[k] = 0;
				//line2.push(10); 
			}
		});
				console.log("test");
				console.log(line1);
	
		console.log("itme ticks->"+tickstr);
		console.log("itme line1->"+line1);
		console.log("itme line2->"+line2);
		  $('#dash_agency_chart1').empty();
		  
	  var strFormat = "%s นาที";
	  	  if(dataType == "time"){
	  	  	strFormat = "%s นาที";
	  	  }else{
	  	  	strFormat = "%s บาท";
	  	  }
	  var plot1b = $.jqplot('dash_agency_chart1', [line1], {
		title: 'Agency Chart',
		stackSeries: true,
		seriesDefaults:{
		  renderer:$.jqplot.BarRenderer,
		  rendererOptions: {
			  highlightMouseDown: true,
			  showDataLabels: true
		  },
		  pointLabels: {show: true,formatString:strFormat}
		},
		legend: { show:true, xoffset:10, yoffset:10},
		axesDefaults: {
			tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
			tickOptions: {
			  fontFamily: 'Georgia',
			  fontSize: '10pt',
			  angle: -30
			}
		},
		series:[
            {label:'Sold'},
            {label:'Unsold'}
        ],
		axes: {
		  xaxis: {
			renderer: $.jqplot.CategoryAxisRenderer,
			ticks: tickstr
		  },
		  yaxis: {
			// Don't pad out the bottom of the data range.  By default,
			// axes scaled as if data extended 10% above and below the
			// actual range to prevent data points right on grid boundaries.
			// Don't want to do that here.
			padMin: 0
		  }
		}
	  });
	}
	});
}


$(document).ready(function(){
		//var ticks = new Array();
		 dashagency_agency_list();
		 dashagency_chartshow();
		 
		$("#dash_agency_date_to").change(function(){
			dashagency_chartshow();
		});
		$("#dash_agency_date_to").change(function(){
			dashagency_chartshow();
		});
			
		$("#dashagency_listunit a").click(function() {
			$("#dashagency_spanunit").html($(this).html());
			//alert($(this).attr("href"));
			$(".nav .btn-group").removeClass("open");
			dataType = $(this).attr("href");
			dashagency_chartshow();
			return false;
		});
		 
	});
										
										/*$(document).ready(function(){
											  var line2 = [['A', 7], ['B', 9], ['C', 15], 
											  ['D', 12], [' E', 3], 
											  ['F', 6]];
											  var plot1b = $.jqplot('dash_agency_chart1', [line2], {
												title: 'Agency Chart',
												series:[{renderer:$.jqplot.BarRenderer}],
												axesDefaults: {
													tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
													tickOptions: {
													  fontFamily: 'Georgia',
													  fontSize: '10pt',
													  angle: -30
													}
												},
												axes: {
												  xaxis: {
													renderer: $.jqplot.CategoryAxisRenderer
												  }
												}
											  });
										});*/
								</script>   
                                
                           	</div>
                            
                    </div>
				</div>
            </div>
            
	</div>
</div>     