<?php
class LoginController extends Controller 
{
	public function actionindex()
	{
		$request = Yii::app()->request;
		$mac=$request->getParam('mac');
	
	//print	'<form action="login/AddExpire" method="post"><input type="hidden" name="mac" value="'.$mac.'"><button type="submit">Renew</button></form>';

	$this->render('login',array('mac'=>$mac));



		# begin debugging
		print '<hr/><h3>Debug:</h3>';
		print '<style>th {text-align:left}</style>';
		print '<table>';
		foreach ($_GET as $key => $value) print implode('', array(
			'<tr>',
			"    <th>{$key}</th>",
			"    <td>{$value}</td>",
			'</tr>'
		));
		print '</table>';
		# end debugging
	}




	public function actionAddExpire()
	{	
		$mac=$_POST["mac"];
		if( $mac=="00-50-56-22-F0-8C"){
		
		$newexpire=date("d M Y H:i:s", mktime(date("H"), date("i")+5, date("s")+0));
		$command=Yii::app()->db->createCommand()->update('radcheck', array('value'=>$newexpire),
				array('and','username=:username','attribute=:attribute'), 
				array(':username'=>$mac,':attribute'=>"Expiration"));

		echo "New Expire: ".$newexpire;
		}else echo "Error";
	}


	public function actionPreLogin()
	{	
		if(empty($_GET['mac']))die();
		if(self::CheckUser($_GET['mac'])){

			$this->render('register',array('_GET'=>$_GET));

		} else $this->render('prelogin',array('_GET'=>$_GET,'redirect'=>"register"));
				
	}

	public function actionLoginForm()
	{	


		# begin debugging
		print '<hr/><h3>Debug:</h3>';
		print '<style>th {text-align:left}</style>';
		print '<table>';
		foreach ($_POST as $key => $value) print implode('', array(
			'<tr>',
			"    <th>{$key}</th>",
			"    <td>{$value}</td>",
			'</tr>'
		));
		print '</table>';
		# end debugging
		
	}

	public function actionRegister()
	{
		$this->render('register',array('_GET'=>$_POST));

				# begin debugging
		print '<hr/><h3>Debug:</h3>';
		print '<style>th {text-align:left}</style>';
		print '<table>';
		foreach ($_POST as $key => $value) print implode('', array(
			'<tr>',
			"    <th>{$key}</th>",
			"    <td>{$value}</td>",
			'</tr>'
		));
		print '</table>';
		# end debugging
		
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



}
	
