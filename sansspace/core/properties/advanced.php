<?php

function objectShowPropertiesAdvanced($object, $update)
{
	echo "<div id='properties-advanced'>";
	
	echo CUFHtml::openActiveCtrlHolder($object, 'hidden');
	echo CUFHtml::activeLabelEx($object, 'hidden');
	echo CUFHtml::activeCheckBox($object, 'hidden', array('class'=>'miscInput'));
	$teacher = getdbosql('Role', "name='teacher'");
	echo "<p class='formHint2'>This item will be visible only to $teacher->description and admins.
	Users still have the permission to use the file according to their role.</p>";
	echo CUFHtml::closeCtrlHolder();

	echo CUFHtml::openActiveCtrlHolder($object, 'model');
	echo CUFHtml::activeLabelEx($object, 'model');
	echo CUFHtml::activeCheckBox($object, 'model', array('class'=>'miscInput'));
	echo "<p class='formHint2'>
	This object and those below will inherit the contents and the related contents 
	of its parent if the parent also has this value checked. Inheritance can go up 
	many levels. As long as this flag is checked.</p>";
	echo CUFHtml::closeCtrlHolder();

// 	echo CUFHtml::openActiveCtrlHolder($object->ext, 'customcolor1');
// 	echo CUFHtml::activeLabelEx($object->ext, 'customcolor1');
// 	echo CUFHtml::activeTextField($object->ext, 'customcolor1', array('class'=>'miscInput'));
// 	echo "<p class='formHint2'>The custom color 1 of this object (lighter).</p>";
// 	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openActiveCtrlHolder($object->ext, 'customcolor2');
	echo CUFHtml::activeLabelEx($object->ext, 'customcolor2');
	echo CUFHtml::activeTextField($object->ext, 'customcolor2', array('class'=>'miscInput'));
	echo "<p class='formHint2'>The Color theme of certain UI and Quiz Modules</p>";
	echo CUFHtml::closeCtrlHolder();
	
	$options = array('default'=>'default');
	$folders = glob(SANSSPACE_HTDOCS.'/images/iconset/*');
	foreach($folders as $f)
	{
		$name = strrchr($f, '/');
		$name = substr($name, 1);
	
		$options[$name] = $name;
	}
	
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::activeLabelEx($object->ext, 'customiconset');
	echo CUFHtml::activeDropDownList($object->ext, 'customiconset', $options, array('class'=>'miscInput'));
	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openActiveCtrlHolder($object->ext, 'customheader');
	echo CUFHtml::activeLabelEx($object->ext, 'customheader');
	echo CUFHtml::activeHiddenField($object->ext, 'customheader');
	
	if(!empty($object->ext->customheader))
		echo "<div class='textInput sans-text'>".getTextTeaser($object->ext->customheader)."</div>";
	showObjectEditorButton('ObjectExt_customheader');
	
	echo "<p class='formHint2'>The custom header of this object.</p>";
	echo CUFHtml::closeCtrlHolder();
	
	if($object->type == CMDB_OBJECTTYPE_FILE && $object->file->filetype == CMDB_FILETYPE_MEDIA)
	{
		$servername = getFullServerName();
		
		$height = 34;
		if($object->file->hasvideo)
			$height = 360;
				
		echo CUFHtml::openCtrlHolder();
		echo CUFHtml::label('Embed Iframe', '');
		echo CUFHtml::textField('objectembed', "<iframe width=480 height=$height frameborder=0 seamless src='$servername/file/embed?id=$object->id'></iframe>",
				array('class'=>'textInput', 'readonly'=>true, 'rows'=>2));
		echo "<p class='formHint2'>Use this HTML code to embed this media file on other web sites.</p>";
		echo CUFHtml::closeCtrlHolder();
	}
	
// 	echo CUFHtml::openActiveCtrlHolder($object->ext, 'custom');
// 	echo CUFHtml::activeLabelEx($object->ext, 'custom');
// 	echo CUFHtml::activeTextField($object->ext, 'custom', array('maxlength'=>200));
// 	echo "<p class='formHint2'>The custom field of this object.</p>";
// 	echo CUFHtml::closeCtrlHolder();
	
	echo "</div>";	
}


