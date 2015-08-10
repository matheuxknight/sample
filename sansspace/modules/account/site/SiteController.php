<?php

require_once('siteview.php');

class SiteController extends CommonController
{

	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xffffff,
			),
		);
	}

	public function actionAnnouncement()
	{
		$this->render('announcement');
	}

	public function actionTerms()
	{
		$this->render('terms');
	}

	public function actionIndex()
	{
		user()->setReturnUrl(array('my/'));
		$this->render('index');
	}

	public function actionSearch()
	{
		$object = getdbo('Object', CMDB_OBJECTROOT_ID);
		$this->render('search', array('object'=>$object));
	}
	
	public function actionTest()
	{
		$this->render('test');
	}

	public function actionLogin()
	{
		$this->render('login');
	}

	public function actionAdmin()
	{
		$this->render('admin');
	}

	public function actionDenied()
	{
		$this->render('denied');
	}

	public function actionLogout()
	{
		$this->identity->logout();
		$this->redirect(array('site/'));
	}

	public function actionRegister()
	{
		User::$isteacher = true;
		
		$form = new RegisterForm;
		$form->user = new User;
		
		// collect user input data
		if(isset($_POST['RegisterForm']))
		{
			$form->attributes = $_POST['RegisterForm'];
			$form->user->attributes = $_POST['User'];

			$form->user->domainid = CMDB_DEFAULT_DOMAINID;
			$form->user->status = CMDB_USERSTATUS_OFFLINE;
			$form->user->enable = true;
			$form->user->updated = now();
			$form->user->created = now();
			$form->user->accessed = now();
			$form->user->used = now();
			$form->user->startdate = nowDate();
			$form->user->enddate = nowDate();
			$form->user->name = "$form->firstname $form->lastname";
			
			User::$isteacher = $form->register_role == 'teacher';

			if($form->user->validate() && $form->validate())
			{
				
				
				$form->user->password = md5($form->user->password);
				$form->user->save();
				
				debuglog("new user saved {$form->user->id}");
				if($form->register_role == 'student')
				{
					$e = new UserEnrollment;
					$e->userid = $form->user->id;
					$e->roleid = SSPACE_ROLE_STUDENT;
					$e->save();
					
					user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account succesfully created! Please login below.</div>");
					$this->redirect(array('site/login'));
				}
				
				else if($form->register_role == 'teacher')
				{
					$e = new UserEnrollment;
					$e->userid = $form->user->id;
					$e->roleid = SSPACE_ROLE_TEACHER;
					$e->save();
					
					user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account succesfully created! Please login below.</div>");
					$this->redirect(array('site/login'));
				}

				user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account succesfully created! Please login below.</div>");
				$this->redirect(array('site/login'));
            }

            $form->addErrors($form->user->getErrors());
		}
		
		$this->render('register', array('form'=>$form));
	}

	public function actionForgot()
	{
		$form = new ForgotForm;
		if(isset($_POST['ForgotForm']))
		{
			$form->attributes = $_POST['ForgotForm'];
			
			$form->name = addslashes($form->name);
			$form->email = addslashes($form->email);
						
			if($form->validate())
			{
				$user = null;
				
				if(!empty($form->name))
					$user = getdbosql('User', "logon='$form->name'");
					//User::model()->find("logon='$form->name'");
				
				else if(!empty($form->email))
					$user = getdbosql('User', "email='$form->email'");
					//User::model()->find("email='$form->email'");
					
				if(!$user)
				{
					user()->setFlash('error', 'Account not found.');
					$this->render('forgot', array('form'=>$form));
					return;
				}
					
				
				
				if($user->domainid != CMDB_DEFAULT_DOMAINID && $user->domain->ldapenable)
				{
					user()->setFlash('error', 'This account is associated with the '. $user->domain->name . ' domain. You cannot change your password here.');
				//	debuglog($user->domain->name);
					$this->render('forgot', array('form'=>$form));
					return;
				}
				
				
				$user->guid = base64_encode(uniqid('5tr8').uniqid('fr6e'));
				$user->save();
				
				$title = param('title');
				$servername = getFullServerName();
				
				mailex('', SANSSPACE_SMTP_EMAIL, $user->email,
					'Learning Site Password Reset', 

				"<img src='http://162.249.105.83/images/wayside/logo2.png' alt='logo'  class='alignnone size-full wp-image-533'><br><br>
				<div style='margin-left:3%; height:100%; font-size:16px; color:#555555; line-height:1.1em; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''><p><div style='font-size:34px; color:#007ABB; font-weight:500; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''>Hello $user->name!</div><br><br>We received your request to change your password for your Learning Site account and promptly sent this message.<br>				
				To change your password just click on the link below:<br><br>
				<a href='{$servername}/index.php?r=site/password&guid=$user->guid'>Click to reset password</a><br>
				After you have clicked the link above, simply choose a new password and save.<br><br>
				If you have opted to reset your password in error, or you had not done so at all, please disregard this message.<br>
				Have any questions or concerns? Don't hesitate to contact us at <a href='mailto:support@waysidepublishing.com'>support@waysidepublishing.com.</a>
				<p>Sincerely,<br>
				Wayside Publishing Support</p>
				<div style='height:70px'>
				<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0
				style='border-collapse:collapse;mso-table-layout-alt:fixed;border:none;
				mso-yfti-tbllook:1184;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
				none;mso-border-insidev:none'>
				<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
				height:10px'>
				<td width=31 style='width:23.4pt;border-top:dotted windowtext 1.0pt;
				border-left:none;border-bottom:none;border-right:dotted windowtext 1.0pt;
				mso-border-top-alt:dotted windowtext .5pt;mso-border-right-alt:dotted windowtext .5pt;
				padding:0in 5.4pt 0in 5.4pt;height:15px'>
				<p class=MsoNormal><span style='mso-fareast-font-family:Calibri'><a
				href='https://www.facebook.com/WaysidePublishing'><span style='mso-fareast-font-family:
				'Times New Roman';color:windowtext;mso-no-proof:yes;text-decoration:none;
				text-underline:none'><img border=0 width=17 height=16 id='_x0000_i1025'
				src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/FacebookLogo1.png'
				alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/FacebookLogo1.png'></span></a></span><span
				style='mso-fareast-font-family:'Times New Roman''><o:p></o:p></span><span style='mso-fareast-font-family:Calibri'><a
				href='https://twitter.com/WaysidePublish'><span style='mso-fareast-font-family:
				'Times New Roman';color:windowtext;mso-no-proof:yes;text-decoration:none;
				text-underline:none'><img border=0 width=17 height=16 id='_x0000_i1026'
				src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/TwitterLogo1.png'
				alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/TwitterLogo1.png'></span></a></span><span
				style='mso-fareast-font-family:'Times New Roman''>
				<o:p></o:p>
				</span></p>
				</td>
				<td width=48 style='width:.5in;border:none;border-top:dotted windowtext 1.0pt;
				mso-border-left-alt:dotted windowtext .5pt;mso-border-top-alt:dotted windowtext .5pt;
				mso-border-left-alt:dotted windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
				height:15px'>
				<p class=MsoNormal align=center style='text-align:center'><span
				style='mso-fareast-font-family:'Times New Roman';mso-no-proof:yes'><img
				border=0 width=34 height=43 id='_x0000_i1027'
				src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/WaysideLogoSignature1.png'
				alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/WaysideLogoSignature1.png'></span><span
				style='mso-fareast-font-family:'Times New Roman''><o:p></o:p></span></p>
				</td>
				<td width=451 style='width:338.1pt;border:none;border-top:dotted windowtext 1.0pt;
				mso-border-top-alt:dotted windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
				height:15px'>
				<p class=MsoAutoSig style='line-height:115%'><span style='mso-fareast-font-family:
				Calibri'><a href='WaysidePublishing.com'><span style='font-size:10.0pt;
				mso-bidi-font-size:12.0pt;line-height:115%;font-family:'Helvetica','sans-serif''>WaysidePublishing.com</span></a></span><span
				style='font-size:10.0pt;mso-bidi-font-size:12.0pt;line-height:115%;
				font-family:'Helvetica','sans-serif';mso-fareast-font-family:'Times New Roman''><o:p></o:p>
				<br>
				</span><span style='font-size:8.0pt;mso-bidi-font-size:9.0pt;
				font-family:'Helvetica','sans-serif';mso-fareast-font-family:Calibri;
				color:#888888'>T/F (888) 302-2519</span><span style='font-size:8.0pt;
				mso-bidi-font-size:9.0pt;font-family:'Helvetica','sans-serif';mso-fareast-font-family:
				'Times New Roman';color:#888888'>
				<o:p></o:p>
				<br>
				</span><b style='mso-bidi-font-weight:normal'><i
				style='mso-bidi-font-style:normal'><span style='font-size:9.0pt;mso-bidi-font-size:
				10.0pt;font-family:'Georgia','serif';mso-fareast-font-family:Calibri;
				mso-bidi-font-family:Helvetica;color:#6CB33F'>A World of Learning Opportunities</span></i></b><span
				style='mso-fareast-font-family:'Times New Roman''>
				<o:p></o:p>
				</span></p>
				</td>
				</tr>
				</table>
				</div>
				</div>
				</div>");

				user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>An email for password reset has been sent to the email address associated with your account.</div>");
				
				$this->redirect(array('site/login'));
			}
		}
		
		$this->render('forgot', array('form'=>$form));
	}

	public function actionPassword()
	{
		$form = new PasswordForm;
		if(!isset($_GET['guid']))
		{
		//	user()->setFlash('error', 'Wrong request.');
		}
		$user = getdbosql('User', "guid='{$_GET['guid']}'");
		//User::model()->find("guid='{$_GET['guid']}'");
		if(!$user)
		{
		//	user()->setFlash('error', 'Wrong request.');
			$this->redirect(array('site/index'));
		}
		
		if(isset($_POST['PasswordForm']))
		{			
			$form->attributes = $_POST['PasswordForm'];
			if($form->validate())
			{
				$user->password = md5($form->password);
				$user->save();
				
				user()->setFlash('message', 'Your password has been changed.');
				$this->redirect(array('site/login'));
			}
		}
		$this->render('password', array('form'=>$form, 'user'=>$user));
	}
	
	public function actionContact()
	{
		$contact = new ContactForm;
		
		
		
		if(isset($_POST['ContactForm']))
		{
			$contact->attributes = $_POST['ContactForm'];
			if($contact->validate())
			{
				mailex('', SANSSPACE_SMTP_EMAIL, $contact->email,
					'Contact Us Form Received - Sample Learning Site', 
					"<img src='http://162.249.105.83/contents/17894.png' alt='logo'  class='alignnone size-full wp-image-533'><br><br>
					<div style='margin-left:3%; height:100%; font-size:16px; color:#555555; line-height:1.1em; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''><p><div style='font-size:34px; color:#007ABB; font-weight:500; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''>Hi $contact->name,</div>
					<br>We’ve received your email and will respond as soon as possible. Fastest response time is from 8:30 a.m. - 4:30 p.m. EST Monday through Friday.<br>
					<p>Sincerely,<br>
					Wayside Publishing Support</p>
					<div style='height:70px'>
					<table class=MsoTableGrid border=0 cellspacing=0 cellpadding=0
					style='border-collapse:collapse;mso-table-layout-alt:fixed;border:none;
					mso-yfti-tbllook:1184;mso-padding-alt:0in 5.4pt 0in 5.4pt;mso-border-insideh:
					none;mso-border-insidev:none'>
					<tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes;
					height:10px'>
					<td width=31 style='width:23.4pt;border-top:dotted windowtext 1.0pt;
					border-left:none;border-bottom:none;border-right:dotted windowtext 1.0pt;
					mso-border-top-alt:dotted windowtext .5pt;mso-border-right-alt:dotted windowtext .5pt;
					padding:0in 5.4pt 0in 5.4pt;height:15px'>
					<p class=MsoNormal><span style='mso-fareast-font-family:Calibri'><a
					href='https://www.facebook.com/WaysidePublishing'><span style='mso-fareast-font-family:
					'Times New Roman';color:windowtext;mso-no-proof:yes;text-decoration:none;
					text-underline:none'><img border=0 width=17 height=16 id='_x0000_i1025'
					src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/FacebookLogo1.png'
					alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/FacebookLogo1.png'></span></a></span><span
					style='mso-fareast-font-family:'Times New Roman''><o:p></o:p></span><span style='mso-fareast-font-family:Calibri'><a
					href='https://twitter.com/WaysidePublish'><span style='mso-fareast-font-family:
					'Times New Roman';color:windowtext;mso-no-proof:yes;text-decoration:none;
					text-underline:none'><img border=0 width=17 height=16 id='_x0000_i1026'
					src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/TwitterLogo1.png'
					alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/TwitterLogo1.png'></span></a></span><span
					style='mso-fareast-font-family:'Times New Roman''>
					<o:p></o:p>
					</span></p>
					</td>
					<td width=48 style='width:.5in;border:none;border-top:dotted windowtext 1.0pt;
					mso-border-left-alt:dotted windowtext .5pt;mso-border-top-alt:dotted windowtext .5pt;
					mso-border-left-alt:dotted windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
					height:15px'>
					<p class=MsoNormal align=center style='text-align:center'><span
					style='mso-fareast-font-family:'Times New Roman';mso-no-proof:yes'><img
					border=0 width=34 height=43 id='_x0000_i1027'
					src='http://www.waysidepublishing.com/wp-content/uploads/2014/05/WaysideLogoSignature1.png'
					alt='http://www.waysidepublishing.com/wp-content/uploads/2014/05/WaysideLogoSignature1.png'></span><span
					style='mso-fareast-font-family:'Times New Roman''><o:p></o:p></span></p>
					</td>
					<td width=451 style='width:338.1pt;border:none;border-top:dotted windowtext 1.0pt;
					mso-border-top-alt:dotted windowtext .5pt;padding:0in 5.4pt 0in 5.4pt;
					height:15px'>
					<p class=MsoAutoSig style='line-height:100%'><span style='mso-fareast-font-family:
					Calibri'><a href='WaysidePublishing.com'><span style='font-size:10.0pt;
					mso-bidi-font-size:12.0pt;line-height:100%;font-family:'Helvetica','sans-serif''>WaysidePublishing.com</span></a></span><span
					style='font-size:10.0pt;mso-bidi-font-size:12.0pt;line-height:100%;
					font-family:'Helvetica','sans-serif';mso-fareast-font-family:'Times New Roman''><o:p></o:p>
					<br>
					</span><span style='font-size:8.0pt;mso-bidi-font-size:9.0pt;
					font-family:'Helvetica','sans-serif';mso-fareast-font-family:Calibri;
					color:#888888'>T/F (888) 302-2519</span><span style='font-size:8.0pt;
					mso-bidi-font-size:9.0pt;font-family:'Helvetica','sans-serif';mso-fareast-font-family:
					'Times New Roman';color:#888888'>
					<o:p></o:p>
					<br>
					</span><b style='mso-bidi-font-weight:normal'><i
					style='mso-bidi-font-style:normal'><span style='font-size:9.0pt;mso-bidi-font-size:
					10.0pt;font-family:'Georgia','serif';mso-fareast-font-family:Calibri;
					mso-bidi-font-family:Helvetica;'><font style-'color:rgb(108,179,63)'>A World of Learning Opportunities</font></span></i></b><span
					style='mso-fareast-font-family:'Times New Roman''>
					<o:p></o:p>
					</span></p>
					</td>
					</tr>
					</table>
					</div>
					</div>
					<br><br><div style='color:#999999; border-top:1px dashed #999999'><br>
					Subject: $contact->subject<br><br>
					$contact->body
					</div>
					</div>");
					
				$c = new Contact;
				$c->name = $contact->name;
				$c->email = $contact->email;
				$c->subject = $contact->subject;
				$c->doctext = $contact->body;
				$c->created = now();
				$c->save();
								
				user()->setFlash('message', 'We have received your message and will respond within 1-2 business days to the email associated with this account.');
				$this->redirect(array('my/'));
			}
		}
		
		$this->render('contact',array('contact'=>$contact));
	}
	
	////////////////////////////////////////////////////////////////////////////
	
	public function actionInstallAndroid()
	{
		$basename = 'sansspace.apk';
		$filename = SANSSPACE_HTDOCS.'/extensions/players/sansspace.apk';
		
		header('Content-Type: application/vnd.android.package-archive');
		header("Content-Disposition: attachment; filename=$basename");
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		ob_clean();
		flush();
		readfile($filename);

		die;
		
	}
	
	public function actionCas()
	{
		$this->identity->initializeCAS();

	//	debuglog("calling phpCAS::forceAuthentication()");
		phpCAS::forceAuthentication();
		
	//	debuglog("  back from phpCAS::forceAuthentication()");
		controller()->redirect(user()->returnUrl);
	}


}





