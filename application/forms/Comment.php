<?php

class Application_Form_Comment extends Zend_Form
{
    public function init()
    {
        	$this->addElementPrefixPath('Application_Validate', APPLICATION_PATH . '/validate/', 'validate');
			
        $this->setName('comment');

        $articleid = new Zend_Form_Element_Hidden('ArticleId');
        $articleid->addFilter('Int');

        $username = new Zend_Form_Element_Text('username', array('class' => 'comment-username'));
        $username->setLabel('User Name')
               ->setRequired(true)
               ->addValidator('Regex', true, '/[a-zA-Z0-9]/')
               ->addValidator('NotEmpty');
			   
		

        $useremail = new Zend_Form_Element_Text('useremail', array('class' => 'comment-useremail'));
        $useremail->setLabel('Email')
              ->setRequired(true)
              ->addValidator('EmailAddress')
              ->addValidator('NotEmpty');
		
		$commenttext = new Zend_Form_Element_Textarea('commenttext', array('class' => 'comment-commenttext'));
        $commenttext->setLabel('Comment')
              ->setRequired(true)
			  ->setAttrib('COLS', '40')
			  ->setAttrib('ROWS', '10')
              ->addFilter('StripTags')
			  ->addValidator('NotEmpty');
			  
		$commentimage = new Zend_Form_Element_File('commentimage');
		
        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'comment-submit'));
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($articleid, $username, $useremail, $commenttext, $commentimage, $submit));
		
        
    }
}