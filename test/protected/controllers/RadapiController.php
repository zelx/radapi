<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class RadapiController extends CController
{
	/**
	 * Index action is the default action in a controller.
	 */
	


	public function actionIndex()
	{
	  $inputJSON = file_get_contents('php://input');
	  $input= json_decode( $inputJSON, TRUE ); //convert JSON into array

//	var_dump(self::Bar("test"));




	  if(empty($input)){
		  echo "WTF";die();
	  }
	

	  $status="Failed";

		//Create UserID
		if(array_key_exists('CREATE',$input)){
		  extract($input['CREATE']);
			if (isset($input['CREATE']['Password'])){
			  if(self::CheckUser($UserID)){
				if(!self::CheckPackageEmpty($Package)){
				  Yii::app()->db->createCommand()->insert("radusergroup", array(
					'username'=>$UserID,
					'groupname'=>$Package,
					'priority'=>1
					));

					self::InsertU("radcheck",$UserID,"MD5-Password",":=",md5($Password));
		
					if(!empty($input['CREATE']['UserID']));
					if(!empty($input['CREATE']['Password']));
					if(!empty($input['CREATE']['Package']));
					$status="Sucess";
				}
			}

					$response=array('CREATE'=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Password"=>$Password,
						"Package"=>$Package,
						"Duration"=>24,
						"MaxDL"=>self::CheckSpeedDL($UserID),
						"MaxUL"=>self::CheckSpeedUL($UserID),
						"Validity"=>"",
						"StartDate"=>"",
						"ExpiredDate"=>"",
						"MAC"=>""
					));		
		
					self::SendJson($response);

	  //Create Package
		} else if (isset($input['CREATE']['Package'])&&(isset($input['CREATE']['MaxDL'])||isset($input['CREATE']['ExpiredDate']))){

				//echo $Package;
			if(self::CheckPackageEmpty($Package)){
		
				self::InsertG("radgroupreply",$Package,"Service-Type",":=","Login-User");
				self::InsertG("radgroupreply",$Package,"Acct-Interim-Interval",":=","60");
				self::InsertG("radgroupreply",$Package,"Idle-Timeout",":=","300");
				self::InsertG("radgroupreply",$Package,"Session-Timeout",":=","10800");
				//self::InsertG("radgroupreply",$Package,"WISPr-Redirection-URL",":=","http://www.google.com");
			

				if(!empty($input['CREATE']['Package']))
				if(!empty($input['CREATE']['MaxDL']))self::InsertG("radgroupreply",$Package,"WISPr-Bandwidth-Max-Down",":=",$MaxDL*1024);
				if(!empty($input['CREATE']['MaxUL']))self::InsertG("radgroupreply",$Package,"WISPr-Bandwidth-Max-Up",":=",$MaxUL*1024);
				if(!empty($input['CREATE']['Validity']));
				if(!empty($input['CREATE']['StartDate']));
				if(!empty($input['CREATE']['ExpiredDate']))self::InsertG("radgroupcheck",$Package,"Expiration",":=",$ExpiredDate);
				
				self::InsertG("radgroupcheck",$Package,"Simultaneous-Use",":=","1");
				self::InsertG("radgroupcheck",$Package,"Auth-Type",":=","PAP");
				$status="Sucess";
			} else $status="Failed";
				
			  $response=array('CREATE'=>array(
						"Package"=>$Package,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,		
						"MaxDL"=>$MaxDL,
						"MaxUL"=>$MaxUL,
						"Validity"=>$Validity,
						"StartDate"=>$StartDate,
						"ExpiredDate"=>$ExpiredDate
					
					));		
		
					self::SendJson($response);

			//Add MAC
		  } else if (isset($input['CREATE']['MAC'])&&!empty($input['CREATE']['Package'])){
			  if(self::CheckUser($MAC)){
				if(!self::CheckPackageEmpty($Package)){
				echo "MAC";




				}
			  }
				


		  }

		}

		//Update 
		if(array_key_exists('UPDATE',$input)){
		  extract($input["UPDATE"]);
			if ($input['UPDATE']['Action']=="change"){
				if(isset($input['UPDATE']['Password'])){				
					if(self::ChangePassword($UserID,$Password)){
							$status="Sucess";
					} else	$status="Failed";

					$response=array("UPDATE"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Action"=>$Action,
						"password"=>$Password));

					self::SendJson($response);

				} else if(isset($input['UPDATE']['Package'])){
					if(!self::CheckPackageEmpty($Package)&&!self::CheckUser($UserID)){
					//echo "change package";
						if(self::ChangePackage($UserID,$Package)){
							$status="Sucess";
						}
					} else $status="Failed";

					$response=array("UPDATE"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Action"=>$Action,
						"Package"=>$Package,
						"Duration"=>"",
						"MaxDL"=>self::CheckSpeedDL($UserID),
						"MaxUL"=>self::CheckSpeedUL($UserID),
						"Validity"=>"",
						"StartDate"=>"",
						"ExpiredDate"=>"",
						"MAC"=>""));		
					self::SendJson($response);
					}
				} else if ($input['UPDATE']['Action']=="Delete"){ 
			  		if(self::DelUser($UserID))$status="Sucess";
					else $status="Failed";

					$response=array("UPDATE"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Action"=>$Action));		
					self::SendJson($response);
				} else if($input['UPDATE']['Action']=="Bar"){ 
				
					if(self::Bar($UserID))$status="Sucess";
					else $status="Failed";

					$response=array("UPDATE"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Action"=>$Action));		
					self::SendJson($response);





				} else if($input['UPDATE']['Action']=="Unbar"){ 
					if(self::UnBar($UserID))$status="Sucess";
					else $status="Failed";
					$response=array("UPDATE"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Action"=>$Action));		
					self::SendJson($response);
				} 


			}	
			
			



		
		//Read User
		if(array_key_exists('READ',$input)){
		extract($input["READ"]);
		
		  if (isset($input['READ']['UserID'])&&!self::CheckUser($UserID)){

				  $status="Sucess";

		  } else  $status="Failed";

			  $response=array("READ"=>array(
						"UserID"=>$UserID,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Package"=>self::CheckPackageUser($UserID),
						"Duration"=>"",
						"MaxDL"=>self::CheckSpeedDL($UserID),
						"MaxUL"=>self::CheckSpeedUL($UserID),
						"Validity"=>"",
						"StartDate"=>"",
						"ExpiredDate"=>self::CheckExpireDate($UserID),
						"MAC"=>""
					));		
		
					self::SendJson($response);
		}


		
	}






	public function ReadUserID()
	{

	}
	
	public function Bar($UserID)
	{
		$ban_msg="Ban";

		if(self::CheckUser($UserID))return false;
		$user = Yii::app()->db->createCommand()
			->select("*")
			->from('radcheck')
			->where(array('and','username=:username','value=:Reject','attribute=:att'), array(':username'=>$UserID,':Reject'=>"Reject",':att'=>"Auth-Type"))
			->queryScalar();

		if(empty($user)){
			if(!self::InsertU("radreply",$UserID,"Reply-Message",":=",$ban_msg))return false;
			if(!self::InsertU("radcheck",$UserID,"Auth-Type",":=","Reject"))return false;

			return true;
			}
		
	
			return false;
	}

	public function UnBar($UserID)
	{	

		if(self::CheckUser($UserID))return false;
		$user = Yii::app()->db->createCommand()
			->select("*")
			->from('radcheck')
			->where(array('and','username=:username','value=:Reject','attribute=:att'), array(':username'=>$UserID,':Reject'=>"Reject",':att'=>"Auth-Type"))
			->queryScalar();

		if(!empty($user)){
			self::DelU($UserID);


			return true;
		}
		else { 

			
			return false;
		}


	}

	public function InsertG($table,$groupname,$attribute,$op,$value)
	{
		$res=Yii::app()->db->createCommand()->insert($table, array(
			'groupname'=>$groupname,
			'attribute'=>$attribute,
			'op'=>$op,
			'value'=>$value
			));
		return $res;
	}

	public function InsertU($table,$username,$attribute,$op,$value)
	{
		$res=Yii::app()->db->createCommand()->insert($table, array(
			'username'=>$username,
			'attribute'=>$attribute,
			'op'=>$op,
			'value'=>$value
			));
		return $res;
	}

	public function DelU($UserID)
	{
		$res1=Yii::app()->db->createCommand()->delete("radcheck",
			array('and','username=:username','value=:Reject','attribute=:att'),
			array(':username'=>$UserID,':Reject'=>"Reject",':att'=>"Auth-Type"));
		
		$res2=Yii::app()->db->createCommand()->delete("radreply",
			array('and','username=:username','attribute=:att'),
			array(':username'=>$UserID,':att'=>"Reply-Message"));


		return $res1;
	}

	public function SendJson($response)
	{
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function CheckUser($UserID)
	{
		$user = Yii::app()->db->createCommand()
			->select("*")
			->from('radusergroup')
			->where('username=:username', array(':username'=>$UserID))
			->queryRow();

		return empty($user);
	}


	public function DelUser($UserID)
	{
		$command1=Yii::app()->db->createCommand()->delete('radcheck', 
				'username=:userid', 
				array(':userid'=>$UserID));
		$command2=Yii::app()->db->createCommand()->delete('radreply', 
				'username=:userid', 
				array(':userid'=>$UserID));
		$command3=Yii::app()->db->createCommand()->delete('radusergroup', 
				'username=:userid', 
				array(':userid'=>$UserID));

		return ($command1||$command2||$command3);
	}

	public function CheckPackageEmpty($Package)
	{
		$user = Yii::app()->db->createCommand()
			->select("*")
			->from('radgroupcheck')
			->where('groupname=:package', array(':package'=>$Package))
			->queryRow();

		return empty($user);
	}

	public function CheckPackageUser($UserID)
	{
		$package = Yii::app()->db->createCommand()
			->select("groupname")
			->from('radusergroup')
			->where('username=:userid', array(':userid'=>$UserID))
			->queryScalar();

		return $package;
	}

	public function CheckSpeedDL($UserID)
	{
		$sql = "select value from radgroupreply where groupname in (select groupname from radusergroup where username = :username) AND attribute=\"WISPr-Bandwidth-Max-Down\"";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":username",$UserID,PDO::PARAM_STR);
		$results=$command->queryRow();
		//var_dump($results);
		return $results['value']/1024;
	}

	public function CheckSpeedUL($UserID)
	{
		$sql = "select value from radgroupreply where groupname in (select groupname from radusergroup where username = :username) AND attribute=\"WISPr-Bandwidth-Max-Up\"";
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(":username",$UserID,PDO::PARAM_STR);
		$results=$command->queryRow();
		return $results['value']/1024;
	}


	public function CheckExpireDate($UserID)
	{
		$user = Yii::app()->db->createCommand()
			->select("value")
			->from('radgroupcheck')
			->where(array('and','groupname=:group','attribute=:att'), array(':group'=>self::CheckPackageUser($UserID),'att'=>"Expiration"))
			->queryScalar();

		if(!empty($user))return $user;
		return "";
	}


	public function ChangePassword($UserID,$newpassword)
	{

		$command=Yii::app()->db->createCommand()->update('radcheck', array('value'=>$newpassword),
				array('and','username=:username','attribute=:Password'), 
				array(':username'=>$UserID,':Password'=>"User-Password"));

		return $command;
	}

	public function ChangePackage($UserID,$newpackage)
	{
		$command=Yii::app()->db->createCommand()->update('radusergroup', array('groupname'=>$newpackage),
				'username=:userid', 
				array(':userid'=>$UserID));

		return $command;
	}



public function RequestID($op)
	{
		return date("YmdHis-".$op."0".rand(0,9)); 
	}

	



}