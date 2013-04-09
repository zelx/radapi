<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Report_agency';
$this->breadcrumbs=array(
    'Report_agency',
);
?>
<div class="row-fluid" >
    <div class="span2" style="font-size:1em; margin-left:2px;" align="right">
        <div class="row-fluid">
            <div class="span4" align="right">
                <label for="reportagency_month" style="font-size:1em;margin-top:7px">เดือน:</label>
            </div>
            <div class="span8" align="left">
                <select class="input-small" type="text" name="reportagency_month" id="reportagency_month" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
                <label for="reportagency_year" style="font-size:1em;margin-top:7px">ปี:</label>
            </div>
            <div class="span8" align="left">
                <select class="input-small" type="text" name="reportagency_year" id="reportagency_year" style="font-size:1em;width:120px;padding-top:4px;padding-bottom:4px;;margin-top:4px;margin-bottom:4px">
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
    <div class="span8" align="right">
        

    </div>
</div>
<div class="container" id="page" style="width:inherit"> 
 <div class="row-fluid">
    <div class="">
        <table align="center" class="table table-bordered" id="reportagency_table">
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

function LastDayOfMonthReagency(Year, Month) {
    
    var reportagency_table = new Date( (new Date(Year, Month,1))-1 );
    
    return(reportagency_table.getDate());
}

function genTaleHeaderReagency(MaxDayofMonth){

    $("#reportagency_table thead tr th").remove();   
    
    $("#reportagency_table thead tr").append(
        
        "<th style='width:10%;text-align:center;padding:2px'>บริษัท</th>"
    )
    
    for( var count_day = 1; count_day <= MaxDayofMonth; count_day++){
        
        $("#reportagency_table thead tr").append(
        
            "<th style='text-align:center;padding:1px;width:22px;'>"+count_day+"</th>"
        )       
        
    }   
    
    $("#reportagency_table thead tr").append(
        
        "<th style='width:8%;text-align:center;padding:2px'>รวมเป็นเงิน</th>"
    )   
}

function zeroPadRagency(num, places) {
  var zero = places - num.toString().length + 1;
  return Array(+(zero > 0 && zero)).join("0") + num;
}


function genOnairProgramRagency(year,month){
    
    var programR3 = 11;
    var dayR3 = 02;
    var planR3 = 0;
    var curr_progID = 0;
    var prev_progname = 0;
    var MaxofMonth = 0;
    
     MaxofMonth = LastDayOfMonthReagency(year, month);
    
    $.ajaxSetup({
        async: false
    });
    
    $.ajax( '?r=report/japi&action=reportQuotaUsage&program='+programR3+'&year='+year+'&month='+month+'&day='+dayR3+'&plan='+planR3+'', {
      type: 'GET',
      dataType: 'json',
      
        success: function(reportQuotaUsage) {
            
            var current_agency = 0;
            var current_price = 0;
            
            $("#reportagency_table tbody tr").remove();
            $.each(reportQuotaUsage[0], function(k,v){
                
                //console.log("agency_name="+v.agency_name+" day="+v.day+" onairtime="+v.onairtime+" price="+v.price+" quota="+v.quota));

                if(current_agency != v.agency_id){
                    
                    var current_agencyname = v.agency_name;
                        current_price = 0;
                        current_price = v.price;
                        current_agency = v.agency_id;
                    
                    var trTable = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
                    trTable += "<td   style='text-align:left;padding:2px'>"+current_agencyname+"</td>";
                    //var trRowSpan = "<tr  style='height:25px;padding-top:4px;padding-bottom:4px'>";
                    
                     for(var countdate = 1; countdate <= MaxofMonth; countdate++){
                        
                        cnt_date = zeroPadRagency(countdate, 2);
                        
                            trTable+="<td  style='text-align:center;padding:1px' id='agency"+current_agency+"_day"+cnt_date+"'></td>" 
                        
                            //trRowSpan +="<td style='text-align:right;padding:2px' id='quota"+current_agency+"_day"+cnt_date+"''></td>" 
            
                    }
                    //trRowSpan += "</tr>";
                    trTable += "<td   style='text-align:right;padding:2px' id='agencyPrice"+current_agency+"'></td>";
                    trTable += "</tr>";
                    //trRowSpan += "</tr>";
                    
                    $("#reportagency_table tbody").append(trTable);
                    //$("#reportagency_table tbody").append(trRowSpan);
                    
                    
                    var onairtime = v.onairtime;
                    //console.log("test"+v.onairtime);
                    
                    var n= onairtime.split("."); 
                    var hour = n[0];
                    var timetoshow;
                    if(n.length==1){
                        timetoshow = hour+":00";
                    }else{
                        if(n[1]<10) n[1] = n[1] * 10;
                        var minute = seconds2time(3600/(100/n[1])); 
                        timetoshow = hour+":"+minute;
                    }
                    
                    var progtime = v.onairtime;
                    if(typeof v.progtime != 'undefined'){
                        progtime = v.progtime; 
                    } 
                    var n_prog=progtime.split("."); 
                    var hour_prog = n_prog[0];
                    var timetoshow_prog;
                    if(n_prog.length==1){
                        timetoshow_prog = hour_prog+":00";
                    }else{
                        timetoshow_prog = n_prog[1];
                        if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
                        var minute_prog = seconds2time(3600/(100/n_prog[1])); 
                        timetoshow_prog = hour_prog+":"+minute_prog;
                    } 
                    
        
                
                    
                    if(onairtime>progtime){
                        $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
                        //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
                    }else{
                        $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
                        //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
                    } 
                    
                    $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).text(convert_time(v.onairtime));
                    //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).text(convert_time(v.onairtime));
                }else{
                    
                    current_price = parseInt(current_price) + parseInt(v.price);
            
                var onairtime = v.onairtime;
                    var n=onairtime.split("."); 
                    var hour = n[0];
                    var timetoshow;
                    if(n.length==1){
                        timetoshow = hour+":00";
                    }else{
                        if(n[1]<10) n[1] = n[1] * 10;
                        var minute = seconds2time(3600/(100/n[1])); 
                        timetoshow = hour+":"+minute;
                    }
                    
                    var progtime = v.onairtime;
                    if(typeof v.progtime != 'undefined'){
                        progtime = v.progtime; 
                    } 
                    var n_prog=progtime.split("."); 
                    var hour_prog = n_prog[0];
                    var timetoshow_prog;
                    if(n_prog.length==1){
                        timetoshow_prog = hour_prog+":00";
                    }else{
                        timetoshow_prog = n_prog[1];
                        if(n_prog[1]<10) n_prog[1] = n_prog[1] * 10;
                        var minute_prog = seconds2time(3600/(100/n_prog[1])); 
                        timetoshow_prog = hour_prog+":"+minute_prog;
                    } 
                    
        
                
                    
                    if(onairtime>progtime){
                        $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
                        //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#ffa9a9');
                    }else{
                        $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
                        //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).css('backgroundColor','#fff47a');
                    } 
                    $("#reportagency_table tbody tr td#agency"+current_agency+"_day"+v.day).text(timetoshow_prog);
                    //$("#reportagency_table tbody tr td#quota"+current_agency+"_day"+v.day).text(timetoshow);
                    //console.log("price="+current_price+" v.price="+v.price);
                }
                
                $("#reportagency_table tbody tr td#agencyPrice"+current_agency).text(chkNum(current_price));
                
                
                //console.log("prog_id="+v.prog_id+"program="+v.prog_name+" day="+v.day+" time="+v.onairtime)
                
            });// Each Reader
      
        }
    });
    
}


    function convert_time(time){    
            var onairtime = time;
                    var n=onairtime.split("."); 
                    var hour = n[0];
                    var timetoshow;
                    if(n.length==1){
                        timetoshow = hour+":00";
                    }else{
                        if(n[1]<10) n[1] = n[1] * 10;
                        var minute = seconds2time(3600/(100/n[1])); 
                        timetoshow = hour+":"+minute;
                    }
                    
        return timetoshow;
       
        // throw 'bad date';
    }
    

$(document).ready(function() {
    var reportagency_current_date = 0;
    var reportagency_current_month = 0;
    var reportagency_current_year = 0;
    
    reportagency_current_date  =  new Date();
    reportagency_current_month =  parseInt(reportagency_current_date.getMonth())+1;   
    reportagency_current_year = parseInt(reportagency_current_date.getFullYear())+543;
    
    var readYear = parseInt(reportagency_current_year)-543;
    
    $("#reportagency_month").val(reportagency_current_month);
    $("#reportagency_year").val(reportagency_current_year);
    
    genTaleHeaderReagency( LastDayOfMonthReagency(reportagency_current_year,reportagency_current_month));
    genOnairProgramRagency(readYear,reportagency_current_month);
});


$("#reportagency_month").change(function(){

    var reportagency_select_month = parseInt($("#reportagency_month").attr('value'));
    var reportagency_select_year = parseInt($("#reportagency_year").attr('value'))-543;   
    
    genTaleHeaderReagency( LastDayOfMonthReagency(reportagency_select_year, reportagency_select_month));
    genOnairProgramRagency(reportagency_select_year,reportagency_select_month);
    
})

$("#reportagency_year").change(function(){
    
    var reportagency_select_month = parseInt($("#reportagency_month").attr('value'));
    var reportagency_select_year = parseInt($("#reportagency_year").attr('value'))-543;   
    
    genTaleHeaderReagency( LastDayOfMonthReagency(reportagency_select_year, reportagency_select_month));
    genOnairProgramRagency(reportagency_select_year,reportagency_select_month);
    
})


</script>
