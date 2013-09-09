<?php

class Application_Model_DbTable_Comments extends Zend_Db_Table_Abstract
{

    protected $_name = 'comments';

	public function addComment($articleid, $commentstatus, $commentusername, $commentuseremail, $commenttext, $commentdate)
    {
        $data = array(
            'ArticleId' => $articleid,
            'CommentStatus' => $commentstatus,
            'CommentUserName' => $commentusername,
            'CommentUserEmail' => $commentuseremail,
            'CommentText' => $commenttext,
            'CommentDate' => $commentdate,
        );
        $this->insert($data);
		
		
    }
	
	public function approveComment($commentid, $commentstatus)
    {
        $data = array(
            'CommentStatus' => $commentstatus,
            
        );
		
        $this->update($data, 'CommentId = '. (int)$commentid);
    }
	
	public function getComment()
	{
		$select = $this->_db->select()->from($this->_name, array('CommentId'))->where('CommentId = 9');
		$response = $this->getAdapter()->fetchAll($select);
		return $response;
	}
}

