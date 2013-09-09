<?php

class Application_Model_DbTable_Articles extends Zend_Db_Table_Abstract
{

    protected $_name = 'articles';
	
	public function updateArticles($id, $artist, $title)
    {
        $data = array(
            'ArticleText' => $artist,
            'ArticleTitle' => $title,
        );
        $this->update($data, 'ArticleId = '. (int)$id);
    }
	
	public function getArticle($id)
    {
        $id = (int)$id;
        $row = $this->fetchRow('ArticleId = ' . $id);
        if (!$row) {
            throw new Exception("Could not find row $id");
        }
        return $row->toArray();
    }
	
	public function addArticle($artist, $title, $date)
    {
        $data = array(
            'ArticleText' => $artist,
            'ArticleTitle' => $title,
            'ArticleDate' => $date,
        );
        $this->insert($data);
    }
	
	public function deleteArticle($id)
    {
        $this->delete('ArticleId =' . (int)$id);
    }

	
}

