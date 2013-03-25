<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Frontend group classes
 *
 * Code is taken over from file contenido/classes/class.frontend.groups.php in
 * favor of
 * normalizing API.
 *
 * @package CONTENIDO API
 * @version 0.1
 * @author Murat Purc <murat@purc.de>
 * @copyright four for business AG <www.4fb.de>
 * @license http://www.contenido.org/license/LIZENZ.txt
 * @link http://www.4fb.de
 * @link http://www.contenido.org
 * @since file available since CONTENIDO release 4.9.0
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

/**
 * Frontend group collection
 *
 * @package CONTENIDO API
 * @subpackage Model
 */
class cApiFrontendGroupCollection extends ItemCollection {

    /**
     * Constructor Function
     */
    public function __construct() {
        global $cfg;
        parent::__construct($cfg['tab']['frontendgroups'], 'idfrontendgroup');
        $this->_setItemClass('cApiFrontendGroup');

        // set the join partners so that joins can be used via link() method
        $this->_setJoinPartner('cApiClientCollection');
    }

    /**
     * Creates a new group
     *
     * @param $groupname string Specifies the groupname
     * @param $password string Specifies the password (optional)
     */
    public function create($groupname) {
        global $client;

        $group = new cApiFrontendGroup();

        // _arrInFilters = array('urlencode', 'htmlspecialchars', 'addslashes');

        $mangledGroupName = $group->_inFilter($groupname);
        $this->select("idclient = " . (int) $client . " AND groupname = '" . $mangledGroupName . "'");

        if (($obj = $this->next()) !== false) {
            $groupname = $groupname . md5(rand());
        }

        $item = parent::createNewItem();

        $item->set('idclient', $client);
        $item->set('groupname', $groupname);
        $item->store();

        return $item;
    }

    /**
     * Overridden delete method to remove groups from groupmember table
     * before deleting group
     *
     * @param $itemID int specifies the frontend user group
     */
    public function delete($itemID) {
        $associations = new cApiFrontendGroupMemberCollection();
        $associations->select('idfrontendgroup = ' . (int) $itemID);

        while (($item = $associations->next()) !== false) {
            $associations->delete($item->get('idfrontendgroupmember'));
        }
        parent::delete($itemID);
    }

}

/**
 * Frontend group item
 *
 * @package CONTENIDO API
 * @subpackage Model
 */
class cApiFrontendGroup extends Item {

    /**
     * Constructor Function
     *
     * @param mixed $mId Specifies the ID of item to load
     */
    public function __construct($mId = false) {
        global $cfg;
        parent::__construct($cfg['tab']['frontendgroups'], 'idfrontendgroup');
        if ($mId !== false) {
            $this->loadByPrimaryKey($mId);
        }
    }

}
