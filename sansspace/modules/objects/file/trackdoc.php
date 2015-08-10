<?php

include '/sansspace/ui/lib/pageheader.php';

echo "</head>";


echo "<body style='overflow: hidden;'>";



$file = getdbo('VFile', getparam('id'));

echo "<div id='sansspace_toolbar' class='ui-widget-header' style='position: fixed; top: 0px; left: 0px; right: 0px; padding: 2px;'>";
echo "&nbsp;<span style='font-weight: bold; font-size: 1.1em; '>SANSSpace</span>";

echo "&nbsp;&nbsp;&nbsp;&nbsp;";

echo "<a id='button-comment'>Comment...</a>";
echo "&nbsp;";
echo "<a id='button-recorder'>Recorder...</a>";

echo "</div>";

switch($file->filetype)
{
	case CMDB_FILETYPE_PDF:
		$url = fileUrl($file);
		break;
		
	case CMDB_FILETYPE_URL:
		$url = $file->pathname;
		break;
}

$user = getUser();
$semester = getCurrentSemester();

$parentid = 'myfolders';
$parentname = 'My Saved Work';

foreach($user->courseenrollments as $e)
{
	if($e->object->type != CMDB_OBJECTTYPE_COURSE) continue;
	$course = $e->object->course;

	if($course->semesterid && $course->semesterid != $semester->id) continue;

	if(isCourseHasObject($course, $file->object))
	{
		$folder = userRecordingFolder($course);
		
		$parentid = $folder->id;
		$parentname = $folder->name;
		
	//	break;
	}
}

echo <<<END

<br><br>


<iframe id='linkframe' frameborder=0 src='{$url}' width='100%' height='10000'>
<p>Your browser does not support iframes.</p></iframe>

</body></html>

<script>
$(function()
{
	$('#linkframe').load(function()
	{
		$('#content').css('padding', 0);
		var t = $('#sansspace_toolbar').height();
		
		$('#linkframe').height(window.innerHeight-t);
	});

	$('#button-comment').button().click(function()
	{
		$('#comment_dialog_div').dialog(
		{
			title: 'Sansspace Comment',
			autoOpen: true,
			modal: false,
			width: 360,
			height: 240,
			minWidth: 200,
			minHeight: 160,
		
			buttons:
			{
				"Cancel": function(){ $(this).dialog("close");},
			
				"Save As...": function()
				{
					onShowObjectBrowser('html', false, true, '', '', '$parentid', '$parentname', 
						function(selectedid, selectedname) 
						{
							var url = '/file/createhtml?id='+selectedid;
							$.post(url, {
								'VFile[name]': selectedname,
								'VFile[originalid]': $file->id,
								'htmlcontents': $('#comment_input').val()
							});

							$('#comment_dialog_div').dialog("close");
						});
				}
			}
		});

		return false;
	});

	$('#button-recorder').button().click(function()
	{
		$('#recorder_dialog_div').remove();
		$('body').append('<div id="recorder_dialog_div" style="padding: 0px;" />');

		$('#recorder_dialog_div').dialog(
		{
			title: 'Sansspace Recorder',
			autoOpen: true,
			modal: false,
			width: 260,
			height: 150,
			minWidth: 200,
			minHeight: 140,

			resize: function(event, ui)
			{
				var h = $('#recorder_dialog_div').height();
				$('#sansmediad').css("height", h);
			},
										
			buttons:
			{
				"Cancel": function(){ $(this).dialog("close");},
			
				"Save As...": function()
				{
					onShowObjectBrowser('flv', false, true, '', '', '$parentid', '$parentname', 
						function(selectedid, selectedname) 
						{
							var url = '/recorder/internalsave?parentid='+selectedid+
								'&masterid='+$file->id+'&name='+selectedname;
							$.get(url, '', function(data)
							{
								$('#recorder_dialog_div').dialog("close");
							});
						});
				}
			}
		});

		$.get('/recorder/internalquickrecorder&id=$file->id', '', function(data)
		{
			$('#recorder_dialog_div').html(data);
		});

		return false;
	});

});

</script>

<div id="comment_dialog_div" style='display: none; overflow: hidden;'>

<textarea id='comment_input' style='width: 99%; height: 99%; '>
</textarea>
		
<br><br><br>
</div>
			

END;



