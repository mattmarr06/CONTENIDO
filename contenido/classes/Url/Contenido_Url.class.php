<?php

// ##############################################################################
// Old version of VersionImport class
//
// NOTE: Class implemetation below is deprecated and the will be removed in
// future versions of contenido.
// Don't use it, it's still available due to downwards compatibility.

/**
 * VersionImport
 *
 * @deprecated [2012-09-04] Use cUri instead of this class.
 */
class Contenido_Url extends cUri {

    /**
     *
     * @deprecated 2012-09-04 this function is not supported any longer
     *             use function located in cUri instead of this
     *             function
     */
    private function __construct() {
        cDeprecated("Use class cVersionImport instead");
        parent::__construct();
    }

    /**
     *
     * @deprecated 2012-09-04 this function is not supported any longer
     *             use function located in cUri instead of this
     *             function
     */
    public function getUrlBuilder() {
        cDeprecated("This function is not supported any longer");
        return cUri::getUriBuilder();
    }

    /**
     *
     * @deprecated 2012-09-04 this function is not supported any longer
     *             use function located in cUri instead of this
     *             function
     */
    public static function getInstance() {
        cDeprecated("This function is not supported any longer");
        return cUri::getInstance();
    }



}

?>
