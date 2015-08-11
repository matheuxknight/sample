
<script type="text/javascript">
$(document).ready(function() 
{
	$('#popup').dialog({ autoOpen: false, modal: true, width: '100%', dialogClass:'modalpopup' })
	$('.viewAlert').click(function(){ $('div#popup').dialog('open'); });
	$('.ui-dialog .ui-dialog-content').css('font-size','1em');
	var closers = document.getElementsByClassName('close-holder');
		for(var i = 0; i < closers.length; i++){
            var id = closers[i].id.substr(20);
            var height = $('#announcement-text-sample-'+id).height();
            $(closers[i]).css('line-height', height+'px');
		}	
});
 
 function expireAlert(clicked){
	    var id = clicked.id.substr(7);
		$.ajax({
            url: 'http://learningsite.waysidepublishing.com/admin/announcementSave',
			type: 'POST',
			data: { num: id, action: 1, message: "", more: "" },
			cache: false,
			success: function(){
			if(confirm("Announcement has been successfully expired")){
				window.location.reload();}
			}
        });}
    function saveAlert(clicked){
	    var id = clicked.id.substr(14);
        var x = document.getElementById('editAlertMessage-'+id).value;
        var y = document.getElementById('editAlertURL-'+id).value;
		$.ajax({
            url: 'http://learningsite.waysidepublishing.com/admin/announcementSave',
			type: 'POST',
			data: { num: id, action: 2, message: x, more: y },
			cache: false,
			success: function(){
			if(confirm("Announcement has been successfully edited!")){
				window.location.reload();}
			}
        });}  
    function addAlert(clicked){
	    var id = 0;
		var x = document.getElementById('newAlertMessage').value;
		var y = document.getElementById('newAlertURL').value;
		$.ajax({
            url: 'http://learningsite.waysidepublishing.com/admin/announcementSave',
			type: 'POST',
			data: { num: id, action: 3, message: x, more: y },
			cache: false,
			success: function(){
			if(confirm("Announcement has been successfully added!")){
				window.location.reload();}
			}
        });} 
    function editAlert(clicked){
		var id = clicked.id.substr(5);
		var container = document.getElementById('alertRow-'+id);
        var message = document.getElementById('simple-message-sample-0'+id).innerHTML;
		var more = "";
		if(document.getElementById('announcement-url-sample-0'+id))
			more = document.getElementById('announcement-url-sample-0'+id).getAttribute('href');
        container.innerHTML = "<td><div id='messageEdit-"+id+"' class='addRow'><div class='alertLabel'>Alert Message:</div><textarea class='alertInput' id='editAlertMessage-"+id+"'>"+message+"</textarea></div><div id='linkEdit-"+id+"' class='addRow'><div class='alertLabel'>Read More URL:</div><input type='text' class='alertInput' id='editAlertURL-"+id+"' value='"+more+"' /></div></td><td class='saveContainer'><button id='editAlertSave-"+id+"' onclick='saveAlert(this)'>Save Announcement</button></td>";
    }
    function fullView(clicked){
		var y = clicked.parentNode.parentNode.childNodes[1].innerHTML;
        $('#fullViewer').html(y);
    }
    function extraAlert(){
        $('#extraAlert').hide();
        $('#addAlertContainer').show();
    }
</script>