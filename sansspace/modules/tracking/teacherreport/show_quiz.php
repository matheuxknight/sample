<?php

function showitem($item, $bold=false)
{
	$item = round($item, 2);
	if($bold)
		echo "<td><b>$item</b></td>";
	else
		echo "<td>$item</td>";
}

$object = getdbo('Object', getparam('id'));
if(!$object) return;

$quiz = getdbosql('Quiz', "quizid=".getparam('id'));
if(!$quiz) return;

$this->pageTitle = app()->name ." - ". $object->name;

showNavigationBar($object->parent);
showObjectHeader($object);
showObjectMenu($object);

$questioncount = getdbocount('QuizQuestionEnrollment', "quizid=$quiz->quizid");
$penaltiesapplied = $quiz->applypenalties? 'Yes': 'No';
$timelimit = $quiz->timelimit? $quiz->timelimit: 'None';
$allowedattempt = $quiz->allowedattempt? $quiz->allowedattempt: 'None';

echo "<table cellspacing=4>";
echo "<tr><td>Number of Questions </td><td><b>$questioncount</b></td></tr>";
echo "<tr><td>Pass Threshold </td><td><b>$quiz->passthreshold</b></td></tr>";
echo "<tr><td>Penalties Applied </td><td><b>$penaltiesapplied</b></td></tr>";
echo "<tr><td>Time Limit </td><td><b>$timelimit</b></td></tr>";
echo "<tr><td>Attempt Limit </td><td><b>$allowedattempt</b></td></tr>";
echo "<tr><td>Grading Method </td><td><b>$quiz->gradingMethodText</b></td></tr>";
echo "</table><br>";

////////////////////////////////////////////////////////////////////////////////////////////

$courseid = user()->getState('courseid');
if($courseid)
	$extracourse = "and courseid=$courseid";
else
	$extracourse = '';
	
showTableSorter('maintable', '{headers: {0: {sorter: false}, 8: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>User</th>";
echo "<th>Started</th>";
echo "<th>Evaluation</th>";
echo "<th>Completed</th>";
echo "<th>First</th>";
echo "<th>Last</th>";
echo "<th>Average</th>";
echo "<th>Highest</th>";
echo "<th>Result</th>";
echo "</tr></thead><tbody>";

$totaluser = 0;
$totalattempt = 0;
$totalstarted = 0;
$totalcompleted = 0;

$totalfirst = 0;
$totallast = 0;
$totalavg = 0;
$totalhighest = 0;

$list = getdbolist('CourseEnrollment', "objectid=$object->id $extracourse");
foreach($list as $model)
{
	$user = $model->user;
	
	$attempts = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and status!=".CMDB_QUIZATTEMPT_STARTED." and status!=".CMDB_QUIZATTEMPT_COMPLETED);
	$started = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and status=".CMDB_QUIZATTEMPT_STARTED);
	$completed = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and status=".CMDB_QUIZATTEMPT_COMPLETED);
	
	$totalattempt += $attempts;
	$totalstarted += $started;
	$totalcompleted += $completed;
	
	$first = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and result is not null order by started");
	$last = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and result is not null order by started desc");
	$highest = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id $extracourse and result is not null order by result desc");
	$avg = dboscalar("select avg(result) from QuizAttempt where quizid=$quiz->quizid $extracourse and userid=$user->id and result is not null");

	if($first)
	{
		$totaluser++;
		$totalfirst += $first->result;
		$totallast += $last->result;
		$totalavg += $avg;
		$totalhighest += $highest->result;
	}
	else
		$avg = '';
	
	echo "<tr class='ssrow'>";
	echo "<td>".userImage($user, 18)."</td>";

	echo "<td style='font-weight: bold;'>";
	echo l($user->name, array('studentreport/', 'id'=>$object->id, 'userid'=>$user->id, 'courseid'=>$courseid));
	echo "</td>";

	echo "<td>$started</td>";
	echo "<td>$completed</td>";
	echo "<td>$attempts</td>";
	
	$result = -1;
	switch($quiz->gradingmethod)
	{
		case CMDB_QUIZGRADING_AVG:
			$result = $avg;
			showitem($first->result);
			showitem($last->result);
			showitem($avg, true);
			showitem($highest->result);
			break;
		case CMDB_QUIZGRADING_FIRST:
			$result = $first->result;
			showitem($first->result, true);
			showitem($last->result);
			showitem($avg, true);
			showitem($highest->result);
			break;
		case CMDB_QUIZGRADING_LAST:
			$result = $last->result;
			showitem($first->result);
			showitem($last->result, true);
			showitem($avg);
			showitem($highest->result);
			break;
		case CMDB_QUIZGRADING_HIGH:
		default:
			$result = $highest->result;
			showitem($first->result);
			showitem($last->result);
			showitem($avg);
			showitem($highest->result, true);
			break;
	}
	
	if(!$first)
		echo "<td></td>";
	else if($result >= $quiz->passthreshold)
		echo "<td>Pass</td>";
	else
		echo "<td>Fail</td>";
		
	echo "</tr>";
}

$count = count($list);
$avgfirst = $totaluser? round($totalfirst / $totaluser, 2): 0;
$avglast = $totaluser? round($totallast / $totaluser, 2): 0;
$avgavg = $totaluser? round($totalavg / $totaluser, 2): 0;
$avghighest = $totaluser? round($totalhighest / $totaluser, 2): 0;

echo "</tbody>";
echo "<tr class='ssrow'>";
echo "<th></th>";
echo "<th><b>$count Users</b></th>";

echo "<th>$totalstarted</th>";
echo "<th>$totalcompleted</th>";
echo "<th>$totalattempt</th>";

echo "<th>$avgfirst</th>";
echo "<th>$avglast</th>";
echo "<th>$avgavg</th>";
echo "<th>$avghighest</th>";
echo "<th></th>";

echo "</tr>";
echo "</table>";



