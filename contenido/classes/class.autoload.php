<?php
/**
 * Project:
 * Contenido Content Management System
 *
 * Description:
 * Implements autoload feature for a Contenido project.
 *
 * Autoloading for Contenido is provided via a generated classmap configuration 
 * file, which is available inside contenido/includes/ folder.
 * - contenido/includes/config.autoloader.php
 *
 * Autoloading is extendable by adding a additional classmap file inside the same 
 * folder, which could contain further classmap settings or could overwrite 
 * settings of main classmap file.
 * - contenido/includes/config.autoloader.local.php
 *
 * Read also docs/techref/backend/backend.autoloader.html to get involved in
 * Contenido autoloader mechanism.
 *
 *
 * Requirements:
 * @con_php_req 5.0
 *
 * @package    Contenido Autoloader
 * @version    0.0.1
 * @author     Murat Purc <murat@purc.de>
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release 4.8.15
 *
 * {@internal
 *   created  2010-12-27
 *   modified 2010-12-29, Murat Purc, separated autoloading of PEAR classes by 
 *                                    mapping classnames to folders.
 *   $Id$:
 * }}
 *
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}


class Contenido_Autoload
{

    const ERROR_FILE_NOT_FOUND = 'file_not_found';

    const ERROR_CLASS_EXISTS = 'class_exists';

    /**
     * Contenido root path. Path to the folder which contains the Contenido installation.
     *
     * @var string
     */
     private static $_conRootPath = null;

    /**
     * Path to bundled PEAR package (see $cfg['path']['pear']).
     *
     * @var string
     */
     private static $_pearPath = null;

    /**
     * Array of interface/class names with related files to include
     *
     * @var array
     */
     private static $_includeFiles = null;

    /**
     * Flag containing initialized status
     *
     * @var bool
     */
     private static $_initialized = null;

    /**
     * Array to store loaded classnames and the paths to the class files.
     * $_loadedClasses['classname'] = '/path/to/the/class.php';
     *
     * @var array
     */
    private static $_loadedClasses = array();

    /**
     * Array to store invalid classnames and the paths to the class files.
     * $_errors[pos] = array('class' => classname, 'file' => file, 'error' => errorType);
     *
     * @var array
     */
    private static $_errors = array();


    /**
     * Initialization of Contenido autoloader autoloader, is to call at least once.
     *
     * Registers itself as a __autoload implementation, includes the classmap file, 
     * if exists the user defined class map file, containing the includes.
     *
     * @param   array  $cfg  The Contenido cfg array
     * @return  void
     */
    public static function initialize(array $cfg)
    {
        if (self::$_initialized == true) {
            return;
        }

        self::$_initialized = true;
        self::$_conRootPath = str_replace('\\', '/', realpath($cfg['path']['contenido'] . '/../')) . '/';
        self::$_pearPath    = $cfg['path']['pear'];

        spl_autoload_register(array(__CLASS__, 'autoload'));

        // load n' store autoloader classmap file
        $file = $cfg['path']['contenido'] . $cfg['path']['includes'] . 'config.autoloader.php';
        if ($arr = include_once($file)) {
            self::$_includeFiles = $arr;
        }

        // load n' store additional autoloader classmap file, if exists
        $file = $cfg['path']['contenido'] . $cfg['path']['includes'] . 'config.autoloader.local.php';
        if (is_file($file)) {
            if ($arr = include_once($file)) {
                self::$_includeFiles = array_merge(self::$_includeFiles, $arr);
            }
        }
    }


    /**
     * The main __autoload() implementation.
     *
     * Tries to include the passed classname by following order:
     * 1. From within Contenido class map configuration
     * 2. From within PEAR folder
     *
     * @param   string  $className  The classname
     * @return  void
     * @throws  Exception  If autoloader wasn't initialized before
     */
    public static function autoload($className)
    {
        if (self::$_initialized !== true) {
            throw new Exception("Autoloader has to be initialized by calling method initialize()");
        }

        if (isset(self::$_loadedClasses[$className])) {
            return;
        }

        $file = '';

        if ($file = self::_getContenidoClassFile($className)) {
            // load class file from class map
            self::_loadFile($file);
        } elseif ($file = self::_getPearClassFile($className)) {
            // load class file from PEAR package, but be as quiet as a mouse!
            self::_loadFile($file, true);
        }

        self::$_loadedClasses[$className] = str_replace(self::$_conRootPath, '', $file);
    }


    /**
     * Returns the loaded classes (@see Contenido_Autoload::$_loadedClasses)
     *
     * @return  array
     */
    public static function getLoadedClasses()
    {
        return self::$_loadedClasses;
    }


    /**
     * Returns the errorlist containing invalid classes (@see Contenido_Autoload::$_errors)
     *
     * @return  array
     */
    public static function getErrors()
    {
        return self::$_errors;
    }


    /**
     * Returns the path to a Contenido class file by processing the given classname
     *
     * @param    string  $className
     * @return  (string|null)  Path and filename or null
     */
    private static function _getContenidoClassFile($className)
    {
        $file = isset(self::$_includeFiles[$className]) ? self::$_conRootPath . self::$_includeFiles[$className] : null;
        return self::_validateClassAndFile($className, $file);
    }


    /**
     * Returns the path to a PEAR class file from bundled and/or configured PEAR 
     * packages by processing the given classname
     *
     * @param    string  $className
     * @return  (string|null)  Path and filename or null
     */
    private static function _getPearClassFile($className)
    {
        $pathName = self::$_pearPath . str_replace('_', '/', $className) . '.php';
        return self::_validateClassAndFile($className, $pathName);
    }


    /**
     * Validates the given class and the file
     *
     * @param   string  $className
     * @param   string  $filePathName
     * @return  (string|null)  The file if validation was successfull, otherwhise null
     */
    private static function _validateClassAndFile($className, $filePathName)
    {
        if (class_exists($className)) {
            self::$_errors[] = array(
                'class' => $className,
                'file'  => str_replace(self::$_conRootPath, '', $filePathName),
                'error' => self::ERROR_CLASS_EXISTS
            );
            return null;
        } elseif (!is_file($filePathName)) {
            self::$_errors[] = array(
                'class' => $className,
                'file'  => str_replace(self::$_conRootPath, '', $filePathName),
                'error' => self::ERROR_FILE_NOT_FOUND
            );
            return null;
        }

        return $filePathName;
    }


    /**
     * Loads the desired file by invoking require_once method
     *
     * @param   string  $filePathName
     * @param   bool    $beQuiet  Flag to prevent thrown warnings/errors by using
     *                            the error control operator @
     * @return  void
     */
    private static function _loadFile($filePathName, $beQuiet = false)
    {
        if ($beQuiet) {
            @require_once($filePathName);
        } else {
            require_once($filePathName);
        }
    }

}
