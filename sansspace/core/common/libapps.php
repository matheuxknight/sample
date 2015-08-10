<?php

function ShowApplication($flashvars, $mode, $name, $height, $showresize=true)
{
	if(!isset($_SERVER['HTTP_REFERER']) && IsMobileDevice()) return;
	$agent = $_SERVER['HTTP_USER_AGENT'];
	
	$returnurl = preg_replace('/&.*$/', '', $_SERVER['REQUEST_URI']);
	
	$appheadcolor = param('appheadercolor');
	$appheadback = param('appheaderback');
	
//	$customcolor1 = currentPageColor1();
	$customcolor2 = currentPageColor2();
	
//	if(!empty($customcolor1))
//		$appheadcolor = $customcolor1;
		
	if(!empty($customcolor2))
		$appheadback = $customcolor2;
		
	$flashvars .= 
		"&headercolor=".preg_replace('/#/', '0x', $appheadcolor).
		"&headerback=".preg_replace('/#/', '0x', $appheadback).
		"&maincolor=".preg_replace('/#/', '0x', param('appmaincolor')).
		"&mainback=".preg_replace('/#/', '0x', param('appmainback')).
		"&mainalpha=".preg_replace('/#/', '0x', param('appmainalpha')).
		"&slidercolor=".preg_replace('/#/', '0x', param('appslidercolor')).
		"&phpsessid=".session_id().
		"&returnurl=".getFullServerName().$returnurl.
		"&connect=".getPlayerConnect().
		"&connecthttp=".getFullServerName().
		"&bookmarkprefix=".param('bookmarkprefix');
	
//	debuglog($_SERVER['REQUEST_URI']);
//	debuglog("sansmedia:mode=$mode&$flashvars");
	
	if(IsMobileEmbeded())
	{
		$prot = '';
		if(preg_match('/android/i', $agent))
			$prot = 'unknown:/';
		
		JavascriptReady("
			var ret = new Object;
			ret['event'] = 'invoke';
			ret['mode'] = '$mode';
			ret['flashvars'] = 'sansspace:mode=$mode&$flashvars';
			document.location = '$prot'+JSON.stringify(ret);");
	}
	
	else if(preg_match('/ipad/i', $agent) || preg_match('/iphone/i', $agent))
	{
		echo '<br>';
		echo "<a href='https://itunes.apple.com/app/sansspace/id630654357'>";
		echo mainimg('install-ipad.jpg').'<br>';
		echo "Install iOS App</a><br>";
		
		JavascriptReady("window.location='sansspace:mode=$mode&$flashvars'");
	}
	
	else if(preg_match('/android/i', $agent))
	{
		echo '<br>';
		echo l(mainimg('install-android.jpg', '', 
			array('width'=>64))."<br>Install Android Application", array('/site/installandroid')).'<br>';

		echo "<p>Before installing the sansspace android application from the link above, you need to allow ".
			"installation of apps from sources other than the Play Store.</p>".
			"<p>You will find this Android Settings under Security as \"Unknown Sources\".</p>";

		echo "<iframe frameborder=0 src='sansspace:mode=$mode&$flashvars' width=1 height=1 >";
	//	debuglog("sansspace:mode=$mode&$flashvars");
	}
	
	else
	{
		$getflash = mainimg('install-flash.jpg');

		echo <<<END
<div id='flashcontent' style='width: 100%; height: 100%'>
	<br><br>&nbsp;&nbsp;<a href='http://get.adobe.com/flashplayer/' target=_blank>$getflash</a><br><br>
</div>
END;
		
		if($showresize)
		{
			$color = param('appmainback');
			echo "<div id='handleBottom' onmousedown='SansspacePlayer.mousedown();' 
				style='width:100%;height:5px;background-color:$color;cursor:s-resize;'></div>";
		}

		echo <<<END
<script>
$(function(){
var params = {};
params.allowscriptaccess = "sameDomain";
params.allowfullscreen = "true";
params.wmode = "opaque";
var attributes = {};
attributes.id = "$name";
attributes.name = "$name";
attributes.align = "middle";

swfobject.embedSWF("/extensions/players/$name.swf", "flashcontent", "100%", "$height", 
	"11.1.0", "playerProductInstall.swf", "$flashvars", params, attributes);
})

</script>
END;
	
		JavascriptFile("/sansspace/ui/js/player.js");
		JavascriptReady("SansspacePlayer.init('$name')");
	}
	
}


