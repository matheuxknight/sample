<?php

echo "<h2>Enroll in my course    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a></h2>";
echo "<p style='color:#555555'>Enter your student access code and click Continue.<br>
You will find your student access code in an insert or on the inside front cover of your textbook.";

$this->widget('UniForm');

echo CUFHtml::beginForm();
echo CUFHtml::errorSummary($code);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

if($code->id)
{
	echo l('Enter Another Code', array('addstudentcode')).'<br><br>';
	echo CUFHtml::activeHiddenField($code, 'id');
	
	echo CUFHtml::openActiveCtrlHolder($code, 'code');
	echo CUFHtml::activeLabelEx($code, 'code');
	echo CUFHtml::activeTextField($code, 'code', array('readonly'=>true));
	echo "<p class='formHint2'></p>";
	echo CUFHtml::closeCtrlHolder();

	echo CUFHtml::openActiveCtrlHolder($code, 'status');
	echo CUFHtml::label('Status', 'status');
	echo CUFHtml::textField('status', $code->statusText, array('readonly'=>true));
	echo "<p class='formHint2'></p>";
	echo CUFHtml::closeCtrlHolder();

	if(controller()->rbac->globalAdmin() || $code->user->id == userid())
	{
		if(!empty($code->started))
		{
			echo CUFHtml::openCtrlHolder();
			echo CUFHtml::label('Activated', 'activated');
			echo CUFHtml::textField('activated', $code->started, array('readonly'=>true));
			echo "<p class='formHint2'></p>";
			echo CUFHtml::closeCtrlHolder();
		}
		
		if($code->user)
		{
			echo CUFHtml::openCtrlHolder();
			echo CUFHtml::label('User', 'user');
			echo CUFHtml::textField('user', $code->user->name, array('readonly'=>true));
			echo "<p class='formHint2'></p>";
			echo CUFHtml::closeCtrlHolder();
		}
		
		if($code->course)
		{
			echo CUFHtml::openCtrlHolder();
			echo CUFHtml::label('Course', 'course');
			echo CUFHtml::textField('course', $code->course->name, array('readonly'=>true));
			echo "<p class='formHint2'></p>";
			echo CUFHtml::closeCtrlHolder();
		}
	}
}
else
{
	echo CUFHtml::openActiveCtrlHolder($code, 'code');
	echo CUFHtml::activeLabelEx($code, 'code');
	echo CUFHtml::activeTextField($code, 'code', array('maxlength'=>32, 'value'=>'1234-1234-1234-1234', 'readonly'=>true));
//	echo "<p class='formHint2'>Type your student code using the following form XXXX-XXXX-XXXX-XXXX.</p>";
	echo "<p class='formHint2'></p>";
	echo CUFHtml::closeCtrlHolder();
}

echo CUFHtml::closeTag('fieldset');

//if($code->id && $code->status == CMDB_USERCODE_UNUSED)
if(controller()->rbac->globalAdmin())
	{showSubmitButton('Continue');}
else if(!$code->id)
	{showSubmitButton('Continue');}

echo CUFHtml::endForm();

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Enroll in my course">
    <p style='font-size:20px' autofocus>Your students must enter a valid Student Access Code to continue past this page.<br>
	<span style='font-size:16px'>Student Access Codes can be found on the inside front cover of student textbooks.<br>New codes can be purchased at <a href='http://www.waysidepublishing.com' target='_blank'>waysidepublishing.com</a>.</p>
    <p style='font-size:16px'>Click <b><u>Continue</u></b>.</p>
</div>
end;





