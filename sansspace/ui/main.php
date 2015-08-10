<?php

require_once('tab.php');
require_once('header.php');
require_once('misc.php');

include 'lib/pageheader.php';

///////////////////////////////////////////////////////////////

echo "</head>";

echo "<body class='page'>";
echo "<div class='wrap' style='min-height:100%; position:relative'>";

if(controller()->id == 'docview')
{
	showPageContent($content);
	echo "</div></body></html>";
	
	return;
}

if(!IsMobileEmbeded() && controller()->id != 'docview')
{
	showPageHeader();
}

if(!IsMobileEmbeded() && controller()->id != 'docview' && (!user()->isGuest))
{
	showMainTabMenu();
}



$server = getdbo('Server', 1);
echo "<div style='padding-left: 10px' id='netmessage'>$server->netmessage</div>";
showFlashMessage();
showPageContent($content);


if(!user()->isGuest)
	{
	showPageFooter($server);
	}

echo "</div></body></html>";

JavascriptReady("$('a', '.buttonwrapper').button();");
JavascriptReady("$(':input', '.buttonwrapper').button();");
JavascriptReady("$(':button', '.ctrlHolder').button();");




