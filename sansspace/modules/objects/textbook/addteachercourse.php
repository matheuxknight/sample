<?php

echo "<main class='container error'><div class='container' align='center' style='width:700px; text-align:center; margin-top: -20px; padding-left:0px'><h3 style='color:rgb(0, 122, 187); width:580px'>Which textbook are you using?</h3></div>";

echo "<div class='container' style='width:700px; height:100%; align-content:center'>";
	

	echo <<<end
	<div class='row'>
	<div align='center' style='width:580px; text-align:center; margin-bottom: 20px; border-bottom:solid #555555 1px'><h3>Spanish</h3></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-4145.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>Azulejo</h4></a></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-4149.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>Tejidos</h4></a></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-4151.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>Tri&aacute;ngulo Aprobado</h4></a></div>
	</div>
	<div class='row'>
	<div align='center' style='width:180px; text-align:center; margin-bottom: 20px; border-bottom:solid #555555 1px; float:left; margin-left:10px; margin-right:10px'><h3>French</h3></div>
	<div align='center' style='width:180px; text-align:center; margin-bottom: 20px; border-bottom:solid #555555 1px; float:left; margin-left:10px; margin-right:10px'><h3>German</h3></div>
	<div align='center' style='width:180px; text-align:center; margin-bottom: 20px; border-bottom:solid #555555 1px; float:left; margin-left:10px; margin-right:10px'><h3>Italian</h3></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-8613.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>APprenons 1<sup>st</sup> Edition</h4></a></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-4158.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>Neue Blickwinkel</h4></a></div>
	<div align='center' style='padding:5px; min-height:150px; min-width:200px; float:left; text-align:center; margin-right:auto; margin-left:auto; margin-bottom:15px; display:block '><a href="/course/createteacher"><img src="http://162.249.105.83/contents/object-7600.png" style="width:100px;"></a><br><a href="/course/createteacher"><h4>Chiarissmo Uno</h4></a></div>
	</div>

	

</div></main>
end;

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Which textbook are you using?">
    <p style='font-size:20px' autofocus>Here you will be able to create your account for the Learning Site.<br>By filling in all the appropriate information for account creation your next step will be to log in.<br><br>Once you signify that you are a teacher, you will be required to fill additional data. This information will be used to make course enrollment for your students, much easier.</p>
    <p style='font-size:14px'>Note: You will not be able to create an account on the Sample Learning Site. This page has been limited to replicate how a user will create an account only.</p>
</div>
end;
