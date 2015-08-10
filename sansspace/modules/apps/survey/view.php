<?php

showNavigationBar($object->parent);
showObjectHeader($object);
showObjectMenu($object);

echo '<br>';
$flashvars = "surveyid=$object->id";
ShowApplication($flashvars, 'recorder', 'sansmediad', 480);

echo <<<END

<div id="htmlcontainer"></div>

<style>
#htmlcontainer
{
	position: absolute;
	top: 0px;
	left: 0px;
	right: 20px;
	height: auto;
	padding: 10px;
	display: block;
	z-order: 100;
	overflow-y: auto;
	display: none;
/*	background-color: yellow;*/
}

</style>

<script>

var adjust_timer;
var htmlcontainer_height;

function setSurveyFlashHeight(height)
{
	getFlashObject('sansmediad').height = height;
//	$(window).scrollTop(0);
}

function loadHtmlContainer(url)
{
	var pos = $('#sansmediad').position();
	pos.top += 24;
		
	$('#htmlcontainer').show();
	$('#htmlcontainer').offset(pos);
		
	$.get(url, '', htmlcontainer_ready);
}

function htmlcontainer_adjust_height()
{
	var h = $('#htmlcontainer').height();
	if(htmlcontainer_height != h)
		getFlashObject("sansmediad").reportSurveyHtmlHeight(h, $(window).height());
}
		
function htmlcontainer_ready(data)
{
	$('#htmlcontainer').html(data);

	htmlcontainer_height = $('#htmlcontainer').height();
	getFlashObject("sansmediad").reportSurveyHtmlHeight(htmlcontainer_height, $(window).height());

	clearTimeout(adjust_timer);
	adjust_timer = setTimeout(htmlcontainer_adjust_height, 4000);
}

///////////////////////////////////////////////////////////////////////////////////////

function showSurveyHtml()
{
	$('#htmlcontainer').show();
}

function hideSurveyHtml()
{
	$('#htmlcontainer').hide();
}

// special case for text answer

function saveSurveyAnswerText(surveyid)
{
	var obj = $('#survey_answer_text');
	var value = $('#survey_answer_text').elrte('val');

	value = encodeURIComponent(value);
	$.get('/survey/saveanswer?id='+surveyid+'&answertext='+value, '', saveSurveyAnswerText_ready);
}

function saveSurveyAnswerText_ready(data)
{
	getFlashObject("sansmediad").reportSurveyAnswerText();
}
		
//////////////////////////////////////////////////

function getFlashObject(movieName)
{
	var isIE = navigator.appName.indexOf('Microsoft') != -1;
	return (isIE) ? window[movieName] : document[movieName];
}

</script>

END;






