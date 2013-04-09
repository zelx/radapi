<?php
class PackController extends Controller {
	
	public function japiReaderPack(){
		
		$connection=Yii::app()->db;
				
		$sql="SELECT * FROM  `packages` 
			  ORDER BY  `packages`.`pkg_date_start` DESC,
			  `packages`.`pkg_date_end` DESC";
			  
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
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
	
	public function actionDeletePack() {
		if(Yii::app()->request->isPostRequest){
			if(isset($_POST['del_pkg_id'])){
				
				$del_pkg_id = 0;
				$del_pkg_id =  $_POST['del_pkg_id'];
				
				$connection=Yii::app()->db;
				
				$sql="DELETE FROM  `packages` WHERE  `packages`.`pkg_id` ='$del_pkg_id'";
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