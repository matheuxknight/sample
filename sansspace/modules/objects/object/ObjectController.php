<?php

class ObjectController extends CommonController
{
	public $defaultAction = 'show';
	private $_object;

	public function RedirectObject($object, $action)
	{
		if($object) switch($object->type)
		{
			case CMDB_OBJECTTYPE_COURSE:
				$this->redirect(array("course/$action", 'id'=>$object->id));
				break;

			case CMDB_OBJECTTYPE_FILE:
				$this->redirect(array("file/$action", 'id'=>$object->id));
				break;
		}
	}

	///////////////////////////////////////////////////////////////

	public function actionShow()
	{
		$object = $this->loadobject();
		if(isset($_POST['dropdown_command']) && isset($_POST['all_objects']))
		{
			objectHandleDropdownCommand();
			$this->redirect(array('show', 'id'=>$object->id));
		}

		$user = getUser();

		if(!controller()->rbac->globalAdmin())
		{
			$courseid = getContextCourseId();
			if(!$courseid)
			{
				$course = getRelatedCourse($object, $user);
				if($course) setContextCourse($course->id);
			}
		}
		
		$this->RedirectObject($object, '');

		$objectext = getdbo('ObjectExt', $object->id);
		if($objectext)
		{
			$objectext->views++;
			$objectext->save();
		}
		
		switch($object->type)
		{
			case CMDB_OBJECTTYPE_LESSON:
			case CMDB_OBJECTTYPE_QUIZ:
			case CMDB_OBJECTTYPE_SURVEY:
				createEnrollmentFromCourse($user, $object);
				break;
		}

		$this->render('show', array('object'=>$object));
	}

	public function actionRecents()
	{
		$object = $this->loadobject();
		if(isset($_POST['dropdown_command']) && isset($_POST['all_objects']))
		{
			objectHandleDropdownCommand();
			$this->redirect(array('show', 'id'=>$object->id));
		}

		$objectext = getdbo('ObjectExt', $object->id);
		$objectext->views++;
		$objectext->save();

		$this->render('showrecents', array('object'=>$object));
	}

	public function actionCreate()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_OBJECT;
		$object->parentid = $_GET['id'];

		$parent = getdbo('Object', $object->parentid);
		$object->post = $parent->post;

		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('create', array('object'=>$object));
				return;
			}

			objectHandleImage($object2);
			objectHandleCategory($object2);
			objectUpdateParent($object2, now());

			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('create', array('object'=>$object));
	}

	public function actionCreateLink()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_LINK;
		$object->parentid = $_GET['id'];

		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createlink', array('object'=>$object));
				return;
			}

			if(empty($object2->name))
			{
				$object = getdbo('Object', $object2->linkid);
				$object2->name = $object->name;
			}

			$object2->save();

			objectHandleImage($object2);
			objectHandleCategory($object2);
			objectUpdateParent($object2, now());

			$this->redirect(array('show', 'id'=>$_GET['id']));
		}

		$this->render('createlink', array('object'=>$object));
	}

	public function actionCreateForum()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_OBJECT;
		$object->parentid = $_GET['id'];
		$object->post = true;
		
		$parent = getdbo('Object', $object->parentid);
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$object->ext->doctext = $_POST['ObjectExt']['doctext'];
				$this->render('createforum', array('object'=>$object));
				return;
			}

			$object2->post = true;
			$object2->save();
			
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('createforum', array('object'=>$object));
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////
	
	public function actionCreateTextbook()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_TEXTBOOK;
		$object->parentid = $_GET['id'];
		
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createtextbook', array('object'=>$object));
				return;
			}

			$object2->model = true;
			$object2->save();
			
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('createtextbook', array('object'=>$object));
	}

	public function actionCreateFlashcard()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_FLASHCARD;
		$object->parentid = $_GET['id'];
		
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createflashcard', array('object'=>$object));
				return;
			}

			$object2->save();
			
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('createflashcard', array('object'=>$object));
	}

	public function actionCreateSurvey()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_SURVEY;
		$object->parentid = $_GET['id'];
		
	//	$parent = getdbo('Object', $object->parentid);
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createsurvey', array('object'=>$object));
				return;
			}

			$object2->save();
			
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('createsurvey', array('object'=>$object));
	}

	public function actionCreateLesson()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_LESSON;
		$object->parentid = $_GET['id'];
		
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createlesson', array('object'=>$object));
				return;
			}

			$object2->save();
			
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}

		$this->render('createlesson', array('object'=>$object));
	}
	
	public function actionCreateQuiz()
	{
		$object = new Object;
		$object->ext = new ObjectExt;
		$object->type = CMDB_OBJECTTYPE_QUIZ;
		$object->parentid = $_GET['id'];
	
		if(isset($_POST['Object']))
		{
			$object2 = objectCreateData($object, $_GET['id'], $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('createquiz', array('object'=>$object));
				return;
			}
	
			$object2->save();
				
			objectUpdateParent($object2, now());
			$this->redirect(array('show', 'id'=>$object2->id));
		}
	
		$this->render('createquiz', array('object'=>$object));
	}

	/////////////////////////////////////////////////////////////////////////////
	
	public function actionUpdate()
	{
		$object = $this->loadobject();
		$this->RedirectObject($object, 'update');

		if(isset($_POST['Object']))
		{
		//	debuglog($_POST['ObjectExt']);
			$oldtype = $object->type;

			$object2 = objectUpdateData($object, $_POST['Object'], $_POST['ObjectExt']);
			if(!$object2)
			{
				$this->render('update', array('object'=>$object));
				return;
			}

			objectHandleImage($object2);
			objectHandleCategory($object2);
			objectUpdateParent($object2, now());
			objectUpdateType($object2, $oldtype);

			user()->setFlash('message', 'Object attributes saved.');
			$this->redirect(array('update', 'id'=>$object2->id));
		}

		$this->render('update', array('object'=>$object));
	}

	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			$object = $this->loadobject();
			$parentid = $object->parentid;

			objectDelete($object);
			$this->redirect(array('object/show', 'id'=>$parentid));
		}
		else
			throw new CHttpException(500, 'Invalid request. Please do not repeat this request again.');
	}

	//////////////////////////////////////// Folder Scan

	public function actionRescan()
	{
		$object = $this->loadobject();

		scanObjectBackground($object);
		user()->setFlash('message', 'Rescan started.');

		sleep(2);
		$this->goback();
	}

	public function actionRescanAll()
	{
		$object = $this->loadobject();

		scanObjectBackground($object, true);
		user()->setFlash('message', 'Rescan started.');

		sleep(2);
		$this->goback();
	}

	public function actionRescanFile()
	{
		$object = $this->loadobject();

		scanFileObject($object);
		user()->setFlash('message', 'File scanned.');

		$this->goback();
	}

	/////////////////////////////////////////////////////////////////////

	public function actionGenerateThumbnails()
	{
		$object = $this->loadobject();

		objectChangeAllFields($object, 'scanstatus', CMDB_OBJECTSCAN_THUMBNAILS);
		user()->setFlash('message', 'Thumbnails generation started.');

		sleep(2);
		$this->goback();
	}

	public function actionCleanDeleted()
	{
		$object = $this->loadobject();

		$children = getdbolist('Object', "parentlist like '%, {$object->id}, %' and deleted");
		foreach($children as $child)
			objectDelete($child);

		user()->setFlash('message', 'Clean Deleted complete.');
		$this->redirect(array('update', 'id'=>$object->id));
	}
	
	/////////////////////////////////////////////////////////////////////

	public function actionDetach()
	{
		$object = $this->loadobject();

		if($object->folderimport->objectid == $object->id)
		{
			$folderimport = $object->folderimport;
			$folderimport->delete();
		}

		objectChangeAllFields($object, 'folderimportid', 0, -1);

		user()->setFlash('message', 'Folder Detached.');
		$this->redirect(array('update', 'id'=>$object->id));
	}

	/////////////////////////////////////////////////////////////////////

	public function loadobject($id=null)
	{
		if($this->_object===null)
		{
			$id = isset($_GET['id'])? $_GET['id']: CMDB_OBJECTROOT_ID;
			$this->_object = getdbo('Object', $_GET['id']);
			//Object::model()->findByPk($id);

			if($this->_object===null)
				throw new CHttpException(500, "The requested object does not exist $id.");
		}
		return $this->_object;
	}

//	protected function processAdminCommand()
//	{
//		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
//		{
//			$this->loadobject($_POST['id']);
//			$this->actionDelete();
//			// reload the current page to avoid duplicated delete actions
//			$this->refresh();
//		}
//	}

	public function actionResetIcon()
	{
		$object = $this->loadobject();

		@unlink(SANSSPACE_CONTENT."/object-{$object->id}.png");
		@unlink(SANSSPACE_CONTENT."/stamped-{$object->id}.png");

		$this->goback();
	}

	public function actionPdf()
	{
		$object = $this->loadobject();
		sendPdf($object);
	}

	public function actionGenerateDocumentation()
	{
		$object = $this->loadobject();

		echo "<html><head>";
		echo CHtml::cssFile('/sansspace/ui/css/main.css');
		echo "<title>{$object->name}</title>";
		echo "</head>";

		echo "<body class='page'>";

		$now = now();
		echo "<div style='text-align:right'>Generated from ".
			"{$_SERVER['SERVER_NAME']} on $now </div>";

		echo "<div id='content'>";
		GenerateFullDocumentation($object);
		echo "</div>";

		echo "</body></html>";
	}

	public function actionSetorder()
	{
		if(!Yii::app()->request->isAjaxRequest || !isset($_GET['id'])) return;

		$object = $this->loadobject();
		$object->parent->defaultsort = 'displayorder';
		$object->parent->save();
		
		$order = $_GET['order'];

		$oldorder = $object->displayorder;

		$bros = getdbolist('Object', "parentid={$object->parentid} order by displayorder, name");
		//Object::model()->findAll(
		//	"parentid={$object->parentid} order by displayorder, name");

		foreach($bros as $n=>$o)
		{
			if($o->id == $object->id)
				$o->displayorder = $order;

			else if($o->displayorder < $order && $o->displayorder < $oldorder)
				$o->displayorder = $n;

			else if($o->displayorder > $order && $o->displayorder > $oldorder)
				$o->displayorder = $n;

			else if($order < $oldorder)
				$o->displayorder = $n + 1;
			else
				$o->displayorder = $n - 1;

			$o->save();
		}
	}

	//////////////////////////////////////////////////////////////////

	public function actionAutoCompleteObject()
	{
		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 500);

			$criteria = new CDbCriteria;
			$criteria->condition = "name LIKE :sterm and not deleted";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
			{
				if($object->parent)
					$returnVal .= h($object->parent->name).'/'.h($object->name)."|{$object->id}\n";
				else
					$returnVal .= '(ROOT) '.h($object->name)."|{$object->id}\n";
			}

			echo $returnVal;
		}

		die();
	}

	public function actionAutoCompleteObject2()
	{
		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 50);

			$criteria = new CDbCriteria;
			$criteria->condition = "name LIKE :sterm not deleted";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
			{
				if($object->parent)
					$returnVal .= h($object->parent->name).'/'.h($object->name)."|{$object->name}\n";
				else
					$returnVal .= '(ROOT) '.h($object->name)."|{$object->name}\n";
			}

			echo $returnVal;
		}

		die();
	}

	public function actionAutoCompletePage()
	{
//		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 50);

			$criteria = new CDbCriteria;
			$criteria->condition = "type != ".CMDB_OBJECTTYPE_FILE." and name LIKE :sterm and not deleted";
//			$criteria->condition = "name LIKE :sterm";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
			{
				if($object->parent)
					$returnVal .= h($object->parent->name).'/'.h($object->name)."|{$object->id}\n";
				else
					$returnVal .= '(ROOT) '.h($object->name)."|{$object->id}\n";
			}

			echo $returnVal;
		}

//		die();
	}

	public function actionAutoCompleteCourse()
	{
		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 50);

			$criteria = new CDbCriteria;
			$criteria->condition = "(type = ".CMDB_OBJECTTYPE_COURSE.") and name LIKE :sterm and not deleted";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
				$returnVal .= h($object->name)."|{$object->id}\n";

			echo $returnVal;
		}

		die();
	}

	public function actionAutoCompleteCourseActivity()
	{
		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 50);

			$criteria = new CDbCriteria;
			$criteria->condition = "type = ".CMDB_OBJECTTYPE_COURSE." and name LIKE :sterm and not deleted";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
				if($object->parent)
					$returnVal .= h($object->parent->name).'/'.h($object->name)."|{$object->id}\n";
				else
					$returnVal .= '(ROOT) '.h($object->name)."|{$object->id}\n";

			//$returnVal .= h($object->name)."|{$object->id}\n";

			echo $returnVal;
		}

		die();
	}

	public function actionAutoCompleteFile()
	{
		if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
		{
			$name = $_GET['q'];
			$limit = min($_GET['limit'], 50);

			$criteria = new CDbCriteria;
			$criteria->condition = "type = ".CMDB_OBJECTTYPE_FILE." and name LIKE :sterm and not deleted";
			$criteria->sort = 'name';
			$criteria->params = array(":sterm"=>"%$name%");
			$criteria->limit = $limit;

			$objects = getdbolist('Object', $criteria);
			//Object::model()->findAll($criteria);
			$returnVal = '';

			foreach($objects as $object)
			$returnVal .= h($object->name)."|{$object->id}\n";

			echo $returnVal;
		}

		die();
	}
	
	public function actionRundata()
	{
		rundata();
	}

	/////////////////////////////////////////////////////////////////////

	public function actionImportFolder()
	{
		$object = $this->loadobject();
		$this->redirect(array('import/create', 'id'=>$object->id));
	}

	public function actionObjectHTML()
	{
		$object = $this->loadobject();
		echo $object->ext->doctext;
	}

	public function actionDownloadHTML()
	{
		$object = $this->loadobject();
		$this->render('downloadhtml', array('object'=>$object));
	}

	public function actionUploadHTML()
	{
		$object = $this->loadobject();
	}

	function actionLeavePage()
	{
		$object = $this->loadobject();
		if($object->type == CMDB_OBJECTTYPE_FILE)
		{
			$file = $object->file;
			if($file->filetype != CMDB_FILETYPE_MEDIA)
			{
				// stop tracking
				$filesessionid = user()->getState('filesession');
				$filesession = getdbo('FileSession', $filesessionid);

				if($filesession)
				{
					$filesession->duration = strtotime("now") - strtotime($filesession->starttime);
					$filesession->status = CMDB_FILESESSIONSTATUS_COMPLETE;
					$filesession->save();
				}
			}
		}

		user()->setState('currentobject', 0);
		user()->setState('currentversion', 0);
		user()->setState('filesession', 0);
	}

	public function actionImage()
	{
		$object = $this->loadobject();
		$url = objectImageUrl($object);

		header("Location: $url");
	}

	public function actionComputeParentlist()
	{
		function computeParentlist($object)
		{
			$object->parentlist = objectParentList($object);
			$object->save();
			
			foreach($object->children as $child)
				computeParentlist($child);
		}
		
		$object = $this->loadobject();
		computeParentlist($object);
		
		user()->setFlash('message', 'Parentlist Computed.');
		$this->goback();
	}

	public function actionCommandcopy()
	{
		$object = $this->loadobject();
		
		user()->setState('clipboardid', $object->id);
		user()->setState('clipboardcommand', 'copy');
		
	//	user()->setFlash('message', "$object->name copied into the clipboard.");
		$this->goback();
	}
	
	public function actionCommandcut()
	{
		$object = $this->loadobject();

		user()->setState('clipboardid', $object->id);
		user()->setState('clipboardcommand', 'cut');
		
	//	user()->setFlash('message', "$object->name cut into the clipboard.");
		$this->goback();
	}
	
	public function actionCommandpaste()
	{
		$object = $this->loadobject();
		
		$clipboardid = user()->getState('clipboardid');
		$clipboardcommand = user()->getState('clipboardcommand');
		
		$clip = getdbo('Object', $clipboardid);
		if($clipboardcommand == 'cut')
		{
			$parent = $clip->parent;
			
			$clip->parentid = $object->id;
			$clip->parentlist = objectParentList($clip);
			$clip->save();
		
			objectUpdateParent($parent, now());
		}
		
		else
			objectCopy($clip, $object->id);
		
		objectUpdateParent($object, now());
		
		user()->setState('clipboardid', 0);
		user()->setState('clipboardcommand', '');
		
	//	$this->goback();
		$this->redirect(array('object/', 'id'=>$object->id));
	}
	
	//////////////////////////////////////////////////////////////////////////////////////
	
	public function actionEmbed()
	{
		$object = $this->loadobject();
		echo "<a href='/object?id=$object->id' target=_top>$object->name</a>";
	}
	
	public function actionResetContextcourse()
	{
		$object = $this->loadobject();
		$object->courseid = 0;
	
		$object->save();
		$this->goback();
	}
	
	public function actionGetCustom()
	{
		$user = getUser();
		$object = $this->loadobject();
	
		$loginpostdata = $object->ext->custom;
		$loginpostdata = preg_replace('/\$user.logon/', $user->logon, $loginpostdata);
		$loginpostdata = preg_replace('/\$user.name/', $user->name, $loginpostdata);
		$loginpostdata = preg_replace('/\$user.firstname/', $user->firstname, $loginpostdata);
		$loginpostdata = preg_replace('/\$user.lastname/', $user->lastname, $loginpostdata);
		
		echo $loginpostdata;
	}
	
	public function actionClearContextCourse()
	{
		setContextCourse(0);
		controller()->goback();
	}
	
}






