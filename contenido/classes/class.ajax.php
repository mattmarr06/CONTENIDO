<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * Class for outputting some content for Ajax use
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Content Types
 * @version    1.0.0
 * @author     Timo Trautmann
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release 4.8.12
 * 
 * {@internal 
 *   created 2009-04-08
 *
 *   $Id$:
 * }}
 * 
 */

/**
 * Class for outputting some content for Ajax use
 *
 */
class Ajax {
	/**
	 * Constructor of class 
	 *
	 * @access public
	 */
	function __construct() {
	
	}
	
	/**
	  * Function for handling requested ajax data
	  *
	  * @param string $sAction - name of requested ajax action
	  * @access public
	  */
	public function handle($sAction) {
		$sString = '';
		switch($sAction) {
			//case to get an article select box param name value and idcat were neded (name= name of select box value=selected item)
			case 'artsel':
				$sName = (string) $_REQUEST['name'];
				$iValue = (int) $_REQUEST['value'];
				$iIdCat = (int) $_REQUEST['idcat'];
				$sString = buildArticleSelect($sName, $iIdCat, $iValue);
				break;
				
			case 'dirlist':	
				global $cfg, $client, $lang, $cfgClient;
				cInclude("classes", "class.cms_filelist.php");
			
				$sDirName 		= (string) $_REQUEST['dir'];
				$iFileListId 	= (int) $_REQUEST['id'];
				$iIdArtLang 	= (int) $_REQUEST['idartlang'];
				
				$oArt 			= new Article(null, null, null, $iIdArtLang);
				$sArtReturn 	= $oArt->getContent('CMS_FILELIST', $iFileListId);
				$oFileList 		= new Cms_FileList($sArtReturn, $iFileListId, 0, '', $cfg, null, '', $client, $lang, $cfgClient, null);
				
				$sString 		= $oFileList->getDirectoryList( $oFileList->buildDirectoryList ( $cfgClient[$client]['upl']['path'] . $sDirName ) );
				break;
				
			case 'filelist':
				global $cfg, $client, $lang, $cfgClient;
				cInclude("classes", "class.cms_filelist.php");
				
				$sDirName 		= (string) $_REQUEST['dir'];
				$iFileListId 	= (int) $_REQUEST['id'];
				$iIdArtLang 	= (int) $_REQUEST['idartlang'];
				
				$oArt 			= new Article(null, null, null, $iIdArtLang);
				$sArtReturn 	= $oArt->getContent('CMS_FILELIST', $iFileListId);
				$oFileList 		= new Cms_FileList($sArtReturn, $iFileListId, 0, '', $cfg, null, '', $client, $lang, $cfgClient, null);
				
				$sString 		= $oFileList->getFileSelect( $sDirName );
				break;
			//if action is unknown generate error message
			default:
				$sString = "Unknown Ajax Action";
				break;
		}
		
		return $sString;
	}
}

?>