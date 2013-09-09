<?php

class Application_Form_Album extends Zend_Form
{
    public function init()
    {
        $this->setName('album');

        $id = new Zend_Form_Element_Hidden('ArticleId');
        $id->addFilter('Int');

        $artist = new Zend_Form_Element_Textarea('ArticleText', array('class' => 'article-articletext'));
        $artist->setLabel('Article Text')
			   ->setAttrib('COLS', '60')
			   ->setAttrib('ROWS', '10')
               ->setRequired(true)
               ->addFilter('StripTags')
               ->addFilter('StringTrim')
               ->addValidator('NotEmpty');

        $title = new Zend_Form_Element_Text('ArticleTitle', array('class' => 'article-articletitle'));
        $title->setLabel('Article Title')
              ->setRequired(true)
              ->addFilter('StripTags')
              ->addFilter('StringTrim')
              ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit', array('class' => 'article-submit'));
        $submit->setAttrib('id', 'submitbutton');

        $this->addElements(array($id, $title, $artist, $submit));
    }
}