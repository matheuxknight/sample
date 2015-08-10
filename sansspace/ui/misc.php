<?php

function showFlashMessage()
{
	if(user()->hasFlash('message'))
	{
		echo "<div class='confirmation'>";
		echo user()->getFlash('message');
		echo "</div>";
	}

	if(user()->hasFlash('error'))
	{
		echo "<div class='errormessage'>";
		echo user()->getFlash('error');
		echo "</div>";
	}
}

function showPageContent($content)
{
	echo "<div id='content'><!-- showPageContent -->";
	echo $content;
	echo "</div><!-- showPageContent -->";
}

function showPageFooter($server)
{
	echo "<br><br><br><br><br><br><br><br><br>";
	echo "<br><br><br><br><br><br><br><br><br>";
	
	if($server && !empty($server->footer))
		echo $server->footer;
	
	else
	{
		echo "<div id='footer'>";
		
		$year = date("Y", time());
		echo "<p>&copy;2006-$year SANS Inc. All Rights Reserved &#9679; SANSSpace&#8482; ";
	
		$version = getdbosql('DatabaseVersion', "1");
		if($version) echo "$version->version &#9679; $version->updated ";
	
		echo "</p>";
		echo "</div><!-- footer -->";
	}
}




