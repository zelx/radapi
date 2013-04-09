<?php
class DateTimeThai extends DateTime {
	public function format($format) {
		$english = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday',
		'January','February','March','April','May','June','July','August','September','October','November','December');
		$thai = array('วันจันทร', 'วันอังคาร', 'วันพุทธ', 'วัพฤหัษบดี', 'วันศุกร์', 'วันเสาร์', 'วันอาทิตย์',
		'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม');
		return str_replace($english, $thai, parent::format($format));
	}
}
	


class OnairController extends Controller {
	/**
	 * Declares class-based actions.
	 */

	public function japiBreakshow($program, $year = 2013, $month = 1, $day = 01, $plan = 0) {
		
		$connection = Yii::app() -> db;
				
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
				
		$sql = "SELECT breaktime.break_seq,breaktime.time_len,onair_schedule.break_id,breaktime.onairtime AS onairtime,break_type.break_type_name,break_type.break_type_id,onair_schedule.onair_prof_id,onair_profile.dayweek_num,time_start
				FROM onair_schedule
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
				INNER JOIN onair_profile ON onair_schedule.onair_prof_id = onair_profile.onair_prof_id
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id
					AND onair_schedule.active_plan = breaktime.break_plan
				INNER JOIN break_type ON breaktime.break_type_id = break_type.break_type_id 
				WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
				AND programs.prog_id = $program
				Order By breaktime.break_seq ASC";
				
		//print($sql);
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$breakseq = $dataReader -> readAll();

			$sql = "SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,tape.tape_name,tape.tape_id,break.break_desc,break.break_type,break.calc_price,break.discount,advertise.price,advertise.discount,advertise.net_price,advertise.pkg_id,onair_schedule.onair_prof_id,breaktime.onairtime AS onairtime,break_type.break_type_name,break_type.break_type_id,onair_profile.dayweek_num,time_start
			FROM onair_schedule
			INNER JOIN break ON onair_schedule.break_id = break.break_id 
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN onair_profile ON onair_schedule.onair_prof_id = onair_profile.onair_prof_id
			INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id AND break.break_seq = breaktime.break_seq
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN break_type ON breaktime.break_type_id = break_type.break_type_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id 
			INNER JOIN agency ON advertise.agency_id = agency.agency_id 
			INNER JOIN tape ON advertise.tape_id = tape.tape_id 
			INNER JOIN product ON tape.prod_id = product.prod_id 
			WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
			AND ( programs.prog_id = $program)
			AND (advertise.active =  '1')
			Order By break.break_seq ASC, break.adv_seq ASC";

		//print($sql);
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$advs = $dataReader -> readAll();
		$connection -> active = false;
		//print_r($breakseq);
		//print("=======");
		//print_r($advs);
		$result = array();
		foreach ($breakseq as $brks){
			//print_r($brks);
			//print("|");
			array_push($result,$brks);
			foreach($advs as $adv){
				
				//print_r($adv);
				if ($brks['break_seq']==$adv['break_seq']){
					
					array_push($result,$adv);
				}
			}
		}
		
		//print_r($result);
		return ($result);
	}

	public function japiBreakcheck($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0) {
		$connection = Yii::app() -> db;

		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
		
		$sql = "SELECT onair_schedule.break_id,onair_schedule.active_plan,onair_schedule.reconsile,onair_schedule.onair_prof_id 
				FROM onair_schedule 
				WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
				AND onair_schedule.prog_id = $program";

		//print($sql);
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		
		return($rows);
	}

	public function japiReadBKPlan($program, $year = 2013, $month = 1, $day = 01, $plan = 0) {

		$time_onair = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);

		$sql = "SELECT DISTINCT (breaktime.break_plan) AS break_plan
				FROM onair_schedule 
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
				WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
				AND programs.prog_id = $program
				Order By breaktime.break_plan ASC";
				
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return($rows);
	}
	
	
	public function japiCheckPlan($program, $year = 2013, $month = 1, $day = 01, $plan = 0) {

		$time_onair = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);

		$sql = "SELECT COUNT(breaktime.break_plan) AS num_plan
				FROM onair_schedule 
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
				WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
				AND programs.prog_id = '$program'
                AND breaktime.break_plan = '$plan'";
				
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return($rows);
	}
	
	public function actionUpdateActivePlan() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['prog_id'])) {
				
				$year = 0;
				$month = 0;
				$day = 0;
				$plan = 0;
				$prog_id = 0;
				
				$year = $_POST['year'];
				$month = $_POST['month'];
				$day = $_POST['day'];
				$plan = $_POST['plan'];
				$prog_id = $_POST['prog_id'];				
				//echo "year".$year."month".$month."day".$day."Plan".$plan;
				
				$month = str_pad($month,2,"0",STR_PAD_LEFT);
				$day = str_pad($day,2,"0",STR_PAD_LEFT);
				
				$connection = Yii::app() -> db;
				
				$sql = "UPDATE  onair_schedule SET  active_plan =  '$plan'
						WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
						AND onair_schedule.prog_id = '$prog_id'";
						
				$command = $connection -> createCommand($sql);
				$command->execute();
				$connection -> active = false;

			}
		}
	}
	
	public function actionDeletePlan() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['prog_id'])) {
				
				$year = 0;
				$month = 0;
				$day = 0;
				$plan = 0;
				$prog_id = 0;
				$prev_plan = 0;
				
				$year = $_POST['year'];
				$month = $_POST['month'];
				$day = $_POST['day'];
				$plan = $_POST['plan'];
				$prog_id = $_POST['prog_id'];
				$prev_plan = $_POST['prev_plan'];	
							
				//echo "year".$year."month".$month."day".$day."Plan".$plan;
				
				$month = str_pad($month,2,"0",STR_PAD_LEFT);
				$day = str_pad($day,2,"0",STR_PAD_LEFT);
				
				$connection = Yii::app() -> db;

				$sql_delete_bk = "DELETE FROM break WHERE break_id IN(
				
									SELECT onair_schedule.break_id
								 	FROM onair_schedule
								 	WHERE ( onair_schedule.prog_id =  '$prog_id')
								 	AND (DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year') 
									
								  )
								  AND break_plan ='$plan' ";
				$command = $connection -> createCommand($sql_delete_bk);
				$command -> execute();

				// Delete breaktime
				$sql_delete_bkt = "DELETE FROM `breaktime` WHERE break_id IN(
				
									SELECT onair_schedule.break_id
								 	FROM onair_schedule
								 	WHERE ( onair_schedule.prog_id =  '$prog_id')
								 	AND (DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year') 
									
								  ) 
								  AND break_plan ='$plan' ";
								   
				$command = $connection -> createCommand($sql_delete_bkt);
				$command -> execute();				
				
				
				$sql = "UPDATE  onair_schedule SET  active_plan =  '$prev_plan'
						WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
						AND onair_schedule.prog_id = '$prog_id'";
						
				$command = $connection -> createCommand($sql);
				$command->execute();
				$connection -> active = false;

			}
		}
	}	
	
	
	public function japiAutoOnairProdName( $prod_name = 0) {  // Auto Complete Product
				
		$connection=Yii::app()->db;
		$sql="SELECT `product`.`prod_id`,  `product`.`prod_name` 
				FROM  `product`  
				WHERE  (product.prod_name LIKE  '".$prod_name."%')
				ORDER BY  `product`.`prod_name` ASC "; 
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows);
	}	


	
	public function japiAutoOnairTapeName( $prod_id = 0, $tape_name = 0) {  // Auto Complete Tape
				
		$connection=Yii::app()->db;
				
		$sql="SELECT  tape.tape_id,tape.tape_name, product.prod_id, product.prod_name, product.prod_desc, tape.time_len
				FROM  tape, product
				WHERE (tape.prod_id = product.prod_id)
				AND (product.prod_id = '".$prod_id."')
				AND (tape.tape_name LIKE '".$tape_name."%')		
				ORDER BY `tape`.`tape_name` ASC";	
				
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows);
	}	
	
	public function japiAutoOnairTapeTime( $prod_id = 0, $tape_id = 0) {  // Auto Complete Tapetime
				
		$connection=Yii::app()->db;
				
		$sql="SELECT  tape.tape_id,tape.tape_name, product.prod_id, product.prod_name, product.prod_desc, tape.time_len
				FROM  tape, product
				WHERE (tape.prod_id = product.prod_id)
				AND (product.prod_id = '".$prod_id."')
				AND (tape.tape_id = '".$tape_id."%')";	
				
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows);
	}
	
	public function japiAutoOnairBkTypeName( $bkTypeName = 0) {  // Auto Complete BreakType
				
		$connection=Yii::app()->db;


		$sql="SELECT `break_type`.`break_type_id`,  `break_type`.`break_type_name` 
				FROM  `break_type`  
				WHERE  (break_type.break_type_name LIKE  '".$bkTypeName."%')
				ORDER BY  `break_type`.`break_type_name` ASC "; 
				
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows);
	}	
	
	public function japiInsertNewBkType( $bkTypeName = 0) {  // Add New BreakType
				
		$connection=Yii::app()->db;

		
		$new_bktype_sql = "INSERT INTO  `break_type` (`break_type_id` ,`break_type_name`)
						   VALUES (NULL ,  '$bkTypeName')";

		$command = $connection -> createCommand($new_bktype_sql);
		$command -> execute();
		$newbktype_id  = Yii::app()->db->getLastInsertID();
		
		$sql="SELECT `break_type`.`break_type_id`,  `break_type`.`break_type_name` 
				FROM  `break_type`  
				WHERE  (break_type.break_type_id = $newbktype_id )
				ORDER BY  `break_type`.`break_type_id` ASC "; 
				
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
				
		$connection->active = false;
		
		return ($rows);
	}
	
	
	
	public function japiOnairPackageList() {  // Read Packages
				
		$connection=Yii::app()->db;
				
		$sql="SELECT  pkg_id,pkg_name
				FROM  packages
				ORDER BY `packages`.`pkg_name` ASC";	
				
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows);
	}
	
	public function actionAddAdverOnair() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['adv_name'])) {
				
				$adv_name = 0;
				$tape_id = 0;
				$adv_time_len = 0;
				$pkg_id = 0;
				$price_type = 0;
				$agency_id = 0;
				$calc_price = 0;
				$discount = 0;

				$adv_name = $_POST['adv_name'];
				$tape_id = $_POST['tape_id'];
				$agency_id = $_POST['agency_id'];
				$adv_time_len = $_POST['adv_time_len'];
				//$pkg_id = $_POST['pkg_id'];
				$calc_price = $_POST['calc_price'];
				$discount = $_POST['discount'];
				
				$net_price = $_POST['net_price'];
				
				
				$price_type = $_POST['price_type']; 
				
				if($pkg_id  == "none" || $pkg_id  ==""){
					
					$pkg_id = 0;
					
				}

				$connection = Yii::app() -> db;
				
				
				$new_adv_sql = "INSERT INTO  `advertise` (`adv_id` ,`adv_name` ,`pkg_id` ,`tape_id` ,`agency_id` ,`price`,`net_price`,`price_type` ,`active` ,`adv_time_len` ,`discount`,`order_id`
						) VALUES ( NULL ,  '$adv_name',  '$pkg_id',  '$tape_id',  '$agency_id',  '$calc_price','$net_price', '$price_type', '1',  '$adv_time_len','$discount',  '0')";

				$command = $connection -> createCommand($new_adv_sql);
				$command -> execute();
				$newadv_id  = Yii::app()->db->getLastInsertID();
						
				$adv_log_sql = "INSERT INTO  `advertise_log` (`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`
						)VALUES ( NULL ,  '$newadv_id',  '',  '',  '', CURRENT_TIMESTAMP ,  'create',  '')";
				$command=$connection->createCommand($adv_log_sql);
				$command->execute();
				
				echo $newadv_id;
				
					
				$connection->active = false;
				
			}
		}
	}

	public function actionAddonair() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['prog_id'])) {
				$prog_id = 0;
				$year = 0;
				$month = 0;
				$day = 0;
				$break_read = 0;
				$time_limit = 0;
				$break_plan = 0;
				$break_desc = array();
				$break_type = array();
				$calc_price = array();
				$discount = array();

				$prog_id = $_POST['prog_id'];
				$year = $_POST['year'];
				$month = $_POST['month'];
				$day = $_POST['day'];
				$month = str_pad($month,2,"0",STR_PAD_LEFT);
				$day = str_pad($day,2,"0",STR_PAD_LEFT);
				
				$break_plan = $_POST['break_plan'];
				$break_read = $_POST['break_read'];
				$time_limit = $_POST['totalbk'];

				$break_desc = $_POST['break_desc'];
				$break_type = $_POST['break_type'];
				$calc_price = $_POST['calc_price'];
				$discount = $_POST['discount'];

				$connection = Yii::app() -> db;

				$sql_break_id = "SELECT onair_schedule.break_id
				FROM onair_schedule
				WHERE ( onair_schedule.prog_id =  '$prog_id')
				AND ( DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year') ";
				
				$command = $connection -> createCommand($sql_break_id);
				$dataReader = $command -> query();
				$break_id_all = $dataReader -> readAll();
				foreach ($break_id_all as $break_id_value) {
					foreach ($break_id_value as $break_id) {
						$break_id = $break_id;
					}
				}
				
				/*
				$sql_break_id = "SELECT onair_schedule.break_id
								 FROM onair_schedule
								 WHERE ( onair_schedule.prog_id =  '$prog_id')
								 AND (DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year') ";
								 
				$command = $connection -> createCommand($sql_break_id);
				$dataReader = $command -> query();
				
				*/

				$sql_delete_bk = "DELETE FROM `break` WHERE `break_id`= '$break_id' 
								  AND break_plan ='$break_plan' ";
				$command = $connection -> createCommand($sql_delete_bk);
				$command -> execute();

				// Delete breaktime
				$sql_delete_bkt = "DELETE FROM `breaktime` WHERE `break_id`= '$break_id' 
								   AND break_plan ='$break_plan' ";
								   
				$command = $connection -> createCommand($sql_delete_bkt);
				$command -> execute();
				
				
				
				$breakOnairTime = $_POST['breakOnairTime'];
				$breakTypeID = $_POST['breakTypeID'];

				$bk_seq = 0;
				$adv_seq = 0;
				$time_limit_val = 0;
				$cnt_time = 0;
				$cnt_other = 0;
				
				$breakOnairTimeVal = 0;
				$breakTypeIDVal = 0;
				
				foreach ($break_read as $break) {
					if (strpos($break, 'break') !== false) {
						
						
						$bk_seq = $bk_seq + 1;
						$adv_seq = 0;
						
						$time_limit_val = $time_limit[$cnt_time];
						$breakOnairTimeVal = $breakOnairTime[$cnt_time];
						$breakTypeIDVal = $breakTypeID[$cnt_time];
						
						$sql_onair_t = " INSERT INTO `breaktime` 
										(`break_id`, `break_seq`, `break_plan`,`onairtime`, `time_len`,`break_type_id`)
										VALUES ('$break_id', '$bk_seq', '$break_plan', '$breakOnairTimeVal', '$time_limit_val','$breakTypeIDVal')";
										
						$command = $connection -> createCommand($sql_onair_t);
						$command -> execute();
						
						$cnt_time = $cnt_time + 1;

					} else if (strpos($break, 'pending') !== false) {
						
						
						$bk_seq = 0;
						$adv_seq = 0;
						$time_limit_val = 0;
						$sql_onair_t = " INSERT INTO `breaktime` 
										(`break_id`, `break_seq`, `break_plan`,`onairtime`, `time_len`,`break_type_id`)
										VALUES ('$break_id', '$bk_seq', '$break_plan', '00:00:00', '$time_limit_val','7')";
						// Default the break type id to be RESERVE break "7"		
						$command = $connection -> createCommand($sql_onair_t);
						$command -> execute();
						
						
					} else {
						$adv_seq = $adv_seq + 1;

						$break_desc_val = $break_desc[$cnt_other];
						$break_type_val = $break_type[$cnt_other];
						$calc_price_val = $calc_price[$cnt_other];
						$discount_val = $discount[$cnt_other];

						$sql_onair = " INSERT INTO `break` (`break_id`, `break_seq`, `adv_seq`, `break_plan`, `break_desc`, `adv_id`, `break_type`,`calc_price`,`discount`)
										VALUES ('$break_id', '$bk_seq', '$adv_seq', '$break_plan', '$break_desc_val', '$break','$break_type_val', '$calc_price_val','$discount_val')";
						$command = $connection -> createCommand($sql_onair);
						$command -> execute();
					}
					$cnt_other++;
				}
				
				
				$connection -> active = false;
			}
		}
	}
	
	public function actionUpdateAdverOnair() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['adv_id'])) {
				
				$adv_name = 0;
				$tape_id = 0;
				$adv_time_len = 0;
				$pkg_id = 0;
				$price_type = 0;
				$agency_id = 0;
				$calc_price = 0;
				$discount = 0;
				
				$adv_id = $_POST['adv_id'];
				$adv_name = $_POST['adv_name'];
				$tape_id = $_POST['tape_id'];
				$agency_id = $_POST['agency_id'];
				$adv_time_len = $_POST['adv_time_len'];
				$pkg_id = $_POST['pkg_id'];
				$calc_price = $_POST['calc_price'];
				$discount = $_POST['discount'];
				
				$net_price = $_POST['net_price'];
				
				$price_type = $_POST['price_type']; 
				
				if($pkg_id  == "none" || $pkg_id  ==""){
					
					$pkg_id = 0;
					
				}

				$connection = Yii::app() -> db;
				
				$update_adv_sql = "UPDATE `advertise` SET 
									`adv_name` ='$adv_name',
									`pkg_id` = '$pkg_id',
									`tape_id` =  '$tape_id',
									`agency_id` =  '$agency_id',
									`price` = '$calc_price',
									`net_price` = '$net_price',
									`price_type` = '$price_type',
									`adv_time_len` = '$adv_time_len',
									`discount` = '$discount'
									WHERE  `advertise`.`adv_id` = $adv_id";

				$command = $connection -> createCommand($update_adv_sql);
				$command -> execute();
				
				
				$update_tape_sql = "UPDATE `tape` SET  `tape_name` =  '$adv_name' 
									WHERE  `tape`.`tape_id` = '$tape_id'";
									
				$command = $connection -> createCommand($update_tape_sql);
				$command -> execute();
					
				$connection->active = false;
				
			}
		}
	}
	
	public function actionAddReserveBkPlan() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['break_id'])) {
				
				$break_id = 0;
				$adv_id = 0;
				$bktype = 0;
				$calprice = 0;
				$bkdiscount = 0;
				$bkinfo = 0;
				$bkplan = 0;
				
				$cur_bkplan = 0;
				$cur_bkplan = $_POST['break_plan'];
				
				$break_id = $_POST['break_id'];
				$adv_id = $_POST['adv_id'];
				$bktype = $_POST['bktype'];
				$calprice = $_POST['calprice'];
				$bkdiscount = $_POST['bkdiscount'];
				$bkinfo = $_POST['bkinfo'];
				
				$connection = Yii::app() -> db;
				
				$bkplan_sql = "SELECT DISTINCT (`break_plan`) AS num_bkplan
									FROM  `breaktime` 
									WHERE  `break_id` = $break_id
									AND  `break_plan` != $cur_bkplan ";				
				
				$command = $connection -> createCommand($bkplan_sql);
				$dataReader = $command -> query();
				$bkplan_all = $dataReader -> readAll();
				
				foreach ($bkplan_all as $bkplan_val) {
					
					if(isset($bkplan_val['num_bkplan'])){
					
						$bkplan = $bkplan_val['num_bkplan'];
						
						$bresk_pending_sql = "SELECT break_seq 
										FROM  `breaktime` 
										WHERE  `break_id` = '$break_id'
										AND  `break_seq` = '0' ";
										
						$command = $connection -> createCommand($bresk_pending_sql);
						$dataReader = $command -> query();
						$bresk_pending_all = $dataReader -> readAll();
						
						$bresk_pending_seq = 0;
						foreach ($bresk_pending_all as $bresk_pending_val) {
							
							$bresk_pending_seq = $bresk_pending_val["break_seq"];
						}									
						
					
						$sql = "SELECT  max(`adv_seq`) as max_adv_id  
								FROM  `break`  
								WHERE (break_id =  $break_id)
								AND (break_plan =  $bkplan) 
								AND ( break_seq =  '0')";
		
						$command = $connection -> createCommand($sql);
						$dataReader = $command -> query();
						$break_all = $dataReader -> readAll();
						
						$new_adv_seq = 0;
						foreach ($break_all as $break_value) {
							
							$new_adv_seq = $break_value["max_adv_id"];
						}
						
						if(isset($bresk_pending_seq)){
							
							$new_adv_seq = $new_adv_seq + 1;
							
						}else {
							
							// Add break sequence 0 in breaktime and break
							$sql = "INSERT INTO  `breaktime` (`break_id`, `break_seq`, `break_plan`, `time_len`, `timeoffset`) VALUES ('$break_id', '0', $bkplan, '0', '0')";
							
							$command = $connection -> createCommand($sql);
							$command -> execute();
							
							$new_adv_seq = $new_adv_seq + 1;						
							
						}
						
						$sql = "INSERT INTO `break`(`break_id`, `break_seq`, `adv_seq`, `break_plan`, `break_desc`, `adv_id`, `break_type`, `calc_price`, `discount`) 
						VALUES ('$break_id','0','$new_adv_seq','$bkplan','$bkinfo','$adv_id','$bktype','$calprice','$bkdiscount')";
			
						$command = $connection -> createCommand($sql);
						$command -> execute();				
						
						
						
					}
				}	
				
				$connection->active = false;			
			}
		}
	}
	
	public function actionDownloadExcel($program = 1, $year = 2013, $month = 1, $day = 01, $plan = 0) {
		
		//----------- Start Database Query ---------------->
		
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
		
		$connection=Yii::app()->db;
		$sql="SELECT DATE_FORMAT( onair_schedule.datetime, '%Y-%m-%d' ) as onairdate, programs.prog_name, SUM(advertise.adv_time_len) as total_time
			FROM onair_schedule
			INNER JOIN break ON onair_schedule.break_id = break.break_id
				AND onair_schedule.active_plan = break.break_plan
			INNER JOIN breaktime ON break.break_id = breaktime.break_id AND break.break_seq = breaktime.break_seq
				AND onair_schedule.active_plan = breaktime.break_plan
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			INNER JOIN advertise ON break.adv_id = advertise.adv_id
			WHERE DATE_FORMAT( onair_schedule.datetime,  '%d-%m-%Y' ) =  '$day-$month-$year'
			AND programs.prog_id = $program
			AND breaktime.break_seq != 0
			GROUP BY break.break_id";
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$prog_info=$dataReader->readAll();
		$connection->active = false;
		
		//print_r($prog_info);
		$program_name = $prog_info[0]['prog_name'];
		$program_total_minute = floor(intval($prog_info[0]['total_time'])/60);
		$program_total_sec = intval($prog_info[0]['total_time'])%60;
		//$program_onairdate = $prog_info[0]['onairdate'];
		
		$prog_date = new DateTime($prog_info[0]['onairdate']);
		$prog_thaidate = new DateTimeThai($prog_info[0]['onairdate']);
		
		$sql="SELECT breaktime.break_seq,DATE_FORMAT( onair_schedule.datetime, '%d-%m-%Y' ) as onairdate, product.prod_name, tape.tape_name,
				advertise.adv_time_len,advertise.pkg_id, advertise.price_type, agency.agency_name, DATE_FORMAT( onair_schedule.datetime, '%H%i' ) as onairtime,
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
			AND breaktime.break_seq != 0
			ORDER BY breaktime.break_seq,break.adv_seq";
		//print($sql);
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		//print($sql);
		//print_r($rows);
		
		// <---------------  End of Database Query ------------

		//print_r($rows);
		
		// ----------------  Excel Function ------------------->

		$objPHPExcel = new PHPExcel();
		$ProgramTitleStyle = array(
			'font' => array(
				'bold' => true,
				'name' => 'DilleniaUPC',
				'size' => 24,
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'borders' => array(
				'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => 'FFFFFFFF'),
					),
			),
		);

		$BlackStyleBorder = array(
			'borders' => array(
				'outline' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('argb' => '00000000'),
					),
			),
		);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7.25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(42.63);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		// ----------------  Program title ------------------->
		$objPHPExcel->getActiveSheet()->setCellValue('A1','คิวโฆษณารายการ '.$program_name);
		$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
		//$objPHPExcel->getActiveSheet()->getStyle("A1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($ProgramTitleStyle);
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(35.25);
		// ----------------  Onair Date ------------------->
		$objPHPExcel->getActiveSheet()->setCellValue('A2','ประจำ'.$prog_thaidate->format('l').
		'ที่ '.$prog_thaidate->format('d').' เดือน '.$prog_thaidate->format('F').' '.$prog_thaidate->format('Y'));
		$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
		$objPHPExcel->getActiveSheet()->getStyle("A2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($ProgramTitleStyle);

		// ----------------  Onair Total Time ------------------->
		$objPHPExcel->getActiveSheet()->setCellValue('A3','(เวลารวม '.$program_total_minute.':'.str_pad($program_total_sec,2,"0",STR_PAD_LEFT).' นาที)');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
		$objPHPExcel->getActiveSheet()->getStyle("A3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($ProgramTitleStyle);
		
		$objPHPExcel->getActiveSheet()->mergeCells('A4:J4');

		$objPHPExcel->getActiveSheet()->getStyle('A1:J4')->applyFromArray($BlackStyleBorder);
		
		//$objPHPExcel->getActiveSheet()->fromArray($rows);
		/*

		 $worksheet->fromArray( $criteria, NULL, 'A1' );
		 $worksheet->fromArray( $database, NULL, 'A4' );
		 */
		$objPHPExcel->getActiveSheet()->setCellValue('A5','วันที่');
		$objPHPExcel->getActiveSheet()->setCellValue('B5','');
		$objPHPExcel->getActiveSheet()->setCellValue('C5','สินค้า/ชุด');
		$objPHPExcel->getActiveSheet()->setCellValue('D5','วินาที');
		$objPHPExcel->getActiveSheet()->setCellValue('E5','PACK');
		$objPHPExcel->getActiveSheet()->setCellValue('F5','บริษัท');
		$objPHPExcel->getActiveSheet()->setCellValue('G5','เวลาออกอากาศ');
		$objPHPExcel->getActiveSheet()->setCellValue('H5','รายการ');
		$objPHPExcel->getActiveSheet()->setCellValue('I5','หมายเหตุ');
		$objPHPExcel->getActiveSheet()->setCellValue('J5','หมายเหตุ');
		$start_row = 6;
//				$objPHPExcel->getActiveSheet()->getStyle('A1:J4')->applyFromArray($BlackStyleBorder);
		$breakseq = 1;
		$rowbk = 6;
		foreach ($rows as $row) {
			//print_r($row);
			if ($breakseq != intval($row['break_seq'])){
				//print('A'.$rowbk.':J'.($start_row-1));
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowbk.':J'.($start_row-1))->applyFromArray($BlackStyleBorder);
				$rowbk = $start_row;
				$breakseq = intval($row['break_seq']);
			}
			$date = new DateTime($row['onairdate']);
			$year_th = substr(strval(intval( $date->format('Y'))+543),2,3);
			$datestr = strval($date->format('dm'));
			$pack = "";
			if(intval($row['pkg_id']) != 0){
				$pack = "PACK";
			}
			$type = PHPExcel_Cell_DataType::TYPE_STRING;
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $start_row)->setValueExplicit("$datestr".$year_th, $type);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $start_row, 'เทป');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $start_row, $row['prod_name'].'('.$row['tape_name'].')');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $start_row, $row['adv_time_len']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $start_row, $pack);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $start_row, $row['agency_name']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $start_row, $row['onairtime']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $start_row, $row['prog_name']);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $start_row, 'Break'.$row['break_seq']);
			$start_row++;
		}
		$objPHPExcel->getActiveSheet()->getStyle('A'.$rowbk.':J'.($start_row-1))->applyFromArray($BlackStyleBorder);

		$objPHPExcel -> getActiveSheet() -> setTitle('Simple');
		$objPHPExcel -> setActiveSheetIndex(0);

		ob_end_clean();
		ob_start();

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="program_' . $program . '_' . $year . '_' . $month . '_' . $day . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter -> save('php://output');
		
	}	
		
	public function actionExcel($program, $year, $month, $day) {// Old vaersion 

		$time = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);

		$sql = "SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_name,advertise.adv_name,agency.agency_name,tape.time_len,programs.prog_name
		FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
		WHERE (breaktime.break_id = break.break_id )
		AND (tape.prod_id = product.prod_id)
		AND (onair_schedule.prog_id = programs.prog_id) 
		AND (agency.agency_id = advertise.agency_id)
		AND (tape.tape_id = advertise.tape_id)
		AND (break.adv_id = advertise.adv_id)
		AND (breaktime.break_seq = break.break_seq )
		AND ( programs.prog_id =onair_schedule.prog_id)
		AND ( break.break_id =onair_schedule.break_id)
		AND ( breaktime.break_id =onair_schedule.break_id)
		AND ( programs.prog_id ='$program')
		AND ( DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year')
		Order By break.break_seq ASC, break.adv_seq ASC ";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;

		//print_r($rows);

		$objPHPExcel = new PHPExcel();

		$objPHPExcel -> getActiveSheet() -> fromArray($rows);
		/*

		 $worksheet->fromArray( $criteria, NULL, 'A1' );
		 $worksheet->fromArray( $database, NULL, 'A4' );
		 */

		$objPHPExcel -> getActiveSheet() -> setTitle('Simple');
		$objPHPExcel -> setActiveSheetIndex(0);

		ob_end_clean();
		ob_start();

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="program_' . $program . '_' . $year . '_' . $month . '_' . $day . '.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter -> save('php://output');
	}

	public function japiGetPrice($prog_id = 0,$onair_prof_id = 0,$dayweek_num = 0, $time_start = 0) {

		$connection = Yii::app() -> db;
			
/*		
		$sql = "SELECT * 
				FROM  `onair_profile` 
				INNER JOIN onair_schedule ON onair_profile.onair_prof_id = onair_schedule.onair_prof_id
				WHERE onair_schedule.onair_prof_id = $onair_prof_id
				AND onair_profile.dayweek_num = DAYOFWEEK(  '$year-$month-$day' ) 
				AND DATE_FORMAT( onair_schedule.datetime,  '%d-%m-%Y' ) =  '$day-$month-$year'
				AND onair_schedule.prog_id = $prog_id ";
*/
		$sql = "SELECT * 
				FROM  `onair_profile` 
				WHERE onair_profile.onair_prof_id = $onair_prof_id
				AND onair_profile.dayweek_num = '$dayweek_num'
				AND onair_profile.time_start = '$time_start'";
		

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiMaxSeq($program, $year = 2013, $month = 1, $day = 01, $plan = 0) {

		$time_onair = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
/*		$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		foreach ($progtime as $timevalue) {
			foreach ($timevalue as $value) {
				$time_onair = $value;
			}
		}*/
/*		$sql = "SELECT MAX(breaktime.break_seq) AS max_seq
						FROM `onair_schedule`,`breaktime`,`programs` 
						WHERE  ( programs.prog_id =onair_schedule.prog_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
						AND ( programs.prog_id ='$program')
						AND ( onair_schedule.datetime = '$year-$month-$day $time_onair') ";
*/						
		$sql = "SELECT MAX(breaktime.break_seq) AS max_seq 
				FROM onair_schedule
				INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
				INNER JOIN break ON onair_schedule.break_id = break.break_id 
				INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
				WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year'
				AND programs.prog_id = '$program'";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiSumUTime($program, $year = 2013, $month = 1, $day = 01) {
		$time_onair = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);
/*		$sql_progtime = "SELECT Distinct(time(datetime)) AS datetime FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		foreach ($progtime as $timevalue) {
			$time_onair = $timevalue['datetime'];
		}*/

		$sql = "SELECT SUM( tape.time_len ) AS tape_time, SUM( advertise.adv_time_len ) AS adv_time
		FROM  `break` ,  `onair_schedule` ,  `programs` ,  `product` ,  `advertise` ,  `tape` 
		WHERE (tape.prod_id = product.prod_id)
		AND (onair_schedule.prog_id = programs.prog_id)
		AND (tape.tape_id = advertise.tape_id)
		AND (break.break_id = onair_schedule.break_id)
		AND (break.adv_id = advertise.adv_id)
		AND (advertise.active =  '1')
		AND (break.break_seq !=  '0')
		AND (programs.prog_id =  '$program')
		AND (DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$year') ";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiSumTotalTime($program, $year = 2013, $month = 1, $day = 01) {
		$time_onair = 0;
		$connection = Yii::app() -> db;
		$sql_progtime = "SELECT Distinct(time(datetime)) AS datetime FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();

		foreach ($progtime as $timevalue) {
			$time_onair = $timevalue['datetime'];
		}

		$sql = "	SELECT SUM(breaktime.time_len ) AS total_time
						FROM  `breaktime` ,  `onair_schedule` ,  `programs` 
                       	WHERE ( breaktime.break_id =onair_schedule.break_id)
						AND (onair_schedule.prog_id = programs.prog_id)
						AND (breaktime.break_seq !=  '0')
						AND (programs.prog_id =  '$program')
						AND (onair_schedule.datetime =  '$year-$month-$day $time_onair')";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}
	
	public function japiDailyUsageTimeofMonth($program, $year = 2013, $month = 1, $day = 01) {
		$time_onair = 0;
		$connection = Yii::app() -> db;
		$month = str_pad($month,2,"0",STR_PAD_LEFT);
		$day = str_pad($day,2,"0",STR_PAD_LEFT);

		$sql = "SELECT onair_schedule.datetime,DATE_FORMAT(onair_schedule.datetime,'%m-%Y') yr_mo,
DATE_FORMAT(onair_schedule.datetime,'%d') day ,
		onair_schedule.break_id,SUM(breaktime.time_len) total_break_time,
		(
			SELECT SUM(advertise.adv_time_len)
			FROM break
			INNER JOIN advertise ON break.adv_id = advertise.adv_id 
			WHERE (break.break_id = onair_schedule.break_id) 
				AND (break.break_plan = onair_schedule.active_plan)
			GROUP BY break.break_id
		) AS total_advqueue_time,
		(
			SELECT SUM(advertise.adv_time_len)
			FROM break
			INNER JOIN advertise ON break.adv_id = advertise.adv_id 
			WHERE (break.break_seq = 0) 
				AND (break.break_id = onair_schedule.break_id) 
				AND (break.break_plan = onair_schedule.active_plan)
			GROUP BY break.break_id
		) AS total_advpending_time
		FROM onair_schedule
		INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
			AND onair_schedule.active_plan = breaktime.break_plan
		INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
		WHERE DATE_FORMAT(onair_schedule.datetime,'%m-%Y') = '$month-$year'
		AND ( programs.prog_id = '$program')
		GROUP BY breaktime.break_id";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}


	public function japiReadProdAgency($adv_id = 1) {
		$connection = Yii::app() -> db;

		$sql = "SELECT product.prod_id,product.prod_name,agency.agency_name,agency.agency_id,tape.time_len
						FROM product,tape,advertise,agency
						WHERE (tape.tape_id = advertise.tape_id)
						AND (tape.prod_id = product.prod_id)
						AND (agency.agency_id = advertise.agency_id)
						AND (advertise.active =  '1')
						AND (advertise.adv_id =$adv_id) ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}
	
	
	// ---------- Split ADV ---------------->
	
	public function japiAutoSplitAdv($adv_name = 0) {
		
			$connection=Yii::app()->db;
			$sql="SELECT `advertise`.`adv_id`,`advertise`.`adv_name` 
						FROM  `advertise`  
						WHERE  (advertise.adv_name LIKE  '".$adv_name."%')
						ORDER BY  `advertise`.`adv_name` ASC "; 
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);
	}	
	
	
	

	public function actionOnSplitadv() {
		if (Yii::app() -> request -> isPostRequest) {
			if (isset($_POST['org_adv_id'])) {

				$org_adv_id = 0;
				$org_prod_id = 0;
				
				$split_prod_id = array();
				$split_prod_name = array();
				$split_adv_name = array();
				$split_adv_timelen = array();
				$split_spot_price = array();
				$split_discount = array();
				

				$org_adv_id = $_POST['org_adv_id'];
				$org_prod_id = $_POST['org_prod_id'];
				
				$split_prod_id = $_POST['split_prod_id'];
				$split_prod_name = $_POST['split_prod_name'];
				$split_adv_name = $_POST['split_adv_name'];
				$split_adv_timelen = $_POST['split_adv_timelen'];
				$split_spot_price = $_POST['split_spot_price'];
				$split_discount = $_POST['split_discount'];
				
				$new_tapname = 0;
				$new_prodname = 0;

				$connection = Yii::app() -> db;

				$tape_count = 0;
				$new_tape_id = array();
				foreach ($split_prod_id as $split_prod_id_value) {// Insert Tape parrallel with ADV and retieve tape id
					if($split_prod_id_value == $org_prod_id){
						
						$adv_tapeid_sql = "SELECT * FROM advertise WHERE  advertise.adv_id = '$org_adv_id'";
						$command = $connection -> createCommand($adv_tapeid_sql);
						$dataReader = $command -> query();
						$adv_tapeid_all = $dataReader -> readAll();		
						
						foreach ($adv_tapeid_all as $adv_tapeid_value) {
							
							$new_tape_id[$tape_count] = $adv_tapeid_value['tape_id'];
							
						}												
						
					}else if($split_prod_id_value != $org_prod_id && $split_prod_id_value != ""){
						
						$adv_tapeid_sql = "SELECT * FROM tape WHERE  tape.prod_id = '$split_prod_id_value'";
						$command = $connection -> createCommand($adv_tapeid_sql);
						$dataReader = $command -> query();
						$adv_tapeid_all = $dataReader -> readAll();		
						
						foreach ($adv_tapeid_all as $adv_tapeid_value) {
							
							$new_tape_id[$tape_count] = $adv_tapeid_value['tape_id'];
							
						}

					}else{
						
						$new_prodname = $split_prod_name[$tape_count];
						
						$product_sql = "INSERT INTO `product` (`prod_id`, `prod_name`, `prod_desc`, `customer`)
										VALUES (NULL, '$new_prodname', '', '')";
						
						$command = $connection -> createCommand($product_sql);
						$command -> execute();
						$new_prod_id = Yii::app() -> db -> getLastInsertID();	
												
						$new_timelength = $split_adv_timelen[$tape_count];
						$new_tapname = $split_adv_name[$tape_count];
						
						$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`)
									 VALUES ( NULL ,  '$new_prod_id',  '$new_tapname',  '$new_timelength' )";
									 
						$command = $connection -> createCommand($tape_sql);
						$command -> execute();
						$new_tape_id[$tape_count] = Yii::app() -> db -> getLastInsertID();	

					}

					$tape_count++;
				}

				$adv_info_sql = "SELECT * FROM advertise WHERE  advertise.adv_id = '$org_adv_id'";
				// ---> Old advertise info queryy
				$command = $connection -> createCommand($adv_info_sql);
				$dataReader = $command -> query();
				$adv_info_all = $dataReader -> readAll();

				$onair_pkg_id = 0;
				$onair_agency_id = 0;
				$onair_price = 0;
				$onair_adv_discount = 0;
				$onair_adv_order_id = 0;

				foreach ($adv_info_all as $adv_info_all_value) {
					
					$onair_pkg_id = $adv_info_all_value['pkg_id'];
					$onair_agency_id = $adv_info_all_value['agency_id'];
					$onair_price = $adv_info_all_value['price'];
					$onair_adv_discount = $adv_info_all_value['discount'];
					$onair_adv_order_id = $adv_info_all_value['order_id'];
				}

				$count_time = 0;
				foreach ($split_adv_timelen as $timelen_value) {
					$split_adv_timelen_value[$count_time++] = $timelen_value;
				}

				$adv_count = 0;
				$adv_tap_id = 0;
				$adv_splited_time = 0;
				$new_split_spot_price = 0;
				$new_split_discount = 0;
				
				
				$new_adv_id = array();

				
				foreach ($split_adv_name as $split_adv_name_value) {
					
					$new_split_discount = $split_discount[$adv_count];
					$new_split_spot_price = $split_spot_price[$adv_count];
					$adv_tap_id = $new_tape_id[$adv_count];
					$adv_splited_time = $split_adv_timelen[$adv_count];
					
					$new_adv_sql = "INSERT INTO  `advertise` (`adv_id` ,`adv_name` ,`pkg_id` ,`tape_id` ,`agency_id` ,`price` ,`active` ,`adv_time_len` ,`discount`,`order_id`
						) VALUES ( NULL ,  '$split_adv_name_value',  '$onair_pkg_id',  '$adv_tap_id',  '$onair_agency_id',  '$new_split_spot_price',  '1',  '$adv_splited_time','$new_split_discount',  '$onair_adv_order_id')";

					$command = $connection -> createCommand($new_adv_sql);
					$command -> execute();
					$new_adv_id[$adv_count] = Yii::app() -> db -> getLastInsertID();
					$adv_count++;
				}

				$program_id = 0;
				$onair_year = 0;
				$onair_month = 0;
				$onair_day = 0;
				$time_onair = 0;
				$program_id = $_POST['prog_id'];
				$onair_year = $_POST['year'];
				$onair_month = $_POST['month'];
				$onair_day = $_POST['day'];

				$org_time_length = 0;
				$split_bk_seq = 0;
				$split_adv_seq = 0;
				$org_time_length = $_POST['time_org'];
				$split_bk_seq = $_POST['split_bk_seq'];
				$split_adv_seq = $_POST['split_adv_seq'];
				
				$split_break_id = 0;
				$split_break_id = $_POST['break_id'];
				
				$split_break_plan = 0;
				$split_break_plan = $_POST['break_plan'];
				
				
				
				//--------->  Query break property of the split advertise
				$break_prop_sql = "SELECT `break_plan`,`break_desc`,`break_type`,`calc_price`,`discount` 
																FROM `break` 
																WHERE (`break_id`='$split_break_id')
																AND(`break_seq`='$split_bk_seq')
																AND(`adv_seq`='$split_adv_seq')
																AND(`adv_id`='$org_adv_id')
																AND (break_plan = $split_break_plan)";
																
				$command = $connection -> createCommand($break_prop_sql);
				$dataReader = $command -> query();
				$break_prop_all = $dataReader -> readAll();

				$break_plan = 0;
				$break_desc = 0;
				$break_type = 0;
				$calc_price = 0;
				$discount = 0;

				foreach ($break_prop_all as $break_prop_value) {
					
					$break_plan = $break_prop_value['break_plan']; // Next STEP break PLAN
					$break_desc = $break_prop_value['break_desc'];
					$break_type = $break_prop_value['break_type'];
					$calc_price = $break_prop_value['calc_price'];
					$discount = $break_prop_value['discount'];
				}

				$price_per_sec = 0;
				$new_adv_price = 0;
				$price_per_sec = $calc_price / $org_time_length;
				$price_count = 0;
				$max_adv_seq = 0;
				$new_adv_seq = 0;

				foreach ($new_adv_id as $new_adv_id_value) {

					$new_advlog_sql = "INSERT INTO  `advertise_log` 
											(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
											VALUES ( NULL ,  '$new_adv_id_value',  '$org_adv_id',  '$program_id',  '$split_break_id', CURRENT_TIMESTAMP ,  'splited',  '')";

					$command = $connection -> createCommand($new_advlog_sql);
					$command -> execute();

					$max_advseq_sql = "SELECT Max(`adv_seq`) AS` max_adv_seq`  
										FROM `break` 
										WHERE (`break_id` = '$split_break_id')
										AND(`break_seq`= '$split_bk_seq')
										AND (break_plan = $split_break_plan)";
										
					$command = $connection -> createCommand($max_advseq_sql);
					$dataReader = $command -> query();
					$max_advseq_all = $dataReader -> readAll();

					$delete_old_advseq = " DELETE FROM `break` WHERE (`break_id`= '$split_break_id')
											AND(`break_seq`= '$split_bk_seq')
											AND(`adv_seq`= '$split_adv_seq')
											AND(`break_plan`= $split_break_plan)";

					$command = $connection -> createCommand($delete_old_advseq);
					$command -> execute();

					foreach ($max_advseq_all as $max_advseq_value) {
						$max_adv_seq = $max_advseq_value['max_adv_seq'];
					}
					
					$new_split_bkdiscount =  0;
					$new_split_bkspot_price = 0;
					$new_split_bkdiscount = $split_discount[$price_count];
					$new_split_bkspot_price = $split_spot_price[$price_count];
					
					$price_count++;
					$new_adv_seq = $max_adv_seq + 1;

					$newonair_sql = " INSERT INTO `break` (`break_id`, `break_seq`, `adv_seq`, `break_plan`, `break_desc`, `adv_id`, `break_type`,`calc_price`,`discount`)
										VALUES ('$split_break_id', '$split_bk_seq', '$new_adv_seq', '$split_break_plan', '$break_desc', '$new_adv_id_value','$break_type', '$new_split_bkspot_price','$new_split_bkdiscount')";
										
					$command = $connection -> createCommand($newonair_sql);
					$command -> execute();
				}

				$connection -> active = false;
			}
		}
	}
	
	// <---------- Split ADV ----------------
	
	// ----------- Old Merge ADV------------>

	public function japiSecMergeProgShow($program, $year = 2013, $month = 1, $day = 01, $plan = 0) {

		$connection = Yii::app() -> db;
		$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		$time_onair = 0;
		foreach ($progtime as $timevalue) {
			foreach ($timevalue as $value) {
				$time_onair = $value;
			}
		}
		// Advertise name should be tape name PLease Check !!!
		// How to manage Plan B

		$sql = " SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,break.break_desc,break.break_type,break.calc_price,break.discount
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
						AND (breaktime.break_seq = break.break_seq )
                        AND(breaktime.break_plan = break.break_plan)
						AND ( break.break_id =onair_schedule.break_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
                        AND (break.adv_id = advertise.adv_id)
						AND  (advertise.active =  '1')
						AND ( programs.prog_id ='$program')
						AND ( onair_schedule.datetime = '$year-$month-$day $time_onair')
						Order By break.break_seq ASC, break.adv_seq ASC";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiSecMergeBkseqShow($program, $year = 2013, $month = 1, $day = 01, $adv_id = 0, $plan = 0) {

		$connection = Yii::app() -> db;
		$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		$time_onair = 0;
		foreach ($progtime as $timevalue) {
			foreach ($timevalue as $value) {
				$time_onair = $value;
			}
		}
		// Advertise name should be tape name PLease Check !!!
		// How to manage Plan B

		$sql = " SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,break.break_desc,break.break_type,break.calc_price,break.discount
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
						AND (breaktime.break_seq = break.break_seq )
                        AND(breaktime.break_plan = break.break_plan)
						AND ( break.break_id =onair_schedule.break_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
                        AND (break.adv_id = advertise.adv_id)
						AND  (advertise.active =  '1')
						AND (advertise.adv_id = '$adv_id')
						AND ( programs.prog_id ='$program')
						AND ( onair_schedule.datetime = '$year-$month-$day $time_onair')
						Order By break.break_seq ASC, break.adv_seq ASC";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiSecMergeAdvseqShow($program, $year = 2013, $month = 1, $day = 01, $adv_id = 0, $break_seq = 0, $plan = 0) {

		$connection = Yii::app() -> db;
		$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		$time_onair = 0;
		foreach ($progtime as $timevalue) {
			foreach ($timevalue as $value) {
				$time_onair = $value;
			}
		}
		// Advertise name should be tape name PLease Check !!!
		// How to manage Plan B

		$sql = " SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,break.break_desc,break.break_type,break.calc_price,break.discount
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
						AND (breaktime.break_seq = break.break_seq )
                        AND(breaktime.break_plan = break.break_plan)
						AND ( break.break_id =onair_schedule.break_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
                        AND (break.adv_id = advertise.adv_id)
						AND  (advertise.active =  '1')
						AND (advertise.adv_id = '$adv_id')
						AND ( programs.prog_id ='$program')
						AND (break.break_seq = '$break_seq')
						AND ( onair_schedule.datetime = '$year-$month-$day $time_onair')
						Order By break.break_seq ASC, break.adv_seq ASC";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiSecMergeBktypeShow($program, $year = 2013, $month = 1, $day = 01, $adv_id = 0, $break_seq = 0, $adv_seq = 0, $plan = 0) {

		$connection = Yii::app() -> db;
		$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();
		$time_onair = 0;
		foreach ($progtime as $timevalue) {
			foreach ($timevalue as $value) {
				$time_onair = $value;
			}
		}
		// Advertise name should be tape name PLease Check !!!
		// How to manage Plan B

		$sql = " SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,break.break_desc,break.break_type,break.calc_price,break.discount
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
						AND (breaktime.break_seq = break.break_seq )
                        AND(breaktime.break_plan = break.break_plan)
						AND ( break.break_id =onair_schedule.break_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
                        AND (break.adv_id = advertise.adv_id)
						AND  (advertise.active =  '1')
						AND (advertise.adv_id = '$adv_id')
						AND ( programs.prog_id ='$program')
						AND (break.break_seq = '$break_seq')
						AND (break.adv_seq = '$adv_seq')
						AND ( onair_schedule.datetime = '$year-$month-$day $time_onair')
						Order By break.break_seq ASC, break.adv_seq ASC";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiReadMeregProg($comp_id = 0, $prog_id = 0) {

		$connection = Yii::app() -> db;
		$sql = "	SELECT  `programs`.`prog_id` ,  `programs`.`prog_name`,`program_owner`.`name` 
				FROM  `program_owner` ,  `programs` 
				WHERE (program_owner.company_id = programs.company_id)
				AND( programs.company_id = '$comp_id')
				AND (programs.minute_price = (SELECT  `programs`.`minute_price` FROM  `programs`  WHERE (programs.prog_id = '$prog_id')) )";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);

		/*
		 $connection=Yii::app()->db;
		 $sql="	SELECT `program_owner`.`company_id`, `program_owner`.`name`
		 FROM `program_owner` ,`programs`
		 WHERE (program_owner.company_id = programs.company_id)
		 AND (programs.prog_id = '$prog_id') ";
		 $command=$connection->createCommand($sql);
		 $dataReader=$command->query();
		 $rows=$dataReader->readAll();
		 $connection->active = false;
		 return ($rows);

		 */
	}

	public function japiMergeAdvProd($adv_id) {

		$connection = Yii::app() -> db;
		$sql = "SELECT product.prod_id,product.prod_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len
						FROM product,tape,advertise,agency
						WHERE (tape.tape_id = advertise.tape_id)
						AND (tape.prod_id = product.prod_id)
						AND (agency.agency_id = advertise.agency_id)
						AND (advertise.adv_id = '$adv_id') ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}
	
	//< ----------- Old Merge ADV------------

	public function japiProgList() {
		$connection = Yii::app() -> db;
		$sql = "SELECT * FROM `programs` ORDER BY  `programs`.`prog_name` ASC ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return array($rows);
	}

	public function japiAddadvProd($adv_id) {
		$connection = Yii::app() -> db;
		$sql = "SELECT product.prod_id,product.prod_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len
						FROM product,tape,advertise,agency
						WHERE (tape.tape_id = advertise.tape_id)
						AND (tape.prod_id = product.prod_id)
						AND (agency.agency_id = advertise.agency_id)
						AND (advertise.adv_id =$adv_id) ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return array($rows);
	}
	
	public function actionChangeBreakSeq() {
			if(Yii::app()->request->isPostRequest) {
					if(isset($_POST['break_seq'])) {
						
							$ch_break_plan = 0;
							$ch_break_seq = 0;
							$ch_prog_id  = 0;
							$ch_year = 0;
							$ch_month = 0;
							$ch_day = 0;		
							$ch_break_seq = $_POST['break_seq'];
							$ch_prog_id  = $_POST['prog_id'];
							$ch_year = $_POST['year'];
							$ch_month = $_POST['month'];
							$ch_day = $_POST['day'];
							
							$break_id = 0;
							$break_plan = 0;
							$break_plan = $_POST['bkplan'];
							$break_id = $_POST['break_id'];
							
							$connection=Yii::app()->db;
							
							// ----------> Find maximum advseq of waiting break 
							$max_advseq = 0;
							$max_seq_sql = "SELECT MAX(`adv_seq`) AS max_advseq  FROM `break` 
							WHERE (`break`.`break_id` = ' $break_id' ) 
							AND ( `break`.`break_seq` = '0')  
							AND  (`break`.`break_plan` = '$break_plan')";
							
							$command=$connection->createCommand($max_seq_sql);
							$dataReader=$command->query();
							$max_seq_all =$dataReader->readAll();	
													
							foreach ($max_seq_all as $max_seq_val){
									 $max_advseq =  $max_seq_val['max_advseq'];
							}
							
							$adv_seq_sql = "SELECT  `break`.`adv_seq` FROM  `break`
															WHERE  (`break`.`break_id` = ' $break_id' ) 							 											
															AND  (`break`.`break_seq` = '$ch_break_seq')   
															AND  (`break`.`break_plan` = '$break_plan')";

							$command=$connection->createCommand($adv_seq_sql);
							$dataReader=$command->query();
							$adv_seq_all =$dataReader->readAll();
							
							$old_advseq = 0;
							$new_advseq = 0;
							foreach ($adv_seq_all as $adv_seq_val){
									$old_advseq =  $adv_seq_val['adv_seq'];
									$new_advseq = $old_advseq +  $max_advseq;
									
									$update_bkseq_sql = "UPDATE  `break` 
									SET  `break_seq` =  '0', `adv_seq`= '$new_advseq'
									WHERE ( `break`.`break_id` = ' $break_id')  
									AND  (`break`.`break_seq` = '$ch_break_seq' )
									AND  (`break`.`adv_seq` = '$old_advseq')
									AND  (`break`.`break_plan` = '$break_plan') "; // -------> Should be changed when we think about PLAN												
									$command=$connection->createCommand($update_bkseq_sql);
									$command->execute();		
							}
							
							$delete_bk_sql= "DELETE FROM `break` WHERE `break_id` = $break_id 
											 AND `break_seq` = $ch_break_seq";
							
							$command=$connection->createCommand($delete_bk_sql);
							$command->execute();
							
							$delete_bk_sql = "DELETE FROM `breaktime` WHERE `break_id` =  $break_id
											  AND `break_seq` = $ch_break_seq";
							
							$command=$connection->createCommand($delete_bk_sql);
							$command->execute();
							
							$connection->active = false;
							
					}
			}
	}
	
	//------------------ Merge Function New---------------------> 

	public function japiCheckMeregProp($prog_id=0, $year = 0, $month = 0,$day = 0,$price_type=0,$agency_id =0,$break_seq=0,$adv_seq=0,$break_plan=0,$break_id=0) {
		
			$connection=Yii::app()->db;
			
			$sql_progtime = "SELECT Distinct(time(datetime)) AS datetime FROM onair_schedule WHERE onair_schedule.prog_id = '$prog_id' ";
			$command=$connection->createCommand($sql_progtime);
			$dataReader=$command->query();
			$progtime =$dataReader->readAll();
							 
			$time_onair  = 0;
			foreach ($progtime as $timevalue){
					$time_onair  = $timevalue['datetime'];
			}
			
				$sql=" SELECT break.break_seq,break.adv_seq,break.break_id, break.adv_id,breaktime.time_len,product.prod_id,product.prod_name,advertise.adv_name,agency.agency_id,agency.agency_name,tape.time_len,advertise.adv_time_len,break.break_desc,break.break_type,break.calc_price,break.discount
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
						AND (breaktime.break_seq = break.break_seq )
                        AND (breaktime.break_plan = break.break_plan)
						AND (break.break_id =onair_schedule.break_id)
						AND (breaktime.break_id =onair_schedule.break_id)
                        AND (break.adv_id = advertise.adv_id)
						AND (advertise.active =  '1')
						AND (agency.agency_id = '$agency_id')
						AND (break.break_type = '$price_type')
						AND (programs.prog_id ='$prog_id')
						AND (break.break_plan = '$break_plan')
						AND (onair_schedule.datetime = '$year-$month-$day $time_onair')
						Order By break.break_seq ASC, break.adv_seq ASC";
						
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);	
			
	}
	
	public function japiAutoMergeProd( $prod_name = 0) {
		
			$connection=Yii::app()->db;
			$sql="SELECT `product`.`prod_id`,  `product`.`prod_name` 
						FROM  `product`  
						WHERE  (product.prod_name LIKE  '".$prod_name."%')
						ORDER BY  `product`.`prod_name` ASC "; 
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);
	}	
	
	public function actionConfirmMergeAdv() {
		if(Yii::app()->request->isPostRequest) {
			if(isset($_POST['break_seq'])) {	
						
				$prod_id = 0;
				$prod_name = 0;
				$calc_price = 0;
				$adv_name = 0;
				$timelen = 0;
				$org_advid = 0;
				$prod_id = $_POST['prod_id'];
				$prod_name = $_POST['prod_name'];
				$adv_name = $_POST['adv_name'];			
				$timelen = $_POST['timelen'];	
				$org_advid =  $_POST['adv_id'];	
						 				
				$new_calc_price  = 0;
				$new_discount = 0;
				$break_seq = 0;
				$adv_seq = 0;
				$calc_price = 0;
				$break_id = 0;
				$break_seq = $_POST['break_seq'];
				$adv_seq = $_POST['adv_seq'];
				$new_calc_price = $_POST['calc_price'];			
				$break_id = $_POST['break_id'];	
				$new_discount = $_POST['discount'];		

				$pkg_id = 0;
				$agency_id = 0;
				$price = 0;
				$adv_discount =  0;
				$adv_order_id =  0;
				
				$Mbreak_plan = 0;
				$Mbreak_plan = $_POST['break_plan'];

				$connection=Yii::app()->db;
							
				$adv_info_sql = "SELECT * FROM advertise WHERE  advertise.adv_id = '$org_advid' "; // ---> Old advertise info queryy
				$command = $connection->createCommand($adv_info_sql);
				$dataReader = $command->query();
				$adv_info_all = $dataReader->readAll();
							
				foreach ($adv_info_all as 	$adv_info_all_value){
					$pkg_id = $adv_info_all_value['pkg_id'];
					$agency_id = $adv_info_all_value['agency_id'];
					$price = $adv_info_all_value['price'];
					$adv_discount =  $adv_info_all_value['discount'];
					$adv_order_id =  $adv_info_all_value['order_id'];
				}
						//--------> Insert Tape table and detect tape id --------->

								
				$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`
										) VALUES ( NULL ,  '$prod_id',  '$adv_name',  '$timelen' )";
				$command=$connection->createCommand($tape_sql);
				$command->execute();
				$new_tape_id = Yii::app()->db->getLastInsertID();

				$new_adv_sql = "INSERT INTO  `advertise` (`adv_id` ,`adv_name` ,`pkg_id` ,`tape_id` ,`agency_id` ,`price` ,`active` ,`adv_time_len` ,`discount`,`order_id`
						) VALUES ( NULL ,  '$adv_name',  '$pkg_id',  '$new_tape_id',  '$agency_id',  '$price',  '1',  '$timelen','$adv_discount',  '$adv_order_id')";
																		
				$command=$connection->createCommand($new_adv_sql);
				$command->execute();
				$new_adv_id = Yii::app()->db->getLastInsertID();
							
							//----------> Find breake ID --------  and add advertise log
							
							//--------->  Query break property of the split advertise
							
				$break_prop_sql ="SELECT `break_plan`,`break_desc`,`break_type`,`calc_price`,`discount` 
								FROM `break` 
								WHERE (`break_id`='$break_id')
								AND(`break_seq`='$break_seq')
								AND(`adv_seq`='$adv_seq')
								AND(`adv_id`='$org_advid')
								AND( break_plan = '$Mbreak_plan')";
								
								
				$command=$connection->createCommand($break_prop_sql);
				$dataReader=$command->query();
				$break_prop_all =$dataReader->readAll();
							
				$break_plan = 0;
				$break_desc =0;
				$break_type = 0;
				$calc_price = 0;
				$discount = 0;
							
				foreach($break_prop_all as $break_prop_value){
					
					$break_plan = $break_prop_value['break_plan'];
					$break_desc = $break_prop_value['break_desc'];
					$break_type = $break_prop_value['break_type'];
					$calc_price = $break_prop_value['calc_price'];
					$discount = $break_prop_value['discount'];
				}
							
				$old_prog_id = 0;
				$m_bkseq = array();
				$m_advseq = array();
				$m_advid = array();
				
				$m_bkseq = $_POST['m_bkseq'];
				$m_advseq = $_POST['m_advseq'];
				$m_advid = $_POST['m_advid'];	
				$old_prog_id =$_POST['old_prog_id'];
							
				//---- Main ADV ------- 
				
				$new_advlog_sql = "INSERT INTO  `advertise_log` 
				(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
				VALUES ( NULL ,  '$new_adv_id',  '$org_advid',  '$old_prog_id',  '$break_id', CURRENT_TIMESTAMP ,  'merged',  '')";	
																				
				$command=$connection->createCommand($new_advlog_sql);
				$command->execute();
				
				
				//----- Parent ADV ------
				foreach($m_advseq as $seqkey => $m_advseqvalue){
					
					$m_advlog_sql = "INSERT INTO  `advertise_log` 
					(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
					VALUES ( NULL ,  '$new_adv_id',  '$m_advid[$seqkey]',  '$old_prog_id',  '$break_id', CURRENT_TIMESTAMP ,  'merged',  '')";	
																					
					$command=$connection->createCommand($m_advlog_sql);
					$command->execute();
					
					$delete_old_advseq = " DELETE FROM `break` WHERE (`break_id`= '$break_id')
										AND(`break_seq`= '$m_bkseq[$seqkey]')
										AND(`adv_seq`= '$m_advseqvalue')
										AND(`break_plan`= '$Mbreak_plan' )";//<------------- Plan 
								
					$command=$connection->createCommand($delete_old_advseq);
					$command->execute();

				}
											
				$break_update_sql = "UPDATE  `break` SET  `adv_id` =  '$new_adv_id', `calc_price`='$new_calc_price',`discount` ='$new_discount'  WHERE  `break`.`break_id` ='$break_id'  
									AND  `break`.`break_seq` ='$break_seq'  AND  `break`.`adv_seq` ='$adv_seq' 
									AND `break`.`adv_id` = '$org_advid'
									AND  `break`.`break_plan` ='$Mbreak_plan'";//<------------- Plan 
									
				$command=$connection->createCommand($break_update_sql);
				$command->execute();
											
				$connection->active = false;
								
			}		
		}
	}
	
	// <-------------- Merge ADV ------------------


//--------------  History Table ------------------>

	
	public function japiTooltipHistory($adv_id = 0) {
		
			$connection=Yii::app()->db;
			
			$sql = "SELECT *  FROM advertise_log
					WHERE advertise_log.adv_id = $adv_id";
														
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);	
	}



	public function japiHistoryReader($onair_year,$onair_month,$onair_day,$onair_progid) {
		
			$connection=Yii::app()->db;
			
			
			$month = str_pad($onair_month,2,"0",STR_PAD_LEFT);
			$day = str_pad($onair_day,2,"0",STR_PAD_LEFT);
			
			$sql = "SELECT *  FROM `advertise_log`
					WHERE `operation` != 'create' 
					AND `operation` != ''
					AND `orig_break_id` IN(
					 	SELECT onair_schedule.break_id
						FROM onair_schedule 
						WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$onair_year'
						AND onair_schedule.prog_id = '$onair_progid'
					)
					ORDER BY advertise_log.adv_id ASC ";
														
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);	
	}


	public function japiDeterAdvHistory($adv_id=0) {
		
			$connection=Yii::app()->db;
			
			$sql = "SELECT * FROM `advertise_log` 
							WHERE (`advertise_log`.`adv_id` = '$adv_id')";
									
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);	
	}
	
	public function japiDeterMainAdv($adv_id=0) { // Split operation
	
			$connection=Yii::app()->db;
	
			$sql="SELECT prod_name,adv_name,time_len,adv_time_len,agency_name,tape_name
					FROM `advertise`,`product`,`agency`,`tape`,`advertise_log`
					WHERE (advertise.tape_id = tape.tape_id)
					AND (advertise_log.adv_id = advertise.adv_id)
					AND (product.prod_id = tape.prod_id )
					AND (agency.agency_id = advertise.agency_id)
					AND (advertise.adv_id = '$adv_id')";
																			
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);		
	
	}
	

	public function japiDeterOrigProg($adv_id=0) { // Moved (Come)
	
			$connection=Yii::app()->db;
	
			$sql="SELECT onair_schedule.datetime, programs.prog_name
				  FROM `onair_schedule`, programs 
				  WHERE  (onair_schedule.prog_id = programs.prog_id)
				  AND (programs.prog_id 
                     IN(SELECT orig_prog_id FROM advertise_log WHERE adv_id = '$adv_id' AND operation = 'move'
                  ))
				  AND (onair_schedule.break_id
                     IN(SELECT orig_break_id FROM advertise_log WHERE adv_id = '$adv_id' AND operation = 'move'
                  ))";
																			
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);		
	
	}
	
	public function japiDeterDefProg($adv_id=0) { // Move (Go)
	
			$connection=Yii::app()->db;
	
			$sql="SELECT onair_schedule.datetime, programs.prog_name
				  FROM `onair_schedule`, programs 
				  WHERE  (onair_schedule.prog_id = programs.prog_id)
				  AND (programs.prog_id 
                     IN(SELECT orig_prog_id FROM advertise_log WHERE adv_id = '$adv_id' AND operation = 'moved'
                  ))
				  AND (onair_schedule.break_id
                     IN(SELECT orig_break_id FROM advertise_log WHERE adv_id = '$adv_id' AND operation = 'moved'
                  ))";
																			
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);		
	
	}
	
	public function japiDeterAdvHisParent($adv_id=0) { // Split operation
		
			$operate =  "splited";
		
			$connection=Yii::app()->db;
							
			$sql="SELECT prod_name,adv_name,time_len,adv_time_len,agency_name,tape_name,advertise.adv_id
				FROM `advertise`,`product`,`agency`,`tape`,`advertise_log` 
				WHERE  advertise.adv_id IN( 
					SELECT  `advertise_log`.`adv_id`  FROM `advertise_log` 
					WHERE `operation`='$operate' AND (advertise_log.orig_adv_id = '$adv_id')
				)
				AND (advertise.tape_id = tape.tape_id)
				AND (advertise_log.adv_id = advertise.adv_id)
				AND (product.prod_id = tape.prod_id )
				AND (agency.agency_id = advertise.agency_id)";
										
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			return ($rows);	
	}
	
	public function japiDeterAdvHisParentM($adv_id=0) { 
		
			$operate =  "merged";
		
			$connection=Yii::app()->db;		
							
			$sql="SELECT prod_name,adv_name,time_len,adv_time_len,agency_name,tape_name,advertise.adv_id
				 FROM `advertise`,`product`,`agency`,`tape`,`advertise_log` 
				WHERE  advertise.adv_id IN( 
					SELECT  `advertise_log`.`orig_adv_id`  FROM `advertise_log` 
					WHERE `operation`='$operate' AND (advertise_log.adv_id = '$adv_id')
				)
				AND (advertise.tape_id = tape.tape_id)
				AND (advertise_log.adv_id = advertise.adv_id)
				AND (product.prod_id = tape.prod_id )
				AND (agency.agency_id = advertise.agency_id)";
			//print($sql);	
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			//print_r($rows);
			return ($rows);	
	}
		
//<------------End of History Table ----------------


	public function japiAdvOnairList() {

		$connection = Yii::app() -> db;
		$sql = "SELECT advertise.adv_id,  `advertise`.`adv_name` 
						FROM  `advertise`  
						WHERE  (advertise.active =  '1')
						ORDER BY  `advertise`.`adv_name` ASC ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows);
	}

	public function japiAddadvAgency() {
		$connection = Yii::app() -> db;
		$sql = "SELECT * FROM `agency` ORDER BY  `agency`.`agency_name` ASC  ";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return array($rows);
	}

	public function actions() {
		return array('japi' => 'JApi', );
	}

	/// dropdown list move program
	public function japiProgListForMove($program_id, $onair_year, $onair_month, $onair_day) {

		$connection = Yii::app() -> db;
		
		/*

		$sql_progtime = "SELECT Distinct(time(datetime)) AS datetime
		FROM onair_schedule WHERE onair_schedule.prog_id = '$program_id' ";
		$command = $connection -> createCommand($sql_progtime);
		$dataReader = $command -> query();
		$progtime = $dataReader -> readAll();

		foreach ($progtime as $timevalue) {
			$time_onair = $timevalue['datetime'];
		}
		$break_id_sql = "SELECT onair_schedule.break_id
		FROM  `onair_schedule` ,  `programs`
		WHERE (programs.prog_id = onair_schedule.prog_id)
		AND (programs.prog_id =  '$program_id')
		AND (onair_schedule.datetime =  '$onair_year-$onair_month-$onair_day $time_onair')";
		*/
		
		/*
		$month = str_pad($onair_month,2,"0",STR_PAD_LEFT);
		$day = str_pad($onair_day,2,"0",STR_PAD_LEFT);
		
		
		$break_id_sql="SELECT breaktime.break_seq,breaktime.time_len,onair_schedule.break_id
						FROM onair_schedule 
						INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
						INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
						WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$onair_day-$onair_month-$onair_year'
						AND programs.prog_id = $program_id
						Order By breaktime.break_seq ASC";
						
		$command = $connection -> createCommand($break_id_sql);
		$dataReader = $command -> query();
		$break_id_all = $dataReader -> readAll();

		$split_break_id = 0;
		foreach ($break_id_all as $break_id_value) {
			$split_break_id = $break_id_value['break_id'];
		}
		
		*/

		
	/*
		$sql = "select  p1.prog_id,p1.prog_name  from programs p1
		inner join  program_owner o1 on p1.company_id = o1.company_id
		inner join
		(
		select p.prog_id, p.minute_price,owner.name   from programs p
		inner join  program_owner owner on p.company_id = owner.company_id
		inner join onair_schedule  onair on p.prog_id = onair.prog_id
		where onair.break_id  = $split_break_id
		) p2 on p1.minute_price = p2.minute_price and o1.name = p2.name";
	*/	
		
		$sql = "SELECT * FROM  `programs` 
				WHERE  `minute_price` IN (
				
					SELECT  `minute_price` 
					FROM programs
					WHERE  `prog_id` = '$program_id'
				)
				ORDER BY  `programs`.`prog_name` ASC ";

		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;

		return ($rows);
	}

	public function getOnairSchedule($program_id, $onair_year, $onair_month, $onair_day) {

		$connection = Yii::app() -> db;
		
		$month = str_pad($onair_month,2,"0",STR_PAD_LEFT);
		$day = str_pad($onair_day,2,"0",STR_PAD_LEFT);
		
		$break_id_sql="SELECT breaktime.break_seq,breaktime.time_len,onair_schedule.break_id
						FROM onair_schedule 
						INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id 
						INNER JOIN breaktime ON onair_schedule.break_id = breaktime.break_id 
						WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$onair_year'
						AND programs.prog_id = $program_id
						Order By breaktime.break_seq ASC";
						
		$command = $connection -> createCommand($break_id_sql);
		$dataReader = $command -> query();
		$break_id_all = $dataReader -> readAll();

		$split_break_id = 0;
		foreach ($break_id_all as $break_id_value) {
			$split_break_id = $break_id_value['break_id'];
		}
		
		$connection -> active = false;
		return $split_break_id;
	}

	
	public function actionOnMoveConfirm() {
		if(Yii::app()->request->isPostRequest) {
			if(isset($_POST['old_move_day'])) {	
						
				$old_move_day = 0;
				$old_move_month = 0;
				$old_move_year = 0;
				$old_move_progid = 0;
				$ori_adv_id = 0;
				$break_plan = 0;
				$ori_break_seq = 0;
				$ori_adv_seq = 0;
				$new_move_progid = 0;
				$new_move_day = 0;
				$new_move_month = 0;
				$new_move_year = 0;
				
				$old_move_progid = $_POST['old_move_progid'];
				$old_move_day = $_POST['old_move_day'];
				$old_move_month = $_POST['old_move_month'];
				$old_move_year = $_POST['old_move_year'];
				$ori_adv_id = $_POST['ori_adv_id'];
				$break_plan = $_POST['break_plan'];
				$ori_break_seq = $_POST['ori_break_seq'];
				$ori_adv_seq = $_POST['ori_adv_seq'];
				$new_move_progid = $_POST['new_move_progid'];
				$new_move_day = $_POST['new_move_day'];
				$new_move_month = $_POST['new_move_month'];
				$new_move_year = $_POST['new_move_year'];
				
				
				$connection = Yii::app() -> db;
				
				
				$month = str_pad($old_move_month,2,"0",STR_PAD_LEFT);
				$day = str_pad($old_move_day,2,"0",STR_PAD_LEFT);
				
				$break_id_sql=" SELECT onair_schedule.break_id
								FROM onair_schedule 
								WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$day-$month-$old_move_year'
								AND onair_schedule.prog_id = $old_move_progid";
								
				$command = $connection -> createCommand($break_id_sql);
				$dataReader = $command -> query();
				$break_id_all = $dataReader -> readAll();
		
				$old_break_id = 0;
				foreach ($break_id_all as $break_id_value) {
					
					$old_break_id = $break_id_value['break_id'];
				}
				

				$new_move_month = str_pad($new_move_month,2,"0",STR_PAD_LEFT);
				$new_move_day = str_pad($new_move_day,2,"0",STR_PAD_LEFT);
				
				$break_id_sql=" SELECT onair_schedule.break_id
								FROM onair_schedule 
								WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y') = '$new_move_day-$new_move_month-$new_move_year'
								AND onair_schedule.prog_id = $new_move_progid";
								
				$command = $connection -> createCommand($break_id_sql);
				$dataReader = $command -> query();
				$breaknew_id_all = $dataReader -> readAll();
		
				$new_break_id = 0;
				foreach ($breaknew_id_all as $breaknew_id_value) {
					
					$new_break_id = $breaknew_id_value['break_id'];
				}
					
	
				if(isset($new_break_id)){ // Move when already have breakID
	
					$sql = "SELECT *  
							FROM  `break`  
							WHERE (break_id =  '$old_break_id')
							AND (break_plan =  '$break_plan')
							AND ( break_seq =  '$ori_break_seq')
							AND (adv_seq = '$ori_adv_seq')";
							
					$command = $connection -> createCommand($sql);
					$dataReader = $command -> query();
					$break_all = $dataReader -> readAll();
	
					$break_desc = 0;
					$adv_id = 0;
					$break_type = 0;
					$calc_price = 0;
					$discount = 0;				
					
					
					foreach ($break_all as $break_value) {
						//$old_break_row = $break_value;
					
						$break_desc = $break_value["break_desc"];
						$adv_id = $break_value["adv_id"];
						$break_type = $break_value["break_type"];
						$calc_price = $break_value["calc_price"];
						$discount = $break_value["discount"];
				
					}
				
					$new_advlog_sql = "INSERT INTO  `advertise_log` 
									(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
									VALUES ( NULL ,  '$ori_adv_id',  '$ori_adv_id',  '$old_move_progid',  '$old_break_id', CURRENT_TIMESTAMP ,  'move',  '')";
	
					$command = $connection -> createCommand($new_advlog_sql);
					$command -> execute();
					
					$bresk_pending_sql = "SELECT break_seq 
									FROM  `breaktime` 
									WHERE  `break_id` = '$new_break_id'
									AND  `break_seq` = '0' ";
									
					$command = $connection -> createCommand($bresk_pending_sql);
					$dataReader = $command -> query();
					$bresk_pending_all = $dataReader -> readAll();
	
					foreach ($bresk_pending_all as $bresk_pending_val) {
						
						$bresk_pending_seq = $bresk_pending_val["break_seq"];
					}									
					
				
					$sql = "SELECT  max(`adv_seq`) as max_adv_id  
							FROM  `break`  
							WHERE (break_id =  '$new_break_id')
							AND (break_plan =  '0') 
							AND ( break_seq =  '0')";
	
					$command = $connection -> createCommand($sql);
					$dataReader = $command -> query();
					$break_all = $dataReader -> readAll();
	
					foreach ($break_all as $break_value) {
						
						$new_adv_seq = $break_value["max_adv_id"];
					}
					
					if(isset($bresk_pending_seq)){
						
						$new_adv_seq = $new_adv_seq + 1;
						
					}else {
						
						// Add break sequence 0 in breaktime and break
						$sql = "INSERT INTO  `breaktime` (`break_id`, `break_seq`, `break_plan`, `time_len`, `timeoffset`) VALUES ('$new_break_id', '0', '0', '0', '0')";
						$command = $connection -> createCommand($sql);
						$command -> execute();
						
						$new_adv_seq = $new_adv_seq + 1;						
						
					}
					
					$sql = "INSERT INTO `break`(`break_id`, `break_seq`, `adv_seq`, `break_plan`, `break_desc`, `adv_id`, `break_type`, `calc_price`, `discount`) 
					VALUES ('$new_break_id','0','$new_adv_seq','0','$break_desc','$ori_adv_id','$break_type','$calc_price','$discount')";
		
					$command = $connection -> createCommand($sql);
					$command -> execute();
		
					$sql = "DELETE    
					FROM  `break`  
					WHERE (break_id =  '$old_break_id')
					AND (break_plan =  '$break_plan')
					AND ( break_seq =  '$ori_break_seq')
					AND (adv_seq = '$ori_adv_seq')";
		
					$command = $connection -> createCommand($sql);
					$dataReader = $command -> query();
		
					$new_advlog_sql = "INSERT INTO  `advertise_log` 
													(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
													VALUES ( NULL ,  '$ori_adv_id',  '$ori_adv_id',  '$new_move_progid',  '$new_break_id', CURRENT_TIMESTAMP ,  'moved',  '')";
		
					$command = $connection -> createCommand($new_advlog_sql);
					$command -> execute();					
					
					
				}else{
					
					return FALSE;
					
				}

				$connection -> active = false;
			}
		}
		
	}
	
	
		public function actionAddUpdateProd() {
			  if(Yii::app()->request->isPostRequest) {
					if(isset($_POST['tape_name'])){
						
						$add_prodname = 0;
						$add_advname = 0;
						$add_advtime = 0;
						$add_advinfo = 0;
						$prod_id = 0;
						$tape_id  = 0;
						$newadv_id = 0;
						
						$prod_id = $_POST['prod_id'];
						$add_prodname = $_POST['prod_name'];
						$add_advname = $_POST['tape_name'];
						$add_advtime = $_POST['timelen'];
	
						$connection=Yii::app()->db;
						
						$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`
						) VALUES ( NULL ,  '$prod_id',  '$add_advname',  '$add_advtime' )";
						$command=$connection->createCommand($tape_sql);
						$command->execute();
						$newtape_id  = Yii::app()->db->getLastInsertID();
						
						echo $newtape_id;
						
						$connection->active = false;
						
					}
			  }
		}
		
			
			public function actionAdd() {
			  if(Yii::app()->request->isPostRequest) {
					if(isset($_POST['tape_name'])){
						
						$add_agencyid = 0;
						$add_prodname = 0;
						$add_advname = 0;
						$add_advtime = 0;
						$add_advinfo = 0;
						$prod_id = 0;
						$tape_id  = 0;
						$newadv_id = 0;
						
						$add_prodname = $_POST['prod_name'];
						$add_advname = $_POST['tape_name'];
						$add_advtime = $_POST['timelen'];
	
						$connection=Yii::app()->db;
						
						$prod_sql =" INSERT INTO `product` (`prod_id` ,`prod_name` ,`prod_desc` ,`customer`
						) VALUES ( NULL ,  '$add_prodname',  '',  '')";
						$command=$connection->createCommand($prod_sql);
						$command->execute();
						$prod_id  = Yii::app()->db->getLastInsertID();
						
						$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`
						) VALUES ( NULL ,  '$prod_id',  '$add_advname',  '$add_advtime' )";
						$command=$connection->createCommand($tape_sql);
						$command->execute();
						$newtape_id  = Yii::app()->db->getLastInsertID();
						
						echo $newtape_id;
						
						$connection->active = false;
					}
			  }
			}



	public function japiMoveProg($program_id, $onair_year, $onair_month, $onair_day, $ori_break_plan, $ori_break_seq, $ori_adv_seq, $new_program_id, $new_onair_date) {
		try {
			$new_break_id = 0;
			$new_break_plan = 0;
			$new_break_seq = 0;
			$new_adv_seq = 0;

			$old_break_id = 0;
			$old_break_plan = $ori_break_plan;
			$old_break_seq = $ori_break_seq;
			$old_adv_seq = $ori_adv_seq;
			
			$connection = Yii::app() -> db;

			$old_onair_row = $this -> getOnairSchedule($program_id, $onair_year, $onair_month, $onair_day);

			$old_break_id = $old_onair_row["break_id"];
			$sql = "SELECT *  
					FROM  `break`  
					WHERE (break_id =  '$old_break_id')
					AND (break_plan =  '$old_break_plan')
					AND ( break_seq =  '$old_break_seq')
					AND (adv_seq = '$old_adv_seq')";
					
			$command = $connection -> createCommand($sql);
			$dataReader = $command -> query();
			$break_all = $dataReader -> readAll();
			$old_break_row = null;
			foreach ($break_all as $break_value) {
				//$old_break_row = $break_value;
			
				$break_desc = $break_value["break_desc"];
				$adv_id = $break_value["adv_id"];
				$break_type = $break_value["break_type"];
				$calc_price = $break_value["calc_price"];
				$discount = $break_value["discount"];
		
			}

			$new_advlog_sql = "INSERT INTO  `advertise_log` 
											(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
											VALUES ( NULL ,  '$adv_id',  '$adv_id',  '$program_id',  '$old_break_id', CURRENT_TIMESTAMP ,  'move',  '')";

			$command = $connection -> createCommand($new_advlog_sql);
			$command -> execute();

			$mdy = explode("/", $new_onair_date);

			$new_onair_row = $this -> getOnairSchedule($new_program_id, $mdy[2], $mdy[0], $mdy[1]);

			$newDateTime = date_create_from_format('m/d/Y', $new_onair_date) -> format('Y-m-d H:i:s');
			if ($new_onair_row) {
				//get max adv_seq
				$new_break_plan = $new_onair_row["active_plan"];
				$new_break_id = $new_onair_row["break_id"];

				$sql = "SELECT  max(`adv_seq`) as max_adv_id  
						FROM  `break`  
						WHERE (break_id =  '$new_break_id')
						AND (break_plan =  '$new_break_plan')
						AND ( break_seq =  '$new_break_seq')";

				$command = $connection -> createCommand($sql);
				$dataReader = $command -> query();
				$break_all = $dataReader -> readAll();

				foreach ($break_all as $break_value) {
					$new_adv_seq = $break_value["max_adv_id"] + 1;
				}

			} else {
				//insert new onair
				$sql = "INSERT INTO `onair_schedule`( `break_id`,`datetime`, `active_plan`, `prog_id`) 
			 	VALUES (NULL, '$newDateTime','$new_break_plan','$new_program_id')";
				$command = $connection -> createCommand($sql);
				$command -> execute();
				$new_break_id = Yii::app() -> db -> getLastInsertID();

			}
			$sql = "SELECT  `break_id` FROM `breaktime` WHERE `break_id` = '$new_break_id' AND `break_plan`='0' AND  `break_seq`='0'";
			$command = $connection -> createCommand($sql);
			$dataReader = $command -> query();
			$breaktime_all = $dataReader -> readAll();
			$breaktime_row = NULL;
			foreach ($breaktime_all as $breaktime_value) {
				$breaktime_row = $breaktime_value;
			}

			if ($breaktime_row == NULL) {
				$sql = "INSERT INTO  `breaktime` (`break_id`, `break_seq`, `break_plan`, `time_len`, `timeoffset`) VALUES ('$new_break_id', '0', '0', '0', '0')";
				$command = $connection -> createCommand($sql);
				$command -> execute();
			}
			

			$sql = "INSERT INTO `break`(`break_id`, `break_seq`, `adv_seq`, `break_plan`, `break_desc`, `adv_id`, `break_type`, `calc_price`, `discount`) 
			VALUES ('$new_break_id','$new_break_seq','$new_adv_seq','$new_break_plan','$break_desc','$adv_id','$break_type','$calc_price','$discount')";

			$command = $connection -> createCommand($sql);
			$command -> execute();

			$sql = "DELETE    
		FROM  `break`  
		WHERE (break_id =  '$old_break_id')
		AND (break_plan =  '$old_break_plan')
		AND ( break_seq =  '$old_break_seq')
		AND (adv_seq = '$old_adv_seq')";

			$command = $connection -> createCommand($sql);
			$dataReader = $command -> query();

			$new_advlog_sql = "INSERT INTO  `advertise_log` 
											(`advertise_log_id` ,`adv_id` ,`orig_adv_id` ,`orig_prog_id` ,`orig_break_id` ,`timestamp` ,`operation` ,`user_id`)
											VALUES ( NULL ,  '$adv_id',  '$adv_id',  '$program_id',  '$new_break_id', CURRENT_TIMESTAMP ,  'moved',  '')";

			$command = $connection -> createCommand($new_advlog_sql);
			$command -> execute();
			
			$connection -> active = false;
			
			return TRUE;
			echo $new_break_id;
		} catch (Exception $ex) {
			return FALSE;
		}
	}

}
?>