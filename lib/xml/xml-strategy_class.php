<?php
/**
 * Google Merchant Center
 *
 * @author    BusinessTech.fr - https://www.businesstech.fr
 * @copyright Business Tech - https://www.businesstech.fr
 * @license   Commercial
 *
 *           ____    _______
 *          |  _ \  |__   __|
 *          | |_) |    | |
 *          |  _ <     | |
 *          | |_) |    | |
 *          |____/     |_|
 */

final class BT_XmlStrategy
{
    /**
     * @var string $sContent : store the XML content
     */
    public $sContent = '';

    /**
     * @var array $aParams : array of params
     */
    public $aParams = array();

    /**
     * @var string $sSep : define the separator
     */
    public $sSep = "\n";

    /**
     * @var string $sTab : define the tabulation
     */
    public $sTab = "\t";

    /**
     * @var string $sDblSep : define the double separator
     */
    public $sDblSep = "\n\n";

    /**
     * @var string $sFileName : store file name
     */
    public $sFileName = '';

    /**
     * @var int $iCounter : count the number of product processed
     */
    public $iCounter = 0;

    /**
     * @var obj $oCurrentObj : store the current obj to handle
     */
    protected $oCurrentObj = null;

    /**
     * @var obj $oFile : store the file object
     */
    protected $oFile = null;

    /**
     * @var bool $bExport : define the export mode
     */
    protected $bExport = null;

    /**
     * @var bool $bOutput : define if we display directly the content
     */
    protected $bOutput = null;

    /**
     * @var obj $data : store currency / shipping / zone / carrier
     */
    public $data = null;

    /**
     * @param array $aParams
     * @param string $sType : define the tpy of the object we need to load for product or combination product
     */
    public function __construct(array $aParams = array(), $sType = null)
    {
        require_once(_GMC_PATH_LIB . 'module-dao_class.php');

        $this->data = new stdClass();
        $this->sContent = '';
        $this->aParams = $aParams;
        $this->iCounter = 0;
        $this->bExport = isset($aParams['bExport']) ? $aParams['bExport'] : 0;
        $this->bOutput = isset($aParams['bOutput']) ? $aParams['bOutput'] : 0;

        if ($sType !== null) {
            $this->oCurrentObj = $this->get($sType, $aParams);
        }
    }


    /**
     * set the XML header
     *
     * @return bool
     */
    public function header()
    {
        // get meta
        $aMeta = Meta::getMetaByPage('index', (int)$this->aParams['iLangId']);

        $sContent = ''
            . '<?xml version="1.0" encoding="UTF-8"?>' . $this->sSep
            . '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">' . $this->sSep
            . '<channel>' . $this->sSep
            . "\t" . '<title><![CDATA[' . Tools::stripslashes(Configuration::get('PS_SHOP_NAME')) . ']]></title>' . $this->sSep
            . "\t" . '<description><![CDATA[' . Tools::stripslashes($aMeta['description']) . ']]></description>' . $this->sSep
            . "\t" . '<link>' . $this->aParams['sGmcLink'] . '</link>' . $this->sSep;

        if (!empty($this->bOutput)) {
            echo $sContent;
        } else {
            $this->sContent .= $sContent;
        }

        return true;
    }

    /**
     * set the XML footer
     *
     * @return bool
     */
    public function footer()
    {
        $sContent = ''
            . '</channel>' . $this->sSep
            . '</rss>';

        if (!empty($this->bOutput)) {
            echo $sContent;
        } else {
            $this->sContent .= $sContent;
        }

        return true;
    }


    /**
     * set the File obj
     *
     * @param obj $oFile
     * @return array
     */
    public function setFile($oFile)
    {
        $this->oFile = $oFile;
    }

    /**
     * write the XML file content
     *
     * @param string $sFileName
     * @param string $sContent
     * @param bool $bVerbose - display comments
     * @param bool $bAdd - adding data
     * @param bool $bStripTag - strip all HTML tags
     * @return bool
     */
    public function write($sFileName, $sContent, $bVerbose = false, $bAdd = false, $bStripTag = false)
    {
        if (empty($this->bOutput)) {
            $this->oFile->write($sFileName, $sContent, $bVerbose, $bAdd, $bStripTag);
        }
        return true;
    }

    /**
     * delete XML file
     *
     * @param string $sFileName
     * @return bool
     */
    public function delete($sFileName)
    {
        return (
        is_file($sFileName) && $this->oFile->delete($sFileName) ? true : false
        );
    }

    /**
     * load Products for XML
     */
    public function loadProduct()
    {
        // set different vars required to calculate some things
        $this->data->currencyId = Currency::getIdByIsoCode(Tools::strtolower($this->aParams['sCurrencyIso']));
        $this->data->currency = new stdClass();
        $this->data->currency = new Currency($this->data->currencyId);

        // store the current carrier
        $this->data->currentCarrier = new stdClass();
        if (!empty(GMerchantCenter::$conf['GMC_SHIP_CARRIERS'][Tools::strtoupper($this->aParams['sCountryIso'])])) {
            $this->data->currentCarrier = new Carrier((int)GMerchantCenter::$conf['GMC_SHIP_CARRIERS'][Tools::strtoupper($this->aParams['sCountryIso'])]);
        }
        $this->data->countryId = Country::getByIso($this->aParams['sCountryIso']);
        $this->data->currentZone = new stdClass();
        $this->data->currentZone = new Zone((int)Country::getIdZone((int)$this->data->countryId));
        $this->data->shippingConfig = Configuration::getMultiple(array(
            'PS_SHIPPING_FREE_PRICE',
            'PS_SHIPPING_FREE_WEIGHT',
            'PS_SHIPPING_HANDLING',
            'PS_SHIPPING_METHOD'
        ));

        Context::getContext()->currency = new Currency((int)$this->data->currencyId);
        Context::getContext()->cookie->id_country = $this->data->countryId;
        Context::getContext()->cookie->id_currency = $this->data->currencyId;

        return BT_GmcModuleDao::getProductIds($this->aParams['iShopId'], $this->bExport, false, $this->aParams['iFloor'], $this->aParams['iStep']);
    }


    /**
     * check if combinations and return them
     *
     * @param int $iProdId
     */
    public function hasCombination($iProdId)
    {
        // check if combinations
        return $this->oCurrentObj->hasCombination($iProdId);
    }

    /**
     * method the number of products processed
     *
     * @return int
     */
    public function getProcessedProduct()
    {
        return (int)$this->iCounter;
    }

    /**
     * construct the XML content
     *
     * @param obj $oData
     * @param obj $oProduct
     * @param array $aCombination
     */
    public function buildProductXml(&$oData, $oProduct, $aCombination)
    {
        // load the product and combination into the matching object
        $this->oCurrentObj->setProductData($oData, $oProduct, $aCombination);

        // build the common and specific part between different type of export
        if ($this->oCurrentObj->buildProductXml()) {
            if (!empty($this->bOutput)) {
                // echo the output
                echo $this->oCurrentObj->buildXmlTags();
            } else {
                $this->sContent .= $this->oCurrentObj->buildXmlTags();
            }

            if ($this->oCurrentObj->hasProductProcessed()) {
                $this->iCounter++;
            }
        }
    }

    /**
     * instantiate matched product object
     *
     * @param string $sProductType
     * @param array $aParams
     * @return obj ctrl type
     */
    public function get($sProductType, array $aParams = null)
    {
        $sProductType = strtolower($sProductType);

        // if valid controller
        if (file_exists(_GMC_PATH_LIB_XML . 'xml-' . $sProductType . '_class.php')) {
            // require
            require_once('base-xml_class.php');
            require_once('xml-' . $sProductType . '_class.php');

            // set class name
            $sClassName = 'BT_Xml' . ucfirst($sProductType);

            try {
                $oReflection = new ReflectionClass($sClassName);

                if ($oReflection->isInstantiable()) {
                    $this->oCurrentObj = $oReflection->newInstance($aParams);
                } else {
                    throw new Exception(GMerchantCenter::$oModule->l('Internal server error => object isn\'t instantiable', 'base-xml_class'), 1000);
                }
            } catch (ReflectionException $e) {
                throw new Exception(GMerchantCenter::$oModule->l('Internal server error => invalid object', 'base-xml_class'), 1001);
            }
        } else {
            throw new Exception(GMerchantCenter::$oModule->l('Internal server error => the object file doesn\'t exist', 'base-xml_class'), 1002);
        }
    }

    /**
     * create singleton
     *
     * @param string $sType
     * @param array $aParams
     * @return obj
     */
    public static function create($sType, array $aParams = array())
    {
        static $oXml;

        if (null === $oXml) {
            $oXml = new BT_XmlStrategy($sType, $aParams);
        }
        return $oXml;
    }
}
