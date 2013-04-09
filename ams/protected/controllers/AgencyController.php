<?php
class AgencyController extends Controller {

	public function japiAgencyList($start_row = 0, $stop_row =10,$search_str=""){
		
		$connection=Yii::app()->db;
		
		if($search_str=="undefined")
		{
			$search_str = "";
		 }
					
		// swordzoro fixed  2013/03/17 //
		if($search_str=="")
		{
		$sql="SELECT ag.agency_id,ag.agency_name,ag.parent_id,ag.agency_desc,ag.agency_tel,ag.agency_fax, 
			(SELECT agency_name FROM agency prn WHERE prn.agency_id = ag.parent_id) as parent_name, contact.tel,contact.firstname
			FROM agency ag
			LEFT JOIN contact ON ag.agency_id = contact.agency_id
			ORDER BY ag.agency_id ASC;
			LIMIT $start_row,$stop_row ";	
		}
		else
		{
		$sql="SELECT ag.agency_id,ag.agency_name,ag.parent_id,ag.agency_desc,ag.agency_tel,ag.agency_fax, 
			(SELECT agency_name FROM agency prn WHERE prn.agency_id = ag.parent_id) as parent_name, contact.tel,contact.firstname
			FROM agency ag
			LEFT JOIN contact ON ag.agency_id = contact.agency_id
			WHERE ag.agency_name LIKE '%".$search_str."%'
			ORDER BY ag.agency_id ASC;
			LIMIT $start_row,$stop_row ";
		}
	
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows=$dataReader->readAll();
		$connection->active = false;
		return array($rows); 				
	}
		public function japiReader($start_row = 0, $stop_row =5){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT agency.agency_id, agency.agency_name,agency.parent_id, agency.agency_desc
						FROM agency
						ORDER BY agency.agency_id DESC
						LIMIT $start_row,$stop_row ";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return array($rows); 				
			}
			
			public function japiParentAgency($parent_id = 0){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT agency.agency_name,agency.agency_tel,agency_fax, agency.agency_desc
							FROM agency
							WHERE  agency.agency_id = '$parent_id'";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}

			public function japiUpdateParentAgency($agency_id = 0){
				
				$parent_id = 0;
				$connection=Yii::app()->db;
				
				$sql="SELECT agency.parent_id
							FROM agency
							WHERE  agency.agency_id = '$agency_id'";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				
				foreach ($rows as 	$rows_value){
						$parent_id = $rows_value['parent_id'];
				}

				$sql="SELECT agency.parent_id,agency.agency_name
							FROM agency
							WHERE  agency.agency_id = '$parent_id'";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				
				$connection->active = false;
				return ($rows); 				
			}
			
			public function japiTelAgency($agency_id = 0){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT `tel` FROM `contact` WHERE `agency_id`='$agency_id '
							Order By `contact_id` ASC";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			public function actionCreateAgency() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['cr_agency_name'] )){
						$cr_agency_name = 0;
						$cr_agency_main = 0;
						$cr_agency_info = 0;
						$cr_agency_name = $_POST['cr_agency_name'];
						$cr_agency_main = $_POST['cr_agency_main'];
						$cr_agency_info = $_POST['cr_agency_info'];
						
						$cr_agency_tel = 0;
						$cr_agency_fax = 0;
						$cr_agency_tel = $_POST['tel'];
						$cr_agency_fax = $_POST['fax'];
						
						$connection=Yii::app()->db;
						
						$sql ="INSERT INTO  `agency` (`agency_id` ,`parent_id` ,`agency_name` ,`agency_tel`,`agency_fax`,`agency_desc`
									) VALUES (NULL ,  '$cr_agency_main',  '$cr_agency_name','$cr_agency_tel','$cr_agency_fax',  '$cr_agency_info')";
									
						$command=$connection->createCommand($sql);
						$command->execute();
						$connection->active = false;
					}
			  }
			}
			
			public function actionCreateAgencyNP() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['cr_agency_name'] )){
						$cr_agency_name = 0;
						$cr_agency_main = 0;
						$cr_agency_info = 0;
						$cr_agency_name = $_POST['cr_agency_name'];
						$cr_agency_info = $_POST['cr_agency_info'];
						
						$cr_agency_tel = 0;
						$cr_agency_fax = 0;
						$cr_agency_tel = $_POST['tel'];
						$cr_agency_fax = $_POST['fax'];
						
						$connection=Yii::app()->db;
						
						$sql ="INSERT INTO  `agency` (`agency_id` ,`parent_id` ,`agency_name` ,
									`agency_tel`,`agency_fax`,`agency_desc`
									) VALUES (NULL ,  '0',  '$cr_agency_name','$cr_agency_tel','$cr_agency_fax',  '$cr_agency_info')";
									
						$command=$connection->createCommand($sql);
						$command->execute();
						
						$connection->active = false;
					}
			  }
			}
			
			public function japiReadcontact($agency_id = 0){
				
				$connection=Yii::app()->db;
				
				$sql="SELECT *  FROM  `contact` WHERE contact.agency_id = '$agency_id '
							ORDER BY  `contact`.`agency_id` ASC   ";
		
				$command=$connection->createCommand($sql);
				$dataReader=$command->query();
				$rows=$dataReader->readAll();
				$connection->active = false;
				return ($rows); 				
			}
			
			public function actionCreatecontact() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['up_agency_name'] )){
						
						$cr_agency_id = 0;
						$cr_firstname = 0;
						$cr_contact_tel = 0;
						$cr_contact_mail = 0;
						$cr_contact_info = 0;
						
						$cr_contact_position = 0;
						$cr_contract_birthday = 0;
						$cr_contact_fax = 0;
						
						$cr_agency_id = $_POST['agency_id'];
						$cr_firstname = $_POST['up_agency_name'];
						$cr_contact_position = $_POST['position'];//
						$cr_contact_fax = $_POST['fax'];//
						$cr_contact_mail = $_POST['up_agency_email'];
						$cr_contact_tel = $_POST['up_agency_tel'];
						$cr_contract_birthday = $_POST['birthday'];//
						$cr_contact_info = $_POST['up_agency_info_cont'];
						
						$connection=Yii::app()->db;
						
						$sql ="INSERT INTO  `contact` (`contact_id` ,`agency_id` ,`lastname` ,`firstname` ,
									`tel` ,
									`position`,
									`mobile`,
									`fax`,
									`email` ,
									`birthday`,
									`desc`
									) VALUES ( NULL ,  '$cr_agency_id ','' , '$cr_firstname',  '$cr_contact_tel',  '$cr_contact_position','','$cr_contact_fax','$cr_contact_mail','$cr_contract_birthday',  '$cr_contact_info' )";
									
						$command=$connection->createCommand($sql);
						$command->execute();
						$connection->active = false;
					}
			  }
			}	
			
			public function japiUpdatecontact($contact_id  = 0) {
				
					$connection=Yii::app()->db;
					
					$sql="SELECT *  FROM  `contact`  WHERE contact.contact_id = '$contact_id ' ";
		
					$command=$connection->createCommand($sql);
					$dataReader=$command->query();
					$rows=$dataReader->readAll();
					$connection->active = false;
					return ($rows); 		
			}	
			
			public function actionEditcontact() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['ed_firstname'] )){
							
						$ed_contact_id = 0;
						$ed_firstname = 0;
						$ed_contact_tel = 0; 
						$ed_contact_email = 0;
						$ed_contact_info_cont = 0;
						$agency_id = 0;
						
						$position = 0;
						$birthday = 0;
						$fax = 0;
						
						$ed_contact_id =  $_POST['ed_contact_id'];
						$ed_firstname =  $_POST['ed_firstname'];
						$ed_contact_tel =  $_POST['ed_contact_tel'];
						$ed_contact_email =  $_POST['ed_contact_email'];
						$ed_contact_info_cont =  $_POST['ed_contact_info_cont'];
						
						$position = $_POST['position'];
						$birthday = $_POST['birthday'];
						$fax = $_POST['fax'];
						
						$agency_id =  $_POST['agency_id'];

						$connection=Yii::app()->db;
						
						$edit_sql = " UPDATE `contact` SET  
						`agency_id` =  '$agency_id',
						`firstname` =  '$ed_firstname',
						`tel` =  '$ed_contact_tel',
						`position` = '$position',
						`fax` = '$fax',
						`email` =  '$ed_contact_email',
						`birthday` = '$birthday',
						`desc` =  '$ed_contact_info_cont'  WHERE  `contact`.`contact_id` ='$ed_contact_id'";
		
						$command=$connection->createCommand($edit_sql);
						$command->execute();
						$connection->active = false;
					}
			  }
			}
			public function actionDeletecontact() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['contact_id'] )){
						$contact_id = 0;
						$contact_id =  $_POST['contact_id'] ;
						$connection=Yii::app()->db;
						$sql="DELETE FROM  `contact` WHERE  `contact`.`contact_id` ='$contact_id'";
						$command=$connection->createCommand($sql);
						$command->execute();
						$connection->active = false;
						
					}
			  }
			}
			
			public function actionEditAgency() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['edit_agency_id'] )){
							
						$ed_agency_id = 0;
						$ed_agency_name = 0;
						$ed_agency_main = 0;
						$ed_agency_info = 0;
						
						$ed_agency_id =  $_POST['edit_agency_id'];
						$ed_agency_name =  $_POST['edit_agency_name'];
						$ed_agency_main =  $_POST['edit_agency_main'];
						$ed_agency_info =  $_POST['edit_agency_info'];
						
						$ed_agency_tel = 0;
						$ed_agency_fax = 0;
						$ed_agency_tel =  $_POST['tel'];
						$ed_agency_fax =  $_POST['fax'];

						$connection=Yii::app()->db;
						
						$edit_sql = " UPDATE  `agency` SET  `parent_id` =  '$ed_agency_main',
												`agency_name` =  '$ed_agency_name',
												`agency_tel` = '$ed_agency_tel',
												`agency_fax` = '$ed_agency_fax',
												`agency_desc` =  '$ed_agency_info' 
												 WHERE  `agency`.`agency_id` ='$ed_agency_id'";

						$command=$connection->createCommand($edit_sql);
						$command->execute();
						$connection->active = false;
					}
			  }
			}
			
			public function actionDeleteAgency() {
			  if(Yii::app()->request->isPostRequest){
					if(isset($_POST['agency_id'] )){
						$agency_id = 0;
						$agency_id =  $_POST['agency_id'] ;
						$connection=Yii::app()->db;
						$sql="DELETE FROM  `agency` WHERE  `agency`.`agency_id` ='$agency_id'";
						$command=$connection->createCommand($sql);
						$command->execute();
						$connection->active = false;
						
					}
			  }
			}
			
	public function japiAgenList(){
		
			$connection=Yii::app()->db;
			$sql="SELECT * FROM `agency` ORDER By `agency_name`ASC"; 
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
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