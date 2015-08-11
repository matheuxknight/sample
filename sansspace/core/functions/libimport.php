<?php

function safeCreateObject($name, $parentid)
{
	$object = getdbosql('Object', "name='$name' and parentid=$parentid");
	if($object) return $object;
		
	return objectCreate($name, $parentid);	
}

function safeCreateCourse($name, $parentid, $semesterid)
{
	$course = getdbosql('VCourse', "name='$name' and parentid=$parentid");
	if($course)
	{
		$recording = $course->recording;
		if($recording) return $course;

		$recording = objectCreate('Recordings', $course->id);
		$recording->recordings = true;
		$recording->parentlist = objectParentList($recording);
		$recording->save();
		
	//	$recording->ext->doctext = "<p>$course->name Dropboxes</p>";
		$recording->ext->doctext = "<p></p>";
		$recording->ext->save();

		$rcourse = getdbo('Course', $course->id);
		$rcourse->recordingid = $recording->id;
		$rcourse->save();

		return getdbo('VCourse', $course->id);
	}

	return courseCreate($name, $parentid, $semesterid);
}

function safeCreateUser($logonname, $username, $emailname, $domainid=0)
{
	$logonname = addslashes($logonname);
	$user = getdbosql('User', "logon='$logonname'");
	if($user)
	{
		$user->used = now();
		$user->save();
		
		return $user;
	}
	
	if(!$domainid) $domainid = param('defaultdomain');
	return userCreate($logonname, $username, $emailname, $domainid);
}

//////

function isUserEnrolled($userid, $roleid)
{
	return getdbosql('UserEnrollment', "userid=$userid and roleid=$roleid");
}

function safeUserEnrollment($userid, $roleid)
{
	if(!$roleid) return null;
	
	$e = isUserEnrolled($userid, $roleid);
	if($e) return $e;
	
	return createUserEnrollment($userid, $roleid);
}

function createUserEnrollment($userid, $roleid)
{
	$e = new UserEnrollment;
	$e->id = $id;
	$e->userid = $userid;
	$e->roleid = $roleid;
	$e->save();
	return $e;
}

//////

function isObjectEnrolled($userid, $objectid, $roleid)
{
	return getdbosql('ObjectEnrollment', "userid=$userid and objectid=$objectid and roleid=$roleid");
}

function safeObjectEnrollment($userid, $roleid, $objectid)
{
	if(!$roleid) return null;
	
	$e = isObjectEnrolled($userid, $objectid, $roleid);
	if($e) return $e;
	
	return createObjectEnrollment($userid, $roleid, $objectid);
}

function createObjectEnrollment($userid, $roleid, $objectid)
{
	$e = new ObjectEnrollment;
	$e->userid = $userid;
	$e->roleid = $roleid;
	$e->objectid = $objectid;
	$e->save();
	return $e;
}

//////

function isCourseEnrolled($userid, $objectid, $courseid=0)
{
	if($courseid)
		return getdbosql('CourseEnrollment', "userid=$userid and objectid=$objectid and courseid=$courseid");
	else
		return getdbosql('CourseEnrollment', "userid=$userid and objectid=$objectid");
}

function safeCourseEnrollment($userid, $roleid, $objectid)
{
	if(!$roleid) return null;

	$user = getdbo('User', $userid);
	if(!$user) return;
	
	$user->used = now();
	$user->save();
	
	$e = isCourseEnrolled($userid, $objectid);
	if($e)
	{
		$e->deleted = false;
		$e->save();
		
		return $e;
	}
	
	return createCourseEnrollment($userid, $roleid, $objectid);
}

function createCourseEnrollment($userid, $roleid, $objectid, $courseid=0)
{
//	debuglog("createCourseEnrollment($userid, $roleid, $objectid)");

	$user = getdbo('User', $userid);
	$object = getdbo('Object', $objectid);
	
	if(!$courseid && $object->type != CMDB_OBJECTTYPE_COURSE)
	{
		$course = getRelatedCourse($object, $user);
		$courseid = $course->id;
	}
	
	$recording = userRecordingFolder($object, $user, $courseid);
	
	$e = new CourseEnrollment;
	$e->userid = $userid;
	$e->roleid = $roleid;
	$e->objectid = $objectid;
	$e->courseid = $courseid;
	$e->recordingid = $recording? $recording->id: 0;
	$e->deleted = false;
	$e->save();
	return $e;
}

function createEnrollmentFromCourse($user, $object)
{
//	debuglog("createEnrollmentFromCourse $object->name");

	$courseid = getContextCourseId();
	if(!$courseid) return false;
// 	{
// 		$parent = $object->parent;
// 		while($parent)
// 		{
// 			if($parent->type == CMDB_OBJECTTYPE_COURSE)
// 			{
// 				$courseid = $parent->id;
// 				break;
// 			}
				
// 			$parent = $parent->parent;
// 		}

// 		if(!$courseid) return false;
// 	}
	
	$e = isCourseEnrolled($user->id, $object->id, $courseid);
	if($e) return true;
	
	$e = isCourseEnrolled($user->id, $courseid);
	if(!$e) return false;

	createCourseEnrollment($user->id, $e->roleid, $object->id, $courseid);
//	userRecordingFolder($object, $user);
	
	return true;
}

///////////////////////////

function safeCreateFile($name, $parentid, $filename='', $masterid=0, 
		$filetype=CMDB_FILETYPE_UNKNOWN)
{
//	debuglog("safeCreateFile $name, $parentid, $filename");
	
	$object = new Object;
	$object->type = CMDB_OBJECTTYPE_FILE;
	$object->name = $name;
	
	$object = objectInit($object, $parentid);
	if(!$object) return;
	
	$ext = getExtension($filename);
	
	$object->pathname = $object->id.$ext;
	$object->save();
	
	$rfile = new File;
	$rfile->objectid = $object->id;
	$rfile->originalid = $masterid;
	$rfile->filetype = $filetype;
	$rfile->save();
	
	$targetname = objectPathname($object);
	
	@unlink($targetname);
	@unlink($targetname.FLV_INDEX_EXTENSION2);
	
	if($filename && $filename != '.flv')
	{
		debuglog("move $filename, $targetname");
		@rename($filename, $targetname);

		$object = scanFileObject($object);
//		$object = scanObjectBackground($object);
	}
	
	objectUpdateParent($object, now());
	return getdbo('VFile', $object->id);
}




