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
	
	if($server && !empty($server->footer) && param('theme') == 'wayside')
		echo $server->footer;
	
	else
	{
		echo "<div id='footer'>";
		echo $server->footer;
		
		$year = date("Y", time());
		echo "<p>&copy;2006-$year SANS Inc. All Rights Reserved &#9679; SANSSpace&#8482; ";
	
		$version = getdbosql('DatabaseVersion', "1");
		if($version) echo "$version->version &#9679; $version->updated ";
	
		echo "</p>";
		echo "</div><!-- footer -->";
	}
}

function showAnnouncement($a){
    $count = 0;
	$announcements = getdbolist('announcement');
	foreach($announcements as $announcement){
        $show = true;
        foreach($a as $b){
            if($b == $announcement->id || $announcement->status == 0){
                $show = false;}
        } 
        if($show){
            if($count > 0)
                echo "<div id='divider-".$announcement->id."' class='announce-divider'></div>";
            if($count == 0){
                echo "<div id='announcement' class='announcement-container'>";
                $endDiv = true;}
            echo $announcement->content;
            $count++;
        }
    }
    if($endDiv){echo "</div>";}
    echo "<script>function removeAlert(alert){
                \$(alert.parentNode.parentNode).slideUp(500);
                var id = alert.id.substr(13);
                if(\$('.announce-divider:visible').length === 0)
                    \$('#announcement').delay(500).slideUp();
                if(document.getElementById('divider-'+id))
                    \$('#divider-'+id).delay(500).slideUp();
                else{
                    var dividers = document.getElementsByClassName('announce-divider');
                    for(var i = 0; i < dividers.length; i++){
                        if(\$(dividers[i]).is(':visible')){
                            \$(dividers[i]).delay(500).slideUp();
                            break;}}}  
                \$.ajax({
				    url: 'http://learningsite.waysidepublishing.com/site/announcementRemove',
				    type: 'POST',
				    data: { num: id },
				    cache: false,
				    success: { }
			     });
            }
            \$('.close-holder').hover(function(){
                var id = \$(this).attr('id').match(/[\d]+$/);
                \$('#announce-overlay-'+id).css('opacity', '.4');}, function(){
                var id = \$(this).attr('id').match(/[\d]+$/);
                \$('#announce-overlay-'+id).css('opacity', '0');});
             \$(window).bind('load', function() {
                \$('.full-announce').each(function(){
                    var id = \$(this).attr('id').match(/[\d]+$/);
                    var height = \$(this).height();
                    var width = \$(this).width();
                    \$('#close-holder-'+id).css('line-height', height+'px');
                    \$('#announce-overlay-'+id).width(width).height(height);});
                });
             \$(window).resize(function(){
                \$('.full-announce').each(function(){
                    var id = \$(this).attr('id').match(/[\d]+$/);
                    var height = \$(this).height();
                    var width = \$(this).width();
                    \$('#close-holder-'+id).css('line-height', height+'px');
                    \$('#announce-overlay-'+id).width(width).height(height);});
                    });
			</script>";
}




