<?php

$this->pageTitle = "$object->name";
include '/sansspace/ui/lib/pageheader.php';

$sessionid = session_id();

echo <<<END
<style>
html
{
}

body
{
	margin: 0;
	padding: 0;
}

#htmlcontainer
{
	position: absolute;
	top: 34px;
	left: 0px;
	right: 0px;
	padding: 10px;
	display: block;
	z-order: 100;
	overflow-y: auto;
/*	background-color: yellow; */
}

</style>

<script>

var adjust_timer;
var htmlcontainer_height;

function setQuizFlashHeight(height)
{
	getFlashObject('sansmediad').height = height;
	$(window).scrollTop(0);
}

function loadHtmlContainer(url)
{
	$.get(url, '', htmlcontainer_ready);
}
		
function htmlcontainer_adjust_height()
{
	var h = $('#htmlcontainer').height();
	if(htmlcontainer_height != h)
	{
		getFlashObject("sansmediad").reportQuizHtmlHeight(h, $(window).height());
		htmlcontainer_height = h;
	}
	
	clearTimeout(adjust_timer);
	adjust_timer = setTimeout(htmlcontainer_adjust_height, 1000);
}

function htmlcontainer_ready(data)
{
	$('#htmlcontainer').html(data);

	htmlcontainer_height = $('#htmlcontainer').height();
	getFlashObject("sansmediad").reportQuizHtmlHeight(htmlcontainer_height, $(window).height());

	clearTimeout(adjust_timer);
	adjust_timer = setTimeout(htmlcontainer_adjust_height, 1000);
}

///////////////////////////////////////////////////////////////////////////////////////

function showQuizHtml()
{
	$('#htmlcontainer').show();
}

function hideQuizHtml()
{
	$('#htmlcontainer').hide();
}

// special case for longtext answer

function saveQuizAnswerLongText(attemptid, questionid)
{
	var obj = $('#quiz_answer_longtext');
	var value = $('#quiz_answer_longtext').elrte('val');

	value = encodeURIComponent(value);

	$.get('/quiz/saveanswer?attemptid='+attemptid+'&questionid='+questionid+'&answerlong='+value, 
		'', saveQuizAnswerLongText_ready);
}

function saveQuizAnswerLongText_ready(data)
{
	getFlashObject("sansmediad").reportQuizAnswerLongText();
}
		
//////////////////////////////////////////////////

function getFlashObject(movieName)
{
	var isIE = navigator.appName.indexOf('Microsoft') != -1;
	return (isIE) ? window[movieName] : document[movieName];
}

/////////////////////////////////////////////////////////////////////////

function initializeHtml5Upload()
{
	$("#upload_field").html5_upload({

		method: 'post',
		sendBoundary: window.FormData || $.browser.mozilla,
		
		url: function(number) {
			return "/upload.php?phpsessid=$sessionid";
		},
		
		onStart: function(event, total) {
			$("#progress_report").show();
			return true;
		},
		
		setName: function(text) {
			$("#progress_report_name").html("<b>"+text+"</b>");
		},
	
		setProgress: function(val) {
			$("#progress_report_bar").css('width', Math.ceil(val*100)+"%");
		},
		
		onFinish: function(event, total) {
			window.location = window.location+'&complete';
		},
		
		onError: function(event, name, error) {
			alert('Error while uploading file ' + name);
		}
	});
}

</script>

</head>
<body>
END;


$getflash = mainimg('getflash.jpg');
$flashvars = "quizid=$object->id";

ShowApplication($flashvars, 'recorder', 'sansmediad', '500', false);
JavascriptReady("RightClick.init('sansmediad');");

echo "<div id='htmlcontainer'></div></body>";









