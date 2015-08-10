<?php

showNavigationBar($object->parent);
showObjectHeader($object);
showObjectMenu($object);

echo '<br>';
$flashvars = "flashcardid=$object->id";
ShowApplication($flashvars, 'recorder', 'sansmediad', 480);







