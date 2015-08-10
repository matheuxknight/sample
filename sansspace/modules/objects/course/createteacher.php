<?php

$parent = getdbo('Object', $_GET['id']);

echo "<main class='error'><h2>About this course</h2>";
echo "</main>";

echo $this->renderPartial('_formteacher', array('course'=>$course, 'update'=>false));






