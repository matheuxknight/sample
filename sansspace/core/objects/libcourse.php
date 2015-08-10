<?php

function getRelatedCourse($object, $user)
{
	foreach($user->courseenrollments as $e)
	{
		if($e->object->type != CMDB_OBJECTTYPE_COURSE) continue;
		$course = $e->object->course;
	
		if(isCourseHasObject($course, $object))
			return $course;
	}
	
//	debuglog("getRelatedCourse($object->name, $user->name) -> null");
	return null;
}

function getContextCourse()
{
	$courseid = user()->getState('courseid');
	if(!$courseid) return null;
	
	$course = getdbo('VCourse', $courseid);
	return $course;
		
// 	foreach($user->courseenrollments as $e)
// 	{
// 	//	debuglog("e {$e->object->name}");
// 		if($e->object->type != CMDB_OBJECTTYPE_COURSE) continue;
// 		if($courseid && $e->object->id != $courseid) continue;
		
// 		$course = $e->object->course;
// 	//	debuglog("looking for $course->name");
	
// 		if(isCourseHasObject($course, $object))
// 		{
// 			debuglog("getContextCourse($object->name) -> $course->name");
// 			return $course;
// 		}
// 	}
	
// 	debuglog("getRelatedCourse($object->name, $user->name) -> null");
// 	return null;
}

function userRecordingFolder($object, $user=null, $courseid=0)
{
//	debuglog("userRecordingFolder($object->name, $user->name, $courseid)");
	if($user == null) $user = getUser();

	if($courseid && $courseid!=$object->id)
		$e = getdbosql('CourseEnrollment', "objectid=$object->id and userid=$user->id and courseid=$courseid");
	else
		$e = getdbosql('CourseEnrollment', "objectid=$object->id and userid=$user->id");
	if(!$e) return null;

	if($e->recording)
		return $e->recording;

	if($e->course)
		$course = $e->course;

	else if($object->type == CMDB_OBJECTTYPE_COURSE)
		$course = $object->course;

	else
	{
		$course = getRelatedCourse($object, $user);
		if(!$course) return null;
	}

	$course_recording = $course->recording;
	if(!$course_recording)
	{
		$course_recording = objectCreate('Recordings', $course->id);
		$course_recording->authorid = 0;
		$course_recording->recordings = true;
		$course_recording->parentlist = objectParentList($course_recording);
		$course_recording->save();

		$rcourse = $course->rcourse;
		$rcourse->recordingid = $course_recording->id;
		$rcourse->save();
	}
	
	$folder = getdbosql('Object', "parentid=$course_recording->id and authorid=$user->id");
	if(!$folder)
	{
		$folder = objectCreate("$user->name ($user->logon)", $course_recording->id);
		$folder->authorid = $user->id;
		$folder->save();
	}
	
	if($object->type != CMDB_OBJECTTYPE_COURSE)
	{
		$folder2 = getdbosql('Object', "parentid=$folder->id and name='$object->name'");
		if(!$folder2)
		{
			$folder2 = objectCreate($object->name, $folder->id);
			$folder2->authorid = $user->id;
			$folder2->save();
		}

		$folder = $folder2;
	}

	return $folder;
}

// function updateAutoEnrollment($user)
// {
// 	if($user->logon == 'guest') return;
	
// 	$courses = getdbolist('VCourse', "enrolltype=".CMDB_OBJECTENROLLTYPE_AUTOSTUDENT);
// 	if($courses) foreach($courses as $course)
// 		safeCourseEnrollment($user->id, SSPACE_ROLE_STUDENT, $course->id);
// }

function updatePersonalRole($user)
{
	foreach($user->courseenrollments as $enrollment)
		safeUserEnrollment($user->id, $enrollment->roleid);
}

function createPersonalFolder($user)
{
	if(!$user->folderid || !$user->folder)
	{
		$personal_root = getdbosql('Object', 
			'parentid='.CMDB_OBJECTROOT_ID.' and name=\''.CMDB_PERSONALFOLDERNAME.'\'');
			
		if(!$personal_root)
		{
			$personal_root = objectCreate(CMDB_PERSONALFOLDERNAME, CMDB_OBJECTROOT_ID);
			$personal_root->displayorder = 99999;
			$personal_root->recordings = true;
			$personal_root->save();
		}
		
		$object = objectCreate("$user->name ($user->logon)", $personal_root->id);

	//	$object->ext->doctext = "<p>Practice Folder</p>";
		$object->ext->save();
	//	$object->recordings = true;
		$object->save(false);

		$user->folderid = $object->id;
		$user->save();
	}
}

function isCourseOutOfDate($object)
{
	$outofdate = false;
	if($object->type == CMDB_OBJECTTYPE_COURSE)
	{
		if($object->course->usedate)
		{
			$startArr = explode("-", $object->course->startdate);
			$endArr = explode("-", $object->course->enddate);

			$startInt = mktime(0, 0, 0, $startArr[1], $startArr[2], $startArr[0]);
			$endInt = mktime(23, 59, 59, $endArr[1], $endArr[2], $endArr[0]);

			if(time() < $startInt || time() > $endInt)
				$outofdate = true;
		}
	}

	return $outofdate;
}

function courseLinks($course)
{
//	debuglog("** $course->name");
	$links = dbocolumn("select linkid from object where parentlist like '%, $course->id, %' and type=".CMDB_OBJECTTYPE_LINK);
	
	$parent = $course;
	while($parent->model)
	{
		$links = array_merge($links, dbocolumn("select linkid from object where parentid=$parent->id and type=".CMDB_OBJECTTYPE_LINK));
		$parent = $parent->parent;
	}
	
//	debuglog($links);
	return $links;
}

function isCourseHasObject($course, $object)
{
//	debuglog("isCourseHasObject($course->name, $object->name)");
	
	if(strstr($object->parentlist, ", $course->id, "))
		return true;
	
	$parent = $course->parent;
	if($parent && $parent->type == CMDB_OBJECTTYPE_TEXTBOOK && strstr($object->parentlist, ", $parent->id, "))
		return true;
	
	$links = courseLinks($course);
	while($object)
	{
		if(in_array($object->id, $links))
			return true;
		
		$object = $object->parent;
	}
	
	return false;
}





