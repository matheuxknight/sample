<?php

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Grades">
    <p style='font-size:20px' autofocus>Teachers:<br>
	<span style='font-size:16px'>Review all of your students&#8217; work in Grades. Locate and assess completed essays and open-ended tasks. Provide both voice and written feedback on all of your students&#8217; recordings.<br>Assess each student&#8217;s performance by evaluating time spent working with audios and videos and attempts made on each quiz. Automatically graded activities, such as multiple choice, true or false, matching, and cloze, are recorded here as well.</span></p>
	<p style='font-size:20px'>Students:<br>
	<span style='font-size:16px'>Your students can view all of their graded or assessed activities and listen to or read your feedback</span></p>
	<p style='font-size:14px'>Click on the <b><u>course name</b></u> to view.</p>
</div>
end;

$this->pageTitle = "My Reports ". Yii::app()->name;
echo "<h2>Grades    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a></h2>";

$user = getUser();
showTableSorter('maintable', '{headers: {1: {sorter: false}, 2: {sorter: false}, 0: {sorter: false}}}');

echo "<thead class='ui-widget-header'><tr>";
echo "<th width=20></th>";
echo "<th>Course</th>";
//echo "<th>Semester</th>";
//echo "<th>File Views</th>";
//echo "<th>Play Time</th>";
//echo "<th>Record Time</th>";
//echo "<th>Status</th>";
//echo "<th>Grade</th>";
echo "<th></th>";
echo "</tr></thead><tbody>";

//$totalplay = 0;
//$totalrecord = 0;

foreach($user->courseenrollments as $enrollment)
{
	if($enrollment->object->type != CMDB_OBJECTTYPE_COURSE) continue;
	$course = $enrollment->object->course;
	

	//$playtime = getPlayTime($course->semester, $course, $user);
	//$recordtime = getRecordTime($course, $user);
	
	//$totalplay = $playtime;
	//$totalrecord = $recordtime;
	
	echo "<tr class='ssrow'>";
	echo '<td>'.objectImage($course, 24).'</td>';
	
	echo "<td style='font-weight: bold;'>";
	if(controller()->rbac->globalTeacher())
	{
		echo "<a href='/teacherreport?id=$course->id'>$course->name</a>";
	}
	else
	{
		echo "<a href='/studentreport?id=$course->id'>$course->name</a>";
	}
	echo "</td>";
		
	//if($course->semester)
		//echo "<td>{$course->semester->name}</td>";
	//else
		//echo "<td></td>";
		
	//echo "<td><b>".l(sectoa($playtime), array('studentreport/', 'id'=>$course->id))."</b></td>";
	
	//$folder = userRecordingFolder($course);
	//echo "<td><b>".l(sectoa($recordtime), array('object/show', 'id'=>$folder->id))."</b></td>";
	
//	echo "<td>$enrollment->statusText</td>";
	//echo "<td>$enrollment->grade</td>";
	
	echo "<td>";
	$teacher = getdbosql('Role', "name='teacher'");
	if(controller()->rbac->objectAction($course, 'update'))
		echo "".l("$teacher->description Report", array('teacherreport/', 'id'=>$course->id));
	
	echo "</td>";
	echo "</tr>";
}

echo "</tbody><tr>";
//echo "<th></th>";
//echo "<th>Total:</th>";
//echo "<th></th>";
//echo "<th>".sectoa($totalplay)."</th>";
//echo "<th>".sectoa($totalrecord)."</th>";
//echo "<th></th>";
//echo "<th></th>";
//echo "<th></th>";
//echo "</tr>";

echo "</table>";
echo "<br/>";






