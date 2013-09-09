<?php

class Application_Form_Search extends Zend_Form
{
    public function init()
    {
        $this->setName('search');

        $articleid = new Zend_Form_Element_Text('search');
        $articleid = $this->addElement('text', 'search', array(
            
            
            
            'class'      => 'search-field',
        ));

        		
        $submit = new Zend_Form_Element_Submit('Search');
		$submit = $this->addElement('submit', 'Search', array(
            
            
            
            'class'      => 'search-submit',
        ));
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($articleid, $submit));
		
        
    }
}