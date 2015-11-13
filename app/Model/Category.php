<?php

/**
 * Category Model
 * 
 * @package         app.Model
 * @author          Nguyen Van Cong
 */
App::uses('AppModel', 'Model');

class Category extends AppModel
{

    public $actsAs = array('Tree');

}
