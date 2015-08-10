<?php

echo "<main class='error' style='width:780px'><h2>Congratulations!</h2>";
echo "<h4>Your course has been successfully created, and you are ready to use your Learning Site.</h4>";

echo "<div style='text-align:center; margin-top:16px'>";
echo "<a href='/my'>";
echo "<input style='margin-right:5px' id='btnSubmit' type='Submit' class='submitButton ui-button ui-widget ui-corner-all ui-state-default' value='Go to my course' role='button' aria-disabled='false'>";
echo "</a>";

echo "<a href='/textbook/addteachercourse'>";
echo "<input style='margin-left:5px' id='btnSubmit' type='Submit' class='submitButton ui-button ui-widget ui-corner-all ui-state-default' value='Create another course' role='button' aria-disabled='false'>";
echo "</a>";

echo "</a>    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a>";

echo "</div>";
echo "</main>";

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Course creation complete">
    <p style='font-size:20px' autofocus>You&#8217;ve just seen all the steps for creating a course.<br>
	<span style='font-size:16px'>From here, you would start working in your course or go back and create another course.</span></p>
    <p style='font-size:16px'>Click the <b><u>Go to my course</u></b> button to return to the My Learning Site page.</p>
</div>
end;





