<?php

function courseShowPropertiesAdvanced($course, $update)
{
	echo "<div id='properties-advanced'>";
	
	echo CUFHtml::openActiveCtrlHolder($course, 'hidden');
	echo CUFHtml::activeLabelEx($course, 'hidden');
	echo CUFHtml::activeCheckBox($course, 'hidden', array('class'=>'miscInput'));
	$teacher = getdbosql('Role', "name='teacher'");
	echo "<p class='formHint2'>This item will be visible only to $teacher->description and admins.
	Users still have the permission to use the file according to their role.</p>";
	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openActiveCtrlHolder($course, 'model');
	echo CUFHtml::activeLabelEx($course, 'model');
	echo CUFHtml::activeCheckBox($course, 'model', array('class'=>'miscInput'));
	echo "<p class='formHint2'>If checked, this course will inherit the links from its parent.</p>";
	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openActiveCtrlHolder($course->ext, 'custom');
	echo CUFHtml::activeLabelEx($course->ext, 'custom');
	echo CUFHtml::activeTextField($course->ext, 'custom', array('maxlength'=>200));
	echo "<p class='formHint2'>The custom field of this object.</p>";
	echo CUFHtml::closeCtrlHolder();
	
	$servername = getFullServerName();
	
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::label('Dropbox Shortcut', '');
	echo CUFHtml::textField('courselink', "$servername/recorder/record?courseid=$course->id",
		array('class'=>'textInput', 'readonly'=>true));
	echo "<p class='formHint2'>Students can use this URL to open the recorder with this course's saved work folder.</p>";
	echo CUFHtml::closeCtrlHolder();
	
	echo "</div>";	
}



