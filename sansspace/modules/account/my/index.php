<?php

function showJumbotron($title, $id, $url, $icon)
{
	$iconset = param('iconset');
	$icon = "/images/iconset/$iconset/$icon";
	
echo <<<end
<a href="$url">
	<div class="jumbotron" id="$id" style='text-align:center; line-height:179px;'>
		<img src="$icon" style="width:150px; vertical-align:center; margin-top:-38px" />
		<h2 style='text-align:left; font-size:28px'>$title</h2>
	</div>
</a>
end;
}

$user = getUser();
$server = getdbo('Server', 1);

if(param('mysansspacetiles'))
{
	echo <<<end
	<main class="container" style="max-width:970px;">
	<div style="line-height:1em; margin-right;10px; margin-top:-22px; margin-bottom:30px; margin-left:-5px">
	<h3 style='width:400px; max-height:80px; text-overflow:visible; margin-top:20px; margin-left:120px; position:absolute;color:rgb(0, 122, 187);' class='error h2'><a href='/my/samplesettings' style='color:rgb(0, 122, 187);text-decoration:none'>
end;
	echo $user->firstname;
	echo "<br>";
	echo $user->lastname;
	echo "</a>";
	echo "</h3>";
	echo "<a href='/my/samplesettings' style='color:rgb(0, 122, 187); text-decoration:none;'>";
	echo userImage($user, 100);
	echo "</a>";
	echo "</div>";
	


	if(controller()->rbac->globalAdmin())
	{
		echo "<h4 align='right' class='error' style='text-decoration:none; color:rgb(0, 122, 187); line-height:1em; margin-right:5px; text-align:right; margin-top:-83px; '><a href='/textbook/addstudentcode' style='text-decoration:none; color:rgb(0, 122, 187)' ><font style='border-bottom:1px blue dotted;'>Enroll in my course</font></a>    <a href='#' id='popuplink6'><em style='font-size:14px; color:#ec4546; verticle-align:middle;' class='fa fa-question-circle'></em></a></h4>";
		echo "<h4 align='right' class='error' style='text-decoration:none; color:rgb(0, 122, 187); line-height:1em; margin-right:5px; text-align:right; margin-bottom:22px'><a href='/textbook/addteachercourse' style='text-decoration:none; color:rgb(0, 122, 187)' ><font style='border-bottom:1px blue dotted;'>Create my course</font></a>    <a href='#' id='popuplink7'><em style='font-size:14px; color:#ec4546; verticle-align:middle;' class='fa fa-question-circle'></em></a></h4>";
		goto end;
	}

	if(controller()->rbac->globalStudent())
		echo "<h4 align='right' class='error' style='text-decoration:none; color:rgb(0, 122, 187); line-height:1em; margin-right:5px; text-align:right; margin-top:-50px; margin-bottom:22px'><a href='/textbook/addstudentcode' style='text-decoration:none; color:rgb(0, 122, 187)' ><font style='border-bottom:1px blue dotted;'>Enroll in my course</font></a></h4>";
	
	if(controller()->rbac->globalTeacher())
	{
		echo "<h4 align='right' class='error' style='text-decoration:none; color:rgb(0, 122, 187); line-height:1em; margin-right:5px; text-align:right; margin-top:-83px; '><a href='/textbook/addstudentcode' style='text-decoration:none; color:rgb(0, 122, 187)' ><font style='border-bottom:1px blue dotted;'>Enroll in my course</font></a>    <a href='#' id='popuplink6'><em style='font-size:14px; color:#ec4546; verticle-align:middle;' class='fa fa-question-circle'></em></a></h4>";
		echo "<h4 align='right' class='error' style='text-decoration:none; color:rgb(0, 122, 187); line-height:1em; margin-right:5px; text-align:right; margin-bottom:22px'><a href='/textbook/addteachercourse' style='text-decoration:none; color:rgb(0, 122, 187)' ><font style='border-bottom:1px blue dotted;'>Create my course</font></a>    <a href='#' id='popuplink7'><em style='font-size:14px; color:#ec4546; verticle-align:middle;' class='fa fa-question-circle'></em></a></h4>";
	}
end:
	echo "<div class='row'>";
	
	echo "<div class='col-md-7'>";
	$iconset = param('iconset');
	$icon = "/images/iconset/$iconset/mycourses.png";
	
	echo <<<end
	<div class="jumbotron" id="my-courses"><div class="row">
	<a href='/my/courses' style='color:white;'><div class="col-md-3" style="float:left"><img src="$icon" style='width:150px; margin-left:-30px' /></div></a><div style="float:right; font-size: 11pt; margin-top:15px"><ul>
end;
	
	$objects = objectList('mycourses');
	foreach($objects as $n=>$object)
		echo '<li>'.l($object->name, array('object/show', 'id'=>$object->id), array('style'=>'color: white')).'</li>';
	
	echo "</ul></div></div><h2 style='color:white; font-size:28px'><a href='/my/courses' style='color:white; text-decoration:none'>Courses</a>    <a href='#' id='popuplink'><em style='font-size:14px; color:white; verticle-align:middle;' class='fa fa-question-circle'></em></a></h2></div></div>";
	
	echo "<div class='col-md-5'>";
	echo <<<end
<a href="/my/reports">
	<div class="jumbotron" id="my-reports" style='text-align:center;'>
		<img src="/images/iconset/wayside/myreports.png" style="width:150px; vertical-align:center;" /></a>
		<h2 style='text-align:left; font-size:28px'><a href="/my/reports" style='color:white; text-decoration:none'>Grades</a>    <a href='#' id='popuplink2'><em style='font-size:14px; color:white; verticle-align:middle;' class='fa fa-question-circle'></em></a></h2>
	</div></div>

end;
	
	echo "</div> <!-- .row -->";
	echo "<div class='row'>";
	
	echo "<div class='col-md-4'>";
	echo <<<end
<a href="/my/folders">
	<div class="jumbotron" id="my-saved-work" style='text-align:center; height:220px; min-height:200px; margin-top:-10px;'>
		<img src="/images/iconset/wayside/myfolders1.png" style="width:150px; vertical-align:center; margin-top:-32px" /></a>
		<h2 style='text-align:left; font-size:28px'><a href="/my/folders" style='color:white; text-decoration:none'>My Work</a>    <a href='#' id='popuplink3'><em style='font-size:14px; color:white; verticle-align:middle;' class='fa fa-question-circle'></em></a></h2>
	</div>

end;
	echo "</div>";
	
	echo "<div class='col-md-4'>";
	echo <<<end
<a href="/pm">
	<div class="jumbotron" id="my-inbox" style='text-align:center; height:220px; min-height:200px; margin-top:-10px'>
		<img src="/images/iconset/wayside/myinbox.png" style="width:150px; vertical-align:center; margin-top:-32px" /></a>
		<h2 style='text-align:left; font-size:28px'><a href="/pm" style='color:white; text-decoration:none'>Inbox</a>    <a href='#' id='popuplink4'><em style='font-size:14px; color:white; verticle-align:middle;' class='fa fa-question-circle'></em></a></h2>
	</div>
</a>
end;
	echo "</div>";
	
	echo "<div class='col-md-4'>";
	echo <<<end
<a href="/my/samplesettings">
	<div class="jumbotron" id="settings" style='text-align:center; height:220px; min-height:200px; margin-top:-10px;'>
		<img src="/images/iconset/wayside/mysettings.png" style="width:150px; vertical-align:center; margin-top:-32px" /><a>
		<h2 style='text-align:left; font-size:28px'><a href="/my/samplesettings" style='color:white; text-decoration:none'>Settings</a>    <a href='#' id='popuplink5'><em style='font-size:14px; color:white; verticle-align:middle;' class='fa fa-question-circle'></em></a></h2>
	</div>
</a>
end;
	echo "</div>";
	
	echo "</div> <!-- .row -->";
	echo "</main>";
}

else
{
	echo "<h2>Welcome {$user->name}</h2>";
	echo "$server->mymessage";

	$commands = RbacCommandMyTable();
	
	echo "<div class='col1'>";
	foreach($commands as $n=>$id)
	{
		$command = getdbo('Command', $id);
		if(!controller()->rbac->globalAccess($command))
			continue;
	
	 	if($n == round(count($commands)/2))
	 		echo "</div><div class='col2'>";
	
		showMyItem($command);
	//	echo "<br>";
	}
	
	echo "</div>";
	echo "<div style='clear:both'></div>";
}

////////////////////////////////////////////////////////////////////

function showMyItem($command)
{
	$user = getUser();
	
	echo "<table cellspacing=0 cellpadding=0 width='100%' class='ssitem'
		style='padding: 6px; '><tr>";
	
	echo "<td width=10>";
	echo "</td>";
	
	echo "<td width=64 valign=top>";
	echo l($command->getImage(48), array($command->url));
	echo "</td>";
	
	if($command->id == SSPACE_COMMAND_MY_INBOX)
	{
		$count = getdbocount('PrivateMessage', "touserid=$user->id and not recv and not draft");
		//PrivateMessage::model()->count("touserid=$user->id and not recv and not draft");
		if($count) $command->name .= " ($count)";
	}
	
	echo "<td id='object_{$object->id}_parent' valign=top>";
	echo l($command->name, array($command->url));
	
	echo "<div class='small'>";
	$objects = null;
	
	switch($command->id)
	{
		case SSPACE_COMMAND_MY_COURSES:
			$objects = objectList('mycourses');
			break;

		case SSPACE_COMMAND_MY_RESOURCES:
			$objects = objectList('mylocations');
			break;

		case SSPACE_COMMAND_MY_FOLDERS:
			$objects = objectList('myfolders');
			break;

		case SSPACE_COMMAND_MY_REPORTS:
			$objects = objectList('mycourses');

			foreach($objects as $n=>$object)
			{
				if($n != 0) echo " &#9679; ";
				echo l(h($object->name), array('studentreport/', 'id'=>$object->id));
			}

			$objects = null;
			break;

		case SSPACE_COMMAND_MY_INBOX:
			echo l("Send a Message", array('pm/create'));

			break;

		case SSPACE_COMMAND_MY_FAVORITES:
			$objects = objectList('myfavorites');
			break;

		case SSPACE_COMMAND_MY_SETTINGS:
			break;
	}

	if($objects) foreach($objects as $n=>$object)
	{
		if($n != 0) echo " &#9679; ";
		echo l($object->name, array('object/show', 'id'=>$object->id));
	}
	echo "</div>";
	
	echo "</td></tr></table>";

}

echo <<<end
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('#popup').dialog('open'); })
	$('#popup2').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink2').click(function(){ $('#popup2').dialog('open'); })
	$('#popup3').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink3').click(function(){ $('#popup3').dialog('open'); })
	$('#popup4').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink4').click(function(){ $('#popup4').dialog('open'); })
	$('#popup5').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink5').click(function(){ $('#popup5').dialog('open'); })
	$('#popup6').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink6').click(function(){ $('#popup6').dialog('open'); })
	$('#popup7').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink7').click(function(){ $('#popup7').dialog('open'); })

})
</script>
<div id="popup" title="Courses">
	<p style='font-size:20px' autofocus>Teachers:<br>
	<span style='font-size:16px'>All of the courses you create will be listed in the Courses box.</span><p>
    <p style='font-size:20px' autofocus>Students<br>
	<span style='font-size:16px'>Your students will see every course they are enrolled in.</span></p>
    <p style='font-size:14px'>Click on the <b><u>course name</b></u> inside the Course box to enter.</p>
</div>
<div id="popup2" title="Grades">
	<p style='font-size:20px' autofocus>Teachers:<br>
	<span style='font-size:16px'>Review all of your students&#8217; work in Grades. Locate and assess completed essays and open-ended tasks. Provide both voice and written feedback on all of your students&#8217; recordings.<br>Assess each student&#8217;s performance by evaluating time spent working with audios and videos and attempts made on each quiz. Automatically graded activities, such as multiple choice, true or false, matching, and cloze, are recorded here as well.</span></p>
	<p style='font-size:20px'>Students:<br>
	<span style='font-size:16px'>Your students can view all of their graded or assessed activities and listen to or read your feedback.</span></p>
</div>
<div id="popup3" title="My Work">
    <p style='font-size:20px' autofocus>Think of My Work as your personal Learning Site hard drive<br>
	<span style='font-size:16px'>Store all of your files here. You can upload any file: doc, dox, pdf, mp3, mp4, etc.<br>The Learning Site will compile all of the files you upload directly into activities here as well, including your recordings.</span></p>
</div>
<div id="popup4" title="Inbox">
    <p style='font-size:20px' autofocus>Send messages to individual students, to small groups, or to your entire class.<br>
	<span style='font-size:16px'>When a class discussion requires a smaller setting than the classroom forum or when you need one-on-one communication with a student, move the discussion to the Learning Site&#8217;s Inbox.</span></p>
</div>
<div id="popup5" title="Settings">
    <p style='font-size:20px' autofocus>Visit your Settings immediately after registering for a Learning Site account!<br>
	<span style='font-size:16px'>You can update your username, name, email, password, school, phone, and street address.<br>Most importantly, you&#8217;ll come here to personalize your Learning Site with a picture of yourself.</span></p>
</div>
<div id="popup6" title="Enroll in my course">
    <p style='font-size:20px' autofocus>As a teacher, you will not see this link on your full Learning Site. Your students will click this link to enroll in the course you create. </p>
    <p style='font-size:16px'>Click on <b><u>Enroll in my Course</u></b> to see the process your students will use to enroll. </p>
</div>
<div id="popup7" title="Create my course">
    <p style='font-size:20px' autofocus>Follow this link to create your course.</p>
    <p style='font-size:16px'>Your students will not be able to see this link.</p>
</div>
end;




