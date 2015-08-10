<?php

function filterSemesters($objects)
{
	if(isset($_GET['semesterid']) && $_GET['semesterid'] != 'undefined')
	{
		$semesterid = $_GET['semesterid'];
		user()->setState('semesterid', $semesterid);
	}

	else
	{
		$semester = getCurrentSemester();
		$semesterid = user()->getState('semesterid', $semester->id);
	}

	if(!$semesterid) return $objects;

	$objects2 = array();
	for($i = 0; $i < count($objects); $i++)
	{
		if($objects[$i]->type == CMDB_OBJECTTYPE_COURSE)
		{
			$course = getdbo('VCourse', $objects[$i]->id);
			if($course->semesterid == 0 || $course->semesterid == $semesterid)
				$objects2[] = $objects[$i];
		}
		else
			$objects2[] = $objects[$i];
	}

	return $objects2;
}

//////////////////////////////////////////////////////////////

function filterRecordingName($object)
{
	if($object->recordings)
	{
		if($object->parent->id == 1)
			$object->name = 'Practice Folders';
		else if(!intval(getparam('id')))
			$object->name = $object->parent->name;
		else
			$object->name = "Students' Work";	// ({$object->parent->name})";
	}

	else if($object->authorid == userid() && $object->parent->recordings)
	{
		if($object->parent->parent->id == 1)
			$object->name = 'My Practice Folder';

		else
			$object->name = $object->parent->parent->name;
	}

	return $object;
}

function filterRecordingNames($objects)
{
	for($i = 0; $i < count($objects); $i++)
		$objects[$i] = filterRecordingName($objects[$i]);

	return $objects;
}

//////////////////////////////////////////////////////////////////////////////////////////

function filterObjectQuery($criteria)
{
	$sql = $criteria->condition;
	if(isset($_GET['s']) && !empty($_GET['s']))
	{
		$search = XssFilter($_GET['s']);
		if(!empty($search) && $search != 'Search')
			$sql = "(name like '%{$search}%' or tags like '%{$search}%' or ".
			"id in (select objectid from ObjectExt where doctext like '%{$search}%') or ".
			"id in (select parentid from Comment where doctext like '%{$search}%')) and ".$sql;
	}

	////////////////////////////////////////////////////////////////

	if(isset($_GET['sort']) && !empty($_GET['sort']))
	{
		$criteria->order = $_GET['sort'];
		user()->setState('listsort', $_GET['sort']);
	}

	else if(user()->getState('listsort') && !isset($_GET['sort']))
		$criteria->order = user()->getState('listsort');

	else
	{
		$criteria->order = 'displayorder, name';
		user()->setState('listsort', $_GET['displayorder']);
	}

	////////////////////////////////////////////////////////////////

// 	$showfilter = isset($_GET['filter'])? $_GET['filter']: '';
// 	if(!empty($showfilter))
// 	{
// 		if($showfilter == 'showfolder')
// 			$sql = "type=".CMDB_OBJECTTYPE_OBJECT." and ".$sql;

// 		if($showfilter == 'showcourse')
// 			$sql = "type=".CMDB_OBJECTTYPE_COURSE." and ".$sql;

// 		if($showfilter == 'showfile')
// 			$sql = "type=".CMDB_OBJECTTYPE_FILE." and ".$sql;

// 		if($showfilter == 'showlink')
// 			$sql = "type=".CMDB_OBJECTTYPE_LINK." and ".$sql;
// 	}

	$showfilter = getparam('filter');
	if($showfilter != -1)
		$sql = "type=$showfilter and $sql";
	
	////////////////////////////////////////////////////////////////

	//	$sql = "not recordings and ".$sql;
	//	debuglog($sql);

	$criteria->condition = $sql;
	return $criteria;
}

//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////

function ObjectQueryIterate(&$addids, $object)
{
	if(!$object) return;
	//	debuglog("ObjectQueryIterate($object->name, $object->id)");

	$os = getdbolist('Object', "parentid={$object->id} and type=".CMDB_OBJECTTYPE_LINK);
	foreach($os as $o)
		ObjectQueryProcessItem(&$addids, $o->link);

	if($object->model)
	{
		$parent = $object->parent;
		while($parent && $parent->model)
		{
			$rcs = getdbolist('Object', "parentid={$parent->id} and type=".CMDB_OBJECTTYPE_LINK);
			if($rcs) foreach($rcs as $rc)
			{
				ObjectQueryProcessItem(&$addids, $rc->link);
				$addids[$rc->id] = true;
			}

			$parent = $parent->parent;
		}
	}
}

function ObjectQueryProcessItem(&$addids, $object, $func=null)
{
	//	debuglog("ObjectQueryProcessItem($object->name, $object->id)");

	if($func) $func(&$addids, $object);
	// 	$found = false;

	// 	// check if already added
	// 	foreach($addids as $ai=>$ar)
		// 	{
		// 		$aio = getdbo('Object', $ai);
		// 		if(	$aio->type != CMDB_OBJECTTYPE_COURSE &&
		// 			$aio->type != CMDB_OBJECTTYPE_ACTIVITY &&
		// 			strstr($object->parentlist, ", $ai,"))
			// 		{
			// 		//	debuglog("found $object->name - {$object->parentlist} - $ai");
			// 			$found = true;
			// 			break;
			// 		}
			// 	}

	// 	if(!$found)
	$addids[$object->id] = true;
}

	///////////////////////////////////////////////////////////////////////

function buildUserQuery($user)
{
	if(controller()->rbac->globalAdmin()) return "1";
	$addids = array();

	$objectids = $user->objectEnrollmentsExt();
	foreach($objectids as $id=>$roleid)
	{
		//	debuglog($id);
		$object = getdbo('Object', $id);
		ObjectQueryProcessItem(&$addids, $object, ObjectQueryIterate);
	}

	/////////////////////////////////////////////

	$sql = "(parentlist like '%, {$user->folder->id}, %'";
	foreach($addids as $ai=>$ar)
	{
		//	debuglog(" ->> $ai");
		$sql .= " or parentlist like '%, $ai, %'";
		$o = getdbo('Object', $ai);

		if($o->type == CMDB_OBJECTTYPE_COURSE)
		{
			//	debuglog(" $o->name");

			$e = getdbosql('CourseEnrollment', "userid=$user->id and objectid=$o->id");
			$f = userRecordingFolder($o, $user);
				
			if($e->roleid == SSPACE_ROLE_TEACHER)
				$f = $f->parent;
				
			$sql .= " or parentlist like '%, $f->id, %'";
		}
	}

	$sql .= ")";
	//	debuglog("buildUserQuery: $sql");

	return $sql;
}

function buildSimpleObjectQuery($user, $parentid)
{
	$sql = buildUserQuery($user);
	$sql = "parentid={$parentid}) and ".$sql;

	$parent = getdbo('Object', $parentid);
	if($parent->type == CMDB_OBJECTTYPE_COURSE && $parent->model)
	{
		$parent = $parent->parent;
		while($parent && $parent->model)
		{
			$rcs = getdbolist('Object', "parentid={$parent->id} and type=".CMDB_OBJECTTYPE_LINK);
			if($rcs) foreach($rcs as $rc)
				$sql = "id=$rc->id or ".$sql;

			$parent = $parent->parent;
		}
	}

	$sql = "(".$sql;
	$sql = $sql." order by displayorder, name";

	//	debuglog("buildSimpleObjectQuery $sql");
	return $sql;
}

function boqAddObject($object)
{
	if(isset($_GET['recursive']) && $_GET['recursive'] == 'true')
		$sql = "not id={$object->id} and parentlist like '%, {$object->id}, %'";

	else
		$sql = "parentid={$object->id}";

	return $sql;
}

function boqAddObjectLink($object)
{
	if(isset($_GET['recursive']) && $_GET['recursive'] == 'true')
		$sql = "parentlist like '%, {$object->id}, %'";

	else
		$sql = "id={$object->id}";

	return $sql;
}

function buildObjectQuery($user, $id)
{
	$criteria = new CDbCriteria;
	if(intval($id))
	{
		$object = getdbo('Object', $id);
		if(!$object) return null;

		$sql = buildUserQuery($user);

		if(!controller()->rbac->objectAction($object, 'update'))
			$sql = "not deleted and not hidden and ".$sql;

		//	debuglog("$sql");
		if($object->type == CMDB_OBJECTTYPE_LINK)
			$object = $object->link;

		$sql2 = boqAddObject($object);
		if($object->model)
		{
			//	debuglog("1 $object->name, $object->id");
			$parent = $object->parent;
			while($parent && $parent->model)
			{
				$rcs = getdbolist('Object', "parentid={$parent->id} and type=".CMDB_OBJECTTYPE_LINK);

				if($rcs) foreach($rcs as $rc)
					$sql2 = boqAddObjectLink($rc)." or $sql2";

				$parent = $parent->parent;
			}
		}

// 		if($object->recordings)
// 		{
// 			$activities = getdbolist('VCourse',
// 					"type=".CMDB_OBJECTTYPE_ACTIVITY.
// 					" and parentlist like '%, {$object->parent->id}, %'");
				
// 			foreach($activities as $activity)
// 			if($activity->recordingid != $object->id)
// 				$sql2 = "$sql2 or id=$activity->recordingid";
// 		}

		//	debuglog("sql2 $sql2");
		$sql = "($sql2) and $sql";
	}

	else
	{
		$objects = objectList($id);
		if(!$objects) return null;

		$sql = "0) " . $sql;
		foreach($objects as $object)
		{
			if($object->type == CMDB_OBJECTTYPE_LINK)
				$object = $object->link;

			if(isset($_GET['recursive']) && $_GET['recursive'] == 'true')
				$sql = "parentlist like '%, {$object->id}, %' or ".$sql;
			else
				$sql = "id={$object->id} or ".$sql;
		}

		$sql = "(" . $sql;
	}

	//	debuglog("buildObjectQuery: $sql");
	$criteria->condition = $sql;
	return $criteria;
}











