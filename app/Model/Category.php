<?php

/**
 * Category Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');
App::uses('Post', 'Model');

class Category extends AppModel
{

    public $actsAs = array('Tree');

    /**
     * Get Count post in Category
     * @param int $categoryId
     * @return array
     */
    public function countPostInCategory($categoryId)
    {
        return ClassRegistry::init('Post')->find('count', array(
                'conditions' => array('Post.category_id' => $categoryId)
        ));
    }

}
