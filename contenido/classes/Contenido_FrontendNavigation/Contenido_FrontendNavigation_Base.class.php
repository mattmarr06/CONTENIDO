<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * Object to build a CONTENIDO Frontend Navigation
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package CONTENIDO Backend Classes
 * @version 1.2
 * @author Rudi Bieller
 * @copyright four for business AG <www.4fb.de>
 * @license http://www.contenido.org/license/LIZENZ.txt
 * @link http://www.4fb.de
 * @link http://www.contenido.org
 *
 *       {@internal
 *       created 2008-02-15
 *       $Id: Contenido_FrontendNavigation_Base.class.php 2898 2012-08-14
 *       23:32:06Z xmurrix $:
 *       }}
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}
class Contenido_FrontendNavigation_Base {

    /**
     * #@+
     *
     * @var int
     * @access protected
     */
    protected $iLang;

    protected $iClient;

    /**
     * #@-
     */

    /**
     *
     * @var array
     * @access protected
     */
    protected $aCategories;

    /**
     *
     * @var obj
     * @access protected
     */
    protected $oCategories;

    // needed properties for db queries
    /**
     *
     * @var obj
     * @access protected
     */
    protected $oDb;

    /**
     *
     * @var array
     * @access protected
     */
    protected $aCfg;

    /**
     *
     * @var array
     * @access protected
     */
    protected $aCfgClient;

    /**
     *
     * @var boolean
     * @access protected
     * @deprecated No longer needed. The backend chooses the debug mode. This is
     *             always true
     */
    protected $bDbg;

    /**
     *
     * @var string
     * @access protected
     * @deprecated No longer needed. The backend chooses the debug mode.
     */
    protected $sDbgMode;

    /**
     *
     * @var obj
     * @access protected
     */
    protected $oDbg;

    /**
     * Constructor.
     *
     * @access public
     * @param DB_Contenido $oDb
     * @param array $aCfg
     * @param int $iClient
     * @param int $iLang
     * @return void
     * @author Rudi Bieller
     */
    public function __construct(DB_Contenido $oDb, array $aCfg, $iClient, $iLang, array $aCfgClient) {
        $this->oDb = $oDb;
        $this->aCfg = $aCfg;
        $this->iClient = (int) $iClient;
        $this->iLang = (int) $iLang;
        $this->aCfgClient = $aCfgClient;
        $this->_iCurrentLoadDepth = 1;
        $this->_aSubCategories = array();
        $this->bDbg = true;
        $this->oDbg = cDebug::getDebugger();
    }

    /**
     * Get a URL to a Navigation point.
     * Depending on style of URL needed, values of $aParams differ.
     *
     * @access public
     * @param array $aParams Parameters needed to build the URL
     * @param string $sStyle Available styles are: front_content, custom,
     *            custom_path
     * @param array $aConfig As default this is cUriBuilderConfig::getConfig(),
     *            can be overridden by setting this value
     * @param boolean $bUseAbsolutePath If true, will use absolute
     *            http://www.xy.com/ as "prefix"
     * @return void
     * @throws InvalidArgumentException
     * @see appropriate cUriBuilder for details on needed params
     * @todo Apply other styles as soon as they are available
     */
    public function getUrl(array $aParams, $sStyle = 'custom_path', array $aConfig = array(), $bUseAbsolutePath = false) {
        try {
            $oUriBuilder = cUriBuilderFactory::getUriBuilder($sStyle);
            if ($bUseAbsolutePath === true) {
                $oUriBuilder->setHttpBasePath($this->aCfgClient[$this->iClient]['path']['htmlpath']);
            }
            $oUriBuilder->buildUrl($aParams, $bUseAbsolutePath, $aConfig);
            return $oUriBuilder->getUrl();
        } catch (InvalidArgumentException $e) {
            throw $e;
        }
    }

    /**
     * Set internal property for debugging on/off and choose appropriate debug
     * object
     *
     * @deprecated No longer needed. The backend chooses the debug mode.
     * @access public
     * @param boolean $bDebug
     * @param string $sDebugMode
     * @return void
     * @author Rudi Bieller
     */
    public function setDebug($bDebug = true, $sDebugMode = cDebug::DEBUGGER_VISIBLE) {
        cDeprecated("This function is no longer needed. \$oDbg gets chosen by the system settings.");

        $this->sDbgMode = $sDebugMode;
        if ($bDebug === true) {
            $this->bDbg = true;
            $this->oDbg = cDebug::getDebugger($sDebugMode);
        } else {
            $this->bDbg = false;
            $this->oDbg = null;
        }
    }

}
?>