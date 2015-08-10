<?php

define('SSPACE_ROLE_NETWORK', 1);
define('SSPACE_ROLE_ADMIN', 2);
define('SSPACE_ROLE_CONTENT', 3);
define('SSPACE_ROLE_TEACHER', 4);
define('SSPACE_ROLE_STUDENT', 5);
define('SSPACE_ROLE_USER', 6);
define('SSPACE_ROLE_ALL', 7);

//define('SSPACE_ROLE_PRESENTER', 8);

function RbacDefaultRoles()
{
	$roles = array(

		SSPACE_ROLE_NETWORK=>array(
			'id'=>SSPACE_ROLE_NETWORK,
			'name'=>'network',
			'description'=>'Network User',
			'type'=>'',
		),

		SSPACE_ROLE_ADMIN=>array(
			'id'=>SSPACE_ROLE_ADMIN,
			'name'=>'admin',
			'description'=>'Admin User',
			'type'=>'user',
		),

		SSPACE_ROLE_CONTENT=>array(
			'id'=>SSPACE_ROLE_CONTENT,
			'name'=>'content',
			'description'=>'Content Manager',
			'type'=>'user, object, course',
		),

// 		SSPACE_ROLE_PRESENTER=>array(
// 			'id'=>SSPACE_ROLE_PRESENTER,
// 			'name'=>'presenter',
// 			'description'=>'Presenter',
// 			'type'=>'user, object, course',
// 		),

		SSPACE_ROLE_TEACHER=>array(
			'id'=>SSPACE_ROLE_TEACHER,
			'name'=>'teacher',
			'description'=>'Teacher',
			'type'=>'user, object, course',
		),

		SSPACE_ROLE_STUDENT=>array(
			'id'=>SSPACE_ROLE_STUDENT,
			'name'=>'student',
			'description'=>'Student',
			'type'=>'user, course',
		),

		SSPACE_ROLE_USER=>array(
			'id'=>SSPACE_ROLE_USER,
			'name'=>'user',
			'description'=>'Registered User',
			'type'=>'user, object',
		),

		SSPACE_ROLE_ALL=>array(
			'id'=>SSPACE_ROLE_ALL,
			'name'=>'all',
			'description'=>'Everyone',
			'type'=>'user, object',
		),

	);
	
	return $roles;
}




