<?php
class AdverController extends Controller {
	
			public function japiReaderProd($start_row =0, $stop_row=10, $search_str=""){
				
				$connection=Yii::app()->db;
				
				if($search_str=="undefined")
					$search_str = "";
				
				$sql="SELECT  tape.tape_id,tape.tape_name, product.prod_id, product.prod_name, product.prod_desc, tape.time_len
						FROM  tape, product
						WHERE (tape.prod_id = product.prod_id)";
				if($search_str!="")
				
					$sql .= " and product.prod_name like '%".$search_str."%' ";		
					$sql .= "ORDER BY  `tape`.`tape_id` DESC
						LIMIT $start_row,$stop_row ";

				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				

			}
			
			public function japiAutoProdName( $prod_name = 0) {  // Auto Complete Product
				
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
						$add_advinfo = $_POST['adv_info'];
	
						$connection=Yii::app()->db;
						
						$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`
						) VALUES ( NULL ,  '$prod_id',  '$add_advname',  '$add_advtime' )";
						$command=$connection->createCommand($tape_sql);
						$command->execute();
						
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
						$add_advinfo = $_POST['adv_info'];
	
						$connection=Yii::app()->db;
						
						$prod_sql =" INSERT INTO `product` (`prod_id` ,`prod_name` ,`prod_desc` ,`customer`
						) VALUES ( NULL ,  '$add_prodname',  '$add_advinfo',  '')";
						$command=$connection->createCommand($prod_sql);
						$command->execute();
						$prod_id  = Yii::app()->db->getLastInsertID();
						
						$tape_sql = "INSERT INTO  `tape` (`tape_id` ,`prod_id` ,`tape_name` ,`time_len`
						) VALUES ( NULL ,  '$prod_id',  '$add_advname',  '$add_advtime' )";
						$command=$connection->createCommand($tape_sql);
						$command->execute();
						
						$connection->active = false;
					}
			  }
			}
			
			public function japiReadUpdateAdv($tape_id=0, $prod_id =0){
				
				$connection=Yii::app()->db;
				$sql="SELECT product.prod_id, product.prod_name, product.prod_desc,tape.tape_id,tape.tape_name,tape.time_len
						FROM tape, product
						WHERE(tape.prod_id = product.prod_id)
						AND (tape.tape_id = '$tape_id' ) ";
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
				
			}
			
			public function actionUpdateProdAdv() {
				  if(Yii::app()->request->isPostRequest) {
						if(isset($_POST['tape_name'])){
							
									$update_prod_id = 0;
									$update_adv_id = 0;
									$update_advprod = 0;
									$update_advname = 0;
									$update_advagency = 0;
									$update_advtime = 0;
									$update_advinfo = 0;
									$update_tapetime  = 0;
									$update_tape_id	= 0;
									
									$update_prod_id = $_POST['prod_id'];
									$update_advprod = $_POST['prod_name'];
									$update_advinfo = $_POST['adv_info'];
									
									$update_advname = $_POST['tape_name'];
									$update_tapetime = $_POST['tape_timelen'];
									$update_tape_id = $_POST['tape_id'];
				
									$connection=Yii::app()->db;
									
									 $update_prod_sql = "UPDATE  `product` SET  `prod_name` =  '$update_advprod', `prod_desc`='$update_advinfo'  WHERE  `product`.`prod_id` ='$update_prod_id'";
									 $command=$connection->createCommand($update_prod_sql);
									 $command->execute();
									 
									 $update_tape_sql = "UPDATE  `tape` SET  `tape_name` =  '$update_advname', `time_len`='$update_tapetime'  WHERE  `tape`.`tape_id` ='$update_tape_id'";
									 $command=$connection->createCommand($update_tape_sql);
									 $command->execute();
									 
									 $connection->active = false;	
						}
				  }
			}
			
			public function actionDeleteProdAdv() {
				if(Yii::app()->request->isPostRequest) {
					if(isset($_POST['tape_id'])){

						$del_tape_id = 0;	
						$del_tape_id = $_POST['tape_id'];
									
						$connection=Yii::app()->db;
									
						$delete_tape_sql ="DELETE FROM `tape` WHERE `tape_id`= '$del_tape_id' ";
						$command=$connection->createCommand($delete_tape_sql);
						$command->execute();
									
						$connection->active = false;		
					}
				}
			}
			
			
			
			
			public function japiAdvagencyList(){
				
					$connection=Yii::app()->db;
					$sql="SELECT * FROM `agency` WHERE 1 ORDER By `agency_name`ASC"; 
					$command=$connection->createCommand($sql);
					$dataReader=$command->query();
					$rows=$dataReader->readAll();
					$connection->active = false;
					return ($rows);
			}
			
			public function japiProdList(){
				
					$connection=Yii::app()->db;
					$sql="SELECT * FROM `product` WHERE 1 ORDER By `prod_name`ASC"; 
					$command=$connection->createCommand($sql);
					$dataReader=$command->query();
					$rows=$dataReader->readAll();
					$connection->active = false;
					return ($rows);
			}
			public function japiAdvList(){
				
					$connection=Yii::app()->db;
					$sql="SELECT  `advertise`.`adv_id`,`advertise`.`adv_name`
						FROM  `advertise`
						WHERE (advertise.active =  '1')
						ORDER BY  `advertise`.`adv_name` ASC"; 
					$command=$connection->createCommand($sql);
					$dataReader=$command->query();
					$rows=$dataReader->readAll();
					$connection->active = false;
					return ($rows);
			}
			
			public function japiNextAdvList($first_adv = 0){
					if($first_adv != 0){
						
							$connection=Yii::app()->db;
							
							$sql="SELECT  DISTINCT(advertise.adv_id) AS `adv_id`,`advertise`.`adv_name`
								FROM  `advertise` 
								WHERE  (advertise.active =  '1')
								ORDER BY  `advertise`.`adv_name` ASC"; 
							$command=$connection->createCommand($sql);
							$dataReader=$command->query();
							$rows=$dataReader->readAll();
							$connection->active = false;
							
							return ($rows);
					}
			}
			
			public function japiReadProdAgency($adv_id=1) {
				
				$connection=Yii::app()->db;
				
				$sql="SELECT product.prod_id,product.prod_name,agency.agency_name,agency.agency_id,tape.time_len
						FROM product,tape,advertise,agency
						WHERE (tape.tape_id = advertise.tape_id)
						AND (tape.prod_id = product.prod_id)
						AND (agency.agency_id = advertise.agency_id)
						AND (advertise.active =  '1')
						AND (advertise.adv_id =$adv_id) ";
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