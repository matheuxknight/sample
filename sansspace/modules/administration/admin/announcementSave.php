<?php

echo announcementSave(trim($_REQUEST['num']),trim($_REQUEST['action']),trim($_REQUEST['message']),trim($_REQUEST['more']));

function announcementSave($num,$action,$message,$more){
    
    switch($action){
        case 1:
                $announcements = getdbolist('announcement');
                foreach($announcements as $a){
                    if($num == $a->id){
                        $a->status = 0;
                        $a->save();
                    }    
                }
                break;
        case 2:
                $announcements = getdbolist('announcement');
                foreach($announcements as $a){
                    if($num == $a->id){
                        $a->message = $message;
                        $a->more = $more;
                        $a->save();
                    } 
                }    
                break;
        case 3: 
                $announcement = new announcement;
                $announcement->message = $message;
                $announcement->more = $more;
                $announcement->save();
                break;
    }    
}