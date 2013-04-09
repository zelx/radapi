<?php
class ProgramController extends Controller {
	
			public function japiReader($prog_start_row=0,$prog_stop_row=10,$search_prog=""){
				
				$connection=Yii::app()->db;
				
				if($search_prog == "undefined")
					$search_prog = "";
				
				$sql= "SELECT programs.prog_id, prog_name, prog_subname, prog_desc, onair_profile.onair_prof_id, 
					   date_start, date_end, dayweek_num, time_start, time_end, comp_paid_id, price_net, 
					   price_minute, price_discount, price_pack, minute_pack, comp_id, comp_name, bbtv_group
						FROM  `programs` 
						INNER JOIN program_schedule ON programs.prog_id = program_schedule.prog_id
						INNER JOIN onair_profile ON program_schedule.onair_prof_id = onair_profile.onair_prof_id
						INNER JOIN company ON Onair_profile.comp_paid_id = company.comp_id ";
				if($search_prog !="")
				
				$sql .="where programs.prog_name like '%".$search_prog."%' ";
				$sql .="ORDER BY programs.prog_name ASC , onair_profile.dayweek_num
						LIMIT $prog_start_row,$prog_stop_row";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 			
					
			}
			
			public function japiSumTimeCountBk($onair_prof_id =0,$time_start = 0,$day_week = 0){
				
				$connection=Yii::app()->db;
				
				
				$sql= "SELECT SUM( break_profile.time_len ) AS total_bktime, 
					   COUNT( break_profile.break_seq ) AS total_bknum
					   FROM  `onair_profile` 
					   INNER JOIN break_profile ON onair_profile.break_prof_id = break_profile.break_prof_id
					   WHERE onair_profile.onair_prof_id = $onair_prof_id
					   AND onair_profile.time_start =  '$time_start'
					   AND onair_profile.dayweek_num = '$day_week'";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				
				return ($rows); 			
					
			}
			
			
			public function japiSummaryProgram($onair_prof_id =0){
				
				$connection=Yii::app()->db;
				
				
				$sql= "SELECT * 
						FROM  `onair_profile` 
						INNER JOIN break_profile ON onair_profile.break_prof_id = break_profile.break_prof_id
						INNER JOIN break_type ON break_profile.break_type_id = break_type.break_type_id
						INNER JOIN program_schedule ON onair_profile.onair_prof_id = program_schedule.onair_prof_id
						INNER JOIN programs ON program_schedule.prog_id = programs.prog_id
						INNER JOIN company ON onair_profile.comp_paid_id = company.comp_id
						WHERE onair_profile.onair_prof_id = $onair_prof_id
						ORDER BY break_profile.break_seq ASC  ";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				
				return ($rows); 			
					
			}
			
	
			public function japiAutoProgComp($comp_name = ""){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT * FROM program_owner where program_owner.name like '%".$comp_name."%'";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				
				return ($rows); 			
					
			}			
			
			public function japiAutoBreakProf($prof_name = ""){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT * FROM break_profile where break_profile.break_prof_name like '%".$prof_name."%'
					  GROUP BY break_prof_id";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				
				return ($rows); 			
					
			}			
						
			public function japiBreakTypeList(){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT * FROM break_type 
					  ORDER BY break_type_id ASC";
				
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				
				return ($rows); 			
					
			}				
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			public function japiReaderByName($program,$prog_start_row=0,$prog_stop_row=10){
				
				$connection=Yii::app()->db;
				$sql="SELECT * FROM programs where prog_name like '%".$program."%'
				ORDER BY  `programs`.`prog_name` ASC
				LIMIT $prog_start_row,$prog_stop_row";
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return array($rows); 			
					
			}
			
			public function japiBreakProp($prog_id){
				$break_id = 0;
				$connection=Yii::app()->db;
				$sql="SELECT MIN(`break_id`) as break_id FROM `onair_schedule` WHERE `prog_id`= '$prog_id'"; // Select only one break_id because it is the same property AT First Step
				$command = $connection->createCommand($sql);
				$dataReader = $command->query();
				$rows = $dataReader->readAll();
				foreach($rows as $break_id_val){
					$break_id = $break_id_val['break_id'];	
				}
				$break_sql = "SELECT COUNT(`break_seq`) AS n_break, SUM(`time_len`) AS t_time
				FROM `breaktime` 
				WHERE  (`break_seq`!= '0')
				AND (`break_id`= '$break_id')
				GROUP BY (`break_id`= '$break_id') ";
				$command = $connection->createCommand($break_sql);
				$dataReader = $command->query();
				$breaks = $dataReader->readAll();
				$connection->active = false;
				return ($breaks); 				
			}
			
			public function actionUpdateOnairProf(){
				
				$connection=Yii::app()->db;
				
				
				$prog_schedule_sql = "SELECT * 
					FROM  `program_schedule` 
					INNER JOIN onair_profile ON program_schedule.onair_prof_id = onair_profile.onair_prof_id
					GROUP BY program_schedule.prog_id
					ORDER BY program_schedule.timestamp";
					
				$command = $connection->createCommand($prog_schedule_sql);
				$dataReader = $command->query();
				$prog_schedule_all = $dataReader->readAll();				
				
				
				$onairProfID = 0;
				$onairProgID = 0;
				
				foreach($prog_schedule_all as $prog_schedule_value){ //Each New onair onair profile ID
				
					$onairProfID = $prog_schedule_value['onair_prof_id'];
					$onairProgID = $prog_schedule_value['prog_id'];
				
					$update_OnairProf_sql = "SELECT * 
										FROM  `break_profile` 
										INNER JOIN programs ON break_profile.break_prof_name = programs.prog_name
										INNER JOIN program_schedule ON programs.prog_id = program_schedule.prog_id
										WHERE programs.prog_id = $onairProgID
										AND program_schedule.onair_prof_id = $onairProfID ";
										
					$command = $connection->createCommand($update_OnairProf_sql);
					$dataReader = $command->query();
					$update_OnairProf_all = $dataReader->readAll();									
					
					$breakProfID = 1; // Default Break Profile ID
					
					if(isset($update_OnairProf_all)){
						
						foreach($update_OnairProf_all as $update_OnairProf_value){
							
							$breakProfID = $update_OnairProf_value['break_prof_id'];
							
						}
							
						$update_bkProfID_sql = "UPDATE  `onair_profile` SET  `break_prof_id` = $breakProfID 
												WHERE  `onair_profile`.`onair_prof_id` = $onairProfID";
												
						$command=$connection->createCommand($update_bkProfID_sql);
						$command->execute();
						
					}else{
						
						$update_bkProfID_sql = "UPDATE  `onair_profile` SET  `break_prof_id` = $breakProfID 
												WHERE  `onair_profile`.`onair_prof_id` = $onairProfID";
												
						$command=$connection->createCommand($update_bkProfID_sql);
						$command->execute();						
						
					}
					
				}
				
				$connection->active = false;
			
			}
			
			public function genDateTimeOnair($date_start,$date_end,$time_start ){
				
				$datetime_onair = array("2013-03-07 ".$time_start,"2013-03-14 ".$time_start,"2013-03-21 ".$time_start,"2013-03-28 ".$time_start);
				
				//echo "Date start= ".$date_start." Date end= ".$date_end." Time start=".$time_start;
				//$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
				return $datetime_onair;
			}

			public function genOnairSchedule(){
				
				//$connection=Yii::app()->db;
				
				$schedule_sql = "SELECT * 
						FROM  `program_schedule` 
						INNER JOIN onair_profile ON program_schedule.onair_prof_id = onair_profile.onair_prof_id
						INNER JOIN break_profile ON onair_profile.break_prof_id = break_profile.break_prof_id
						INNER JOIN break_type ON break_profile.break_type_id = break_type.break_type_id
						WHERE program_schedule.timestamp = ( 
							  SELECT MAX( TIMESTAMP ) 
							  FROM  `program_schedule` 
						)
						GROUP BY program_schedule.prog_id";		
						
				$command = $connection->createCommand($schedule_sql);
				$dataReader = $command->query();
				$schedule_all = $dataReader->readAll();
				
				if(isset($schedule_all)){
				
					foreach($schedule_all as $schedule){
						
						$date_start = $schedule['date_start'];
						$date_end = $schedule['date_end'];
						$onair_prof_id = $schedule['onair_prof_id'];
						$time_start = $schedule['time_start'];
						$new_prog_id = $schedule['prog_id'];
						$new_onair_prof_id = $schedule['onair_prof_id'];
						
					}
					
					$onair_prof_sql ="SELECT onair_schedule.break_id FROM onair_schedule 
									  WHERE onair_schedule.onair_prof_id = $new_onair_prof_id ";
										
					$command = $connection->createCommand($onair_prof_sql);
					$dataReader = $command->query();
					$onair_prof_check = $dataReader->readAll();	
					
						
					if(empty($onair_prof_check)){
						
					
						$datetimeOnair = $this -> genDateTimeOnair($date_start,$date_end,$time_start );
						//print_r($datetimeOnair);
						
						foreach ($datetimeOnair as $value){
							
							$sql_onair ="INSERT INTO  `onair_schedule` (`break_id`,`prog_id`,`onair_prof_id` ,`datetime` ,`active_plan` ) 
										VALUES (NULL ,$new_prog_id,$new_onair_prof_id, '$value', 0)";
															
							$command=$connection->createCommand($sql_onair);
							$command->execute();
				
						}
									
						$sql_break_id ="SELECT onair_schedule.break_id FROM onair_schedule 
										WHERE onair_schedule.prog_id = $new_prog_id
										AND onair_schedule.onair_prof_id = $new_onair_prof_id";
										
						$command = $connection->createCommand($sql_break_id);
						$dataReader = $command->query();
						$break_id_all = $dataReader->readAll();	
						
						$break_seq = 0;
						$time_len = 0;
						$onairtime = 0;
						$breakTypeId = 0;
								
						foreach ($break_id_all as $break_value){
							foreach ($break_value as $break_id_value){
								
									foreach ($schedule_all as $seq_value){
												
										$break_seq = $seq_value['break_seq'];
										$time_len = $seq_value['time_len'];
										$onairtime = $seq_value['onairtime'];
										$breakTypeId = $seq_value['break_type_id'];
												
										$sql_breaktime ="INSERT INTO  `breaktime` (
													`break_id` ,`break_seq` ,`break_plan` ,`onairtime`,`time_len`,`break_type_id`) 
													VALUES ( $break_id_value ,$break_seq ,0 ,'$onairtime','$time_len', $breakTypeId )";	
													
										$command=$connection->createCommand($sql_breaktime);
										$command->execute();
												
									}
											
							}
						} 
						
					}

				}

				$connection->active = false;
				//echo "Date start= ".$date_start." Date end= ".$date_end." Onair prof id=".$onair_prof_id." Time start=".$time_start;
				
			}

			public function actionImportProgram() {
				
				$this -> genOnairSchedule();

			}

/*
			public function actionAddprog() {
				
					$prog_id = 0;
					$minute_price = 0;
					$pack_price  = 0;
					$num_break = 0;
					$time_break =  0;
					$owner_comp = 1;
					$primetime = 0;
					$comp_name = 0;
					$company_id = 0;
					

					$discount = 0;
					$netprice = 0;
					
					
					$prog_name = $_POST['prog_name'];
					if(isset($_POST['prog_desc'])){
						$prog_desc = $_POST['prog_desc'];
					}else{
						$prog_desc = " ";
					}

					$prog_datetime = array();
					$start_date_master = $_POST['start_date_master'];
					$stop_date_master = $_POST['stop_date_master'];					
					$prog_datetime = $_POST['date_time'];
					$comp_name  = $_POST['comp_name'];
					$company_id = $_POST['comp_id'];
					$owner_comp = $_POST['owner_comp'];
					

					if(isset($prog_name) && isset($prog_datetime)){
						
						
							$connection = Yii::app()->db;
							
							if($company_id == "0"){
								
								$owner_prog_sql = "INSERT INTO `program_owner` (`company_id`, `name`, `desc`, `contract_id`, `self_owner`) VALUES (NULL, '$comp_name', '', '', '$owner_comp')";
								
								$command=$connection->createCommand($owner_prog_sql);
								$command->execute();
								$company_id = Yii::app()->db->getLastInsertID();
								
							}
							
							// Onair Prof id = 1 for MUAY THAI
							
							$onairProfId = 1;
							
							$sql_programs = " INSERT INTO `programs` (`prog_id`, `prog_name`, `prog_subtitle`,onair_prof_id,comp_own_id, `prog_desc`, `orig_prog_id`,`contractor`,`user_id`,`company_id`,`date_start`, `date_end`)
							 VALUES (NULL, '$prog_name', '$prog_name',$onairProfId,$company_id,'$prog_desc', '','','','$company_id','$start_date_master','$stop_date_master')";
							 
							$command=$connection->createCommand($sql_programs);
							$command->execute();
							$prog_id = Yii::app()->db->getLastInsertID();
							
							foreach ($prog_datetime as $value){
								
								$sql_onair ="INSERT INTO  `onair_schedule` (`break_id` ,`datetime` ,`active_plan` ,`prog_id`) 
											VALUES (NULL ,  '$value', 0,'$prog_id')";
												
								$command=$connection->createCommand($sql_onair);
								$command->execute();
									
							}
							
							$sql_break_id ="SELECT onair_schedule.break_id FROM onair_schedule WHERE onair_schedule.prog_id ='$prog_id' ";
							$command = $connection->createCommand($sql_break_id);
							$dataReader = $command->query();
							$break_id_all = $dataReader->readAll();
							
							
							$sql_break_prof ="SELECT * 
											FROM  `break_profile` 
											INNER JOIN break_type ON break_profile.break_type_id = break_type.break_type_id
											INNER JOIN onair_profile ON break_profile.break_prof_id = onair_profile.break_prof_id
											INNER JOIN programs ON onair_profile.onair_prof_id = programs.onair_prof_id
											WHERE programs.prog_id = $prog_id ";
											
							$command = $connection->createCommand($sql_break_prof);
							$dataReader = $command->query();
							$sql_break_prof_all = $dataReader->readAll();
							
							$break_seq = 0;
							$max_time_len = 0;
							$onairtime = 0;
							$breakTypeId = 0;
							
							foreach ($break_id_all as $break_value){
								foreach ($break_value as $break_id_value){
									
										foreach ($sql_break_prof_all as $seq_value){
											
											$break_seq = $seq_value['break_seq'];
											$max_time_len = $seq_value['max_time_len'];
											$onairtime = $seq_value['onairtime'];
											$breakTypeId = $seq_value['break_type_id'];
											
											$sql_breaktime ="INSERT INTO  `breaktime` (
												`break_id` ,`break_seq` ,`break_plan` ,`timelimit`,`time`,`break_type_id`) 
												VALUES ( $break_id_value ,$break_seq ,0 ,$max_time_len,'$onairtime', $breakTypeId )";	
												
											$command=$connection->createCommand($sql_breaktime);
											$command->execute();
											
										}
										
								}
							} 
							
							$sql_program_log ="INSERT INTO  `program_log` ( prog_id, onair_prof_id , operarion) 
							VALUES ( $prog_id ,$onairProfId ,'created')";	
												
							$command=$connection->createCommand($sql_program_log);
							$command->execute();
							
							$connection->active = false;
							 
					}
			}
			
*/
			
			public function japiReadUpdateProg($prog_id = 0){

				$connection=Yii::app()->db;
				
				$read_prog_sql = " SELECT * FROM  `programs` 
									INNER JOIN program_owner ON programs.company_id = program_owner.company_id
									WHERE  `programs`.`prog_id` =  '$prog_id'";

				$command = $connection->createCommand($read_prog_sql);
				$dataReader = $command->query();
				$rows = $dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			public function japiReadUpdateComp($company_id = 0){

				$connection=Yii::app()->db;
				
				$read_comp_sql = " SELECT * FROM `program_owner` WHERE `company_id` = '$company_id'";

				$command = $connection->createCommand($read_comp_sql);
				$dataReader = $command->query();
				$rows = $dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			/*
			
			public function actionAddprog() {
				
					$prog_id = 0;
					$minute_price = 0;
					$pack_price  = 0;
					$num_break = 0;
					$time_break =  0;
					$owner_comp = 1;
					$primetime = 0;
					$comp_name = 0;
					$company_id = 0;
					
					$prog_name = $_POST['prog_name'];
					$owner_comp = $_POST['owner_comp'];
					$minute_price =$_POST['minute_price'];
					$pack_price = $_POST['pack_price'];
					$num_break =$_POST['num_break'];
					$time_break = $_POST['time_break'];
					$primetime = $_POST['primetime'];
					$start_date_master = $_POST['start_date_master'];
					$stop_date_master = $_POST['stop_date_master'];
					
					$prog_datetime = $_POST['date_time'];
					$time_bk = $_POST['time_bk'];
					$comp_name  = $_POST['comp_name'];
					$company_id = 0;
					
					$company_id = $_POST['comp_id'];

					$discount = 0;
					$netprice = 0;
					$discount = $_POST['discount'];
					$netprice = $_POST['netprice'];
					
					$onairweekly = 0;
					$onairweekly = $_POST['onairweekly'];
					
					$breaktime_list = 0;
					$breaktime_list = $_POST['breaktime_list'];
					
				
					if(isset($_POST['prog_desc'])){
						$prog_desc = $_POST['prog_desc'];
					}else{
						$prog_desc = " ";
					}
					if(isset($prog_name) && isset($prog_datetime)){
						
						
							$connection = Yii::app()->db;
							
							if($company_id == "0"){
								
								$owner_prog_sql = "INSERT INTO `program_owner` (`company_id`, `name`, `desc`, `contract_id`, `self_owner`) VALUES (NULL, '$comp_name', '', '', '$owner_comp')";
								$command=$connection->createCommand($owner_prog_sql);
								$command->execute();
								$company_id = Yii::app()->db->getLastInsertID();

								
								
							}
							
							$sql_programs = " INSERT INTO `programs` (`prog_id`, `prog_name`, `prog_desc`, `orig_prog_id`,`contractor`,`user_id`,`company_id`,`net_price`,`minute_price`,`pack_price`,`discount`,`num_break`,`time_break`,`date_start`, `date_end`,`onairweekly`,`primetime`,`active`,`breaktime_list`)
							 VALUES (NULL, '$prog_name','$prog_desc', '','','','$company_id','$netprice','$minute_price','$pack_price ','$discount', '$num_break','$time_break','$start_date_master','$stop_date_master','$onairweekly','$primetime','1','$breaktime_list')";
							$command=$connection->createCommand($sql_programs);
							$command->execute();
							$prog_id = Yii::app()->db->getLastInsertID();
							
							foreach ($prog_datetime as $value){
								$sql_onair ="INSERT INTO  `onair_schedule` (
									`break_id` ,`datetime` ,`active_plan` ,`prog_id`
									) VALUES (
									NULL ,  '$value', 0,'$prog_id'
									)";	
								$command=$connection->createCommand($sql_onair);
								$command->execute();	
							}
							
							$sql_break_id ="SELECT onair_schedule.break_id FROM onair_schedule WHERE onair_schedule.prog_id ='$prog_id' ";
							$command = $connection->createCommand($sql_break_id);
							$dataReader = $command->query();
							$break_id_all = $dataReader->readAll();
							
							foreach ($break_id_all as $break_value){
								foreach ($break_value as $break_id_value){
										foreach ($time_bk as $seq_key =>$seq_value){
											$seq_key = $seq_key+1;
											$sql_breaktime ="INSERT INTO  `breaktime` (
												`break_id` ,`break_seq` ,`break_plan` ,`timelimit`
												) VALUES (
												'$break_id_value' , '$seq_key' , 0,'$seq_value'
												)";	
											$command=$connection->createCommand($sql_breaktime);
											$command->execute();
										}
								}
							} 
							
							$connection->active = false; 
					}
			}
			
			public function japiReadUpdateProg($prog_id = 0){

				$connection=Yii::app()->db;
				
				$read_prog_sql = " SELECT * FROM  `programs` 
									INNER JOIN program_owner ON programs.company_id = program_owner.company_id
									WHERE  `programs`.`prog_id` =  '$prog_id'";

				$command = $connection->createCommand($read_prog_sql);
				$dataReader = $command->query();
				$rows = $dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			public function japiReadUpdateComp($company_id = 0){

				$connection=Yii::app()->db;
				
				$read_comp_sql = " SELECT * FROM `program_owner` WHERE `company_id` = '$company_id'";

				$command = $connection->createCommand($read_comp_sql);
				$dataReader = $command->query();
				$rows = $dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			*/
			
			public function actionUpdateProg() {
				  if(Yii::app()->request->isPostRequest) {
						if(isset($_POST['prog_id'])){
							
								$update_prog_id = 0;
								$update_prog_name = 0;
								$update_prog_prime = 0;
								$update_prog_owner = 0;
								$update_prog_minprice = 0;
								$update_prog_packprice = 0;
								$update_prog_info = 0;
								$update_comp_name = 0;
								$update_comp_id = 0;
								
								$update_prog_id = $_POST['prog_id'];
								$update_prog_name = $_POST['prog_name'];
								$update_prog_prime = $_POST['primetime'];
								$update_prog_owner = $_POST['owner_comp'];
								$update_prog_minprice = $_POST['minute_price'];
								$update_prog_packprice = $_POST['pack_price'];
								$update_prog_info = $_POST['prog_desc'];
								$update_comp_name = $_POST['comp_name'];
								$update_comp_id = $_POST['comp_id'];
								
								$discount = 0;
								$num_break = 0;
								$netprice = 0;
								$dayweekly = 0; 
								$breaktime_list = 0;
								$totaltime_bk = 0;
								$discount = $_POST['discount'];
								$num_break = $_POST['num_break'];
								$netprice = $_POST['netprice'];
								$dayweekly = $_POST['dayweekly'];
								$breaktime_list = $_POST['breaktime_list'];
								$totaltime_bk = $_POST['totaltime_bk'];
								
								
								$connection=Yii::app()->db;
								
								if($update_comp_id == "0"){
									
									$owner_prog_sql = "INSERT INTO `program_owner` (`company_id`, `name`, `desc`, `contract_id`, `self_owner`) VALUES (NULL, '$update_comp_name', '', '', '$update_prog_owner')";
									$command=$connection->createCommand($owner_prog_sql);
									$command->execute();
									$update_comp_id = Yii::app()->db->getLastInsertID();
	
								}else{
								
									$comp_sql = "UPDATE  `program_owner` SET  `name` =  '$update_comp_name',  `self_owner`= '$update_prog_owner'
												WHERE  `program_owner`.`company_id` = '$update_comp_id'";
									$command=$connection->createCommand($comp_sql);
									$command->execute();									

								}
								
								
								$sql="UPDATE  `programs`
										SET  	`prog_name` ='$update_prog_name',
											 	`prog_desc` ='$update_prog_info',
												`company_id` ='$update_comp_id',
												`net_price` = '$netprice',
											 	`minute_price` ='$update_prog_minprice',
												`pack_price` ='$update_prog_packprice',
												`discount` = '$discount',
												`num_break` = '$num_break',
												`time_break` = '$totaltime_bk',
												`onairweekly` = '$dayweekly',
												`primetime` ='$update_prog_prime',
												`breaktime_list` = '$breaktime_list'
										WHERE  `programs`.`prog_id` ='$update_prog_id' ";
										
								$command=$connection->createCommand($sql);
								$command->execute();
								
								$connection->active = false;	
								
						}
				  }
			}
			
			public function actionUpdateAddProg() {
				  if(Yii::app()->request->isPostRequest) {
						if(isset($_POST['prog_id'])){
							
								$update_prog_id = 0;
								$update_prog_name = 0;
								$update_prog_prime = 0;
								$update_prog_owner = 0;
								$update_prog_minprice = 0;
								$update_prog_packprice = 0;
								$update_prog_info = 0;
								$update_comp_name = 0;
								$update_comp_id = 0;
								
								$update_prog_id = $_POST['prog_id'];
								$update_prog_name = $_POST['prog_name'];
								$update_prog_prime = $_POST['primetime'];
								$update_prog_owner = $_POST['owner_comp'];
								$update_prog_minprice = $_POST['minute_price'];
								$update_prog_packprice = $_POST['pack_price'];
								$update_prog_info = $_POST['prog_desc'];
								$update_comp_name = $_POST['comp_name'];
								$update_comp_id = $_POST['comp_id'];
								
								$discount = 0;
								$num_break = 0;
								$netprice = 0;
								$dayweekly = 0; 
								$breaktime_list = 0;
								$totaltime_bk = 0;
								$discount = $_POST['discount'];
								$num_break = $_POST['num_break'];
								$netprice = $_POST['netprice'];
								$dayweekly = $_POST['dayweekly'];
								$breaktime_list = $_POST['breaktime_list'];
								$totaltime_bk = $_POST['totaltime_bk'];
								
								$update_date_time = array();
								$update_date_time = $_POST['date_time'];
								
								$time_break = array();
								$time_break = $_POST['time_break'];
 								
								
								$connection=Yii::app()->db;
								
								if($update_comp_id == "0"){
									
									$owner_prog_sql = "INSERT INTO `program_owner` (`company_id`, `name`, `desc`, `contract_id`, `self_owner`) VALUES (NULL, '$update_comp_name', '', '', '$update_prog_owner')";
									$command=$connection->createCommand($owner_prog_sql);
									$command->execute();
									$update_comp_id = Yii::app()->db->getLastInsertID();
	
								}else{
								
									$comp_sql = "UPDATE  `program_owner` SET  `name` =  '$update_comp_name',  `self_owner`= '$update_prog_owner'
												WHERE  `program_owner`.`company_id` = '$update_comp_id'";
									$command=$connection->createCommand($comp_sql);
									$command->execute();									

								}
								
								$sql="UPDATE  `programs`
										SET  	`prog_name` ='$update_prog_name',
											 	`prog_desc` ='$update_prog_info',
												`company_id` ='$update_comp_id',
												`net_price` = '$netprice',
											 	`minute_price` ='$update_prog_minprice',
												`pack_price` ='$update_prog_packprice',
												`discount` = '$discount',
												`num_break` = '$num_break',
												`time_break` = '$totaltime_bk',
												`onairweekly` = '$dayweekly',
												`primetime` ='$update_prog_prime',
												`breaktime_list` = '$breaktime_list'
										WHERE  `programs`.`prog_id` ='$update_prog_id' ";
										
								$command=$connection->createCommand($sql);
								$command->execute();
								
								
							foreach ($update_date_time as $value){
								
								$sql_onair ="INSERT INTO  `onair_schedule` 
											(`break_id` ,`datetime` ,`active_plan` ,`prog_id`) 
											VALUES ( NULL ,  '$value', 0,'$update_prog_id')";
										
								$command=$connection->createCommand($sql_onair);
								$command->execute();	
								$break_id = Yii::app()->db->getLastInsertID();
								
								foreach ($time_break as $seq_key =>$seq_value){
									
									$seq_key = $seq_key+1;
									
									$sql_breaktime ="INSERT INTO  `breaktime` (
													`break_id` ,`break_seq` ,`break_plan` ,`time_len`) 
													 VALUES ('$break_id' , '$seq_key' , 0,'$seq_value')";	
													 
									$command=$connection->createCommand($sql_breaktime);
									$command->execute();
								}
								
								
							}

							$connection->active = false;	
								
						}
				  }
			}
			
			public function actionDeleteProg() {
				  if(Yii::app()->request->isPostRequest){
						if(isset($_POST['prog_id'])){
							
							$prog_id = $_POST['prog_id'];
							$del_break_id = 0;
							
							$connection=Yii::app()->db;
							
							$sql="SELECT `break_id` FROM `onair_schedule` WHERE `prog_id`= '$prog_id'"; 
							$command = $connection->createCommand($sql);
							$dataReader = $command->query();
							$rows = $dataReader->readAll();
							foreach($rows as $break_id_val){
								
									$del_break_id = $break_id_val['break_id'];
									$break_time_sql="DELETE FROM  `breaktime` WHERE  `breaktime`.`break_id` ='$del_break_id'";
									$command=$connection->createCommand($break_time_sql);
									$command->execute();
									
									$break_time_sql="DELETE FROM  `break` WHERE  `break`.`break_id` ='$del_break_id'";
									$command=$connection->createCommand($break_time_sql);
									$command->execute();
								
							}
							$sql="DELETE FROM  `onair_schedule` WHERE  `onair_schedule`.`prog_id` ='$prog_id'";
							$command=$connection->createCommand($sql);
							$command->execute();
							
							$sql="DELETE FROM  `programs` WHERE  `programs`.`prog_id` ='$prog_id'";
							$command=$connection->createCommand($sql);
							$command->execute();
							$connection->active = false;					
						}
				  }
			}
			
			
			
			
			
			
			
			
			
			
			public function japiProgList()
			{
					$connection=Yii::app()->db;
					$sql="SELECT * FROM `programs` ORDER By `prog_id` ASC"; 
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