<?php

showUserHeader($user, "Settings");
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

echo CUFHtml::openActiveCtrlHolder($user, 'name');
echo CUFHtml::activeLabelEx($user, 'name');
echo "<div class='textInput sans-input'>{$user->name}</div>";
echo "<p class='formHint2'>Your complete name.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'email');
echo CUFHtml::activeLabelEx($user, 'email');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'>The email that this server will use to send you information about your account.</p>";
echo CUFHtml::closeCtrlHolder();

if(!$user->domain->ldapenable)
{
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::label('Password', 'password');
	echo "<div class='textInput sans-input'></div>";
	echo "<p class='formHint2'>Leave blank for no change.</p>";

//	if(!empty($user->password) && !param('required_password'))
//		echo CHtml::linkButton('[Reset Password]',
//			array('submit'=>array('resetpassword', 'id'=>$user->id), 'confirm'=>'Are you sure?'));				
	echo CUFHtml::closeCtrlHolder();
	
	echo CUFHtml::openCtrlHolder();
	echo CUFHtml::label('Confirm', 'confirm');
	echo "<div class='textInput sans-input'></div>";
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
echo "<div class='textInput sans-input'></div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'phone1');
echo CUFHtml::activeLabelEx($user, 'phone1');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'address');
echo CUFHtml::activeLabelEx($user, 'address');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'city');
echo CUFHtml::activeLabelEx($user, 'city');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'postal');
echo CUFHtml::activeLabelEx($user, 'postal');
echo "<div class='textInput sans-input'></div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'state');
echo CUFHtml::activeLabelEx($user, 'state');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($user, 'country');
echo CUFHtml::activeLabelEx($user, 'country');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo "</div>";

/////////////////////////////////////////////////////////////////////////

echo "<div id='tabs-3'>";

ShowUploadHeader();

echo CUFHtml::openCtrlHolder();
echo CUFHtml::label('Upload File', '');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'>Upload an image file from your hard drive.</p>";
echo CUFHtml::closeCtrlHolder();
echo '<div class="flash" id="fsUploadProgress"></div>';

echo CUFHtml::openCtrlHolder();
echo CUFHtml::label('Internet File', 'icon_url');
echo "<div class='textInput sans-input'></div>";
echo "<p class='formHint2'>Or enter an internet url to an image file.</p>";
echo CUFHtml::closeCtrlHolder();

//////////////////////////////////////////////////////////////

$arrayoptions = array('id'=>'webcamsnapshotholder');

echo CUFHtml::openCtrlHolder($arrayoptions);
echo CUFHtml::label('Webcam Snapshot', 'range');

echo "<div class='textInput sans-input'></div>";
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
	array('submit'=>array('my/'), 'confirm'=>'Are you sure?'));

	
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
echo "<a href='#'><input role='button' style='width:110px; height:40px;' type='button' class='submitButton ui-button ui-widget ui-state-default ui-corner-all' value='Save' ></input></a>    <a href='#' id='popuplink'><em style='color:#ec4546; verticle-align:middle' class='fa fa-question-circle'></em></a>";
echo CUFHtml::endForm();


echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Settings">
    <p style='font-size:20px' autofocus>Visit your Settings immediately after registering for a Learning Site account!<br>
	<span style='font-size:16px'>You can update your username, name, email, password, school, phone, and street address.<br>Most importantly, you&#8217;ll come here to personalize your Learning Site with a picture of yourself.</span></p>
</div>
end;



