<?php

class Application_Model_DbTable_Images extends Zend_Db_Table_Abstract
{

    protected $_name = 'images';
	
	public function AddImagelink($link, $commentid)
	{
		$data = array(
            'ImageLink' => $link,
            'CommentId' => $commentid,
            
			);
		$this->insert($data);
	}


}

