<?php

function showitem($item, $bold=false)
{
	$item = round($item, 2);
	if($bold)
		echo "<td><b>$item</b></td>";
	else
		echo "<td>$item</td>";
}

$this->pageTitle = app()->name ." - ". $object->name;

$isteacher = false;
if(controller()->rbac->objectUrl($object, 'teacherreport'))
	$isteacher = true;

showNavigationBar($object->parent);
showObjectHeader($object);
showObjectMenu($object);

if($isteacher)
	showUserHeader($user, $user->name, "/studentreport?id=$object->id&userid=$user->id");

InitMenuTabs('#properties-tabs');
echo "<div id='properties-tabs' style='display:none;'><ul>";
echo "<li><a href='#tabs-1'>Quizzes</a></li>";
echo "<li><a href='#tabs-2'>Surveys</a></li>";
echo "<li><a href='#tabs-3'>Recordings</a></li>";
echo "</ul><br>";

echo "<div id='tabs-1'>";

showTableSorter('maintable', '{headers: {0: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>Quiz</th>";
echo "<th>Started</th>";
echo "<th>Evaluation</th>";
echo "<th>Completed</th>";
echo "<th>First</th>";
echo "<th>Last</th>";
echo "<th>Average</th>";
echo "<th>Highest</th>";
echo "<th>Result</th>";
echo "</tr></thead><tbody>";

$totalattempt = 0;
$totalstarted = 0;
$totalcompleted = 0;

$totalfirst = 0;
$totallast = 0;
$totalavg = 0;
$totalhighest = 0;

$list = getdbolist('Quiz', "quizid in (select quizid from QuizAttempt where userid=$user->id and courseid=$object->id)");
foreach($list as $quiz)
{
 	$quizobject = $quiz->object;
	
	$attempts = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and status!=".CMDB_QUIZATTEMPT_STARTED." and status!=".CMDB_QUIZATTEMPT_COMPLETED);
	$started = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and status=".CMDB_QUIZATTEMPT_STARTED);
	$completed = getdbocount('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and status=".CMDB_QUIZATTEMPT_COMPLETED);
	
	$totalattempt += $attempts;
	$totalstarted += $started;
	$totalcompleted += $completed;
	
	$first = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and result is not null order by started");
	$last = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and result is not null order by started desc");
	$highest = getdbosql('QuizAttempt', "quizid=$quiz->quizid and userid=$user->id and result is not null order by result desc");
	$avg = dboscalar("select avg(result) from QuizAttempt where quizid=$quiz->quizid and userid=$user->id and result is not null");
	
	if($first)
	{
		$totalfirst += $first->result;
		$totallast += $last->result;
		$totalavg += $avg;
		$totalhighest += $highest->result;
	}
	else
		$avg = '';
	
 	echo "<tr class='ssrow'>";
 	echo "<td>".objectImage($quizobject, 18)."</td>";
	
 	echo "<td style='font-weight: bold;'>";
 	echo l($quizobject->name, array('studentreport/', 'id'=>$quizobject->id, 'userid'=>$user->id, 'courseid'=>$courseid));
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

echo "</table>";
echo "</div>";

///////////////////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-2'>";

showTableSorter('maintable', '{headers: {0: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>Survey</th>";
echo "<th>Questions</th>";
echo "</tr></thead><tbody>";

$list = getdbolist('Object', "id in (select objectid from Survey where id in (select surveyid from SurveyAnswer where userid=$user->id))");
foreach($list as $folder)
{
	$answerred = getdbocount('SurveyAnswer', "userid=$user->id and courseid=$object->id");
	
	echo "<tr class='ssrow'>";
 	echo "<td>".objectImage($folder, 18)."</td>";
 	echo "<td style='font-weight: bold;'>";
 	echo l($folder->name, array('studentreport/', 'id'=>$folder->id, 'userid'=>$user->id, 'courseid'=>$object->id));
 	echo "</td>";
 		
	echo "<td>$answerred</td>";
	
 	echo "</tr>";
}

echo "</table>";
echo "</div>";

///////////////////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-3'>";
showTableSorter('maintable', '{headers: {0: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>File</th>";
echo "<th>Activity</th>";
echo "<th>Duration</th>";
echo "<th>Created</th>";
echo "</tr></thead><tbody>";

$folder = userRecordingFolder($object, $user);
$list = getdbolist('VFile', "authorid=$user->id and parentlist like '%, $folder->id, %'");
foreach($list as $file)
{
	$parent = $file->parent;
	
	echo "<tr class='ssrow'>";
	echo '<td width=24>'.objectImage($file, 18).'</td>';

	echo '<td style="font-weight: bold;">';
	showObjectMenuContext($file);
	echo '</td>';
	
	echo '<td>';
	echo l($parent->name, array('object/', 'id'=>$parent->id));
	echo '</td>';
		
	echo "<td nowrap>".sectoa($file->duration/1000)."</td>";
	echo '<td nowrap>'.datetoa($file->created).'</td>';
	
	echo "</tr>";
}

echo "</table>";
echo "</div>";








