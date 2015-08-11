<?php
require_once('announcement.js');
showAdminHeader(4);
$announcements = getdbolist('announcement');

echo "<table id='alertTable'><tr id='alertHeader'><th>Active Annoncements</th><th>Modify</th></tr>";
foreach($announcements as $a){
        if($a->status == 1){
            echo "
            <tr id='alertRow-".$a->id."' class='alertContainer'>
                <td class='alertContent' id='alertContent-".$a->id."'><div class='announcement-container'>$a->sampleContent</div></td>
                <td class='alertModify'>
                    <div id='view-".$a->id."' class='viewAlert' onclick='fullView(this)'>Full Screen View</div>
                    <div id='edit-".$a->id."' class='editAlert' onclick='editAlert(this)'>Edit Alert</div>
                    <div id='delete-".$a->id."' class='deleteAlert' onclick='expireAlert(this)'>Delete Alert</div>
                </td>
            </tr>";
        }    
    }
echo "
<tr id='addAlertButton'>
    <td colspan='2'><button id='extraAlert' onclick='extraAlert()'>Add Announcement</button></td>
</tr>
<tr id='addAlertContainer'>
        <td>
            <div id='messageAdd' class='addRow'>
                <div class='alertLabel'>Alert Message:</div>
                <textarea class='alertInput' id='newAlertMessage'></textarea>
            </div>
            <div id='linkAdd' class='addRow'>
                <div class='alertLabel'>Read More URL:</div>
                <input type='text' class='alertInput' id='newAlertURL' placeholder='ex. www.waysidepublishing.com/learningsite'/>
            </div>
        </td>
        <td class='saveContainer'>
            <button id='addAlertSave' onclick='addAlert(this)'>Save Announcement</button>
        </td>
</tr>
</table>

<div id='popup' title='Full View Announcement'>
    <div id='fullViewer' autofocus></div>
</div>";