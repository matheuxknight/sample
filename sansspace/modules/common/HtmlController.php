<?php

class HtmlController extends CommonController
{
//	public function actionQuickFile()
//	{
//		$user = getUser();
//		$object = getdbo('Object', $_GET['id']);
//
//		echo "<div style='width: 100%; height: 100%'>";
//		showQuickContent($object);
//		echo "</div>";
//	}

	public function actionObjectResults()
	{
		$user = getUser();
		$id = $_GET['id'];
		
		if(isset($_GET['sort']) && !empty($_GET['sort']) && $_GET['sort'] != 'undefined')
			user()->setState('listsort', $_GET['sort']);

		else
			user()->setState('listsort', 'displayorder, name');
		
		if(isset($_GET['layout']) && $_GET['layout'] != 'undefined')
			user()->setState('layout', $_GET['layout']);
		
		else
			user()->setState('layout', 'showsmall');
		
		if($id == 'adminsearch')
		{
			$criteria = new CDbCriteria;
			$criteria->condition = isset($_GET['param']) && !empty($_GET['param'])? $_GET['param']: '1';

		//	$criteria->condition = $sql;
			$criteria = filterObjectQuery($criteria);
	
			$pages = new CPagination(getdbocount('Object', $criteria));
			$pages->pageSize = param('pagecount');
			$pages->applyLimit($criteria);
	
			$objects = getdbolist('Object', $criteria);
			$objects = filterSemesters($objects);
			
			$objects = filterRecordingNames($objects);
			showListResult($id, $objects, $pages);
		}

		else if(getparam('recursive') == 'true')
		{
			$criteria = buildObjectQuery($user, $id);
			if(!$criteria) return;

			$criteria = filterObjectQuery($criteria);

			$pages = new CPagination(getdbocount('Object', $criteria));
			$pages->pageSize = param('pagecount');
			$pages->applyLimit($criteria);
	
			$objects = getdbolist('Object', $criteria);
			$objects = filterSemesters($objects);
			
			$objects = filterRecordingNames($objects);
			showListResult($id, $objects, $pages);
		}
		
		else
		{
			$object = getdbo('Object', $id);
			if($object)
				objectContentDisplay($object);
			
			else
			{
				$objects = objectList($id);
				if(!$objects) return;
	
				showListResult($id, $objects);
			}
		}
	}

	public function actionListObject()
	{
	//	if(!Yii::app()->request->isAjaxRequest || !isset($_GET['id'])) return;
		$selected = null;
		if(isset($_SERVER['HTTP_REFERER']))
		{
			$selectedid = substr(strstr($_SERVER['HTTP_REFERER'], '&id='), 4);
			if(!$selectedid)
				$selectedid = substr(strstr($_SERVER['HTTP_REFERER'], '?id='), 4);
			if(intval($selectedid))
				$selected = getdbo('Object', intval($selectedid));
		}

	//	if(!isset($_GET['filter'])) $_GET['filter'] = -1;
		
		$objects = objectList($_GET['id']);
		$objects = filterSemesters($objects);
				
		if($objects)
			foreach($objects as $object)
				showListObject($object, $selected);
	}

	public function actionMenuObject()
	{
		$user = getUser();

		$object = getdbo('Object', getparam('id'));
		if(!$object) return;

		////////////////////////////////

		echo "<ul>";

		if(	$object->type == CMDB_OBJECTTYPE_FILE &&
			$this->rbac->objectAction($object, 'update'))
		{
			$file = $object->file;
			if(strstr($file->mimetype, '/zip'))
			{
				echo "<li>";
				echo l(mainimg('menudot.png')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Extract Files",
					array('file/unzip', 'id'=>$object->id));

				echo "</li>";
			}

			else if(strstr($file->mimetype, '/x-rar'))
			{
				echo "<li>";
				echo l(mainimg('menudot.png')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Extract Files",
					array('file/unrar', 'id'=>$object->id));

				echo "</li>";
			}
		}

		//if(param('theme') != 'wayside')
		//	if(!user()->isGuest && $object->id != CMDB_OBJECTROOT_ID)
		//	{
		//		if(hasFavorite($user->id, $object->id))
		//			echo '<li>'.l(mainimg('menudot.png').
		//				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Remove from Favorites',
		//				array('favorite/delete', 'id'=>$object->id));
	
		//		else
		//			echo '<li>'.l(mainimg('menudot.png').
		//				'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add to Favorites',
		//				array('favorite/create', 'id'=>$object->id));
		//	}

		$commands = array();
		switch($object->type)
		{
			case CMDB_OBJECTTYPE_LINK:
				$commands = RbacCommandLinkTable();
				break;

			case CMDB_OBJECTTYPE_QUESTIONBANK:
				$commands = RbacCommandQuestionTable();
				break;

			case CMDB_OBJECTTYPE_FILE:
				$commands = RbacCommandFileTable();
				break;

			case CMDB_OBJECTTYPE_COURSE:
				$commands = RbacCommandCourseTable();
				break;

			case CMDB_OBJECTTYPE_FLASHCARD:
				$commands = RbacCommandFlashcardTable();
				break;

			case CMDB_OBJECTTYPE_SURVEY:
				$commands = RbacCommandSurveyTable();
				break;

			case CMDB_OBJECTTYPE_TEXTBOOK:
				$commands = RbacCommandTextbookTable();
				break;

			case CMDB_OBJECTTYPE_LESSON:
				$commands = RbacCommandLessonTable();
				break;
				
			case CMDB_OBJECTTYPE_QUIZ:
				$commands = RbacCommandQuizTable();
				break;
				
			default:
				if($object->post)
					$commands = RbacCommandForumTable();
				else
					$commands = RbacCommandObjectTable();
		}

		$createmenu = null;
		$showseparator = false;
		foreach($commands as $id)
		{
			if($id == SSPACE_COMMAND_SEPARATOR)
			{
				if($showseparator)
				{
					echo "<hr>";
					$showseparator = false;
				}
				
				continue;
			}

			$command = getdbo('Command', $id);
			if(!$this->rbac->objectAccess($object, $command))
				continue;
			
		//	debuglog("$command->name $command->image");
			if(	$command->id == SSPACE_COMMAND_ENROLL_ENROLL_SELF || 
				$command->id == SSPACE_COMMAND_ENROLL_UNENROLL_SELF)
			{
				if(!$object->course) continue;
				if($object->course->enrolltype != CMDB_OBJECTENROLLTYPE_SELF) continue;
	
				$b = isCourseEnrolled(getUser()->id, $object->id);
				
				if($b && $command->id == SSPACE_COMMAND_ENROLL_ENROLL_SELF) continue;
				if(!$b && $command->id == SSPACE_COMMAND_ENROLL_UNENROLL_SELF) continue;
			}

			if($object->folderimportid && (
				$command->id == SSPACE_COMMAND_COURSE_CREATE || 
				$command->id == SSPACE_COMMAND_OBJECT_IMPORTFOLDER))
				continue;

			if($command->id == SSPACE_COMMAND_FILE_MODIFY)
			{
				if(	$object->file->filetype != CMDB_FILETYPE_MEDIA &&
					!strstr($object->file->mimetype, 'x-empty') &&
					!strstr($object->file->mimetype, 'text') &&
					!strstr($object->file->mimetype, 'html'))
					continue;
			}

			if(strstr($command->url, '/delete'))
			{
				if($object->id == CMDB_OBJECTROOT_ID) continue;
				
				if($object->parent->id == CMDB_OBJECTROOT_ID && 
					$object->name == CMDB_PERSONALFOLDERNAME) continue;
				
				if($object->parent->id == CMDB_OBJECTROOT_ID &&
					$object->name == CMDB_LANGUAGECOURSESNAME) continue;
				
				$icon = $command->image;
				echo "<li>";
				echo l("{$icon}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$command->name",
					'#', array('id'=>"delete_$object->id"));

				echo <<<END
<script>$(function(){ $('#delete_$object->id').click(function(){
	if(confirm("Are you sure you want to delete this item?"))
		jQuery.yii.submitForm(this, "/$command->url&id=$object->id",{});
	return false;});});</script>
END;
				echo "</li>";
				continue;
			}
			
			if($command->id == SSPACE_COMMAND_OBJECT_PASTE)
			{
				$clipboardid = user()->getState('clipboardid');
				if(!$clipboardid) continue;
				
				$clip = getdbo('Object', $clipboardid);
				if(!$clip) continue;
				
				$clipboardcommand = user()->getState('clipboardcommand');
				if($clipboardcommand == 'copy')
					$command->name = "Paste \"$clip->name\"";
				else
					$command->name = "Move \"$clip->name\"";
			}
			
			$showseparator = true;
			if($command->createitem)
			{
				if(!$createmenu)
				{
					$createmenu = true;
                    if(controller()->rbac->globalAdmin()){
					   echo "<li id='object_submenu_$object->id'><a href='#'><img src='/images/base/dot.png'> 
						  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add Content</a><ul class='objectmenu'>";}
				}
				
				showListCommand($command, "id=$object->id");
			}
			else
			{
				if($createmenu)
				{
					$createmenu = null;
					echo '</ul></li>';
					echo "<hr>";

					JavascriptReady("object_buildsubmenu('object_submenu_$object->id');");
				}
				
				showListCommand($command, "id=$object->id");
			}
		}

		$allparents = array();
		$parent = $object->parent;

		while($parent)
		{
			if(!$this->rbac->objectAction($parent))
				break;

			array_unshift($allparents, $parent);
			$parent = $parent->parent;
		}

		if(count($allparents) > 0)
		{
			if($showseparator)
				echo "<hr>";
			
			echo "&nbsp;&nbsp;Location:";
			foreach($allparents as $object)
			{
				echo "<li>";
				echo l(objectImage($object, 18).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
					$object->name);
			 	echo '</li>';
			}
		}

		echo "</ul>";
	}

	/////////////////////////////////////////////////////////////

	public function actionBrowseHeader()
	{
		if(!Yii::app()->request->isAjaxRequest) return;
		showBrowserHeader(getparam('returnid'));
	}

	public function actionBrowseObject()
	{
		if(!Yii::app()->request->isAjaxRequest || !isset($_GET['id'])) return;
		showBrowserObject(getparam('returnid'), getparam('id'));
	}	
}






