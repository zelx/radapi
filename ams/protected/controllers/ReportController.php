<?php
class ReportController extends Controller {
/*******************************************************/
	public function japiReportActualOnair($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0){		
	$month = str_pad($month,2,"0",STR_PAD_LEFT);
	$day = str_pad($day,2,"0",STR_PAD_LEFT);
	$connection=Yii::app()->db;
	
	
	$sql="SELECT programs.prog_id, programs.prog_name as program, DATE_FORMAT(onair_schedule.datetime,'%d') as day, SUM(advertise.adv_time_len)/60 as onairtime, programs.time_break FROM onair_schedule 
		INNER JOIN break ON onair_schedule.break_id = break.break_id 
			AND onair_schedule.active_plan = break.break_plan
		INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
		INNER JOIN advertise ON break.adv_id = advertise.adv_id
		WHERE DATE_FORMAT(onair_schedule.datetime,'%m-%Y') = '$month-$year'
		GROUP BY programs.prog_id,break.break_id
		Order By programs.prog_id ASC";

	$command=$connection->createCommand($sql);
	$dataReader=$command->query();
	$rows=$dataReader->readAll();
	$connection->active = false;
	$result = array();
	$proglist = array();
	$prog_id = 0;
	$prog_cnt = 0;
	foreach($rows as $progday){
		
		if($prog_id != $progday['prog_id']){
			$prog_cnt++;
			array_push($proglist,array('prog_id'=>$progday['prog_id'],'prog_name'=>$progday['program']));
			array_push($proglist,array('day'=>$progday['day'],'onairtime'=>$progday['onairtime'],'progtime'=>$progday['time_break']));
			$prog_id = $progday['prog_id'];
		}else{
			array_push($proglist,array('day'=>$progday['day'],'onairtime'=>$progday['onairtime']));
			$prog_name = $progday['program'];
		}
	}
	//print_r($proglist);
	return ($proglist); 				
	}

/*******************************************************/
	public function japiReportIncomeByProgram($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0){		
	$month = str_pad($month,2,"0",STR_PAD_LEFT);
	$day = str_pad($day,2,"0",STR_PAD_LEFT);
	$connection=Yii::app()->db;
	
	
	$sql="SELECT programs.prog_name, programs.onairweekly, DATE_FORMAT( programs.date_start,  '%H.%i' ) AS onairstart, DATE_FORMAT( programs.date_end, '%H.%i' ) AS onairend, 
		SUM(advertise.adv_time_len) as adv_time, SUM(advertise.price) as price
		FROM onair_schedule
		INNER JOIN break ON onair_schedule.break_id = break.break_id
		INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
		INNER JOIN advertise ON break.adv_id = advertise.adv_id
		WHERE DATE_FORMAT( onair_schedule.datetime,  '%m-%Y' ) =  '$month-$year'
		GROUP BY programs.prog_id";
	
	$command=$connection->createCommand($sql);
	$dataReader=$command->query();
	$rows=$dataReader->readAll();
	$connection->active = false;
	return ($rows);
	}

/*******************************************************/
	public function japiReportQuotaUsage($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0){		
	$month = str_pad($month,2,"0",STR_PAD_LEFT);
	$day = str_pad($day,2,"0",STR_PAD_LEFT);
	$connection=Yii::app()->db;
	
	
	$sql="SELECT DATE_FORMAT( onair_schedule.datetime,  '%d' ) as day, agency.agency_id,agency.agency_name, SUM(advertise.adv_time_len)/60 as onairtime,SUM(advertise.price) as price
		FROM onair_schedule
		INNER JOIN break ON onair_schedule.break_id = break.break_id
		INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
		INNER JOIN advertise ON break.adv_id = advertise.adv_id
		INNER JOIN agency ON advertise.agency_id = agency.agency_id
		WHERE DATE_FORMAT( onair_schedule.datetime,  '%m-%Y' ) =  '$month-$year'
		GROUP BY agency.agency_id,day";

	$command=$connection->createCommand($sql);
	$dataReader=$command->query();
	$quotausage=$dataReader->readAll();
	$connection->active = false;

	
	$sql="SELECT agency.agency_id,agency.agency_name, quota.monthly 
			FROM `quota`,`agency` 
			WHERE (quota.agency_id = agency.agency_id)
			AND DATE_FORMAT(date_start,'%m-%Y' ) =  '$month-$year'
			ORDER BY agency.agency_name ASC";

	$command=$connection->createCommand($sql);
	$dataReader=$command->query();
	$quota=$dataReader->readAll();
	$connection->active = false;

	return (array($quotausage,$quota));
	}

/*******************************************************/
	public function japiReportProgramQueue($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0){		
	$month = str_pad($month,2,"0",STR_PAD_LEFT);
	$day = str_pad($day,2,"0",STR_PAD_LEFT);
	$connection=Yii::app()->db;
	
	
	$sql="SELECT breaktime.break_seq,DATE_FORMAT( onair_schedule.datetime, '%d-%m-%Y' ) as onairdate, product.prod_name, tape.tape_name,
			advertise.adv_time_len, advertise.price_type, agency.agency_name, DATE_FORMAT( onair_schedule.datetime, '%H%i' ) as onairtime,
			programs.prog_name
		FROM onair_schedule
		INNER JOIN break ON onair_schedule.break_id = break.break_id
			AND onair_schedule.active_plan = break.break_plan
		INNER JOIN breaktime ON break.break_id = breaktime.break_id AND break.break_seq = breaktime.break_seq
			AND onair_schedule.active_plan = breaktime.break_plan
		INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
		INNER JOIN advertise ON break.adv_id = advertise.adv_id
		INNER JOIN agency ON advertise.agency_id = agency.agency_id
		INNER JOIN tape ON advertise.tape_id = tape.tape_id
		INNER JOIN product ON tape.prod_id = product.prod_id
		WHERE DATE_FORMAT( onair_schedule.datetime,  '%d-%m-%Y' ) =  '$day-$month-$year'
		AND programs.prog_id = $program
		ORDER BY breaktime.break_seq";

	$command=$connection->createCommand($sql);
	$dataReader=$command->query();
	$rows=$dataReader->readAll();
	$connection->active = false;
	return ($rows);
	}
		
	public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}
?>