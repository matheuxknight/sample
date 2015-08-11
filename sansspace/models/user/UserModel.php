<?php

class User extends CActiveRecord
{
	static public $isteacher = null;
	private $_objectids = null;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'User';
	}

	public function rules()
	{
		return array(
			array('name','length','max'=>200),
			array('email','length','max'=>200),
			array('phone1','length','max'=>200),
			array('phone2','length','max'=>200),
			array('language','length','max'=>200),
			array('organisation','length','max'=>200),
			array('address','length','max'=>200),
			array('city','length','max'=>200),
			array('postal','length','max'=>200),
			array('state','length','max'=>200),
			array('country','length','max'=>200),
			array('custom1','length','max'=>200),
			array('custom2','length','max'=>200),
			array('enrolled','length','max'=>200),
			
			array('domainid', 'required'),
			array('name', 'required'),
		
			array('logon', 'required'),
			array('logon', 'unique'),
				
			param('theme')=='wayside'? array('exempt','length','max'=>200): null,
			param('theme')=='wayside'? array('logon', 'match', 'pattern'=>'/^([0-9a-zA-Z\-_!#*%\.@])+$/'): null,
			param('theme')=='wayside'? array('logon', 'length', 'min'=>2): null,
			param('theme')=='wayside'? array('logon', 'length', 'max'=>32): null,

			param('theme')=='wayside'? array('email', 'required'): null,
			param('theme')=='wayside'? array('email', 'email'): null,
			param('theme')=='wayside'? array('email', 'unique'): null,

			param('theme')=='wayside'? array('password', 'required'): null,
			param('theme')=='wayside'? array('password', 'length', 'min'=>8): null,

			param('theme')=='wayside'? array('organisation', 'required'): null,
			param('theme')=='wayside'? array('city', 'required'): null,
			param('theme')=='wayside'? array('state', 'required'): null,
			param('theme')=='wayside'? array('postal', 'required'): null,
			param('theme')=='wayside'? array('country', 'required'): null,

 			//User::$isteacher? array('organisation', 'required'): null,
 			//User::$isteacher? array('city', 'required'): null,
 			//User::$isteacher? array('state', 'required'): null,
 			//User::$isteacher? array('postal', 'required'): null,
 			//User::$isteacher? array('country', 'required'): null,
		);
	}

	public function relations()
	{
		return array(
			'domain'=>array(self::BELONGS_TO, 'Domain', 'domainid'),
			'userenrollments'=>array(self::HAS_MANY, 'UserEnrollment', 'userid'),
			'objectenrollments'=>array(self::HAS_MANY, 'ObjectEnrollment', 'userid'),
			'courseenrollments'=>array(self::HAS_MANY, 'CourseEnrollment', 'userid'),
			'comments'=>array(self::HAS_MANY, 'Comment', 'authorid'),
			'favorites'=>array(self::HAS_MANY, 'Favorite', 'userid'),
			'folder'=>array(self::BELONGS_TO, 'Object', 'folderid'),
			'session'=>array(self::HAS_MANY, 'Session', 'userid'),
			'fileSession'=>array(self::HAS_MANY, 'FileSession', 'userid'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'id'=>'Id',
			'domainid'=>'Domain',
			'name'=>'Name',
			'logon'=>'Username',
			'password'=>'Password',
			'autoadded'=>'Autoadded',
			'enable'=>'enable',
			'status'=>'Status',
			'updated'=>'Updated',
			'created'=>'Created',
			'accessed'=>'Last Access',
			'expires'=>'Expires',
			'email'=>'Email',
			'roleid'=>'Role',
			'msn'=>'Msn',
			'phone1'=>'Phone',
			'phone2'=>'Phone2',
			'language'=>'Language',
			'organisation'=>'School',
			'address'=>'Street Address',
			'city'=>'City',
			'postal'=>'Zip Code',
			'state'=>'State',
			'country'=>'Country',
			'custom1'=>'Custom1',
			'custom2'=>'Custom2',
			'comment'=>'Comment',
			'enrolled'=>'Course Enrollment Status',
			'exempt'=>'Exempt',
			'announcement'=>'Announcement',
            'alertid'=>'Alert ID',
		);
	}

	/////////////////////////////////////////////////////////////////////////////////////////
	
	public function objectEnrollmentsExt()
	{
		if($this->_objectids) return $this->_objectids;
		
	//	debuglog("User::objectEnrollmentsExt()");
		$objectids = array();
		
		$extraroles = array(SSPACE_ROLE_ALL);
		if($this->logon != 'guest') $extraroles[] = SSPACE_ROLE_USER;
		if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_ADMIN;
		if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_NETWORK;
		
		$extrastring = implode(',', $extraroles);
		
		$enrollments = getdbolist('ObjectEnrollment', "userid=$this->id or ".
			"(userid=0 and (".
			"roleid in (select roleid from UserEnrollment where userid=$this->id) or ".
			"roleid in ($extrastring)))");
		
		foreach($enrollments as $enrollment)
			$objectids[$enrollment->objectid] = $enrollment->roleid;
		
		$enrollments = getdbolist('CourseEnrollment', "userid=$this->id");
		foreach($enrollments as $enrollment)
		{
		//	debuglog($enrollment->objectid);
			$objectids[$enrollment->objectid] = $enrollment->roleid;

			$links = getdbolist('Object', "type=".CMDB_OBJECTTYPE_LINK.
				" and parentlist like '%, $enrollment->objectid, %'");

			foreach($links as $link)
				$objectids[$link->linkid] = SSPACE_ROLE_USER;

			$model = $enrollment->object->parent;
			while($model && $model->model)
			{
				$links = getdbolist('Object', "type=".CMDB_OBJECTTYPE_LINK.
					" and parentid='%, $model->id, %'");

				foreach($links as $link)
					$objectids[$link->linkid] = SSPACE_ROLE_USER;
				
				$model = $model->parent;
			}
		}
		
		$this->_objectids = $objectids;
	//	debuglog($objectids);
		return $objectids;
	}

	public function objectEnrollmentsExt2()
	{
		if($this->_objectids) return $this->_objectids;
		
	//	debuglog("User::objectEnrollmentsExt2()");
		$objectids = array();
		
		$extraroles = array(SSPACE_ROLE_ALL);
		if($this->logon != 'guest') $extraroles[] = SSPACE_ROLE_USER;
		if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_ADMIN;
		if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_NETWORK;
		
		$extrastring = implode(',', $extraroles);
		
		$enrollments = getdbolist('ObjectEnrollment', "userid=$this->id or ".
			"(userid=0 and (".
			"roleid in (select roleid from UserEnrollment where userid=$this->id) or ".
			"roleid in ($extrastring)))");
		
		foreach($enrollments as $enrollment)
			$objectids[$enrollment->objectid] = $enrollment->roleid;
		
		return $objectids;
	}

	////////////////////////////////////////////////////////////////////////////////
	
	public function objectRoles($object, $command=null)
	{
	//	debuglog("User::objectRoles($object->id, $object->name) $this->id");
		$course = getContextCourse();
		
		$inherit = true;
		if($command)
		{
			$c = RbacFindDefaultCommands($command->id);
			if(isset($c['inherit']) && !$c['inherit'])
				$inherit = false;
		}

		$parent = $object;
		$roles = array();
		
		$extraroles = array(SSPACE_ROLE_ALL);
		if($this->logon != 'guest') $extraroles[] = SSPACE_ROLE_USER;
	//	if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_ADMIN;
		if($this->logon == 'admin') $extraroles[] = SSPACE_ROLE_NETWORK;
		$extrastring = implode(',', $extraroles);
		
		while($this->id && $parent)
		{
		//	debuglog("User::objectRoles1($parent->id, $parent->name)");
			
			$es = getdbolist('ObjectEnrollment', "objectid=$parent->id and (".
				"userid=$this->id or (userid=0 and (".
				"roleid in (select roleid from UserEnrollment where userid=$this->id) or ".
				"roleid in ($extrastring))))");
			foreach($es as $e)
			{
			//	debuglog("adding role $e->roleid for ObjectEnrollment $parent->name");
				$roles[$e->roleid] = $e->roleid;
			}
			
			if($course && $parent->model && isCourseHasObject($course, $parent))
			{
				$e = getdbosql('CourseEnrollment', "objectid=$course->id and userid=$this->id");
				if($e)
				{
					if($e->courseid && !$inherit && $e->roleid == SSPACE_ROLE_TEACHER)
						$roles[SSPACE_ROLE_USER] = SSPACE_ROLE_USER;
					else
						$roles[$e->roleid] = $e->roleid;
				}
			}

		//	debuglog("objectid=$parent->id and userid=$this->id");
			$es = getdbolist('CourseEnrollment', "objectid=$parent->id and userid=$this->id");
			foreach($es as $e)
			{
			//	debuglog("adding role $e->roleid for CourseEnrollment $parent->name");
		
				if($e->courseid && !$inherit && $e->roleid == SSPACE_ROLE_TEACHER)
					$roles[SSPACE_ROLE_USER] = SSPACE_ROLE_USER;
				else
					$roles[$e->roleid] = $e->roleid;
			}
			
			if($roleid = $this->objectLinked($parent))
			{
				if($inherit)
					$roles[$roleid] = $roleid;
				else
					$roles[SSPACE_ROLE_USER] = SSPACE_ROLE_USER;
			}
			
			if($parent->post)
				$roles[SSPACE_ROLE_FORUM] = SSPACE_ROLE_FORUM;
			
			$parent = $parent->parent;
		}
		
		if($object->authorid == $this->id)
			$roles[SSPACE_ROLE_OWNER] = SSPACE_ROLE_OWNER;
	
		if($object->recordings || $object->parent->recordings)
		{
			unset($roles[SSPACE_ROLE_ALL]);
			unset($roles[SSPACE_ROLE_USER]);
			unset($roles[SSPACE_ROLE_STUDENT]);
		}

		return $roles;
	}
	
	public function objectLinked($object)
	{
	//	debuglog("User::objectLinked($object->id, {$object->parent->name}/$object->name)");

		if($object->type == CMDB_OBJECTTYPE_LINK &&	$roleid = $this->linkInherited($object))
			return $roleid;
		
		$parent = $object->parent;
		if($parent && $parent->type == CMDB_OBJECTTYPE_TEXTBOOK && $roleid = $this->linkInherited($parent))
			return $roleid;
		
		$links = getdbolist('Object', "linkid=$object->id and type=".CMDB_OBJECTTYPE_LINK);
		foreach($links as $link)
		{
			if($roleid = $this->linkInherited($link))
				return $roleid;
		}
		
		return 0;
	}
	
	public function linkInherited($object)
	{
	//	debuglog("User::linkInherited {$object->parent->name}\\$object->name");
		
		while($object && !$object->model && $object->type != CMDB_OBJECTTYPE_COURSE)
			$object = $object->parent;
		if(!$object) return 0;
		
		foreach($this->courseenrollments as $e)
		{
			$model = $e->object;

			if($model->id == $object->id) return $e->roleid;
			$model = $model->parent;

			while($model && $model->model)
			{
				if($model->id == $object->id) return $e->roleid;
				$model = $model->parent;
			}
		}

		return 0;
	}
	
	/////////////////////////////////////////////////////////////////////////
	
	public function getStatusOptions()
	{
		return array(
			CMDB_USERSTATUS_ONLINE=>'Online',
			CMDB_USERSTATUS_AWAY=>'Away',
			CMDB_USERSTATUS_BUSY=>'Busy',
			CMDB_USERSTATUS_OFFLINE=>'Offline',
		);
	}

	public function getStatusText()
	{
		$options = $this->statusOptions;
		return isset($options[$this->status])? $options[$this->status]: "none";
	}

	public function getStatusIcon()
	{
		switch($this->status)
		{
			case CMDB_USERSTATUS_ONLINE:
				return mainimg('online.gif');

			case CMDB_USERSTATUS_OFFLINE:
				return mainimg('offline.gif');

			case CMDB_USERSTATUS_AWAY:
				return mainimg('away.gif');
		}
	}

	//////////////////////

	public function getDomainOptions()
	{
		return CHtml::listData(getdbolist('Domain', ""), 'id', 'name');
	}

	public function getDomainText()
	{
		$options = $this->domainOptions;
		return isset($options[$this->domainid])? $options[$this->domainid]: "none";
	}

	//////////////////////

	public function getRoleOptions()
	{
		return CHtml::listData(getdbolist('Role', '1 order by id'), 'id', 'description');
	}

	public function getRoleText()
	{
		$string = '';
		foreach($this->userenrollments as $e)
		{
			$role = getdbo('Role', $e->roleid);
			$string .= "$role->name, ";
		}
		
		return $string;
	}
	
	public function getRoles()
	{
		$res = array();
		foreach($this->userenrollments as $e)
		{
			$role = getdbo('Role', $e->roleid);
			$res[$role->id] = $role->name;
		}
		
		return $res;
	}
	
	////////////////////////////////////
	
	public function getExempt()
	{
		if ($this->custom1 == 1)
			$exempt = "Exempt";
		else
			$exempt = "Not Exempt";
		return $exempt;
	}

	public function getEnrolled()
	{
		$semester = getCurrentSemester();
		$coursecount = 0;
		
		foreach($this->courseenrollments as $ce)
		{
			if($ce->course->type != CMDB_OBJECTTYPE_COURSE)
				if($ce->object->type != CMDB_OBJECTTYPE_QUIZ)
				if($ce->object->type != CMDB_OBJECTTYPE_LESSON)
				if($ce->object->type != CMDB_OBJECTTYPE_SURVEY)
				$coursecount++;
		}
		
		if ($coursecount > 0) {
		$coursecount = "Enrolled ($coursecount)";
		}
		
		else
		$coursecount = "Not Enrolled";

		return $coursecount;
	}

	public function getFirstname()
	{
		$a = explode(' ', $this->name);
		return isset($a[0])? $a[0]: '';
	}
	

	public function getLastname()
	{
		$a = explode(' ', $this->name);
		unset($a[0]);
		$a = implode(" ", $a);
		return($a);
	}
 
	public function getSecondname()
	{
		$a = explode(' ', $this->name);
		return isset($a[1])? $a[1]: '';
	}
	
	public function getThirdname()
	{
		$a = explode(' ', $this->name);
		return isset($a[2])? $a[2]: '';
	}

	public function getFourthname()
	{
		$a = explode(' ', $this->name);
		return isset($a[3])? $a[3]: '';
	}

	public function getFifthname()
	{
		$a = explode(' ', $this->name);
		return isset($a[4])? $a[4]: '';
	}

	public function getSixthname()
	{
		$a = explode(' ', $this->name);
		return isset($a[5])? $a[5]: '';
	}
		
	public function getCreatedint()
	{
		$a = explode(' ', $this->created);
		return isset($a[0])? $a[0]: '';
	}
	
	public function getLastlogint()
	{
		$a = explode(' ', $this->accessed);
		return isset($a[0])? $a[0]: '';
	}

	
	public function getLastlogyearint()
	{
		$a = explode('-', $this->lastlogint);
		return isset($a[0])? $a[0]: '';
	}

	public function getLastlogmonthint()
	{
		$a = explode('-', $this->lastlogint);
		return isset($a[1])? $a[1]: '';
	}

	public function getLastlogdayint()
	{
		$a = explode('-', $this->lastlogint);
		return isset($a[2])? $a[2]: '';
	}

	public function getNowint()
	{
		$a = explode(' ', now());
		return isset($a[0])? $a[0]: '';
	}
	
		public function getNowyearint()
	{
		$a = explode('-', $this->nowint);
		return isset($a[0])? $a[0]: '';
	}

	public function getNowmonthint()
	{
		$a = explode('-', $this->nowint);
		return isset($a[1])? $a[1]: '';
	}

	public function getNowdayint()
	{
		$a = explode('-', $this->nowint);
		return isset($a[2])? $a[2]: '';
	}
	
}





