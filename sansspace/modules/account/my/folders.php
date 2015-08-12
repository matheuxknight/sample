<?php

$this->pageTitle = "Uploads | ". Yii::app()->name;
echo "<h2>My Work    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a></h2>";
echo "<br><img src='/contents/18053.png' alt='Screenshot of uploads page'>";

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="My Work">
     <p style='font-size:20px' autofocus>Think of My Work as your personal Learning Site hard drive<br>
	<span style='font-size:16px'>Store all of your files here. You can upload any file: doc, dox, pdf, mp3, mp4, etc.<br>The Learning Site will compile all of the files you upload directly into activities here as well, including your recordings.</span></p>
</div>
end;





