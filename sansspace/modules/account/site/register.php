<?php

echo "<h2>Register a Learning Site account</h2>";
//echo "<p>Fields with <span class='required'>*</span> are required.</p>";

$this->widget('UniForm');

echo CUFHtml::beginForm();
echo CUFHtml::errorSummary($form);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

echo CUFHtml::openActiveCtrlHolder($form, 'firstname');
echo CUFHtml::activeLabelEx($form, 'firstname');
echo "<div class='textInput sans-input'>John</div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form, 'lastname');
echo CUFHtml::activeLabelEx($form, 'lastname');
echo "<div class='textInput sans-input'>Doe</div>";
echo "<p class='formHint2'></p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'logon');
echo CUFHtml::activeLabelEx($form->user, 'logon');
echo "<div class='textInput sans-input'>johndoeteacher</div>";
echo "<p class='formHint2'>Select the username you will use to log in to the Learning Site. Your username cannot contain spaces.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'password');
echo CUFHtml::activeLabelEx($form->user, 'password');
echo "<div class='textInput sans-input'>password123</div>";
echo "<p class='formHint2'>Minimum 8 characters and maximum 20.</p>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form, 'confirm_password');
echo CUFHtml::activeLabelEx($form, 'confirm_password');
echo "<div class='textInput sans-input'>password123</div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'email');
echo CUFHtml::activeLabelEx($form->user, 'email');
echo "<div class='textInput sans-input'>jdoe@email.com</div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'organisation');
echo CUFHtml::activeLabelEx($form->user, 'organisation');
echo "<div class='textInput sans-input'>J.F.K. High School</div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form, 'register_role');
echo CUFHtml::activeLabelEx($form, 'register_role').'<br>';
echo CUFHtml::activeRadioButtonList($form, 'register_role', array('student'=>'I am a student', 'teacher'=>'I am a teacher'));
echo CUFHtml::closeCtrlHolder();

if($form->register_role == 'teacher')
	echo "<span id='teacher_fields'>";
else
	echo "<span id='teacher_fields' style='display: none;'>";

echo CUFHtml::openActiveCtrlHolder($form->user, 'city');
echo CUFHtml::activeLabelEx($form->user, 'city');
echo "<div class='textInput sans-input'>Any Town</div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'state');
echo CUFHtml::activeLabelEx($form->user, 'state');
echo "<div class='textInput sans-input'>Any State</div>";
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($form->user, 'postal');
echo CUFHtml::activeLabelEx($form->user, 'postal');
echo "<div class='textInput sans-input'>12345</div>";
echo CUFHtml::closeCtrlHolder();

$country_array = array("United States", "Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");

echo CUFHtml::openActiveCtrlHolder($form->user, 'country');
echo CUFHtml::activeLabelEx($form->user, 'country');
echo CUFHtml::activeDropDownList($form->user, 'country', $country_array);
echo CUFHtml::closeCtrlHolder();

echo "</span>";

if(extension_loaded('gd'))
{
	echo CUFHtml::openActiveCtrlHolder($form, 'verifyCode');
	echo CUFHtml::activeLabelEx($form, 'verifyCode', array('label'=>'Enter code on right:'));
	echo CUFHtml::activeTextField($form, 'verifyCode');
	echo "<p class='formHint2'>Enter the letters as they are shown in the image below.
		Letters are not case-sensitive.</p>";
	echo CUFHtml::closeCtrlHolder();

	echo "<div style='float: right; width:35%;'>";
	$this->widget('CCaptcha');
	echo "</div>";
}

echo CUFHtml::closeTag('fieldset');
echo "<a href='/site/login'><input role='button' style='width:110px; height:40px; margin-left:20px' type='button' class='submitButton ui-button ui-widget ui-state-default ui-corner-all' value='Register' ></input></a>    <a href='javascript:void(0)' id='popuplink'><em style='color:#ec4546; verticle-align:middle' class='fa fa-question-circle'></em></a>";
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
<script>
$(function()
{
	$('input:radio').change(
	    function()
	    {
			if(this.value == 'student')
				$('#teacher_fields').hide();
			else
				$('#teacher_fields').show();
		}
	);
});
	

</script>
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
end;
echo "<div id='popup' title='Account Registration'>";
echo "<p style='font-size:20px' autofocus>This is step one.<br><span style='font-size:16px'>
		In the full version of the Learning Site, you and your students will sign up for accounts on a page like this one.<br>
		Click on the &#34;I am a teacher&#34; button to see the information you will need to provide during registration.<br>
		Some of the additional information that teachers provide on this form makes it easier for students to find and enroll in their teacher&#39;s courses.<br><br>
		Click Register to go back to the login page.
</span></p>";
echo "</div>";









