<?php

class AdminController extends CommonController
{
	public function actionTest()
	{
	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionDebug()
	{
		$this->render('debug');
	}

	public function actionFixhofstra()
	{
		$this->render('fixdoublon');
	}

	public function actionFixfs()
	{
		$this->render('fixfs');
	}

	public function actionFixmidd()
	{
		$file = fopen("D:/temp/midd_sansspace_user_ids-1.csv", 'r');
		if(!$file) return;
		
		while(!feof($file))
		{
			$line = fgetcsv($file);
			if(!$line) continue;
			
			$user = getdbosql('User', "logon='{$line[0]}'");
			if($user)
			{
				$user->logon = $line[1];
				$user->save();
			}
		}
		
		fclose($file);
	}

	public function actionPhp()
	{
		phpinfo(INFO_ALL);
	}

	public function actionCourses()
	{
		$courses = getdbolist('VCourse',array('condition'=>'type='.CMDB_OBJECTTYPE_COURSE.
			' and not deleted and not hidden', 'order'=>'parentid' ));
		//VCourse::model()->findAll(
		//	array('condition'=>'type='.CMDB_OBJECTTYPE_COURSE.
		//	' and not deleted and not hidden', 'order'=>'parentid'));

		$this->render('courses', array('courses'=>$courses));
	}

	public function actionMaintenance()
	{
		$this->render('maintenance', array());
	}

	///////////////////////////////////////////////////////////

	public function actionLogs()
	{
		$this->render('logs');
	}

	public function actionShowLog()
	{
		$basename = $_GET['filename'];
		$filename = SANSSPACE_LOGS."/$basename";

		echo "<pre>";
		readfile($filename);
		echo "</pre>";
	}

	public function actionDeleteLog()
	{
		$basename = $_GET['deletename'];
		$filename = SANSSPACE_LOGS."/$basename";

		unlink($filename);
		$this->redirect(array('logs'));
	}

	///////////////////////////////////////////////////////////

	public function actionRestart()
	{
		$this->render('restart');
	}

	public function actionRestartInternal()
	{
		sendMessageSansspace("RESTART Service");
	}

	///////////////////////////////////////////////////////////

	public function actionUpdate()
	{
		$this->render('update');
	}

	public function actionUpdateInternal()
	{
		SansspaceUpdate();
	}

	////////////////////////////////////////////////////////

	public function actionSearch()
	{
		if(isset($_POST['dropdown_command']) && isset($_POST['all_objects']))
		{
			objectHandleDropdownCommand();
			$this->refresh();
		}

		$this->render('search', array());
	}

	///////////////////////////////////////////////////////////

// 	public function actionSecurity()
// 	{
// 		$filename = GetUploadedFilename();
// 		if($filename)
// 		{
// 			$cmd = "\"".SANSSPACE_BIN."\\mysql\"".
// 				" --host ".SANSSPACE_DBHOST.
// 				" -u ".SANSSPACE_DBUSER.
// 				" -p".SANSSPACE_DBPASSWORD.
// 				" ".SANSSPACE_DBNAME." < $filename";

// 			system($cmd);
// 			@unlink($filename);

// 			user()->setFlash('message', 'File restored.');
// 		}

// 		$this->render('security', array());
// 	}


}







