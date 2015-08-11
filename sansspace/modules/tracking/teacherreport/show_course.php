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
echo "<th>Needs Grading</th>";
echo "<th>Results</th>";
echo "<th>Surveys</th>";
echo "<th>Recordings</th>";
echo "<th>Role</th>";
echo "<th></th>";
echo "</tr></thead><tbody>";

$total_quizzes = 0;
$total_evalutations = 0;
$total_surveys = 0;
$total_recordings = 0;
$total_results = 0;
$total_users = 0;

$list = getdbolist('CourseEnrollment', "objectid=$object->id");
foreach($list as $model)
{
	$user = $model->user;
	
	$quizzes = getdbocount('Quiz', "quizid in (select quizid from QuizAttempt where userid=$user->id and courseid=$object->id)");
	$evaluations =  getdbocount('Quiz', "quizid in (select quizid from QuizAttempt where userid=$user->id and courseid=$object->id and 
			status!=".CMDB_QUIZATTEMPT_STARTED." and 
			status!=".CMDB_QUIZATTEMPT_FAILED." and 
			status!=".CMDB_QUIZATTEMPT_PASSED.")");
	$surveys = getdbocount('SurveyAnswer', "userid=$user->id and courseid=$object->id");
	$folder = userRecordingFolder($object, $user);
	$recordings = getdbocount('VFile', "authorid=$user->id and parentlist like '%, $folder->id, %'");

	$result = 0;
	$list2 = getdbolist('Quiz', "quizid in (select quizid from QuizAttempt where userid=$user->id and courseid=$object->id and 
			status!=".CMDB_QUIZATTEMPT_STARTED." and status!=".CMDB_QUIZATTEMPT_COMPLETED.")");
	foreach($list2 as $quiz)
	{
	//	debuglog("quiz $quiz->quizid");
		switch($quiz->gradingmethod)
		{
			case CMDB_QUIZGRADING_AVG:
				$result += dboscalar("select avg(result) from QuizAttempt where quizid=$quiz->quizid and userid=$user->id and result is not null");
				break;
			case CMDB_QUIZGRADING_FIRST:
				$result += dboscalar("select result from QuizAttempt where quizid=$quiz->quizid and userid=$user->id and result is not null order by 

started");
				break;
			case CMDB_QUIZGRADING_LAST:
				$result += dboscalar("select result from QuizAttempt where quizid=$quiz->quizid and userid=$user->id and result is not null order by started 

desc");
				break;
			case CMDB_QUIZGRADING_HIGH:
				$result += dboscalar("select result from QuizAttempt where quizid=$quiz->quizid and userid=$user->id and result is not null order by result 

desc");
				break;
		}
	}
	
	if(count($list2))
	{
		$total_users++;
		$results = round($result/count($list2), 2);
		$total_results += $results;
		$results = "$results %";
		$total_evalutations += $evaluations;
	}
	else
		$results = '';
	
	echo "<tr class='ssrow'>";
	echo "<td>".userImage($user, 18)."</td>";
	
	echo "<td style='font-weight: bold;'>";
	echo l($user->name, array('studentreport/', 'id'=>$object->id, 'userid'=>$user->id));
	echo "</td>";
	
	echo "<td>$quizzes</td>";
	echo "<td><b>$evaluations</b></td>";
	echo "<td><b>$results</b></td>";
	echo "<td>$surveys</td>";
	echo "<td>$recordings</td>";
	
	echo "<td>{$model->role->description}</td>";
	echo "<td>";

	if(controller()->rbac->globalAdmin())
	{
		echo l(mainimg('16x16_delete.png'), '#', array('id'=>"delete_enrollment_{$model->id}"));
		echo <<<END
<script>$(function(){ $('#delete_enrollment_{$model->id}').click(function(){
	if(confirm('Are you sure you want to unenroll this user {$object->name}?'))
		jQuery.yii.submitForm(this, '/enroll/deletecourse?id=$model->id',{});
	return false;});});</script>
END;
	}
	
	echo "</td>";
	echo "</tr>";

	$total_quizzes += $quizzes;
	$total_surveys += $surveys;
	$total_recordings += $recordings;
}

echo "</tbody>";

$count = count($list);
$avg = $total_users? round($total_results/$total_users, 2).' %': '';

echo "<tr class='ssrow'>";
echo "<td></td>";
echo "<td>$count Users</td>";
echo "<td>$total_quizzes</td>";
echo "<td><b>$total_evalutations</b></td>";
echo "<td>$avg</td>";
echo "<td>$total_surveys</td>";
echo "<td>$total_recordings</td>";
echo "<td></td>";
echo "<td></td>";

echo "</tr>";
echo "</table>";







