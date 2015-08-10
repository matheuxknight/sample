<?php

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Courses">
    <p style='font-size:20px' autofocus>Teachers:<br>
	<span style='font-size:16px'>All of the courses you create will be in the <b><u>Courses</b></u> page.</span><p>
    <p style='font-size:20px' autofocus>Students<br>
	<span style='font-size:16px'>Your students will see every course they are enrolled in.</span></p>
    <p style='font-size:14px'>Click on the <b><u>course name</b></u> to enter.</p>
</div>
end;


$this->pageTitle = "My Courses ". Yii::app()->name;

echo "<h2>Courses    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a></h2>";



showFolderContents("mycourses");




