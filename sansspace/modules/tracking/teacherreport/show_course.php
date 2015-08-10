<?php

$this->pageTitle = app()->name ." - ". $object->name;

showNavigationBar($object->parent);
showObjectHeader($object);
showObjectMenu($object);

echo "<h2>Grades</h2>";

showTableSorter('maintable', '{headers: {0: {sorter: false}, 7: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>User</th>";
echo "<th>Quizzes</th>";
echo "<th>Surveys</th>";
echo "<th>Recordings</th>";
echo "<th>Grade</th>";
echo "<th>Role</th>";
echo "<th></th>";
echo "</tr></thead><tbody>";

$total_quizzes = 0;
$total_surveys = 0;
$total_recordings = 0;

$list = getdbolist('CourseEnrollment', "objectid=$object->id");
foreach($list as $model)
{
	$user = $model->user;
	
	$quizzes = getdbocount('QuizAttempt', "userid=$user->id and courseid=$object->id");
	$surveys = getdbocount('SurveyAnswer', "userid=$user->id and courseid=$object->id");
	$folder = userRecordingFolder($object, $user);
	$recordings = getdbocount('VFile', "authorid=$user->id and parentlist like '%, $folder->id, %'");
	
	echo "<tr class='ssrow'>";
	echo "<td>".userImage($user, 18)."</td>";
	
	echo "<td style='font-weight: bold;'>";
	echo l($user->name, array('studentreport/', 'id'=>$object->id, 'userid'=>$user->id));
	echo "</td>";
	
	echo "<td>$quizzes</td>";
	echo "<td>$surveys</td>";
	echo "<td>$recordings</td>";
	
	echo "<td>{$model->grade}</td>";

	echo "<td>{$model->role->description}</td>";
	echo "<td>";

	//echo l(mainimg('16x16_delete.png'), '#', array('id'=>"delete_enrollment_{$model->id}"));
	
	//echo <<<END
//<script>$(function(){ $('#delete_enrollment_{$model->id}').click(function(){
	//if(confirm('Are you sure you want to unenroll this user {$object->name}?'))
		//jQuery.yii.submitForm(this, '/enroll/deletecourse?id=$model->id',{});
	//return false;});});</script>
//END;
	
	echo "</td>";
	echo "</tr>";

	$total_quizzes += $quizzes;
	$total_surveys += $surveys;
	$total_recordings += $recordings;
}

echo "</tbody>";

echo "<tr class='ssrow'>";
$count = count($list);

echo "<td></td>";
echo "<td>$count Users</td>";
echo "<td>$total_quizzes</td>";
echo "<td>$total_surveys</td>";
echo "<td>$total_recordings</td>";
	
echo "</tr>";
echo "</table>";







