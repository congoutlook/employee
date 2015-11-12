<?php

App::uses('AclInterface', 'Controller/Component/Acl');
App::uses('Permission', 'Model');

/**
 * SystemAcl implements an access control system using an INI file & permission setup in the database. An example
 * of the ini file used can be found in /config/aclsystem.ini.php.
 *
 * @package         App.Controller.Component.Acl
 * @author          Nguyen Van Cong
 */
class SystemAcl extends Object implements AclInterface
{

    /**
     * Array with configuration, parsed from ini file
     *
     * @var array
     */
    public $config     = null;
    public $Permission = null;

    public function __construct()
    {
        parent::__construct();
        $this->config     = $this->getAllPermissions();
        $this->Permission = ClassRegistry::init('Permission')->getPermissions();
    }

    /**
     * Initialize method
     *
     * @param Component $component The AclComponent instance.
     * @return void
     */
    public function initialize(Component $component)
    {
        $component->config     = $this->config;
        $component->Permission = $this->Permission;
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
     * 
     * @example
     * $check = $Acl->check($Auth, 'controllerLogical', 'actionLogical');
     * $check = $Acl->check($this->Auth, 'users', 'add');
     * $check = $Acl->check($userGroupId, 'users', 'add');
     * $check = $Acl->check(1, 'users', 'add');
     * $check = $Acl->check($userArrayGroupIds, 'users', 'add');
     * $check = $Acl->check(array(1, 2, 3), 'users', 'add');
     * $check = $Acl->check(array('Group' => array('id' => 1)), 'users', 'add');
     */
    public function check($aro, $aco, $action = null)
    {
        // source for comparing
        $source = $action ?
            (isset($this->Permission[$aco][$action]) ? $this->Permission[$aco][$action] : array()) : $this->Permission[$aco]
        ;

        // if $aro is group id
        if (is_int($aro)) {
            if (array_search((int) $aro, $source) !== false) {
                return true;
            }
        }

        // if $aro is instance of AuthComponent
        if ($aro instanceof AuthComponent) {
            if (array_search($aro->user('group_id'), $source) !== false) {
                return true;
            }
        }
        
        // if $aro is array of int
        if (is_array($aro) && $this->_isArrayInt($aro)) {
            foreach ($aro as $groupId) {
                if (array_search($groupId, $source) !== false) {
                    return true;
                }
            }
        }
        
        // if $aro array('Group' => array('id' => 1))
        if (is_array($aro) && isset($aro['Group']['id'])) {
            if (array_search($aro['Group']['id'], $source) !== false) {
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
                        $permissions = $this->arrayTrim(explode(',', $aclPermission['permissions']));
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
    
    private function _isArrayInt($array) {
        foreach ($array as $key => $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }
        return true;
    }

}
