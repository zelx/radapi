<?php
class QuotaController extends Controller {
	
			public function japiReaderAgency(){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT agency.agency_id, agency.agency_name,agency.parent_id, agency.agency_desc
						FROM agency
						ORDER BY agency.agency_name ASC ";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
				//return array('prog_id'=>'1','tv_program'=>'product1','numb_break'=>'agency1','total_break'=>'15','price_min'=>'agency1','pricepack_min'=>'15');
			}
	
	public function actionAddQuotaMinute() {
	  if(Yii::app()->request->isPostRequest)
	  {
		if(isset($_POST['id'])){
			
			list($idagency,$idday) = split('_',  $_POST['id']);
			$agency_id = substr($idagency,6);
			$day_id = substr($idday,-2);
			$value = $_POST['value'];
//			$start_date = $_POST['start_date'];
			$yr_mo = $_POST['yr_mo'];
			//echo "agency:".$agency_id.$day_id.' '.$yr_mo.";";
			$connection=Yii::app()->db;
			
			$max_order_sql = "SELECT date_start, monthly 
							  FROM quota 
							  WHERE agency_id = '$agency_id' 
							  AND DATE_FORMAT(date_start,'%Y-%m') = '$yr_mo'";	
							  
			$command=$connection->createCommand($max_order_sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			//echo $rows[0]['date_start'];
			$montly = split(",",$rows[0]['monthly']);
			if(intval($day_id) > 0)
				$montly[intval($day_id)-1] = $value;
			//echo implode(',', $montly);
			$datamonthly = implode(',', $montly);
			$sql = "UPDATE  quota SET monthly = '$datamonthly' WHERE agency_id = '$agency_id' 
							  AND DATE_FORMAT(date_start,'%Y-%m') = '$yr_mo'";
					
			$command=$connection->createCommand($sql);
			$command->execute();
	
			$connection->active = false;				

			//echo "aaa".$montly[intval($day_id)]."xxx";
		}
/*		if(isset($_POST['agency_id']))
		{
			$agency_id = $_POST['agency_id'];
			$start_date = $_POST['start_date'];
			$end_date=$_POST['end_date'];
			$minute = $_POST['minute'];
			
			$connection=Yii::app()->db;
			
			$max_order_sql = "SELECT MAX(change_order) AS maxOrderID 
							  FROM `quota` WHERE agency_id = '$agency_id' ";	
							  
			$command=$connection->createCommand($max_order_sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			
			foreach($rows as $maxvalue){
				
				$maxvalue = $maxvalue['maxOrderID'];
			}
			
			$change_order = $maxvalue + 1;
			//echo "maxvalue=".$change_order;
	
			$sql = "INSERT INTO `quota` (`agency_id`, `date_start`, `date_end`, `minute`, `change_order`, `prog_id`) 
					VALUES ('$agency_id', '$start_date', '$end_date', '$minute', '$change_order', '0')";
					
			$command=$connection->createCommand($sql);
			$command->execute();
	
			$connection->active = false;				
		
		}
		*/
	  }

		if(isset($_POST['value']))
		{
			echo $_POST['value'];
		}
	}
	
	public function actionAddQuotaMinMonth() {
	  if(Yii::app()->request->isPostRequest)
	  {
		if(isset($_POST['agency_id'])){
			
			$agency_id = $_POST['agency_id'];
			$start_date = $_POST['start_date'];
			$end_date=$_POST['end_date'];
			$minute = $_POST['minute'];
			$monthly = $_POST['monthly'];
			
			$connection=Yii::app()->db;
	
			$sql = "INSERT INTO `quota` (`agency_id`, `date_start`, `date_end`, `minute`, `change_order`, `prog_id`,`monthly`) 
					VALUES ('$agency_id', '$start_date', '$end_date', '$minute', '0', '0','$monthly')";
					
			$command=$connection->createCommand($sql);
			$command->execute();
	
			$connection->active = false;
			
			
		}
	  }
	}
			public function japiReaderQuota($yr_mo='2013-03'){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT agency.agency_id,agency.agency_name, quota.minute, quota.date_start, 
						quota.date_end, quota.change_order,quota.monthly 
						FROM `quota`,`agency` 
						WHERE (quota.agency_id = agency.agency_id)
						AND DATE_FORMAT(date_start,'%Y-%m') = '$yr_mo'
						ORDER BY agency.agency_name ASC";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
				//return array('prog_id'=>'1','tv_program'=>'product1','numb_break'=>'agency1','total_break'=>'15','price_min'=>'agency1','pricepack_min'=>'15');
			}
			
	public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}
?>