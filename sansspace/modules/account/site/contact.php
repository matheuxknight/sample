<?php

$this->pageTitle=Yii::app()->name . ' Contact Us';

echo "<main class='error'>";
echo "<h2>Contact Us</h2>";

echo "<p class='error' style='width:54%'>Use the form below to send us a message with any comments, questions, or concerns and we will be sure to get back to you within 1-2 business days.</p>";
echo "<p class='error' style='width:54%'><b>Provide all necessary information for support to assist with your query. Student Access Codes, usernames, times, and specific error messages will help us cut down on the time it will take for us to help you.</b></p>";
echo "<p class='error' style='width:54%'>Be sure to check your email for a confirmation of your message sent, as well as a response from Wayside Support.</p>";

$this->widget('UniForm');

echo CUFHtml::beginForm();
echo CUFHtml::errorSummary($contact);
echo CUFHtml::openTag('fieldset', array('class'=>'inlineLabels'));

echo CUFHtml::openActiveCtrlHolder($contact, 'name');
echo CUFHtml::activeLabelEx($contact,'name');
echo CUFHtml::activeTextField($contact,'name', array('maxlength'=>200));
echo CUFHtml::closeCtrlHolder();

//echo CUFHtml::openActiveCtrlHolder($user, 'logon');
//echo CUFHtml::activeLabelEx($user,'logon');
//echo CUFHtml::activeTextField($user,'name', array('maxlength'=>200));
//echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($contact, 'email');
echo CUFHtml::activeLabelEx($contact,'email');
echo CUFHtml::activeTextField($contact,'email', array('maxlength'=>200));
echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($contact, 'subject');
echo CUFHtml::activeLabelEx($contact,'subject');
echo CUFHtml::activeTextField($contact,'subject', array('maxlength'=>200));
echo CUFHtml::closeCtrlHolder();

//echo CUFHtml::openActiveCtrlHolder($contact, 'body');
//echo CUFHtml::activeTextArea($contact,'body', array('maxlength'=>200));
//showAttributeEditor($contact, 'body');
//echo CUFHtml::closeCtrlHolder();

echo CUFHtml::openActiveCtrlHolder($contact, 'body');
echo CUFHtml::activeLabelEx($contact,'body', array('label'=>Message));
echo CHtml::textArea('ContactForm[body]', $contact->body, 
	array('style'=>'width: 37%;height: 12em;'));
echo CUFHtml::closeCtrlHolder();


echo CUFHtml::closeTag('fieldset');
showSubmitButton('Submit');
echo CUFHtml::endForm();

