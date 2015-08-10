<?php

$this->widget('UniForm');

echo CUFHtml::beginForm('', 'post', array('enctype'=>'multipart/form-data'));
echo CUFHtml::errorSummary($course);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

echo CUFHtml::openActiveCtrlHolder($course, 'name');
echo CUFHtml::activeLabelEx($course, 'Course Name');
echo CUFHtml::activeTextField($course, 'name', array('maxlength'=>55, 'value'=>''));
echo "<p class='formHint2'>Create a unique name for your course.<br>Consider your naming convention if you plan to create more than one course. </p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($course, 'startdate');
echo CUFHtml::activeLabelEx($course, 'startdate');

if(!$update || time() < strtotime($course->startdate))
	showDatetimePicker($course, 'startdate');

else
	echo CUFHtml::activeTextField($course, 'enddate', array('readonly'=>true));
echo "<p class='formHint2'>Your students will not be able to enroll within your course until this start date. Courses will be active for one year from this date.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($course, 'enddate');
echo CUFHtml::activeLabelEx($course, 'enddate');
echo CUFHtml::activeTextField($course, 'enddate', array('readonly'=>true));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::closeTag('fieldset');
echo "<a href='/course/createdteacher'><input role='button' style='width:110px; height:40px; margin-left:20px' type='button' class='submitButton ui-button ui-widget ui-state-default ui-corner-all' value='Create' ></input></a>      <a href='#' id='popuplink'><em style='color:#ec4546; verticle-align:middle' class='fa fa-question-circle'></em></a>";
echo CUFHtml::endForm();

echo <<<end
<script>

$(function()
{
	$('#VCourse_startdate').change(function()
	{
		var d1 = this.value.match(/^(\d{4})\-/);
		var d2 = parseInt(d1[1]);
		
		var s = this.value.replace(/^(\d{4})\-/, d2+1+'-');
		$('#VCourse_enddate').val(s);
	});
});

</script>
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="About this course">
    <p style='font-size:20px' autofocus>You will give each one of your courses a unique name on this page.<br>
	<span style='font-size:16px'>If you are creating multiple courses for the same textbook, consider using a simple naming convention.  The name may consist of several words.<br>For example: Wayside High - Mrs. Johnson&#8217;s 1st Period AP Spanish</span></p>
    <p style='font-size:20px'>You must also choose a start date for your course.<br>
	<span style='font-size:16px'>The start date is the first day that your students will be able to enroll in your course. Picking a start date before the beginning of the school year will allow you to review the Learning Site before it is available to your students. After the start date you choose arrives, you will no longer be able to alter it. The end date is automatically one year from the start date.</span></p>
</div>
end;








