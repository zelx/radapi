<?php
class DashSumController extends Controller {
	
			public function japiTestsum(){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' )  AS datetime, 
					  SUM(`timelimit`) AS spot_time FROM `breaktime`,`onair_schedule`,`programs`
					  WHERE (breaktime.break_id = onair_schedule.break_id )
					  AND (onair_schedule.prog_id = programs.prog_id)
					  GROUP BY DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' ) 
					  ORDER BY onair_schedule.datetime ASC";
							
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return  ($rows); 				
			}
			
			
			public function japiProgsum($prog_id=0){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' )  AS datetime, 
					  SUM(`timelimit`) AS spot_time FROM `breaktime`,`onair_schedule`,`programs`
					  WHERE (breaktime.break_id = onair_schedule.break_id )
					  AND (onair_schedule.prog_id = programs.prog_id)
					  AND (programs.prog_id = '$prog_id')
					  GROUP BY DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' ) 
					  ORDER BY onair_schedule.datetime ASC";
							
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return  ($rows); 				
			
			}
			
/*			
			public function japiProgsum($prog_id=0){
				$connection=Yii::app()->db;
				
				$sql="SELECT DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' )  AS datetime, SUM( programs.prog_price )  AS prog_price
							FROM programs, onair_schedule
							WHERE onair_schedule.prog_id = programs.prog_id
							AND (onair_schedule.datetime LIKE  '2013-01%')
							AND programs.prog_id = '$prog_id'
							GROUP BY DATE_FORMAT( onair_schedule.datetime,  '%Y-%m-%d' ) 
							ORDER BY onair_schedule.datetime ASC ";
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return  ($rows); 				
			
			}
*/
	public function japiDashSummery($prog_id=0,$Res='M',$year=2013,$month=3,$day=1,$Prime=2,$period=30){

		$connection=Yii::app()->db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
/**** All Sold ***/
		//WHERE DATE_FORMAT(onair_schedule.datetime,'%m-%Y') = '$month-$year';
		$sql_allsold="SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE onair_schedule.datetime between '".$year."-".$month."-".$day."' and  DATE_ADD('".$year."-".$month."-".$day."',INTERVAL ".$period." DAY)
			AND break.break_plan = 0
			GROUP BY onairdatetime
			ORDER BY onairdatetime ASC";
		$command=$connection->createCommand($sql_allsold);
		$dataReader=$command->query();
		$allsold=$dataReader->readAll();
		$connection->active = false;

/**** All Prime ***/		
		$sql_allprime="SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE onair_schedule.datetime between '".$year."-".$month."-".$day."' and  DATE_ADD('".$year."-".$month."-".$day."',INTERVAL ".$period." DAY)
			AND programs.primetime = 1
			AND break.break_plan = 0
			GROUP BY onairdatetime";
		$command=$connection->createCommand($sql_allprime);
		$dataReader=$command->query();
		$soldprime=$dataReader->readAll();
		$connection->active = false;

/**** All NON Prime ***/
		$sql_allnonprime="SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE onair_schedule.datetime between '".$year."-".$month."-".$day."' and  DATE_ADD('".$year."-".$month."-".$day."',INTERVAL ".$period." DAY)
			AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command=$connection->createCommand($sql_allnonprime);
		$dataReader=$command->query();
		$soldnonprime=$dataReader->readAll();
		$connection->active = false;

/**** All Avialable Break ***/
		$sql_breakprime="SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE onair_schedule.datetime between '".$year."-".$month."-".$day."' and  DATE_ADD('".$year."-".$month."-".$day."',INTERVAL ".$period." DAY)
			AND programs.primetime = 1
			GROUP BY onairdatetime";
		$command=$connection->createCommand($sql_breakprime);
		$dataReader=$command->query();
		$breaktime_prime=$dataReader->readAll();
		$connection->active = false;

/**** All Avialable Break ***/
		$sql_breaknonprime="SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE DATE_FORMAT(onair_schedule.datetime,'%m-%Y') = '$month-$year'
			AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command=$connection->createCommand($sql_breaknonprime);
		$dataReader=$command->query();
		$breaktime_nonprime=$dataReader->readAll();
		$connection->active = false;

		return  (array('allsold'=>$allsold,'soldprime'=>$soldprime,'soldnonprime'=>$soldnonprime,'breakprime'=>$breaktime_prime,'breaknonprime'=>$breaktime_nonprime)); 				

	}


	public function japiDashCompare($prog_id = 0, $date_A = '2013-03-01', $date_B = '2013-03-01', $sample = 7, $agency_id = 0, $prod_id = 0, $prime = 2, $soldprime = 1, $soldnonprime = 1, $unsoldprime = 1, $unsoldnonprime = 1
	,$inday=''
	) {
		/* $prime = 0:none_prime,1:prime,2:all */
		$connection = Yii::app() -> db;
		//		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		//		$day = str_pad($day,2,"0",STR_PAD_LEFT);

		$date_A_query = " onair_schedule.datetime BETWEEN '$date_A' AND DATE_ADD('$date_A',INTERVAL $sample DAY) ";
		$date_B_query = " onair_schedule.datetime BETWEEN '$date_B' AND DATE_ADD('$date_B',INTERVAL $sample DAY) ";

		if ($agency_id != 0) {
			$agency_query = "AND agency.agency_id = " . $agency_id . " ";
		} else {
			$agency_query = "";
		}
		if ($prod_id != 0) {
			$prod_query = "AND product.prod_id = " . $prod_id . " ";
		} else {
			$prod_query = "";
		}
		if ($prime = 2) {
			$prime_query = "";
		} else {
			$prime_query = "AND programs.primetime = " . $prime . " ";
		}
if ($inday != '') {
	$date_A_query = $date_A_query." AND DAYOFWEEK(   onair_schedule.datetime ) in  (".$inday.") ";
	$date_B_query =  $date_B_query." AND DAYOFWEEK(   onair_schedule.datetime ) in  (".$inday.") ";
}
		/**** All Sold ***/
		$sql_allsold = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_A_query . $agency_query . "AND break.break_plan = 0
			GROUP BY onairdatetime
			ORDER BY onairdatetime ASC";
			 
		$command = $connection -> createCommand($sql_allsold);
		$dataReader = $command -> query();
		$allsold = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Prime ***/
		$sql_allprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_A_query . $agency_query . "AND programs.primetime = 1
			AND break.break_plan = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_allprime);
		$dataReader = $command -> query();
		$soldprime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All NON Prime ***/
		$sql_allnonprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_A_query . $agency_query . "AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_allnonprime);
		$dataReader = $command -> query();
		$soldnonprime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Avialable Break ***/
		$sql_breakprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_A_query . $agency_query . "AND programs.primetime = 1
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_breakprime);
		$dataReader = $command -> query();
		$breaktime_prime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Avialable Break ***/
		$sql_breaknonprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_A_query . $agency_query . "AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_breaknonprime);
		$dataReader = $command -> query();
		$breaktime_nonprime = $dataReader -> readAll();
		$connection -> active = false;
 
 
		$statA = array();
		for ($i = 0; $i < count($allsold); $i++) {
			$tmpStatA = array();
			$tmpStatA["onairdatetime"] =    $allsold[$i]["onairdatetime"] ;
			$tmpStatA["spot_time"] = 0;
			$tmpsoldprime = 0;
			$tmpsoldnonprime = 0;
			if ($soldprime) {
				 if (isset($soldprime[$i])) {
				 	 $tmpsoldprime = intval(   $soldprime[$i]["spot_time"]);
					 $tmpStatA["spot_time"] += $tmpsoldprime;
					
				 }
				
				
			}
			if ($soldnonprime) {
				 if (isset($soldprime[$i])) {
				 	 $tmpsoldnonprime = intval(   $soldnonprime[$i]["spot_time"]);
					 $tmpStatA["spot_time"] += $tmpsoldnonprime;
					
				 }
				 
			}
			if ($unsoldprime) {if (isset( $breaktime_prime[$i])) {
				$tmpStatA["spot_time"] += (intval(  $breaktime_prime[$i]["spot_time"]) -$tmpsoldprime);}
			}
			if ($unsoldnonprime) {if (isset( $breaktime_nonprime[$i])) {
				$tmpStatA["spot_time"] += (intval(  $breaktime_nonprime[$i]["spot_time"] )-$tmpsoldnonprime);}
			}
			$statA[] = $tmpStatA;
		}

		/****** STAT B ******/
		/**** All Sold ***/
		$sql_allsold = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_B_query . $agency_query . "AND break.break_plan = 0
			GROUP BY onairdatetime
			ORDER BY onairdatetime ASC";
		$command = $connection -> createCommand($sql_allsold);
		$dataReader = $command -> query();
		$allsold = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Prime ***/
		$sql_allprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_B_query . $agency_query . "AND programs.primetime = 1
			AND break.break_plan = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_allprime);
		$dataReader = $command -> query();
		$soldprime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All NON Prime ***/
		$sql_allnonprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(advertise.adv_time_len) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_B_query . $agency_query . "AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_allnonprime);
		$dataReader = $command -> query();
		$soldnonprime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Avialable Break ***/
		$sql_breakprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_B_query . $agency_query . "AND programs.primetime = 1
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_breakprime);
		$dataReader = $command -> query();
		$breaktime_prime = $dataReader -> readAll();
		$connection -> active = false;

		/**** All Avialable Break ***/
		$sql_breaknonprime = "SELECT DATE_FORMAT(onair_schedule.datetime,'%Y-%m-%d') as onairdatetime, SUM(breaktime.timelimit) as spot_time  
			FROM onair_schedule 
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
			INNER JOIN breaktime ON break.break_id = breaktime.break_id
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			INNER JOIN agency ON advertise.agency_id = agency.agency_id
			WHERE " . $date_B_query . $agency_query . "AND programs.primetime = 0
			GROUP BY onairdatetime";
		$command = $connection -> createCommand($sql_breaknonprime);
		$dataReader = $command -> query();
		$breaktime_nonprime = $dataReader -> readAll();
		$connection -> active = false;

		$statB = array();
		for ($i = 0; $i < count($allsold); $i++) {
			$tmpStatB = array();
			$tmpStatB["onairdatetime"] =    $allsold[$i]["onairdatetime"] ;
			$tmpStatB["spot_time"] = 0;
			$tmpsoldprime = 0;
			$tmpsoldnonprime = 0;
			if ($soldprime) {
				 if (isset($soldprime[$i])) {
				 	 $tmpsoldprime = intval(   $soldprime[$i]["spot_time"]);
					 $tmpStatB["spot_time"] += $tmpsoldprime;
					
				 }
				
				
			}
			if ($soldnonprime) {
				 if (isset($soldprime[$i])) {
				 	 $tmpsoldnonprime = intval(   $soldnonprime[$i]["spot_time"]);
					 $tmpStatB["spot_time"] += $tmpsoldnonprime;
					
				 }
				 
			}
			if ($unsoldprime) {
				if (isset( $breaktime_prime[$i])) {
						$tmpStatB["spot_time"] += (intval(  $breaktime_prime[$i]["spot_time"]) -$tmpsoldprime);
				}
			
			}
			if ($unsoldnonprime) {
				if (isset ( $breaktime_nonprime[$i]["spot_time"]) ) {
					$tmpStatB["spot_time"] += (intval(  $breaktime_nonprime[$i]["spot_time"] )-$tmpsoldnonprime);
				}
				
			}
			$statB[] = $tmpStatB;
		}

		return array("comp_a" => $statA, "comp_b" => $statB);

	}
	public function japiDashProgram($prog1=0,$prog2=0,$prog3=0,$prog4=0,$prog5=0,$date_start='2013-03-01',$date_end='2013-03-31',$data_type='time'){
		$prog_filter = implode(', ',array($prog1,$prog2,$prog3,$prog4,$prog5));
		$connection=Yii::app()->db;

		if($data_type == "time"){
			$sql="SELECT programs.prog_id,programs.prog_name, SUM(advertise.adv_time_len)/60 as spot_time 
	,SUM(breaktime.timelimit)/60 as break_time   
				FROM onair_schedule 
				INNER JOIN break ON onair_schedule.break_id = break.break_id 
					AND onair_schedule.active_plan = break.break_plan
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
					AND onair_schedule.active_plan = breaktime.break_plan
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
				INNER JOIN advertise ON break.adv_id = advertise.adv_id
				INNER JOIN agency ON advertise.agency_id = agency.agency_id
				WHERE onair_schedule.datetime between  '".$date_start."' AND '".$date_end."'
				AND programs.prog_id IN (".$prog_filter.")
				GROUP BY programs.prog_id";
		}else{
			$sql="SELECT programs.prog_id,programs.prog_name, SUM(advertise.net_price) as spot_time 
	,SUM(breaktime.timelimit)/60 as break_time   
				FROM onair_schedule 
				INNER JOIN break ON onair_schedule.break_id = break.break_id 
					AND onair_schedule.active_plan = break.break_plan
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
					AND onair_schedule.active_plan = breaktime.break_plan
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
				INNER JOIN advertise ON break.adv_id = advertise.adv_id
				INNER JOIN agency ON advertise.agency_id = agency.agency_id
				WHERE onair_schedule.datetime between  '".$date_start."' AND '".$date_end."'
				AND programs.prog_id IN (".$prog_filter.")
				GROUP BY programs.prog_id";
		}


		//print($sql);
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return($rows);

	}

	public function japiDashAgency($agency1=0,$agency2=0,$agency3=0,$agency4=0,$agency5=0,$date_start='2013-03-01',$date_end='2013-03-31',$data_type='time'){
		$agency_filter = implode(', ',array($agency1,$agency2,$agency3,$agency4,$agency5));
		$connection=Yii::app()->db;
		if($data_type == "time"){
			$sql="SELECT agency.agency_id,agency.agency_name, SUM(advertise.adv_time_len)/60 as spot_time 
	,SUM(breaktime.timelimit)/60 as break_time   
				FROM onair_schedule 
				INNER JOIN break ON onair_schedule.break_id = break.break_id 
					AND onair_schedule.active_plan = break.break_plan
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
					AND onair_schedule.active_plan = breaktime.break_plan
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
				INNER JOIN advertise ON break.adv_id = advertise.adv_id
				INNER JOIN agency ON advertise.agency_id = agency.agency_id
				WHERE onair_schedule.datetime between  '".$date_start."' AND '".$date_end."'
				AND agency.agency_id IN (".$agency_filter.")
				GROUP BY agency.agency_id";			
		}else{
			$sql="SELECT agency.agency_id,agency.agency_name, SUM(advertise.net_price) as spot_time 
	,SUM(breaktime.timelimit)/60 as break_time   
				FROM onair_schedule 
				INNER JOIN break ON onair_schedule.break_id = break.break_id 
					AND onair_schedule.active_plan = break.break_plan
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
					AND onair_schedule.active_plan = breaktime.break_plan
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
				INNER JOIN advertise ON break.adv_id = advertise.adv_id
				INNER JOIN agency ON advertise.agency_id = agency.agency_id
				WHERE onair_schedule.datetime between  '".$date_start."' AND '".$date_end."'
				AND agency.agency_id IN (".$agency_filter.")
				GROUP BY agency.agency_id";						
		}

		//print($sql);
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return($rows);

	}



		public function japiProgramSchedule($date_start='2013-04-01',$date_end='2013-04-02'){
		$connection=Yii::app()->db;

		$sql=	"SELECT *
				FROM program_schedule 
				INNER JOIN onair_profile on program_schedule.onair_prof_id = onair_profile.onair_prof_id
				INNER JOIN company on onair_profile.comp_paid_id = company.comp_id
				WHERE program_schedule.date_start between  '".$date_start."' AND '".$date_end."'";			

		//print($sql);
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return($rows);
	}



		


	public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}
?>		