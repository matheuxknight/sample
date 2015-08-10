<?php

$this->pageTitle = app()->name ." - ". $course->name;
user()->setState('courseid', $course->id);

showRoleBar($course);
showNavigationBar($course->parent);
showObjectHeader($course);
showObjectMenu($course->object);

$teachername = $course->getTeacherName();
$teacherrole = getdbosql('Role', "name='teacher'");

echo "<table>";

if(!empty($teachername))
	echo "<br><tr><td style='font-size:18px; line-height:1em;'>$teacherrole->description:</td><td style='font-size:18px; line-height:1em;'>&nbsp;<b> $teachername</b></td></tr>";

if($course->semester)
{
	echo "<tr><td>Semester:</td><td><b>{$course->semester->name}</b></td></tr>";
	echo "<tr><td>Starts:</td><td><b>{$course->semester->starttime}</b></td></tr>";
	echo "<tr><td>Ends:</td><td><b>{$course->semester->endtime}</b></td></tr>";
}

// else if($course->usedate)
// {
// 	echo "<tr><td>Starts:</td><td><b>$course->startdate</b></td></tr>";
// 	echo "<tr><td>Ends:</td><td><b>$course->enddate</b></td></tr>";
// }

echo "</table>";

echo processDoctext($course, $course->ext->doctext);
showFolderContents($course->id);

// if($course->usedate)
// {
// 	echo "<div id='toto'></div>";
// 	echo "<script type='text/javascript'>
// 			$('#toto').DatePicker({
// 				readOnly: true,
// 				flat: true,
// 				calendars: 3,
// 				mode: 'range',
// 				starts: 0,
// 				date: ['{$course->startdate}','{$course->enddate}'],
// 				current: '".nowDate(60*60*24*29)."'
// 			});
// 			</script>";
// 	echo "<br><br>";
// }

showObjectFooter($course);
showPreviousNext($course);
showObjectComments($course);

user()->setState('currentobject', $course->id);
user()->setState('currentversion', $course->version);

JavascriptReady("window.onbeforeunload = function(){
	$.ajax({url: '/object/leavepage?id=$course->id', async: false});}");







