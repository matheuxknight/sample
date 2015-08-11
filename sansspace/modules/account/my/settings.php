<?php

showUserHeader($user, "My Profile");
$this->widget('UniForm');

echo CUFHtml::beginForm();
echo CUFHtml::errorSummary($user);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

InitMenuTabs('#tabs');

echo "<div id='tabs' style='display:none;'><ul>";
echo "<li><a href='#tabs-1'>Account</a></li>";
echo "<li><a href='#tabs-2'>Personal</a></li>";
echo "<li><a href='#tabs-3'>Picture</a></li>";
echo "</ul><br>";

/////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-1'>";

echo CUFHtml::openActiveCtrlHolder($user, 'logon');
echo CUFHtml::activeLabelEx($user, 'logon');
echo "<div class='textInput sans-input'>{$user->logon}</div>";
echo "<p class='formHint2'>Your logon used to connect to this server.</p>";
echo CUFHtml::closeCtrlHolder();

//echo "&nbsp;&nbsp;<b>Global Roles</b> ";
//$roles = controller()->rbac->globalRoles();
//foreach($roles as $roleid)
//{
//	if($roleid == SSPACE_ROLE_ALL || $roleid == SSPACE_ROLE_USER) continue;
//	
//	$role = getdbo('Role', $roleid);
//	echo "$role->name, ";
//}

echo CUFHtml::openActiveCtrlHolder($user, 'name');
echo CUFHtml::activeLabelEx($user, 'name');
echo CUFHtml::activeTextField($user, 'name', array('maxlength'=>45));
echo "<p class='formHint2'>Your complete name.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'email');
echo CUFHtml::activeLabelEx($user, 'email');
echo CUFHtml::activeTextField($user, 'email', array('maxlength'=>80));
echo "<p class='formHint2'>The email that this server will use to send you information about your account.</p>";
echo CUFHtml::closeCtrlHolder();

if(!$user->domain->ldapenable)
{
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::label('Password', 'password');
	echo CUFHtml::passwordField('password', '', array('maxlength'=>50, 'class'=>'miscInput'));
	echo "<p class='formHint2'>Leave blank for no change.</p>";

//	if(!empty($user->password) && !param('required_password'))
//		echo CHtml::linkButton('[Reset Password]',
//			array('submit'=>array('resetpassword', 'id'=>$user->id), 'confirm'=>'Are you sure?'));				
	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::label('Confirm', 'confirm');
	echo CUFHtml::passwordField('confirm', '', array('maxlength'=>50, 'class'=>'miscInput'));
	echo "<p class='formHint2'>Confirm your new password.</p>";
	echo CUFHtml::closeCtrlHolder();
}

else
	echo "<br>Your password is not kept on this server. To change your password, you need
	to change it on your institution server.";

echo "</div>";

/////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-2'>";

echo CUFHtml::openActiveCtrlHolder($user, 'organisation');
echo CUFHtml::activeLabelEx($user,'organisation');
echo CUFHtml::activeTextField($user,'organisation', array('maxlength'=>80));
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'phone1');
echo CUFHtml::activeLabelEx($user, 'phone1');
echo CUFHtml::activeTextField($user, 'phone1', array('maxlength'=>50));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'address');
echo CUFHtml::activeLabelEx($user, 'address');
echo CUFHtml::activeTextField($user, 'address', array('maxlength'=>80));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'city');
echo CUFHtml::activeLabelEx($user, 'city');
echo CUFHtml::activeTextField($user, 'city', array('maxlength'=>50));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'postal');
echo CUFHtml::activeLabelEx($user, 'postal');
echo CUFHtml::activeTextField($user, 'postal', array('maxlength'=>20));
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'state');
echo CUFHtml::activeLabelEx($user, 'state');
echo CUFHtml::activeTextField($user, 'state', array('maxlength'=>20));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'country');
echo CUFHtml::activeLabelEx($user, 'country');
echo CUFHtml::activeTextField($user, 'country', array('maxlength'=>50));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();


echo CUFHtml::openActiveCtrlHolder($user, 'enrolled');
echo CUFHtml::activeLabelEx($user, 'enrolled');
echo CUFHtml::activeTextField($user, 'enrolled', array('readonly'=>true));
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo "</div>";

/////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-3'>";

ShowUploadHeader();

echo CUFHtml::openCtrlHolder();
echo CUFHtml::label('Upload File', '');
echo '<div class="miscInput"><span id="spanButtonPlaceholder"></span></div>';
echo "<p class='formHint2'>Upload an image file from your hard drive.</p>";
echo CUFHtml::closeCtrlHolder();
echo '<div class="flash" id="fsUploadProgress"></div>';

echo CUFHtml::openCtrlHolder();
echo CUFHtml::label('Internet File', 'icon_url');
echo CUFHtml::textField('icon_url', '', array('maxlength'=>50, 'class'=>'textInput'));
echo "<p class='formHint2'>Or enter an internet url to an image file. (http://server.com/file.png).</p>";
echo CUFHtml::closeCtrlHolder();

//////////////////////////////////////////////////////////////

$arrayoptions = array('id'=>'webcamsnapshotholder');

echo CUFHtml::openCtrlHolder($arrayoptions);
echo CUFHtml::label('Webcam Snapshot', 'range');

echo CUFHtml::button('...', array('onclick'=>"onWebcamSnapshotClick()"));
echo "<p class='formHint2'>Or take a snapshot from your webcam.</p>";

$getflash = mainimg('getflash.jpg');

echo <<<END
<div id='webcamsnapshottemplate' style='display: none; '>
<br>
<div id='webcamsnapshotflash'>
<br><br><a href='http://get.adobe.com/flashplayer/' target=_blank>$getflash</a><br><br>
</div>
</div>
END;

echo CUFHtml::closeCtrlHolder();

echo '<br>'.CHtml::linkButton('[Reset Picture]',
	array('submit'=>array('my/resetpicture'), 'confirm'=>'Are you sure?'));

	
echo "</div>";

//////////////////////////////////////////////////////////////

$connect = getPlayerConnect();
$flashvars = "connect=$connect";

echo <<<END
<script type='text/javascript'>

function onWebcamSnapshotClick()
{
	if($('#webcamsnapshottemplate').is(':visible'))
	{
		$('#webcamsnapshottemplate').hide();
		return;
	}

	$('#webcamsnapshottemplate').show();
	
	var params = {};
	params.allowscriptaccess = "sameDomain";
	params.allowfullscreen = "false";
	params.wmode = "opaque";
	
	var attributes = {};
	attributes.id = "sssnap";
	attributes.name = "sssnap";
	attributes.align = "middle";
	
	swfobject.embedSWF(
		"/extensions/players/sssnap.swf", "webcamsnapshotflash", 
		"100%", "250", "10.0.0", "playerProductInstall.swf", 
		"$flashvars", params, attributes);
}

</script>
END;


/////////////////////////////////////////////////////////////////////////

echo "</div>";

echo CUFHtml::closeTag('fieldset');
showSubmitButton('Save');
echo CUFHtml::endForm();





