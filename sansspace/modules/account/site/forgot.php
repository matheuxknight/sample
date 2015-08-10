<?php

$this->pageTitle=Yii::app()->name . ' Forgot Password';

echo '<h2>Forgot Password</h2>';

echo '<p>If you already have an account on this server but forgot the password, type in 
either your username OR your email address in the fields below, enter the verification code 
and click the Submit button.</p>';

echo '<p>You will then receive an email with instructions on how to set a new password for
your account.</p>';

$this->widget('UniForm');

echo CUFHtml::beginForm();
echo CUFHtml::errorSummary($form);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

echo CUFHtml::openActiveCtrlHolder($form, 'name');
echo CUFHtml::activeLabelEx($form, 'name');
echo "<div class='textInput sans-input'>johndoeteacher</div>";
echo "<p class='formHint2'>Your username on this server.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form, 'email');
echo CUFHtml::activeLabelEx($form, 'email');
echo "<div class='textInput sans-input'>jdoe@email.com</div>";
echo "<p class='formHint2'>Or, the email address you used to register your account.</p>";
echo CUFHtml::closeCtrlHolder();

if(extension_loaded('gd'))
{
	echo CUFHtml::openActiveCtrlHolder($form, 'verifyCode');
	echo CUFHtml::activeLabel($form, 'verifyCode', array('label'=>'Enter code on right:'));
	echo "<div class='textInput sans-input'></div>";
	echo "<p class='formHint2'>Enter the letters as they are shown in the image below.
		Letters are not case-sensitive.</p>";
	echo CUFHtml::closeCtrlHolder();

	echo "<div style='float: right; width:35%;'>";
	$this->widget('CCaptcha');
	echo "</div>";
}

echo CUFHtml::closeTag('fieldset');
echo "<a href='/site/login'><input role='button' style='width:110px; height:40px; margin-left:20px' type='button' class='submitButton ui-button ui-widget ui-state-default ui-corner-all' value='Submit' ></input></a>    <a href='#' id='popuplink'><em style='color:#ec4546; verticle-align:middle' class='fa fa-question-circle'></em></a>";
echo CUFHtml::endForm();

echo "<br><br><br><br><br><br><br><br><br><br>";
echo "<footer>";
echo "<div class='container'>";
echo "<div class='row' style='max-width:910px'>";
echo "<div class='col-md-8' style='margin-top:10px;float:left;width:auto'>";
echo "<p class='footer-contact'>";
echo "<em class='fa fa-question-circle'></em> Need help? <a id='contactlink' class='contactlink' href='mailto:support@waysidepublishing.com'>Contact us.</a></p>";
echo "<p7>Copyright &#169; 2010-2014 <a href='http://www.waysidepublishing.com' style='text-decoration:none'  target='_blank'>Wayside Publishing.</a> All Rights Reserved.<br>Audios, videos, and visual materials are copyright protected and may not be downloaded without permission from <a href='http://www.waysidepublishing.com' style='text-decoration:none'  target='_blank'>Wayside Publishing.</a><br>";
echo "</p7></div>";
echo "<div class='col-md-3 powered-by' style='width:201px'>";
echo "<p style='margin-bottom:-4px'>Powered by</p>";
echo "<img src='/contents/16826.png' alt='Sansspace logo'>";
echo "</div>";
echo "</div> <!-- .row -->";
echo "</div> <!-- .container -->";
echo "</footer>";  

echo <<<end
</script>
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Forgot your password?">
    <p style='font-size:20px' autofocus>Sometimes you may forget your password, and this page will help you find it.</p>
    <p style='font-size:16px'>Click <b><u>Submit</u></b> to return to the login page.</p>
</div>
end;


