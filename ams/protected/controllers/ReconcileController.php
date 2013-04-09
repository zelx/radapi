<?php
class ReconcileController extends Controller {
	
	public function japiReconcileQueue($date = 0,$time = 0){
		
		$connection=Yii::app()->db;
				
		$sql="SELECT onair_schedule.datetime, programs.prog_name, programs.prog_id, onair_schedule.break_id
			FROM onair_schedule
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			WHERE DATE_FORMAT( onair_schedule.datetime,  '%d-%M-%Y %H:%i' ) <  '$date $time'
			AND onair_schedule.reconsile = 0
			ORDER BY programs.prog_id ASC , onair_schedule.break_id ASC ";
			  
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return ($rows); 	
				
	}
	
	
	public function japiUpdateRecocileq($break_id=0,$date = 0,$time = 0){
		
		$connection=Yii::app()->db;
		
		$update_recon_sql = "UPDATE  `onair_schedule` SET  `reconsile` =  '1' 
							 WHERE  `onair_schedule`.`break_id` = $break_id";
		
		
		$command=$connection->createCommand($update_recon_sql);
		$command->execute();
				
		$sql="SELECT onair_schedule.datetime, programs.prog_name, programs.prog_id, onair_schedule.break_id
			FROM onair_schedule
			INNER JOIN programs ON onair_schedule.prog_id = programs.prog_id
			WHERE DATE_FORMAT( onair_schedule.datetime,  '%d-%M-%Y %H:%i' ) <  '$date $time'
			AND onair_schedule.reconsile = 0
			ORDER BY programs.prog_id ASC , onair_schedule.break_id ASC ";
			  
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		
		return ($rows); 	
				
	}
	
	
	public function japiTodayProgram($date = 0){
		
		$connection=Yii::app()->db;
		
		$sql = "SELECT onair_schedule.datetime,DATE_FORMAT(onair_schedule.datetime,'%m-%Y') yr_mo,
DATE_FORMAT(onair_schedule.datetime,'%d') day ,
		onair_schedule.break_id,SUM(breaktime.timelimit) total_break_time,
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
		WHERE WHERE DATE_FORMAT( onair_schedule.datetime,  '%d-%M-%Y' ) <  '$date'
		GROUP BY breaktime.break_id";
		$command = $connection -> createCommand($sql);
		$dataReader = $command -> query();
		$rows = $dataReader -> readAll();
		$connection -> active = false;
		return ($rows); 	
				
	}
	
	public function actionAddPack(){
		if(Yii::app()->request->isPostRequest){
			
			if(isset($_POST['add_pkg_name'])){
				
				$add_pkg_name = 0;
				$add_pkg_start = 0;
				$add_pkg_end = 0;
				$add_pkg_desc = 0;
				
				$add_pkg_name = $_POST['add_pkg_name'];
				$add_pkg_start = $_POST['add_pkg_start'];
				$add_pkg_end = $_POST['add_pkg_end'];
				$add_pkg_desc = $_POST['add_pkg_desc'];

				$connection=Yii::app()->db;
				
				$sql="INSERT INTO `packages` (
						`pkg_id` ,`pkg_name` ,`pkg_date_start` ,
						`pkg_date_end` ,`pkg_desc` ,`saller` ,
						`user_id`
						)
						VALUES (NULL ,  '$add_pkg_name',  '$add_pkg_start',  '$add_pkg_end',  '$add_pkg_desc',  '',  '')";
					
				$command=$connection->createCommand($sql);
				$command->execute();
				$connection->active = false;
			}
		}
	}
	
	
	public function japiReadPackId($pkg_id = 0){
		
		$connection=Yii::app()->db;
				
		$sql="SELECT * FROM  `packages` WHERE `packages`.`pkg_id` = '$pkg_id'";
			  
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return ($rows); 	
				
	}
	
	public function actionEditPack(){
		if(Yii::app()->request->isPostRequest){
			if(isset($_POST['edit_pkg_name'])){
				
				$edit_pkg_id = 0;
				$edit_pkg_name = 0;
				$edit_pkg_start = 0;
				$edit_pkg_end = 0;
				$edit_pkg_desc = 0;
				
				$edit_pkg_id = $_POST['edit_pkg_id'];
				$edit_pkg_name = $_POST['edit_pkg_name'];
				$edit_pkg_start = $_POST['edit_pkg_start'];
				$edit_pkg_end = $_POST['edit_pkg_end'];
				$edit_pkg_desc = $_POST['edit_pkg_desc'];

				$connection=Yii::app()->db;
				
				$sql="UPDATE `packages` SET  `pkg_name` =  '$edit_pkg_name',
						`pkg_date_start` =  '$edit_pkg_start',
						`pkg_date_end` =  '$edit_pkg_end',
						`pkg_desc` =  '$edit_pkg_desc' 
					  WHERE  `packages`.`pkg_id` ='$edit_pkg_id'";
					
				$command=$connection->createCommand($sql);
				$command->execute();
				$connection->active = false;
			}
		}
	}
	


	public function actions()
    {
        return array(
            'japi'=>'JApi',
        );
    }
}
?>