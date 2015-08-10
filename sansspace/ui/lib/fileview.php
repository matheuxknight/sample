<?php

function showFileContent($file)
{
	$filesession = new FileSession;
	$filesession->sessionid = controller()->identity->session->id;
	$filesession->fileid = $file->id;
	$filesession->userid = userid();
	$filesession->starttime = now();
	$filesession->duration = 0;
	$filesession->status = CMDB_FILESESSIONSTATUS_OPEN;
	
	switch($file->filetype)
	{
		case CMDB_FILETYPE_SRT:
		case CMDB_FILETYPE_BOOKMARKS:
		case CMDB_FILETYPE_MEDIA:
			showMediaContent($file);
			break;
		
		case CMDB_FILETYPE_LIVE:
			showLiveContent($file);
			break;
		
		case CMDB_FILETYPE_IMAGE:
		case CMDB_FILETYPE_TEXT:
			showDocumentContent($file);
			break;
			
		case CMDB_FILETYPE_PDF:
			if(!param('usetrackdoc'))
				showEmbeddedContent(fileUrl($file), false);
			else
				JavascriptReady("window.open('/file/trackdoc?id=$file->id',
					'sansspace_tracking_$file->id');");

			break;
			
		case CMDB_FILETYPE_URL:
			if(!param('usetrackdoc'))
				showEmbeddedContent($file->pathname, false);
			else
				JavascriptReady("window.open('/file/trackdoc?id=$file->id', 
					'sansspace_tracking_$file->id');");
			break;

		case CMDB_FILETYPE_SWF:
			$url = fileUrl($file);
			JavascriptReady("window.open('$url', 'sansspace_tracking_$file->id',
				'location=0, status=0, toolbar=0, menubar=0, resizable=1').focus();");
			break;
				
		case CMDB_FILETYPE_DOCUMENT:
		//	error_log("$file->mimetype");
			break;
			
//		case CMDB_FILETYPE_APPLICATION:
//		case CMDB_FILETYPE_DVD:
//			echo "<br>".l(mainimg('16x16_link.png').
//				" Open this document in a new window", fileUrl($file),
//				array('target'=>'_blank'))."<br>";
//			break;
	}

	$filesession->save();
	
	user()->setState('filesession', $filesession->id);
	user()->setState('currentobject', $file->id);
	
	JavascriptReady("window.onbeforeunload = function(){
		$.ajax({url: '/object/leavepage?id=$file->id', async: false});}");
	
	return $filesession;
}

function showEmbeddedContent($url, $internal)
{
	echo l(mainimg('16x16_link.png').' Open in a New Window', $url,
		array('target'=>'_blank', 'title'=>$url));
		
	echo <<<END
&nbsp;&nbsp;
<input type='checkbox' id='hidelink' checked />
<label for='hidelink'>Show Document</label>
<br><br>
<iframe id='linkframe' frameborder=0 src='{$url}' width='100%' height='100'>
<p>Your browser does not support iframes.</p></iframe><br>
<script>
$(function(){
	$('#hidelink').click(function(){ $('#linkframe').toggle();});
END;

	if($internal)
		echo <<<END
	$('#linkframe').load(function(){
		var h = $('#linkframe').contents().find('html').height();
		$('#linkframe').height(h);
	});
END;
	
	else
		echo <<<END
		$('#linkframe').height($(window).height());
		window.location.hash = 'linkframe';
END;

	echo "})</script>";
}

function showDocumentContent($file)
{
// 	echo l(mainimg('16x16_link.png').' Open in a new window', fileUrl($file),
// 		array('target'=>'_blank'));

// 	function showControl()
// 	{
// 		echo "&nbsp;&nbsp;";
			
// 		echo "<input type='checkbox' id='hidelink' checked />
// 			<label for='hidelink' >Show Document</label>";
					
// 		echo "<script>$('#hidelink').click(function(){ $('#linkframe').toggle();});</script>";
// 	}

	if(strstr($file->mimetype, 'image/'))
	{
	//	showControl();
		echo "<div id='linkframe'><br>";
		echo img(fileUrl($file));
		echo "</div>";
	}
	
	else if(strstr($file->mimetype, 'text/') || strstr($file->mimetype, '/xml'))
	{
//		showControl();
		echo "<div id='linkframe'>";
		$filename = objectPathname($file);
		$contents = file_get_contents($filename);
		
		echo processDoctext($file, $contents);
		echo "</div>";
	}
	
	echo "<br><br>";
}









