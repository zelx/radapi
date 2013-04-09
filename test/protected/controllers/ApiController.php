<?php

class ApiController extends CController
{
	public function actionIndex()
	{
	  $inputJSON = file_get_contents('php://input');
	  $input= json_decode( $inputJSON, TRUE ); //convert JSON into array
	  
	  if(empty($input)){
		  echo "Unknow";die();
	  }
	

	
	  if(array_key_exists('CREATE',$input)){
		if (!empty($input['CREATE']['UserID']) && !empty($input['CREATE']['Password']) && !empty($input['CREATE']['Package']))self::CREATE_UserID($input);
		
		if (!empty($input['CREATE']['Package'])&&( !empty($input['CREATE']['MaxDL']) || !empty($input['CREATE']['MaxUL']) || !empty($input['CREATE']['ExpiredDate']) ))self::CREATE_Package($input);
		
		if (!empty($input['CREATE']['MAC']) && !empty($input['CREATE']['Package']) )self::CREATE_MAC($input);

	  } else if(array_key_exists('UPDATE',$input)){

		if (!empty($input['UPDATE']['UserID']) && !empty($input['UPDATE']['Action']) && !empty($input['UPDATE']['Password']) &&($input['UPDATE']['Action']=="Change")  )echo "Update Password";

		if (!empty($input['UPDATE']['UserID']) && !empty($input['UPDATE']['Action']) && !empty($input['UPDATE']['Package']) &&($input['UPDATE']['Action']=="Change")  )echo "Update Package";

		if (!empty($input['UPDATE']['UserID']) && !empty($input['UPDATE']['Action']) && ($input['UPDATE']['Action']=="Bar")  )echo "Update Bar";

		if (!empty($input['UPDATE']['UserID']) && !empty($input['UPDATE']['Action']) && ($input['UPDATE']['Action']=="Unbar")  )echo "Update Unbar";

		if (!empty($input['UPDATE']['UserID']) && !empty($input['UPDATE']['Action']) && ($input['UPDATE']['Action']=="Delete")  )echo "Update Delete";



	  } else if(array_key_exists('READ',$input)){

		if (!empty($input['READ']['UserID']))echo "READ UserID";

		if (!empty($input['READ']['MAC']))echo "READ MAC";

	  } else


		echo "Unkonw";

	}

	public function CREATE_UserID($input)
	{	
		extract($input['CREATE']);
		echo "Create User ID";
	}

	public function CREATE_Package($input)
	{		
		extract($input['CREATE']);
		if(self::CheckPackageEmpty($Package)){
		
				//self::InsertG("radgroupreply",$Package,"Service-Type",":=","Login-User");
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
				//self::InsertG("radgroupcheck",$Package,"Auth-Type",":=","PAP");
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
	}

	public function CREATE_MAC($input)
	{	extract($input['CREATE']);
		$status="Failed";
		//echo "Create MAC";

		 if(self::CheckUser($MAC)){
				if(!self::CheckPackageEmpty($Package)){
				  Yii::app()->db->createCommand()->insert("radusergroup", array(
					'username'=>$MAC,
					'groupname'=>$Package,
					'priority'=>1
					));

					self::InsertU("radcheck",$MAC,"Auth-Type",":=","Accept");
					self::InsertU("radcheck",$MAC,"Service-Type","==","Framed-User");
					if(!empty($input['CREATE']['UserID']));
					if(!empty($input['CREATE']['Password']));
					if(!empty($input['CREATE']['Package']));
					$status="Sucess";
				}
		 }

					$response=array('CREATE'=>array(
						"MAC"=>$MAC,
						"RequestID"=>self::RequestID(1),
						"Status"=>$status,
						"Package"=>$Package,
						"Duration"=>24,
						"MaxDL"=>self::CheckSpeedDL($MAC),
						"MaxUL"=>self::CheckSpeedUL($MAC),
						"Validity"=>"",
						"StartDate"=>"",
						"ExpiredDate"=>""
						
					));		
		
					self::SendJson($response);



	}












///////////////////////////////////////////////////////////////////////////////////
//
//						Method
//
//////////////////////////////////////////////////////////////////////////////////



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


