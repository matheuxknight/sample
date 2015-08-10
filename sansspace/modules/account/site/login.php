<?php

echo <<<end
	<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: true, modal: true, width: '600', });
})
	</script>
end;

if(IsMobileDevice())
{
echo "<div class='login'>";
echo "<main class='container'>";
echo "<h1>Under Construction</h1>";
echo "<h4>Mobile Device User<br>You are attempting to access the Learning Site from a mobile device. Mobile devices are not supported at this time. We are currently developing a free app for mobile device users. Visit waysidepublishing.com for updates about the release of the Learning Site app.</h4>";
//echo "<h4>Feel free to access the Learning Site from any popular browser on your Desktop, or Laptop Computer.</h4>";
echo "<div style='margin-top:-30px' class='row'>";
echo "<div class='col-md-7'>";
echo "<div class='row'>";
echo "<div class='col-md-5'>";
echo "<p style='text-align:center'>Don't have an account?<br>Create one today!</p>";
echo "<a href='' class='btn btn-primary btn-lg btn-block'>Sign Up</a>";
echo "</div>";
echo "<div class='col-md-7'>";
echo "<div class='jumbotron'>";
echo "<form action='/' method='post'>";
echo "<div class='form-group'>";
echo "<input type='text' class='form-control' name='LoginForm[username]' id='LoginForm_username' placeholder='username' readonly>";
echo "</div>";
echo "<div class='form-group'>";
echo "<input type='password' class='form-control' name='LoginForm[password]' id='LoginForm_password' placeholder='password' readonly>";
echo "</div>";
echo "<div class='checkbox'>";
echo "<label>";
echo "<input name='LoginForm[rememberMe]' id='ytLoginForm_rememberMe' type='checkbox'> remember me";
echo "</label>";
echo "</div>";
echo "<div class='arrow-down-shadow'></div>";
echo "<div class='arrow-down'></div>";
echo "<a href='' class='btn btn-primary btn-lg btn-block'>Log In</a>";
echo "</form>";
echo "</div>";
echo "<p>";
echo "<a class='password-reset' href=''>Forgot your password?</a>";
echo "</p></div></div></div></div><p>";
echo "<em class='fa fa-question-circle'></em> Need help? Contact us at";
echo "<a href='mailto:support@waysidepublishing.com'> support@waysidepublishing.com</a></p>";
echo "</main>";
echo "</div>";
}

else
{
echo "<div id='popup' title='Sample Learning Site'>
    	<p style='font-size:20px' autofocus>Welcome to the Sample Learning Site.</p>
	<p style='font-size:14px'>
	You won&#8217;t need to sign up for an account to gain access to this sample version. Just choose a textbook from the dropdown menu and click <i>Log In</i>. Inside, you&#8217;ll find all the materials from one chapter or section of the book you&#8217;re exploring.<br><br>
	<!--When you are ready to purchase a subscription, just click on the shopping cart in the upper-right corner of your browser.<br><br>-->
	Click on the <em style='color:#ec4546; verticle-align:middle' class='fa fa-question-circle'></em> icons for more information about the features you see.
	</p>
	<strong>
	Looking to review a full Learning Site? <a href='mailto:support@waysidepublishing.com' style='color:#00b898'>Contact us to request your review today!</a>
	</strong>
	</div>";
echo "<div class='login'>";
echo "<main class='container'>";
echo "<div style='float:right; margin-top:15px'>Don't have an account? ";
echo "<a href='/site/register'>Sign up today!</a></div>";
echo "<a href='/site/admin'><div style='position:absolute; bottom:0; right:0; width:5%; height:5%;'></div></a>";
echo "<h1 style='margin-top:40px'>Start Here</h1>";
echo "<div class='row' style='margin-top:10px'>";
echo "<div class='col-md-7'>";
echo "<div class='row' id='loginbox' style='max-width:600px;margin-right:auto;margin-left:auto'>";
echo "<div class='col-md-8' id='loginbox' style='width: 80%'>";
echo "<div class='jumbotron'>";
echo "<form action='/' method='post'>";
echo "<div class='form-group'>";
echo "<select class='form-control' name='LoginForm[username]' id='LoginForm_username'><option value='' selected>Select Textbook From List Below:</option><option value='triangulo'>Tri&#225;ngulo Aprobado</option><option value='tejidos'>Tejidos</option><option value='azulejo'>Azulejo</option><option value='apprenons'>APprenons</option><option value='neue'>Neue Blickwinkel</option>";
echo "<option value='chiarissimo'>Chiarissimo Uno</option>";
echo "</select>";
echo "</div>";
echo "<div class='form-group'>";
echo "<input type='password' class='form-control' name='LoginForm[password]' id='LoginForm_password' placeholder='password' readonly>";
echo "</div>";
echo "<div class='checkbox' value='true'>";
echo "<label>";
echo "<input name='LoginForm[rememberMe]' id='ytLoginForm_rememberMe' type='checkbox'> remember me";
echo "</label>";
echo "</div>";
echo "<div class='arrow-down-shadow'></div>";
echo "<div class='arrow-down'></div>";
echo "<input id='btnSubmit' name='yt0' value='Log In' type='submit' class='btn btn-primary btn-lg btn-block'>";
echo "</form>";
echo "</div>";
echo "<p>";
echo "<a class='password-reset' href='/site/forgot'>Forgot your password?</a>";
echo "</p></div></div></div></div><p>";
echo "<em class='fa fa-question-circle'></em><span style='font-size:1em;'>
	Looking to review a full Learning Site? <a href='mailto:support@waysidepublishing.com'>Contact us to request your review today!</a>
	</span><br/><a href='mailto:support@waysidepublishing.com'><em class='fa fa-question-circle'></em></a> Need help? Contact us at";
echo "<a href='mailto:support@waysidepublishing.com'> support@waysidepublishing.com</a></p>";
echo "</main>";
echo "</div>";
}



//echo <<<end
//<script type="text/javascript">
//	$(document).ready(function() 
//{
//	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
//	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
//})
//</script>
//<div id="popup" title="Learning Site Log In">
//    <p style='font-size:20px' autofocus>Here you will be able to create your account for the Learning Site.<br>By filling in all the appropriate information for account creation your next step will be to log in.<br><br>Once you signify that you are a teacher, you will be required to fill additional data. This information will be used to make course enrollment for your students, much easier.</p>
//    <p style='font-size:14px'>Note: You will not be able to create an account on the Sample Learning Site. This page has been limited to replicate how a user will create an account only.</p>
//</div>
//end;


