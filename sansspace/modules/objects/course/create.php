<?php

$parent = getdbo('Object', $_GET['id']);
showNavigationBar($parent);
showObjectHeader($parent);
showObjectMenu($parent);

echo "<h2>New Course</h2>";
$this->widget('UniForm');

echo CUFHtml::beginForm('', 'post', array('enctype'=>'multipart/form-data'));
echo CUFHtml::errorSummary($course);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

InitMenuTabs('#properties-tabs');

echo "<div id='properties-tabs' style='display:none;'><ul>";
echo "<li><a href='#properties-course'>Course</a></li>";
//echo "<li><a href='#properties-categories'>Categories</a></li>";
echo "<li><a href='#properties-icon'>Icon</a></li>";
echo "<li><a href='#properties-date'>Date</a></li>";
echo "</ul><br>";

courseShowProperties($course, false);
//objectShowPropertiesCategories($course, false);
objectShowPropertiesIcon($course, false);
objectShowPropertiesDate($course, false);

echo CUFHtml::closeTag('fieldset');
showSubmitButton('Save');
echo CUFHtml::endForm();

	