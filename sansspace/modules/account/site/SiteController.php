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

	public function actionRelease()
	{
		$this->render('release');
	}

	public function actionTraining()
	{
		$this->render('training');
	}
    
    public function actionAnnouncementRemove()
	{
		$this->render('announcementRemove');
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
		if(param('theme') == 'wayside')
			$this->render('login-wayside');
		else
			$this->render('login');
	}

	public function actionAdmin(){
		$this->render('admin');
	}

	public function actionMobileInit()
	{
		$this->identity->logout();
		$this->redirect(array('my/', 'noheader'=>1));
	}
	
	public function actionDenied()
	{
		$this->render('denied');
	}

	public function actionLogout()
	{
		$noheader = user()->getState('noheader');
		$this->identity->logout();
		
		if($noheader)
			$this->redirect(array('site/login', 'noheader'=>1));
		else
			$this->redirect(array('site/login'));
	}
	
	public function actionStartMobileApp()
	{
		$this->render('startmobileapp');
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
					
					user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account successfully created! Please login below.</div>");
					$this->redirect(array('site/login'));
				}
				
				else if($form->register_role == 'teacher')
				{
					$e = new UserEnrollment;
					$e->userid = $form->user->id;
					$e->roleid = SSPACE_ROLE_TEACHER;
					$e->save();
					
					user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account successfully created! Please login below.</div>");
					$this->redirect(array('site/login'));
				}

				user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>Account successfully created! Please login below.</div>");
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
				
				else if(!empty($form->email))
					$user = getdbosql('User', "email='$form->email'");
					
				if(!$user)
				{
					user()->setFlash('error', 'Account not found.');
					$this->render('forgot', array('form'=>$form));
					return;
				}
				
				if($user->domainid != CMDB_DEFAULT_DOMAINID && $user->domain->ldapenable)
				{
					user()->setFlash('error', 'This account is associated with the '. $user->domain->name . ' domain. You cannot change your password here.');
					$this->render('forgot', array('form'=>$form));
					return;
				}
				
				$user->guid = base64_encode(uniqid('5tr8').uniqid('fr6e'));
				$user->save();
				
				emailUserForgot($user);
				user()->setFlash('message', "<div style=text-align:center; margin-bottom:-20px>An email for password reset has been sent to the email address associated with your account.</div>");
				
				$this->redirect(array('site/login'));
			}
		}
		
		$this->render('forgot', array('form'=>$form));
	}

	public function actionPassword()
	{
		$form = new PasswordForm;
		$guid = addslashes(getparam('guid'));
		
		$user = getdbosql('User', "guid='$guid'");
		if(!$user)
		{
			user()->setFlash('error', "<div style=text-align:center; margin-bottom:-20px>Reset link already used. Please use <a style='color:red' href='site/forgot'>Forgot Password</a> feature again.</div>");
			$this->redirect(array('site/login'));
		}
		
		if(isset($_POST['PasswordForm']))
		{
			$form->attributes = $_POST['PasswordForm'];
			if($form->validate())
			{
				$user->password = md5($form->password);
				$user->guid = '';
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
		
		$user = getUser();
		if($user && $user->logon != 'guest')
		{
			$contact->name = $user->name;
			$contact->email = $user->email;
			
		}
		
		if(isset($_POST['ContactForm']))
		{
			$contact->attributes = $_POST['ContactForm'];
			if($contact->validate())
			{
				mailex('', SANSSPACE_SMTP_EMAIL, $contact->email,
					'Contact Us Form Received', 
					"<img src='http://learningsite.waysidepublishing.com/contents/17894.png' alt='logo'  class='alignnone size-full wp-image-533'><br><br>
					<div style='margin-left:3%; height:100%; font-size:16px; color:#555555; line-height:1.1em; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''><p><div style='font-size:34px; color:#007ABB; font-weight:500; font-family:Gotham, 'Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif''>Hi $user->name,</div>
					<br>We've received your email and will respond as soon as possible. Fastest response time is from 8:30 a.m. - 4:30 p.m. EST Monday through Friday.<br>
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
					Name: $contact->name<br>
					Email: $contact->email<br>
					Username: $user->logon<br>
					Subject: $contact->subject<br><br>
					$contact->body<br><br>
					$contact->accesscode<br>
					$contact->teacher<br>
					$contact->course<br>
					$contact->it<br>
					$contact->itemail<br>
					$contact->itphone
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
		if(param('theme') == 'wayside')
			$basename = 'wayside.apk';
		else
			$basename = 'sansspace.apk';
		
		$filename = SANSSPACE_HTDOCS.'/extensions/players/'.$basename;
		
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





