<?php


echo "<h2>Choose my course    <a href='#' id='popuplink'><em style='color:#ec4546; font-size:16px; verticle-align:middle' class='fa fa-question-circle'></em></a></h2>";
echo "Courses available for this code: <b>$code->code</b><br><br>";

echo CUFHtml::label('Search: ', '').' ';
echo CUFHtml::textField('course_search_input', '', array('class'=>'textInput', 'maxlength'=>200));

echo "(Course, Teacher, School, City, State, ZIP Code, or Country)<br><br>";

echo <<<end

<div id='show_results'></div>

<script>
$(function()
{
	$.get('/textbook/choosecourse_results?code=$code->code', '', function(data)
	{
		$('#show_results').html(data);
	});

	$('#course_search_input').bind('keyup', function(event)
	{
		var searchstring = $('#course_search_input').val();
		$.get('/textbook/choosecourse_results?code=$code->code&search='+searchstring, '', function(data)
		{
			$('#show_results').html(data);
		});
	})
});

function doenrollment(code, courseid, coursename, teachername)
{
	if(confirm("Are you sure you want to enroll into this course? "+coursename+", Teacher "+teachername+". This action cannot be undone."))
		window.location.href='/my';
}

</script>


</script>
<script type="text/javascript">
	$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '40%', dialogClass:'modalpopup' })
	$('#popuplink').click(function(){ $('div#popup').dialog('open'); });
})
</script>
<div id="popup" title="Choose my course">
	<p style='font-size:20px' autofocus>Students will use this search to find you.<br>
	<span style='font-size:16px'>Students can search by course name, teacher, school, city, state, ZIP code, or country. Once students click on your course, they must confirm their choice. Once the choice is confirmed, they have completed enrollment, and the Student Access Code they used will no longer be valid.</span></p>
	<p style='font-size:16px' autofocus><b>IMPORTANT NOTE</b>: As the teacher, you choose the start date of your course. Your students will not see your course on this list or be able to enroll until the start date. </p>    
	<p style='font-size:16px'>Click on the <b><u>Learning Site logo</u></b> in the upper left hand corner of the screen to return to the My Learning Site page.<br><b>OR</b><br>
	Click on a <b><u>course name</u></b> and simulate enrollment in a course, which will then return to the My Learning Site page.</p>
</div>

end;







