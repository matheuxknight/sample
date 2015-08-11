<?php

// function showReportNavigationBar($group)
// {
// 	$parentList = array();
// 	$parentgroup = $group;

// 	while($parentgroup !== null)
// 	{
// 		if($parentgroup->type == CMDB_OBJECTTYPE_COURSE || $parentgroup->type == CMDB_OBJECTTYPE_ACTIVITY)
// 			array_unshift($parentList, $parentgroup);

// 		$parentgroup = $parentgroup->parent;
// 	}

// 	//if(empty($parentList)) return;
// 	echo "<div class='ssnavigation'>";

// 	echo l("My Report", array("report/show"))." / ";
// 	foreach($parentList as $model)
// 		echo l(h($model->name), array("report/show", 'id'=>$model->id))." / ";

// 	echo "</div>";
// }

function showButtonHeader()
{
	echo "<div class='buttonwrapper'>";
}

function showButton($name, $link, $htmlOptions = array())
{
	echo CHtml::link($name, $link, $htmlOptions);
}

function showButtonPost($name, $htmlOptions)
{
	echo CHtml::linkButton($name, $htmlOptions);
}

function showTextTeaser($text, $more, $count = 80, $class = 'text')
{
	if(empty($text)) return "";

	$text = strip_tags($text);
	if(strlen($text) < $count)
	{
		echo "<p class='$class'>$text</p>";
		return;
	}

	$text = substr($text, 0, $count)."...";
	echo "<p class='$class'>".$text." [".CHtml::link("more...", $more)."]</p>";
}

function getTextTeaser($text, $count = 120)
{
	if(empty($text)) return "";

	$text = strip_tags($text);
	if(strlen($text) < $count)
		return $text;

	$text = substr($text, 0, $count)."...";
	return $text;
}

function showObjectMenu($object)
{
	if($object->recordings) return;
	$commands = null;

	$showfilters = false;
	switch($object->type)
	{
		case CMDB_OBJECTTYPE_LINK:
			$shortcut = RbacCommandLinkShortcut();
			$commands = RbacCommandLinkTable();
			break;
			
		case CMDB_OBJECTTYPE_QUESTIONBANK:
			$shortcut = RbacCommandQuestionShortcut();
			$commands = RbacCommandQuestionTable();
			break;
			
		case CMDB_OBJECTTYPE_FILE:
			$shortcut = RbacCommandFileShortcut();
			$commands = RbacCommandFileTable();
			break;

		case CMDB_OBJECTTYPE_COURSE:
		//	$showfilters = true;
			$shortcut = RbacCommandCourseShortcut();
			$commands = RbacCommandCourseTable();
			break;
			
		case CMDB_OBJECTTYPE_FLASHCARD:
			$shortcut = RbacCommandFlashcardShortcut();
			$commands = RbacCommandFlashcardTable();
			break;
			
		case CMDB_OBJECTTYPE_SURVEY:
			$shortcut = RbacCommandSurveyShortcut();
			$commands = RbacCommandSurveyTable();
			break;
			
		case CMDB_OBJECTTYPE_TEXTBOOK:
		//	$showfilters = true;
			$shortcut = RbacCommandTextbookShortcut();
			$commands = RbacCommandTextbookTable();
			break;
			
		case CMDB_OBJECTTYPE_LESSON:
		//	$showfilters = true;
			$shortcut = RbacCommandLessonShortcut();
			$commands = RbacCommandLessonTable();
			debuglog($shortcut);
			break;
			
		case CMDB_OBJECTTYPE_QUIZ:
			$shortcut = RbacCommandQuizShortcut();
			$commands = RbacCommandQuizTable();
			break;
			
		default:
		//	$showfilters = true;
			if($object->post)
			{
				$shortcut = RbacCommandForumShortcut();
				$commands = RbacCommandForumTable();
			}
			else
			{
				$shortcut = RbacCommandObjectShortcut();
				$commands = RbacCommandObjectTable();
			}
				
			break;
	}
	
	$createmenu = null;
	$count = 0;
	
	foreach($shortcut as $id)
	{
		if($id == SSPACE_COMMAND_SEPARATOR) continue;
		$found = false;
		foreach($commands as $c)
		{
			if($c == $id)
			{
				$found = true;
				break;
			}
		}

		if(!$found) continue;
		$command = getdbo('Command', $id);
		
 	//	debuglog($id);
		if(!controller()->rbac->objectAccess($object, $command))
			continue;
		
		if(strstr($command->url, '/import') && $object->folderimportid)
			continue;
		
		$urlarray = array($command->url, 'id'=>$object->id);
		
		if($command->id == SSPACE_COMMAND_ENROLL_ENROLL_SELF || 
			$command->id == SSPACE_COMMAND_ENROLL_UNENROLL_SELF)
		{
			if(!$object->course) continue;
			if($object->course->enrolltype != CMDB_OBJECTENROLLTYPE_SELF) continue;

			$b = isCourseEnrolled(getUser()->id, $object->id);

			if($b && $command->id == SSPACE_COMMAND_ENROLL_ENROLL_SELF) continue;
			if(!$b && $command->id == SSPACE_COMMAND_ENROLL_UNENROLL_SELF) continue;
		}

		if($command->id == SSPACE_COMMAND_COURSE_CONNECT)
		{
			$server = getdbo('Server', 1);
			if(!$server->allow_chat) continue;
		}
		
		//////////////////////////////////////////////////////////
		
//		if(!$count) echo "<hr style='margin: 0; height: 10px; visibility:hidden;' />";
		if($command->createitem)
		{
			if(!$createmenu)
			{
				$createmenu = true;

				echo "<a href='' id='link_shortcut_add_content'>Add Content&nbsp;&nbsp;
						<img src='/images/ui/arrow-down.gif'></a>&nbsp;&nbsp;";
				
				if(param('shortcutbutton'))
					JavascriptReady("$('#link_shortcut_add_content').button();");
				
				echo "<div id='objectmenu-container' class='objectmenu-box''><ul class='objectmenu'>";
				
				JavascriptReady("
				$('#link_shortcut_add_content').click(function(e)
				{
					$('#objectmenu-container').toggle();
					return false;
				});
						
				$('#objectmenu-container').hover(null, function(e)
				{
					$('#objectmenu-container').hide();
				});");
			}
			
			showListCommand($command, "id=$object->id");
		}
		else
		{
			if($createmenu)
			{
				$createmenu = null;
				echo '</ul></div>';
			}
			
			echo l("$command->image $command->name", $urlarray, 
				array('id'=>"link_shortcut_$command->id", 'title'=>$command->title)).'&nbsp;&nbsp;';
			
			if(param('shortcutbutton'))
				JavascriptReady("$('#link_shortcut_$command->id').button();");
		}
				
		$count++;
	}

	if($createmenu)
	{
	//	$createmenu = null;
		echo '</ul></div>';
	}

	if($showfilters)
	{
		echo "<a href='' id='showpanel'>Filters&nbsp;&nbsp;
			<img src='/images/ui/arrow-down.gif'></a>&nbsp;&nbsp;";
	
		if(param('shortcutbutton'))
			JavascriptReady("$('#showpanel').button();");
	
		JavascriptReady("
			$('#showpanel').click(function(e)
			{
				$('#searchpanel').toggle();
				return false;
			});");
	}
	
	
// 	$user = getUser();
// 	if($object->id == $user->folderid)
// 	{
// 		echo l(mainimg('menudot.png', '', array('width'=>16)).' Recover Recordings',
// 			array('user/recover')).'&nbsp;&nbsp;';
// 	}

	if($count)
		echo '<br>';
}

function showListObject($object, $selected=null)
{
	$color = array();
	if($selected)
		if(strstr($selected->parentlist, ", $object->id, "))
			$color = array('style'=>'background:#A1B4D9');

	echo "<li id=$object->id>".l(objectImage($object, 18).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.
		h($object->name), objectUrl($object), $color);

	if($object->type == CMDB_OBJECTTYPE_LINK)
		$object = $object->link;

	if($object)
	{
		$count = getdbocount('Object', "parentid={$object->id} and not deleted and not hidden");
		if($count)
		 	echo '<ul><li>'. l(mainimg('loading_white.gif', '', array('width'=>16)).
		 		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'Loading...') .'</li></ul>';
	}

 	echo '</li>';
}

function showListCommand($command, $id='')
{
	$liid = '';
	$idstring = '';

	if(!empty($id)) $idstring = "&$id";

	if($command->url == 'my/courses')
		$liid = "id='mycourses'";

	else if($command->url == 'my/locations')
		$liid = "id='mylocations'";

	else if($command->url == 'my/folders')
		$liid = "id='myfolders'";

	else if($command->url == 'my/favorites')
		$liid = "id='myfavorites'";

	echo "<li $liid>".l("$command->image &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$command->name",
		"/{$command->url}$idstring", array('title'=>$command->title));

	if($liid != '')
	{
		echo '<ul><li>'. l(mainimg('loading_white.gif', '', array('width'=>16)).
	 		'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'Loading...') .'</li></ul>';
	}

	echo '</li>';
}

function showTableSorter($id, $options='')
{
	JavascriptReady("$('#{$id}').tablesorter({$options});");
	echo "<table id='$id' class='dataGrid2'>";
}

//function getAvatarIcon($user, $width=48)
//{
//	$avatarname = SANSSPACE_CONTENT."/avatar-{$user->id}.png";
//
//	if(file_exists($avatarname))
//		return img("/contents/avatar-{$user->id}.png", '', array('width'=>$width));
//	else
//		return iconimg('user.png', '', array('width'=>$width));
//}



