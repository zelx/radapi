<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class TestController extends Controller
{
	/**
	 * Index action is the default action in a controller.
	 */
	




	public function actionindex()
	{
			$request = Yii::app()->request;
			$mac=$request->getParam('mac');
			$ip=$request->getParam('ip');
			$username=$request->getParam('username');
			$linklogin=$request->getParam('link-login');
			$linkorig=$request->getParam('link-orig');
			$error=$request->getParam('error');
			$chapid=$request->getParam('chap-id');
			$chapchallenge=$request->getParam('chap-challenge');
			$linkloginonly=$request->getParam('link-login-only');
			$linkorigesc=$request->getParam('link-orig-esc');
			$macesc=$request->getParam('mac-esc');
		
			$this->render('login', array('mac'=>$mac,'ip'=>$ip,'username'=>$username,'linklogin'=>$linklogin,'linkorig'=>$linkorig,'error'=>$error,'chapid'=>$chapid,'chapchallenge'=>$chapchallenge,'linkloginonly'=>$linkloginonly,'linkorigesc'=>$linkorigesc,'macesc'=>$macesc));

	}
}