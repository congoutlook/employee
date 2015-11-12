<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller.Component.Acl
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AclInterface', 'Controller/Component/Acl');
App::uses('Permission', 'Model');

/**
 * IniAcl implements an access control system using an INI file. An example
 * of the ini file used can be found in /config/acl.ini.php.
 *
 * @package       Cake.Controller.Component.Acl
 */
class SystemAcl extends Object implements AclInterface
{

    /**
     * Array with configuration, parsed from ini file
     *
     * @var array
     */
    public $config = null;
    
    public $Permission = null;

    public function __construct()
    {
        parent::__construct();
        $this->config = $this->getAllPermissions();
        
    }

    /**
     * Initialize method
     *
     * @param Component $component The AclComponent instance.
     * @return void
     */
    public function initialize(Component $component)
    {
        $component->config = $this->config;
        $component->Permission = ClassRegistry::init('Permission')->getPermissions();
    }

    /**
     * No op method, allow cannot be done with IniAcl
     *
     * @param string $aro ARO The requesting object identifier.
     * @param string $aco ACO The controlled object identifier.
     * @param string $action Action (defaults to *)
     * @return bool Success
     */
    public function allow($aro, $aco, $action = "*")
    {
        
    }

    /**
     * No op method, deny cannot be done with IniAcl
     *
     * @param string $aro ARO The requesting object identifier.
     * @param string $aco ACO The controlled object identifier.
     * @param string $action Action (defaults to *)
     * @return bool Success
     */
    public function deny($aro, $aco, $action = "*")
    {
        
    }

    /**
     * No op method, inherit cannot be done with IniAcl
     *
     * @param string $aro ARO The requesting object identifier.
     * @param string $aco ACO The controlled object identifier.
     * @param string $action Action (defaults to *)
     * @return bool Success
     */
    public function inherit($aro, $aco, $action = "*")
    {
        
    }

    /**
     * Main ACL check function. Checks to see if the ARO (access request object) has access to the
     * ACO (access control object).Looks at the acl.ini.php file for permissions
     * (see instructions in /config/acl.ini.php).
     *
     * @param string $aro ARO
     * @param string $aco ACO
     * @param string $action Action
     * @return bool Success
     */
    public function check($aro, $aco, $action = null)
    {
        if (!$this->Permission) {
            $this->Permission = ClassRegistry::init('Permission')->getPermissions();
        }
        
        // source for comparing
        $source = $action ? 
            (isset($this->Permission[$aco][$action]) ? $this->Permission[$aco][$action] : array()) : $this->Permission[$aco]
        ;
        
        if ($aro instanceof AuthComponent) {
            if (array_search($aro->user('group_id'), $source)) {
                return true;
            }
        }
 
        return false;
    }

    public function getAllPermissions()
    {
        if (!$this->config) {
            $aclPermissions = $this->readConfigFile(APP . 'Config' . DS . 'aclsystem.ini.php');

            if (is_array($aclPermissions)) {
                foreach ($aclPermissions as $logical => $aclPermission) {
                    $permissions = array();
                    if (isset($aclPermission['permissions'])) {
                        $permissions = $this->arrayTrim(explode(",", $aclPermission['permissions']));
                    }
                    $this->config[$logical] = $permissions;
                }
            }
        }

        return $this->config;
    }

    /**
     * Parses an INI file and returns an array that reflects the
     * INI file's section structure. Double-quote friendly.
     *
     * @param string $filename File
     * @return array INI section structure
     */
    public function readConfigFile($filename)
    {
        App::uses('IniReader', 'Configure');
        $iniFile = new IniReader(dirname($filename) . DS);
        return $iniFile->read(basename($filename));
    }

    /**
     * Removes trailing spaces on all array elements (to prepare for searching)
     *
     * @param array $array Array to trim
     * @return array Trimmed array
     */
    public function arrayTrim($array)
    {
        foreach ($array as $key => $value) {
            $array[$key] = trim($value);
        }
        return $array;
    }

}
