<?php

class Application_Form_CommentReview extends Zend_Form
{
    public function init()
    {
        $this->setName('commentreview');

        $commentid = new Zend_Form_Element_Hidden('commentid');
        $commentid->addFilter('Int');
		        		
        $approvesubmit = new Zend_Form_Element_Submit('approvesubmit');
        $approvesubmit->setAttrib('Approved', 'submitbutton');
		
		$declinesubmit = new Zend_Form_Element_Submit('declinesubmit');
        $declinesubmit->setAttrib('Declined', 'submitbutton');

        $this->addElements(array($commentid, $approvesubmit, $declinesubmit));
		
        
    }
}