<?php

class ImExportController extends Controller {
	private $m_sheetOrder;	

	public function dumpOrder($dumpdata)
	{
		foreach($dumpdata as $ordersheetIdx => $ordersheetfield) {
			print( "[$ordersheetIdx] => [$ordersheetfield] | ");
			foreach($ordersheetfield as $ordersheetIdx => $ordersheetfield) {
				print( "[$ordersheetIdx] => [$ordersheetfield] \n ");
			}			
		}
	}
	public function dumpAdvertis($dumpdata)
	{
		foreach($dumpdata as $ordersheetIdx => $ordersheetfield) {
			//print( "[$ordersheetIdx] => [$ordersheetfield] | ");
			//print( "$ordersheetfield[Date] -> $ordersheetfield[Advertise]" );
			//foreach($ordersheetfield as $orderFieldkey => $orderFieldData) {
			//	print( "$ordersheetfield[Date] : $ordersheetfield[Advertise] \n ");
			//}			
		}
	}
	public function readExcelOrder($inputFileName)
	{
		$result = array();
		$inputFileType = 'Excel5';
		//$inputFileName = 'toImport.xls';
		
		Yii::import('application.extensions.PHPExcel',true);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$sheetDatas = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
		foreach($sheetDatas as $sheetIndex => $sheetData) {
			if($sheetIndex > 1){
				foreach($sheetData as $sheetfieldIdx => $sheetfield) {
					//print( "[$sheetfieldIdx] => [$sheetfield] | ");
					$ordersheet[$sheetfieldIdx] = $sheetfield;
					//$orderdata[$sheetIndex]
				}
				$orderdata[$sheetIndex] = array(
					'Company' => $sheetData['A'],
					'Order' => $sheetData['B'],
					'Time' => $sheetData['C'],
					'Program' => $sheetData['D'],
					'Product' => $sheetData['E'],
					'Advertise' => $sheetData['F'],
					'Lenght' => $sheetData['G'],
					'Date' => $sheetData['H'],
					'DiscPect' => $sheetData['I'],
					'DiscPrice' => $sheetData['J'],
					'NetPrice' => $sheetData['K']
				);
				//print("$ordersheet");
				//foreach($ordersheet as $ordersheetIdx => $ordersheetfield) {
				//	print( "[$ordersheetIdx] => [$ordersheetfield] | ");
				//}

			}// if sheetIndex
		}// foreach sheetDatas
		return $orderdata;

	}
	public function validation_Advertise($sheetOrder)
	{
		$Adv = array();
		$Advertise = array();
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Adv,$ordersheetfield['Advertise']);
			
		}
		$Advertise = array_unique($Adv);
		foreach ($Advertise as  $adv)
		{
			//print("[");
			//print($adv);
			//print("]");
		}

		$AdvList = Yii::app()->db->createCommand()
			->select('adv_name')
			->from('advertise')
			->where(array('or like','adv_name', $Advertise))
			->queryAll();
		/*
		print_r($AdvList);
		print("data count:");
		print(count($AdvList));
		*/
	}
	
	public function validation_Tape($sheetOrder)
	{
		$Te = array();
		$Tape = array();
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Te,$ordersheetfield['Advertise']);
			
		}
		$Tape = array_unique($Te);
		foreach ($Tape as  $ta)
		{
			//print("[");
			//print($ta);
			//print("]");
		}

		$TapeList = Yii::app()->db->createCommand()
			->select('tape_name')
			->from('tape')
			->where(array('or like','tape_name', $Tape))
			->queryAll();
		
		//print_r($TapeList);
		//print("data count:");
		//print(count($TapeList));
		
	}

	public function insert_Tape($sheetOrder)
	{
		$Te = array();
		$Tape = array();
		$connection = Yii::app()->db;
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Te,$ordersheetfield['Advertise']);
//INSERT INTO tape (tape_name, prod_id)
//SELECT 'WARRANTY', prod_id FROM product WHERE prod_name LIKE 'SAMSUNG HHP'
			$sql="INSERT IGNORE INTO tape (tape_name,prod_id,time_len) SELECT '$ordersheetfield[Advertise]', prod_id,'$ordersheetfield[Lenght]' FROM product WHERE prod_name LIKE '$ordersheetfield[Product]'";
			$command=$connection->createCommand($sql);
			$command->execute();			
		}
		$Tape = array_unique($Te);
		$Tape_name = array();
/*		foreach ($Tape as  $ta)
		{
			$command=$connection->createCommand($sql);
			$command->execute();
			array_push($Tape_name,$ta);
		}*/
		//print_r($Tape);
	}


	public function insert_Advertise($sheetOrder)
	{
		$Adv = array();
		$Product = array();
		//$ProdList = Yii::app()->db->createCommand();
		$connection = Yii::app()->db;

			
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {

			
			$sql="INSERT IGNORE INTO advertise (adv_name,tape_id,adv_time_len,order_id,agency_id,price) SELECT '$ordersheetfield[Advertise]', tape_id,'$ordersheetfield[Lenght]','$ordersheetfield[Order]',agency.agency_id , '$ordersheetfield[NetPrice]' FROM tape,agency WHERE tape_name LIKE '$ordersheetfield[Advertise]' AND agency.agency_name LIKE '$ordersheetfield[Company]'";
			$command=$connection->createCommand($sql);
			$command->execute();	
			
			$sql="SELECT LAST_INSERT_ID()";
			$command = $connection->createCommand($sql);
			$advlastid = $command->queryAll();	
			array_push($Adv,array_values($advlastid[0]));
			//print(array_values($advlastid[0]));
			$advlast = array_values($advlastid[0]);
			//print($advlast[0]);
			//print($ordersheetfield[Advertise]);

			$datepart = split('/',$ordersheetfield['Date']);
			$onair_datetime = $datepart[0].'-'.$datepart[1].'-'.$datepart[2].' '.$ordersheetfield['Time'];
			
			$sql = "INSERT IGNORE INTO breaktime (break_id ,break_seq ,break_plan ,timelimit ,timeoffset) SELECT onair_schedule.break_id, '0', '0', '0', '0' FROM onair_schedule WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i') = '$onair_datetime'";
			//print($onair_datetime);
			//print($sql);
			$command=$connection->createCommand($sql);
			$command->execute();
			
			$sql = "INSERT IGNORE INTO break(break_id,break_plan,break_seq,adv_seq,adv_id) SELECT break_id,0,0,'$advlast[0]','$advlast[0]' FROM onair_schedule WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i') = '$onair_datetime'";
			//print($sql);
			$command=$connection->createCommand($sql);
			$command->execute();			
		}
		$Advertise = array_unique($Adv);
		$Adv_name = array();
/*		foreach ($Advertise as  $adv)
		{
			$sql="INSERT IGNORE INTO advertise (adv_name) VALUES('$adv')";
			$command=$connection->createCommand($sql);
			$command->execute();
			array_push($Adv_name,$adv);
		}*/
		//print_r($Adv);
			
	

		//print("data count:");
		//print(count($ProdList));
		
	}

	public function validation_Product($sheetOrder)
	{
		$Prod = array();
		$Product = array();
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Prod,$ordersheetfield['Product']);
			
		}
		$Product = array_unique($Prod);
		foreach ($Product as  $prod)
		{
			//print("[");
			//print($prod);
			//print("]");
		}

		$ProdList = Yii::app()->db->createCommand()
			->select('prod_name')
			->from('product')
			->where(array('or like','prod_name', $Product))
			->queryAll();
		
		//print_r($ProdList);
		//print("data count:");
		//print(count($ProdList));
		
	}

	public function insert_Product($sheetOrder)
	{
		$Prod = array();
		$Product = array();
		//$ProdList = Yii::app()->db->createCommand();
		$connection = Yii::app()->db;
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Prod,$ordersheetfield['Product']);
			
		}
		$Product = array_unique($Prod);
		$Prod_name = array();
		foreach ($Product as  $prod)
		{
			$prodrow['prod_name'] = $prod;
			//print($prodrow[0]);
			$sql="INSERT IGNORE INTO product (prod_name) VALUES('$prod')";
			$command=$connection->createCommand($sql);
			$command->execute();
			array_push($Prod_name,$prod);
			//$ProdList->insert('product',array('prod_name'=>$prod));
			
			//print("[");
			//print($prod);
			//print("]");
		}
		//print_r($Prod_name);
			
	

		//print("data count:");
		//print(count($ProdList));
		
	}
	
	public function validation_Program($sheetOrder)
	{
		$Prog = array();
		$Program = array();
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			array_push($Prog,$ordersheetfield['Program']);
			
		}
		$Program = array_unique($Prog);
		foreach ($Program as  $prg)
		{
			//print("[");
			//print($prg);
			//print("]");
		}

		$ProgList = Yii::app()->db->createCommand()
			->select('prog_name')
			->from('programs')
			->where(array('or like','prog_name', $Program))
			->queryAll();
		
		//print_r($ProgList);
		//print("data count:");
		//print(count($ProgList));
		
	}
	
	public function validation_ProgramByTime($sheetOrder)
	{
		$Progtime = array();
		$ProgramTime = array();
		$connection = Yii::app()->db;
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {
			//$PGTime = $ordersheetfield[Date]." ".$ordersheetfield[Time];
			$PGTime = date("m-d-Y ", strtotime($ordersheetfield['Date'])).$ordersheetfield['Time'];
			//print("B");
			//print($ordersheetfield[Date]);
			//print("H");
			$datepart = split('/',$ordersheetfield['Date']);
			$onair_datetime = $datepart[0].'-'.$datepart[1].'-'.$datepart[2].' '.$ordersheetfield['Time'];
			//print($onair_datetime);
			//print(date("m-d-Y", strtotime($ordersheetfield[Date])));
			//print("E");
			array_push($Progtime,$PGTime);
			/*
			$ProgList = Yii::app()->db->createCommand()
				->select('prog_id')
				->from('onair_schedule')
				->where('datetime=:datetime',array(':datetime'=>"DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i')", $onair_datetime))->gettext();*/
			//$sql="INSERT IGNORE INTO product (prod_name) VALUES('$prod')";
			$sql="SELECT prog_id,break_id FROM onair_schedule WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i') = '$onair_datetime'";
			$command=$connection->createCommand($sql);
			$ProgList = $command->queryAll();
			//print_r($ProgList[0]);
			
		}
		$OrderDate = array_unique($Progtime);
		foreach ($OrderDate as  $oddate)
		{
			//print("[");
			//print($oddate);
			//print("]");
		}

		//print("data count:");
		//print(count($ProgList));
		
	}


	public function import_Order($sheetOrder)
	{
		$Progtime = array();
		$ProgramTime = array();
		$connection = Yii::app()->db;
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {

			$datepart = split('/',$ordersheetfield['Date']);
			$onair_datetime = $datepart[0].'-'.$datepart[1].'-'.$datepart[2].' '.$ordersheetfield['Time'];
			//print($onair_datetime);
			//print(date("m-d-Y", strtotime($ordersheetfield[Date])));
			//print("E");
			array_push($Progtime,$PGTime);
			/*
			$ProgList = Yii::app()->db->createCommand()
				->select('prog_id')
				->from('onair_schedule')
				->where('datetime=:datetime',array(':datetime'=>"DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i')", $onair_datetime))->gettext();*/
			//$sql="INSERT IGNORE INTO product (prod_name) VALUES('$prod')";
			$sql="SELECT prog_id,break_id FROM onair_schedule WHERE DATE_FORMAT(onair_schedule.datetime,'%d-%m-%Y %H%i') = '$onair_datetime'";
			$command=$connection->createCommand($sql);
			$ProgList = $command->queryAll();
			//print_r($ProgList[0]);
			//print("test");
			
		}
		$OrderDate = array_unique($Progtime);
		foreach ($OrderDate as  $oddate)
		{
			//print("[");
			//print($oddate);
			//print("]");
		}

		//print("data count:");
		//print(count($ProgList));
		
	}
	public function validation_Order($sheetOrder)
	{
		$Ord = array();
		$Order = array();
		$OrderList = array("Fail:Order Duplicate");
		$connection = Yii::app()->db;
		foreach($sheetOrder as $ordersheetIdx => $ordersheetfield) {

			$datepart = split('/',$ordersheetfield['Date']);
			$onair_datetime = $datepart[0].'-'.$datepart[1].'-'.$datepart[2].' '.$ordersheetfield['Time'];

			array_push($Order,$ordersheetfield['Order']);


			
		}
		//print_r($Order);
		$Order = array_unique($Order);
		//print_r($Order[0]);
		$OrderList = Yii::app()->db->createCommand()
			->select('order_id,adv_id,adv_name')
			->from('advertise')
			->where(array('in','order_id', $Order))
			->queryAll();
			
/*		$sql="SELECT adv_id,adv_name FROM advertise WHERE order_id = '$Order'";
		$command=$connection->createCommand($sql);
		$OrderList = $command->queryAll();
		
*/		
		//$OrderList = array_unique($OrderList);
		//print_r($OrderList);
		return $OrderList;
	}
	public function japiImportAdvertise($orderfile)
	{
		$m_sheetOrder = $this->readExcelOrder($orderfile);
		$OrderValid = $this->validation_Order($m_sheetOrder);
		//print_r($OrderValid);
		if(count($OrderValid)){
			//print_r($OrderValid);
			return array("Fail:Order Duplicate",$OrderValid);
		}else{
			$this->insert_Product($m_sheetOrder);
			$this->insert_Tape($m_sheetOrder);
			$this->insert_Advertise($m_sheetOrder);
		}
		return;
		
	}
	/******************************************************************
	Programming schedule importing
	******************************************************************/
	public function readExcelProgram($inputFileName)
	{
		$result = array();
		$inputFileType = 'Excel5';
		$program = array();
		$ProgList = array();
		$CompList = array();
		$ProgImport = array();
		$program_cnt = 0;
		//$inputFileName = 'toImport.xls';
		
		Yii::import('application.extensions.PHPExcel',true);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objPHPExcel = $objReader->load($inputFileName);
		$sheetDatas = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	
		foreach($sheetDatas as $sheetIndex => $sheetData) {
			if($sheetIndex > 1){
				if(!strcmp('รายการ',$sheetData['A'])){
				}else{
					$program = preg_split("/^[\s]*[0-9]+\.[\s]*+|^[\s]*|[\s]+[(]|[)][\s]*$/",$sheetData['A']);
					if(!empty($program[1]) && !empty($program[2])){
						$program_cnt++;
						foreach($sheetData as $sheetfieldIdx => $sheetfield) {
							$ordersheet[$sheetfieldIdx] = $sheetfield;
						}
						$progtime = preg_split("/[\s][-][\s]|[\s]|[\.]$/",$sheetData['C']);
						array_push($ProgList,$program[1]);
						if(!empty($sheetData['B'])){
							array_push($CompList,$sheetData['B']);
						}
						$OnairDay = $program[2];
						$ThaiDay = array('จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์', 'อาทิตย์');
						$DayWeek = array('0', '1', '2', '3', '4', '5', '6');
						$OnairDayWeek = str_replace($ThaiDay, $DayWeek, $OnairDay);
						$dayweeklist = array();
						if(preg_match("/-/",$OnairDayWeek)){
							$dwrange = preg_split("/-/",$OnairDayWeek);
							foreach(range($dwrange[0],$dwrange[1]) as $dayweek){
								array_push($dayweeklist,$dayweek);
							}
						}else{
							$dayweeklist = preg_split("/,/",$OnairDayWeek);
						}

						if(!isset($ProgImport[$program[1]])){
							$ProgImport[$program[1]] = array();
						}
						foreach($dayweeklist as $dwidx => $dwval ){
							$intdw = $dwval + 1;
							if ($intdw > 6)
								$intdw = 0;
							array_push($ProgImport[$program[1]],array(
								'Program' => $program[1],
								'Onairdayweek' => $intdw,
								'Company' => $sheetData['B'],
								'ProgStart' => $progtime[0],
								'ProgEnd' => $progtime[1],
								'PriceMinute' => $sheetData['D'],
								'Discount' => $sheetData['E'],
								'PricePack' => $sheetData['G']
							));
						}

					}
				}
			}// if sheetIndex
		}// foreach sheetDatas

		/****** Program Master Database Validation *****/
		$ProgList = array_unique($ProgList);
		$ProgList = array_values($ProgList);
		$ProgMaster = Yii::app()->db->createCommand()
			->select('prog_name')
			->from('programs')
			->where(array('or like','prog_name', $ProgList))
			->queryAll();
		$ProgExisting = array();
		foreach($ProgMaster as $ProgIndex => $ProgData) {
			array_push($ProgExisting,$ProgData['prog_name']);
		}

		$ProgNotExist = array_diff($ProgList,$ProgExisting);
		$connection = Yii::app()->db;
		if(!empty($ProgNotExist)){
			$sql="INSERT IGNORE INTO programs (prog_name) VALUES ('".implode("'), ('", $ProgNotExist)."')";
			$command=$connection->createCommand($sql);
			$command->execute();
		}
		
		/****** Paid Company Master Database Validation *****/
		$CompList = array_unique($CompList);
		$CompList = array_values($CompList);
		$CompMaster = Yii::app()->db->createCommand()
			->select('comp_name')
			->from('company')
			->where(array('or like','comp_name', $CompList))
			->queryAll();
		$CompExisting = array();
		foreach($CompMaster as $CompIndex => $CompData) {
			array_push($CompExisting,$CompData['comp_name']);
		}
		$CompNotExist = array_diff($CompList,$CompExisting);
		$connection = Yii::app()->db;
		if(!empty($CompNotExist)){
			$sql="INSERT IGNORE INTO company (comp_name) VALUES ('".implode("'), ('", $CompNotExist)."')";
			$command=$connection->createCommand($sql);
			$command->execute();
		}
		
		
		
		$month = '04';
		$onairprof = 0;
		foreach($ProgImport as $ProgIndex => $ProgData) {
			//print($ProgIndex);
			$sql="SELECT onair_prof_id FROM program_schedule 
				INNER JOIN programs ON program_schedule.prog_id = programs.prog_id
				WHERE programs.prog_name LIKE '$ProgIndex'
				AND DATE_FORMAT(program_schedule.date_start,'%Y-%m') = '2013-".$month."'";
			$command=$connection->createCommand($sql);
			$OnairProfID = $command->queryAll();
			if(empty($OnairProfID)){
				$sql="INSERT IGNORE INTO program_schedule (prog_id,date_start) SELECT prog_id, '2013-".$month."-01' FROM programs WHERE prog_name LIKE '$ProgIndex'";
				$command=$connection->createCommand($sql);
				$command->execute();	
				$sql="SELECT LAST_INSERT_ID() as onair_prof_id";
				$command = $connection->createCommand($sql);
				$OnairProfID = $command->queryAll();	
				//print("get last id:");
				//print("[".$OnairProfID[0]['onair_prof_id']."]");
				$onairprof = $OnairProfID[0]['onair_prof_id'];
			}else{
				//print("[".$OnairProfID[0]['onair_prof_id']."]");
				$onairprof = $OnairProfID[0]['onair_prof_id'];
			}
			foreach($ProgData as $ProgIdx => $ProgVal){
				//print($ProgVal['Program']."DayWeek".$ProgVal['Onairdayweek']);
				$sql="INSERT IGNORE INTO onair_profile (onair_prof_id, dayweek_num,time_start,time_end,price_minute,comp_paid_id) VALUE( '".$onairprof."','".
				$ProgVal['Onairdayweek']."','".
				preg_replace('/\./',':',$ProgVal['ProgStart'])."','".
				preg_replace('/\./',':',$ProgVal['ProgEnd'])."','".
				$ProgVal['PriceMinute']."',".
				"(SELECT comp_id FROM company WHERE comp_name LIKE '".$ProgVal['Company']."'))";
				
				$command=$connection->createCommand($sql);
				$command->execute();	
				preg_replace('/\%/',' percentage',$realname); 
				
			}
		}

		return $orderdata;
	}

	public function genOnairSchedule($inputFileName = 01)
	{
		$connection = Yii::app()->db;


/*		echo date('D, M jS Y',  time()).'<br/>';
		echo date('D, M jS Y',  strtotime("first monday 2013-04-00")).'<br/>';
		echo date('D, M jS Y',  strtotime("second monday 2013-04-00")).'<br/>';
		echo date('D, M jS Y',  strtotime("third monday 2013-04-00")).'<br/>';
		echo date('D, M jS Y',  strtotime("fourth monday 2013-04-00")).'<br/>';
		echo date('D, M jS Y',  strtotime("fifth monday 2013-04-00")).'<br/>';*/
		//$day = str_pad($dayweek,2,"0",STR_PAD_LEFT);
		$month = '04';
		$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
		$order = array('first', 'second', 'third', 'fourth','fifth');
		for ($day = 0; $day <= 6; $day++){
			for ($week = 0; $week <= 4; $week++){
				if(intval(date('m', strtotime($order[$week]." ".$days[$day]." 2013-".$month."-00"))) == intval($month)){
					//echo $order[$week]." ".$days[$day]." 2013-".$month."-00".'<br/>';
					//echo date('D, M jS Y', strtotime($order[$week]." ".$days[$day]." 2013-".$month."-00")).'<br/>';
				}
			}
		}
		$sql="SELECT programs.prog_name,programs.prog_id, onair_profile.time_start,onair_profile.time_end, onair_profile.dayweek_num,onair_profile.onair_prof_id 
			FROM onair_profile
			INNER JOIN program_schedule ON onair_profile.onair_prof_id = program_schedule.onair_prof_id
			INNER JOIN programs ON program_schedule.prog_id = programs.prog_id
			WHERE DATE_FORMAT(program_schedule.date_start,'%Y-%m') = '2013-$month'
			ORDER BY programs.prog_id,onair_profile.dayweek_num ASC";
		$command=$connection->createCommand($sql);
		$ProgramSch = $command->queryAll();
		foreach($ProgramSch as $ProgIndex => $ProgData) {
			$day = intval($ProgData['dayweek_num']);
			for ($week = 0; $week <= 4; $week++){
				//echo date('D, M jS Y', strtotime($order[$week]." ".$days[$day]." 2013-".$month."-00")).'<br/>';
				if(intval(date('m', strtotime($order[$week]." ".$days[$day]." 2013-".$month."-00"))) == intval($month)){
					$datetime = date('Y-m-d',strtotime($order[$week]." ".$days[$day]." 2013-".$month."-00"))." ".$ProgData['time_start'];
					$onair_prof_id = $ProgData['onair_prof_id'];
					$prog_id = $ProgData['prog_id'];
					echo $ProgData['prog_name']." ".$datetime.":".$ProgData['onair_prof_id'];
					$sql="INSERT IGNORE INTO onair_schedule (prog_id,onair_prof_id,datetime)
					SELECT * FROM (SELECT '$prog_id' as prog_id,'$onair_prof_id' as onair_prof_id,'$datetime' as datetime) AS tmp
					WHERE NOT EXISTS (
					    SELECT prog_id,onair_prof_id  FROM onair_schedule 
					    WHERE prog_id = '$prog_id' 
					    	AND datetime = '$datetime'
					) LIMIT 1";
					$command=$connection->createCommand($sql);
					$command->execute();	
					
					$sql="SELECT LAST_INSERT_ID() as break_id";
					$command = $connection->createCommand($sql);
					$BreakID = $command->queryAll();	
					//print("get last id:");
					//print("[".$OnairProfID[0]['onair_prof_id']."]");
					$break_id = $BreakID[0]['break_id'];
					
					print("[break_id:".$break_id."]");
					
					$bkDayWeek = 0;
					$bkStartTime = 0;
					
					$bkDayWeek = $ProgData['dayweek_num'];
					$bkStartTime = $ProgData['time_start'];
					
					$bkTime_sql = "SELECT * 
								FROM  `onair_profile` 
								INNER JOIN break_profile ON onair_profile.break_prof_id = break_profile.break_prof_id
								WHERE onair_profile.onair_prof_id = $onair_prof_id
								AND onair_profile.time_start =  '$bkStartTime'
								AND onair_profile.dayweek_num =  '$bkDayWeek'";
								
					$command=$connection->createCommand($bkTime_sql);
					$dataReader=$command->query();
					$bkTime_all=$dataReader->readAll();							

					$break_seq = 0;
					$time_len = 0;
					$onairtime = 0;
					$breakTypeId = 0;
					
					foreach($bkTime_all as $bkTime_val){

						$break_seq = $bkTime_val['break_seq'];
						$time_len = $bkTime_val['time_len'];
						$onairtime = $bkTime_val['onairtime'];
						$breakTypeId = $bkTime_val['break_type_id'];
						
						$sql = "INSERT IGNORE INTO breaktime (break_id, break_seq, break_plan,onairtime,time_len,break_type_id) 
										 VALUES ('$break_id' , '$break_seq' , '0','$onairtime' ,'$time_len',$breakTypeId)";	
										 
						$command=$connection->createCommand($sql);
						$command->execute();
						
					}

				}
			}
		}
		
		$connection->active = false;
	}
	
	public function updateOnairProf(){
				
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
	
	public function japiImportProgram($progfile)
	{
		$m_sheetOrder = $this->readExcelProgram($progfile);
		
		$this->updateOnairProf();
		$this->genOnairSchedule();
/*		$OrderValid = $this->validation_Program($m_sheetOrder);
		//print_r($OrderValid);
		if(count($OrderValid)){
			//print_r($OrderValid);
			return array("Fail:Order Duplicate",$OrderValid);
		}else{
			$this->insert_Product($m_sheetOrder);
			$this->insert_Tape($m_sheetOrder);
			$this->insert_Advertise($m_sheetOrder);
		}
*/		return;
		
	}


	public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}
?>
