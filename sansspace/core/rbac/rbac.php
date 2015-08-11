<?php

include 'defaultroles.php';
include 'tablecommands.php';
include 'exceptionFilter.php';

if(param('theme') == 'wayside')
	include 'defaultcommands-wayside.php';
else
	include 'defaultcommands.php';

/////////////////////////////////////////////////////

class RBAC3
{
	private $_user;

	private $_globalRoles;
	private $_objectsRoles;

	/////////////////////////////////////////////////////////////////////

	public function __construct($user)
	{
	//	debuglog("RBAC::__construct($user->logon) ----------------------");
		$this->_user = $user;
		
		$this->_globalRoles = array(SSPACE_ROLE_ALL=>SSPACE_ROLE_ALL);
		
		if($user->logon != 'guest')
			$this->_globalRoles[SSPACE_ROLE_USER] = SSPACE_ROLE_USER;
		
		if($user->logon == 'admin')
			$this->_globalRoles[SSPACE_ROLE_NETWORK] = SSPACE_ROLE_NETWORK;
		
		foreach($user->userenrollments as $e)
			$this->_globalRoles[$e->roleid] = $e->roleid;
	}

	/////////////////////////////////////////////////////////////////////

	private function commandEquivalent($url)
	{
	//	debuglog("RBAC::commandEquivalent($url)");
		
		$es = RbacDefaultCommandEquivalent();
		foreach($es as $e) if($e['url'] == $url)
		{
			$command = getdbo('Command', $e['id']);
			return $command;
		}
		
		return null;
	}

	public function commandFromurl($controller, $action='')
	{
	//	debuglog("RBAC::commandFromurl($controller, $action)");
		
		$command = getdbosql('Command', "url = '$controller/$action'");
		if($command) return $command;
		
		$command = $this->commandEquivalent("$controller/$action");
		if($command) return $command;
		
		$command = getdbosql('Command', "url = '$controller/'");
		if($command) return $command;
		
		return $command;
	}

	////////////////////////////////////////////////////////////////

	public function globalRoles()
	{
		return $this->_globalRoles;
	}
	
	public function globalNetwork()
	{
		foreach($this->_globalRoles as $roleid)
			if($roleid == SSPACE_ROLE_NETWORK) return true;

		return false;
	}

	public function globalAdmin()
	{
		foreach($this->_globalRoles as $roleid)
		{
			if($roleid == SSPACE_ROLE_ADMIN) return true;
			if($roleid == SSPACE_ROLE_NETWORK) return true;
 		}

		return false;
	}

	public function globalStudent()
	{
		foreach($this->_globalRoles as $roleid)
			if($roleid == SSPACE_ROLE_STUDENT) return true;

	//	debuglog('not student');
		return false;
	}

	public function globalTeacher()
	{
		foreach($this->_globalRoles as $roleid)
			if($roleid == SSPACE_ROLE_TEACHER) return true;

		return false;
	}

	public function globalUrl($controller, $action='')
	{
		$b = RbacFilterException($controller, $action);
		if($b) return true;
		
		$command = $this->commandFromurl($controller, $action);
		if(!$command && $this->globalAdmin()) return true;
		if(!$command) return false;
		
		return $this->globalAccess($command);
	}

	public function globalAccess($command)
	{
	//	debuglog("RBAC::globalAccess($command->id)");
	
		if($this->globalNetwork()) return true;
		if($this->globalAdmin()) return true;
		
		$ces = getdbolist('CommandEnrollment', "commandid=$command->id and objectid=0");
		foreach($ces as $ce)
		{
			foreach($this->_globalRoles as $roleid)
				if($roleid == $ce->roleid) return true;
		}

		return false;
	}

	////////////////////////////////////////////////////////////////

	public function objectUrl($object, $controller, $action='')
	{
	//	debuglog("RBAC::objectUrl($object->id, $controller/$action)");
		
		$b = RbacFilterException($controller, $action);
		if($b) return true;
		
		$command = $this->commandFromurl($controller, $action);
		if(!$command && $this->globalAdmin()) return true;
		if(!$command) {debuglog("no command");return false;}
		
		$result = $this->objectAccess($object, $command);
	//	debuglog("  result $result");
		return $result;
	}
	
	public function objectUrl2($object, $url)
	{
		$controller = substr($url, 0, strpos($url, '/'));
		$action = substr($url, strpos($url, '/')+1);
		
		return $this->objectUrl($object, $controller, $action);
	}
	
	public function objectAction($object, $action='')
	{
		$controller = '';
		switch($object->type)
		{
			case CMDB_OBJECTTYPE_COURSE:
				$controller = 'course';
				break;
				
			case CMDB_OBJECTTYPE_FILE:
				$controller = 'file';
				break;
				
			default:
				$controller = 'object';
				break;
		}
		
		return $this->objectUrl($object, $controller, $action);
	}
	
	public function objectAccess2($object, $commandid)
	{
		$command = getdbo('Command', $commandid);
		return $this->objectAccess($object, $command);
	}
	
	private function hasaccess($ces, $roles)
	{
		foreach($ces as $ce)
		{
			if($ce->has) foreach($roles as $roleid)
			{
	 			if($ce->roleid == SSPACE_ROLE_ALL) return true;
	 			if($ce->roleid == SSPACE_ROLE_USER &&
	 				$this->_user->logon != 'guest') return true;
				
				if($roleid == $ce->roleid) return true;
			}
		}
		
		return false;
	}
		
	public function objectAccess($object, $command)
	{
	//	debuglog("RBAC::objectAccess($object->id, $object->name, $command->id, $command->name, $command->url)");
	//	if($this->_user->logon == 'admin') return true;
		if(!$object) return $this->globalAccess($command);
		if($command->hideadmin && $this->globalAdmin($command)) return false;
		
		$roles = $this->objectRoles($object, $command);
	//	debuglog($roles);
		foreach($roles as $roleid)
		{
			if($roleid == SSPACE_ROLE_ADMIN) return true;
			if($roleid == SSPACE_ROLE_NETWORK) return true;
		}

		$ces = getdbolist('CommandEnrollment', "commandid=$command->id and objectid=0");
		$access = $this->hasaccess($ces, $roles);

		while($object)
		{
			$ces = getdbolist('CommandEnrollment', "commandid=$command->id and objectid=$object->id");
			if(count($ces))
			{
				$access = $this->hasaccess($ces, $roles);
				break;
			}
			
			$object = $object->parent;
		}

		return $access;
	}
	
	public function objectRoleAccess($commandid, $roleid, $object=null)
	{
//		if($ce->roleid == SSPACE_ROLE_ALL) return true;
//		if($ce->roleid == SSPACE_ROLE_USER &&

		$ar = array($roleid);
		if($roleid != SSPACE_ROLE_ALL) $ar[] = SSPACE_ROLE_ALL;
		if($roleid != SSPACE_ROLE_ALL && $roleid != SSPACE_ROLE_USER) $ar[] = SSPACE_ROLE_USER;
		$st = implode(',', $ar);
		
		$ce = getdbosql('CommandEnrollment', "commandid=$commandid and roleid in ($st) and objectid=0");
		$access = $ce? $ce->has: false;
		
		while($object)
		{
			$ce = getdbosql('CommandEnrollment', "commandid=$commandid and roleid in ($st) and objectid=$object->id");
			if($ce)
			{
				$access = $ce->has;
				break;
			}

			$object = $object->parent;
		}
		
		return $access;
	}

	public function objectRoles($object, $command=null)
	{
		if($this->_user->logon == 'admin') return array(SSPACE_ROLE_NETWORK);
		if(!$object) return $this->_globalRoles;
		
		$commandid = 0;
		if($command) $commandid = $command->id;
		
		if(isset($this->_objectsRoles[$object->id][$commandid]))
			return $this->_objectsRoles[$object->id][$commandid];
		
		$this->_objectsRoles[$object->id][$commandid] = $this->_user->objectRoles($object, $command);
		return $this->_objectsRoles[$object->id][$commandid];
	}
	
// 	public function objectHasRole($object, $roleid)
// 	{
// 		$roles = $this->objectRoles($object);
// 		foreach($roles as $role)
// 			if($role->id == $roleid)
// 				return true;
			
// 		return false;
// 	}
	
}




