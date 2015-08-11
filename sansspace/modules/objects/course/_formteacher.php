<?php

echo <<<end
<script>

	$(document).ready(function() 
{
	$('#create').dialog({ autoOpen: true, modal: true, width: '620', dialogClass:'modalpopup' })
})

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

$(document).ready(function(){
$(".ui-button-icon-only").hide();
});

$(document).ready(function(){
  $("#enter").click(function(){
    $(".ui-dialog").hide();
	$(".ui-widget-overlay").hide();
  });
});

$(document).ready(function(){
  $("button").hover(function(){
	$( this ).removeClass( "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" );
	$( this ).addClass( "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" );
	},function() {
    $( this ).removeClass( "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover" );
	$( this ).addClass( "ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" );
     }
	);
});

</script>
end;

$this->widget('UniForm');

echo CUFHtml::beginForm('', 'post', array('enctype'=>'multipart/form-data'));
echo CUFHtml::errorSummary($course);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

echo CUFHtml::openActiveCtrlHolder($course, 'name');
echo CUFHtml::activeLabelEx($course, 'name');
echo CUFHtml::activeTextField($course, 'name', array('maxlength'=>55));
echo "<p class='formHint2'>Create a unique name for your course.<br>Consider your naming convention if you plan to create more than one course. </p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($course, 'startdate');
echo CUFHtml::activeLabelEx($course, 'startdate');

if(!$update || time() < strtotime($course->startdate))
	showDatetimePicker($course, 'startdate');
else
	echo CUFHtml::activeTextField($course, 'startdate', array('readonly'=>true));

echo "<p class='formHint2'>Your students will not be able to enroll within your course until this start date. Courses will be active for one year from this date.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($course, 'enddate');
echo CUFHtml::activeLabelEx($course, 'enddate');
echo CUFHtml::activeTextField($course, 'enddate', array('readonly'=>true));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::closeTag('fieldset');
showSubmitButton($update? 'Save': 'Create');

echo "<div id='create' title='Learning Site Access for Teachers'>
		<p class='error' style='font-size:18px; padding:0px' autofocus>Notice!</p>
		<p style='font-size:14px;'>Wayside Publishing provides you with free access to the resources in our online Learning Site as long as your students enroll in your course. Free access is dependent on student enrollment.<br><br>
		If no students are enrolled in your course after 30 days, your course will be removed.<br><br>
		By choosing \"Agree\" you are agreeing to these terms.</p>
		<div class='ui-dialog-buttonpane ui-widget-content ui-helper-clearfix'>
		<div class='ui-dialog-buttonset'>
		<a href='/my'><button id='cancel' type='button' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' value='Cancel' role='button' aria-disabled='false'><span class='ui-button-text'>Cancel</span></button></a><button id='enter' type='button' class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' role='button' aria-disabled='false'><span class='ui-button-text'>Agree</span></button>
		</div></div>";

echo CUFHtml::endForm();