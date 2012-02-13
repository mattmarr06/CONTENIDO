<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * CMS_DYNAMIC code
 *
 * Requirements:
 * @con_php_req 5.0
 *
 * @package    CONTENIDO Backend Includes
 * @version    0.0.1
 * @author     Murat Purc <murat@purc.de>
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release 4.9.0
 *
 * {@internal
 *   created  2012-02-14
 *
 *   $Id: $:
 * }}
 *
 */


if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

// CMS_DYNAMIC

cInclude('classes', 'class.globals.config.php');
cInclude('classes', 'module/AbstractModule.php');

$tmp = $a_content['CMS_DYNAMIC'][$val];

$oCmsDynamic = new Cms_Dynamic($tmp, $val, $idartlang);
$oCmsDynamic->start();

if ($edit) {
    $tmp = $oCmsDynamic->showToolbar();
    $tmp .= $oCmsDynamic->showContent();
} else {
    $tmp = $oCmsDynamic->showContent();   
}


?>