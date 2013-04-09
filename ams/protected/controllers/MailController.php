<?php 
class MailController extends CController
{
    public function actionTest()
    {
        // ...

	//Yii::import('ext.swiftMailer.SwiftMailer');
	//$Transport = SwiftMailer::smtpTransport($host, $port);
 
    	// Render view and get content
    	// Notice the last argument being `true` on render()
    	//$content = $this->render('viewTest', array(
        //	'Test' => 'TestText 123',
    	//), true);
    	// Plain text content
    	$plainTextContent = "This is my Plain Text Content for those with cheap emailclients ;-)\nThis is my second row of text";
    	$content = "<hr><H1>Ads schedule files from Ad management system</H1>";
 
    	// Get mailer
    	$SM = Yii::app()->swiftMailer;
	$files_path = realpath(Yii::app()->basePath . '/../files/');
	//echo $files_path;
    	// Get config
    	$mailHost = '202.170.119.39';
    	$mailPort = 25; // Optional
 
    	// New transport
    	$Transport = $SM->smtpTransport($mailHost, $mailPort)->setUsername('bbtv')->setPassword('[u[umu;u12+');
    	// Mailer
    	$Mailer = $SM->mailer($Transport);
        print_r( $Transport);
    	// New message
    	$Message = $SM
        	->newMessage('ad schedule files')
        	->setFrom(array('no-reply@bbtvnewmedia.com' => 'AMS No-Reply'))
        	->setTo(array('developer@bugaboo.tv' => 'users'))
		    ->setCC(array('surachet.tor@gmail.com' => 'users'))
        	->addPart($content, 'text/html')
			
        	->setBody($plainTextContent)->attach(Swift_Attachment::fromPath('/home/ams/htdocs/ams/files/img.png'),"img.png","image/png"); 
    	// Send mail
    	$result = $Mailer->send($Message);
    	echo $result;


    }
    
    public function actionExcel($program, $year, $month, $day) {
		   
			
			$time = 0;
			$connection=Yii::app()->db;
			$sql_progtime = "SELECT Distinct(time(datetime)) FROM onair_schedule WHERE onair_schedule.prog_id = '$program' ";
			$command=$connection->createCommand($sql_progtime);
			$dataReader=$command->query();
			$progtime =$dataReader->readAll();
			foreach ($progtime as $timevalue){
				foreach ($timevalue as $time){ 
								$time =  $time;
				}
			}
				$sql="
SELECT break.break_seq,break.adv_seq, break.adv_id,breaktime.timelimit,product.prod_name,advertise.adv_name,agency.agency_name,tape.time_len,programs.prog_name
						FROM `break`,`onair_schedule`,`breaktime`,`programs`,`product`,`advertise`,`agency`,`tape`
						WHERE (breaktime.break_id = break.break_id )
                         AND (tape.prod_id = product.prod_id)
                         AND (onair_schedule.prog_id = programs.prog_id) 
                         AND (agency.agency_id = advertise.agency_id)
                         AND (tape.tape_id = advertise.tape_id)
                         AND (break.adv_id = advertise.adv_id)
						AND (breaktime.break_seq = break.break_seq )
						AND ( programs.prog_id =onair_schedule.prog_id)
						AND ( break.break_id =onair_schedule.break_id)
						AND ( breaktime.break_id =onair_schedule.break_id)
						AND ( programs.prog_id ='$program')
						AND ( onair_schedule.datetime = '$year-$month-$day  $time')
						Order By break.break_seq ASC, break.adv_seq ASC ";
						
			$command=$connection->createCommand($sql);
			$dataReader=$command->query();
			$rows=$dataReader->readAll();
			$connection->active = false;
			
			//print_r($rows);
		 
        $objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getActiveSheet()->fromArray($rows);
		/*

		$worksheet->fromArray( $criteria, NULL, 'A1' );
		$worksheet->fromArray( $database, NULL, 'A4' );
*/

        $objPHPExcel->getActiveSheet()->setTitle('Simple');
        $objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
        ob_start();

       
        /*header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="test.xls"');
        header('Cache-Control: max-age=0');*/
       /// ตรงนี้ครับ--->> $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        //$objWriter->save('php://output'); 
        //$objWriter->save('files/program_'.$program.'_'.$year.'_'.$month.'_'.$day.'.xls');
       /// ตรงนี้ครับ--->>$objWriter->save('files/program_test.xls');
        
        $plainTextContent = "This is my Plain Text Content for those with cheap emailclients ;-)\nThis is my second row of text";
    	$content = "<hr><H1>Ad schedule Files </H1>";
		
        // $this->actionTest();
    	// Get mailer
    	$SM = Yii::app()->swiftMailer;
		$files_path = realpath(Yii::app()->basePath . '/../files/');
		//echo $files_path;
    	// Get config
    	$mailHost = '202.170.119.39';
    	$mailPort = 25; // Optional
 
    	// New transport 
        $Transport = $SM->smtpTransport($mailHost, $mailPort)->setUsername('bbtv')->setPassword('[u[umu;u12+');
 
    	// Mailer
    	$Mailer = $SM->mailer($Transport);
 
    	// New message
    	$Message = $SM
        	->newMessage('My subject XLS')
        	->setFrom(array('no-reply@bugaboo.tv' => 'AMS'))
        	->setTo(array('amsch7@bugaboo.tv' => 'ams group'))
			->setCC(array('inoomzaa@gmail.com' => 'users'))
		    ->setCC(array('developer@bugaboo.tv' => 'users'))
			->setCC(array('surachet.tor@gmail.com' => 'users'))
			//->setCC(array('sarut@ch7.com' => 'sarutc@ch7.com','inoomzaa@gmail.com'))
        	->addPart($content, 'text/html')
        	->setBody($plainTextContent);//->attach(Swift_Attachment::fromPath('/home/ams/htdocs/ams/files/program_'.$program.'_'.$year.'_'.$month.'_'.$day.'.xls',"application/")); 
    	// Send mail
    	$result = $Mailer->send($Message);
    	echo $result;
	}

 
    public function actionContact()
    {
        // ...
    }
}

?>
