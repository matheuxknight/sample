<?php

if(IsMobileDevice())
{
echo "<div class='login'>";
echo "<main class='container'>";
echo "<h1>Under Construction :( </h1>";
echo "<h4>Mobile Device User<br>You are accessing the Learning Site from a mobile device. Mobile devices are not supported at this time, and you will not be able to access all of the features of the Learning Site. We are currently developing a free app for mobile device users. Visit waysidepublishing.com for updates about the release of the Learning Site app.</h4>";
echo "<h4>Feel free to access the Learning Site from any popular browser on your Desktop, or Laptop Computer.</h4>";
echo "<div class='row'>";
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
echo "<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->";
echo "<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js' type='text/javascript'></script>";
echo "<!-- Include all compiled plugins (below), or include individual files as needed -->";
echo "<script src='js/bootstrap.min.js' type='text/javascript'></script>"; 
echo "</div>";
}

else
{
echo "<div class='login'>";
echo "<main class='container'>";
echo "<div style='float:right; margin-top:15px'><a href='javascript:void(0)' id='popuplink'><em class='fa fa-question-circle'></em></a>  Don't have an account? ";
echo "<a href='/site/register'>Sign up today!</a></div>";
echo "<h1 style='margin-top:40px'>Start Here</h1>";
echo "<div class='row' style='margin-top:10px'>";
echo "<div class='col-md-7'>";
echo "<div class='row' id='loginbox' style='max-width:600px;margin-right:auto;margin-left:auto'>";
echo "<div class='col-md-8' id='loginbox' style='width: 80%'>";
echo "<div class='jumbotron'>";
echo "<form action='/' method='post'>";
echo "<div class='form-group'>";
echo "<input type='text' class='form-control' name='LoginForm[username]' id='LoginForm_username' placeholder='username'>";
echo "</div>";
echo "<div class='form-group'>";
echo "<input type='password' class='form-control' name='LoginForm[password]' id='LoginForm_password' placeholder='password'>";
echo "</div>";
echo "<div class='checkbox'>";
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
echo "<a href='mailto:support@waysidepublishing.com'><em class='fa fa-question-circle'></em></a> Need help? Contact us at";
echo "<a href='mailto:support@waysidepublishing.com'> support@waysidepublishing.com</a></p>";
echo "</main>";
echo "</div>";
}