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

class BT_GmcModuleTools
{
    /**
     * all details of the shop group or one required detail
     *
     * @param string $sDetail
     * @return mixed : array or string
     */
    public static function getGroupShopDetail($sDetail = null)
    {
        // get the current group shop
        $oGroupShop = new ShopGroup(Context::getContext()->shop->id_shop_group);

        $aDetails = $oGroupShop->getFields();

        return ($sDetail !== null && isset($aDetails[$sDetail])) ? $aDetails[$sDetail] : $aDetails;
    }


    /**
     * returns good translated errors
     */
    public static function translateJsMsg()
    {
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['link'] = GMerchantCenter::$oModule->l('You have not filled out the shop URL option', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['token'] = GMerchantCenter::$oModule->l('Field is required or Token must be 32 characters', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['customlabel'] = GMerchantCenter::$oModule->l('You have not filled out the custom label', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['apikey'] = GMerchantCenter::$oModule->l('You have not filled out your api key', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['merchantid'] = GMerchantCenter::$oModule->l('You have not filled out your Merchant Center ID', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['category'] = GMerchantCenter::$oModule->l('You did not select any category to export', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['brand'] = GMerchantCenter::$oModule->l('You did not select any brand to export', 'module-tools_class');
        $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['color'] = GMerchantCenter::$oModule->l('You did not select any attribute or feature to fit to your color tag', 'module-tools_class');

        foreach (Language::getLanguages() as $aLang) {
            $GLOBALS[_GMC_MODULE_NAME . '_JS_MSG']['homecat'][$aLang['id_lang']] = GMerchantCenter::$oModule->l('You have not filled out type of product your are selling with language', 'module-tools_class')
                . ' ' . $aLang['name'] . '. ' . GMerchantCenter::$oModule->l('Click on the drop-down flag list in order to fill out the correct language field(s).', 'module-tools_class');
        }
    }

    /**
     * update new keys in new module version
     */
    public static function updateConfiguration()
    {
        // check to update new module version
        foreach ($GLOBALS[_GMC_MODULE_NAME . '_CONFIGURATION'] as $sKey => $mVal) {
            // use case - not exists
            if (Configuration::get($sKey) === false) {
                // update key/ value
                Configuration::updateValue($sKey, $mVal);
            }
        }
    }

    /**
     * set all constant module in ps_configuration
     *
     * @param array $aOptionListToUnserialize
     * @param int $iShopId
     */
    public static function getConfiguration(array $aOptionListToUnserialize = null, $iShopId = null)
    {
        // get configuration options
        if (null !== $iShopId && is_numeric($iShopId)) {
            GMerchantCenter::$conf = Configuration::getMultiple(
                array_keys($GLOBALS[_GMC_MODULE_NAME . '_CONFIGURATION']),
                null,
                null,
                $iShopId
            );
        } else {
            GMerchantCenter::$conf = Configuration::getMultiple(array_keys($GLOBALS[_GMC_MODULE_NAME . '_CONFIGURATION']));
        }
        if (
            !empty($aOptionListToUnserialize)
            && is_array($aOptionListToUnserialize)
        ) {
            foreach ($aOptionListToUnserialize as $sOption) {
                if (
                    !empty(GMerchantCenter::$conf[strtoupper($sOption)])
                    && is_string(GMerchantCenter::$conf[strtoupper($sOption)])
                    && !is_numeric(GMerchantCenter::$conf[strtoupper($sOption)])
                ) {
                    GMerchantCenter::$conf[strtoupper($sOption)] = unserialize(GMerchantCenter::$conf[strtoupper($sOption)]);
                }
            }
        }
    }


    /**
     * set good iso lang
     *
     * @return string
     */
    public static function getLangIso($iLangId = null)
    {
        if (null === $iLangId) {
            $iLangId = GMerchantCenter::$iCurrentLang;
        }

        // get iso lang
        $sIsoLang = Language::getIsoById($iLangId);

        if (false === $sIsoLang) {
            $sIsoLang = 'en';
        }
        return $sIsoLang;
    }

    /**
     * return Lang id from iso code
     *
     * @param string $sIsoCode
     * @return int
     */
    public static function getLangId($sIsoCode, $iDefaultId = null)
    {
        // get iso lang
        $iLangId = Language::getIdByIso($sIsoCode);

        if (empty($iLangId) && $iDefaultId !== null) {
            $iLangId = $iDefaultId;
        }
        return $iLangId;
    }


    /**
     * get available languages
     *
     * @param int $iShopId
     * @return array
     */
    public static function getAvailableLanguages($iShopId)
    {
        // set
        $aAvailableLanguages = array();

        $aShopLanguages = Language::getLanguages(false, (int) ($iShopId));

        foreach ($aShopLanguages as $aLanguage) {
            if ($aLanguage['active'] && array_key_exists(
                $aLanguage['iso_code'],
                $GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES']
            )) {
                $aAvailableLanguages[] = $aLanguage;
            }
        }
        return $aAvailableLanguages;
    }


    /**
     * returns information about languages / countries and currencies available for Google
     *
     * @param array $aAvailableLanguages
     * @param array $aAvailableCountries
     * @return array
     */
    public static function getLangCurrencyCountry(array $aAvailableLanguages, array $aAvailableCountries)
    {
        // set
        $aLangCurrencyCountry = array();

        foreach ($aAvailableLanguages as $aLanguage) {
            if (isset($aAvailableCountries[$aLanguage['iso_code']])) {
                foreach ($aAvailableCountries[$aLanguage['iso_code']] as $sCountry => $aLocaleData) {
                    $oLanguage = new Language($aLanguage['id_lang']);
                    $iCountryId = Country::getByIso(Tools::strtolower($sCountry));
                    if ($iCountryId) {
                        $sCountryName = Country::getNameById(GMerchantCenter::$iCurrentLang, $iCountryId);
                        $oCountry = new Country($iCountryId);
                        if (!empty($oCountry->id)) {
                            foreach ($aLocaleData['currency'] as $iKey => $sCurrency) {
                                // manage the currency data
                                $iCurrencyId = Currency::getIdByIsoCode($sCurrency);
                                $oCurrency = new Currency($iCurrencyId);
                                if (Currency::getIdByIsoCode($sCurrency)) {
                                    $aLangCurrencyCountry[] = array(
                                        'langId' => $aLanguage['id_lang'],
                                        'langIso' => $aLanguage['iso_code'],
                                        'countryIso' => $sCountry,
                                        'currencyIso' => $sCurrency,
                                        'currencyId' => Currency::getIdByIsoCode($sCurrency),
                                        'currencyFirst' => ($iKey == 0 ? 1 : 0),
                                        'langName' => $oLanguage->name,
                                        'countryName' => $sCountryName,
                                        'currencySign' => $oCurrency->sign,
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }

        return $aLangCurrencyCountry;
    }


    /**
     * returns current currency sign or id
     *
     * @param string $sField : field name has to be returned
     * @param string $iCurrencyId : currency id
     * @return mixed : string or array
     */
    public static function getCurrency($sField = null, $iCurrencyId = null)
    {
        // set
        $mCurrency = null;

        // get currency id
        if (null === $iCurrencyId) {
            $iCurrencyId = Configuration::get('PS_CURRENCY_DEFAULT');
        }

        $aCurrency = Currency::getCurrency($iCurrencyId);

        if ($sField !== null) {
            switch ($sField) {
                case 'id_currency':
                    $mCurrency = $aCurrency['id_currency'];
                    break;
                case 'name':
                    $mCurrency = $aCurrency['name'];
                    break;
                case 'iso_code':
                    $mCurrency = $aCurrency['iso_code'];
                    break;
                case 'iso_code_num':
                    $mCurrency = $aCurrency['iso_code_num'];
                    break;
                case 'sign':
                    $mCurrency = $aCurrency['sign'];
                    break;
                case 'conversion_rate':
                    $mCurrency = $aCurrency['conversion_rate'];
                    break;
                case 'format':
                    $mCurrency = $aCurrency['format'];
                    break;
                default:
                    $mCurrency = $aCurrency;
                    break;
            }
        }

        return $mCurrency;
    }


    /**
     * returns template path
     *
     * @param string $sTemplate
     * @return string
     */
    public static function getTemplatePath($sTemplate)
    {
        // set
        $mTemplatePath = null;

        if (version_compare(_PS_VERSION_, '1.5', '>')) {
            $mTemplatePath = GMerchantCenter::$oModule->getTemplatePath($sTemplate);
        } else {
            if (file_exists(_PS_THEME_DIR_ . 'modules/' . GMerchantCenter::$oModule->name . '/' . $sTemplate)) {
                $mTemplatePath = _PS_THEME_DIR_ . 'modules/' . GMerchantCenter::$oModule->name . '/' . $sTemplate;
            } elseif (file_exists(_PS_MODULE_DIR_ . GMerchantCenter::$oModule->name . '/' . $sTemplate)) {
                $mTemplatePath = _PS_MODULE_DIR_ . GMerchantCenter::$oModule->name . '/' . $sTemplate;
            }
        }

        return $mTemplatePath;
    }


    /**
     * returns product link
     *
     * @param obj $oProduct
     * @param int $iLangId
     * @return string
     */
    public static function getProductLink($oProduct, $iLangId)
    {
        $sProdUrl = '';

        if (!empty(GMerchantCenter::$bCompare1550)) {
            $sProdUrl = Context::getContext()->link->getProductLink(
                $oProduct,
                null,
                null,
                null,
                (int) $iLangId,
                null,
                0,
                false
            );
        } else {
            if (Configuration::get('PS_REWRITING_SETTINGS')) {
                $sProdUrl = Context::getContext()->link->getProductLink(
                    $oProduct,
                    null,
                    null,
                    null,
                    (int) $iLangId,
                    null,
                    0,
                    true
                );
            } else {
                $sProdUrl = Context::getContext()->link->getProductLink(
                    $oProduct,
                    null,
                    null,
                    null,
                    (int) $iLangId,
                    null,
                    0,
                    false
                );
            }
        }

        return $sProdUrl;
    }

    /**
     * returns the product condition
     *
     * @param string $sCondition
     * @return string
     */
    public static function getProductCondition($sCondition = null)
    {
        $sResult = '';

        if (
            $sCondition !== null
            && in_array($sCondition, array('new', 'used', 'refurbished'))
        ) {
            $sResult = $sCondition;
        } else {
            $sResult = !empty(GMerchantCenter::$conf['GMC_COND']) ? GMerchantCenter::$conf['GMC_COND'] : 'new';
        }

        return $sResult;
    }


    /**
     * returns product image
     *
     * @param obj $oProduct
     * @param string $sImageType
     * @param array $aForceImage
     * @param string $sForceDomainName
     * @return obj
     */
    public static function getProductImage(
        Product &$oProduct,
        $sImageType = null,
        $aForceImage = false,
        $sForceDomainName = null
    ) {
        $sImgUrl = '';

        if (Validate::isLoadedObject($oProduct)) {
            // use case - get Image
            $aImage = $aForceImage !== false ? $aForceImage : $oProduct->getImages(GMerchantCenter::$iCurrentLang);

            if (!empty($aImage)) {

                // get image url
                if ($sImageType !== null) {
                    $sImgUrl = Context::getContext()->link->getImageLink(
                        $oProduct->link_rewrite,
                        $oProduct->id . '-' . $aImage['id_image'],
                        $sImageType
                    );
                } else {
                    $sImgUrl = Context::getContext()->link->getImageLink(
                        $oProduct->link_rewrite,
                        $oProduct->id . '-' . $aImage
                    );
                }

                // use case - get valid IMG URI before  Prestashop 1.4
                $sImgUrl = self::detectHttpUri($sImgUrl, $sForceDomainName);
            }
        }
        return $sImgUrl;
    }

    /**
     * detects and returns available URI - resolve Prestashop compatibility
     *
     * @param string $sURI
     * @param string $sForceDomainName
     * @return mixed
     */
    public static function detectHttpUri($sURI, $sForceDomainName = null)
    {
        // use case - only with relative URI
        if (!strstr($sURI, 'http')) {
            $sHost = $sForceDomainName !== null ? $sForceDomainName : $_SERVER['HTTP_HOST'];
            $sURI = 'http' . (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 's' : '') . '://' . $sHost . $sURI;
        } elseif (
            $sForceDomainName !== null
            && !strstr($sURI, $sForceDomainName)
            && strstr($sURI, $_SERVER['HTTP_HOST'])
        ) {
            $sTmpDomainName = str_replace(
                '//',
                '',
                substr($sForceDomainName, strpos($sForceDomainName, '//'), strlen($sForceDomainName))
            );
            $sURI = str_replace($_SERVER['HTTP_HOST'], $sTmpDomainName, $sURI);
        }
        return $sURI;
    }

    /**
     * truncate current request_uri in order to delete params : sAction and sType
     *
     * @param mixed: string or array $mNeedle
     * @return mixed
     */
    public static function truncateUri($mNeedle = '&sAction')
    {
        // set tmp
        $aQuery = is_array($mNeedle) ? $mNeedle : array($mNeedle);

        // get URI
        $sURI = $_SERVER['REQUEST_URI'];

        foreach ($aQuery as $sNeedle) {
            $sURI = strstr($sURI, $sNeedle) ? substr($sURI, 0, strpos($sURI, $sNeedle)) : $sURI;
        }
        return $sURI;
    }

    /**
     * detects available method and apply json encode
     *
     * @return string
     */
    public static function jsonEncode($aData)
    {
        if (method_exists('Tools', 'jsonEncode')) {
            $aData = Tools::jsonEncode($aData);
        } elseif (function_exists('json_encode')) {
            $aData = json_encode($aData);
        } else {
            if (is_null($aData)) {
                return 'null';
            }
            if ($aData === false) {
                return 'false';
            }
            if ($aData === true) {
                return 'true';
            }
            if (is_scalar($aData)) {
                $aData = addslashes($aData);
                $aData = str_replace("\n", '\n', $aData);
                $aData = str_replace("\r", '\r', $aData);
                $aData = preg_replace('{(</)(script)}i', "$1'+'$2", $aData);
                return "'$aData'";
            }
            $isList = true;
            for ($i = 0, reset($aData); $i < count($aData); $i++, next($aData)) {
                if (key($aData) !== $i) {
                    $isList = false;
                    break;
                }
            }
            $result = array();

            if ($isList) {
                foreach ($aData as $v) {
                    $result[] = self::json_encode($v);
                }
                $aData = '[ ' . join(', ', $result) . ' ]';
            } else {
                foreach ($aData as $k => $v) {
                    $result[] = self::json_encode($k) . ': ' . self::json_encode($v);
                }
                $aData = '{ ' . join(', ', $result) . ' }';
            }
        }

        return $aData;
    }

    /**
     * detects available method and apply json decode
     *
     * @return mixed
     */
    public static function jsonDecode($aData)
    {
        if (method_exists('Tools', 'jsonDecode')) {
            $aData = Tools::jsonDecode($aData);
        } elseif (function_exists('json_decode')) {
            $aData = json_decode($aData);
        }

        return $aData;
    }

    /**
     * check if specific module and module's vars are available
     *
     * @param int $sModuleName
     * @param array $aCheckedVars
     * @param bool $bObjReturn
     * @param bool $bOnlyInstalled
     * @return mixed : true or false or obj
     */
    public static function isInstalled(
        $sModuleName,
        array $aCheckedVars = array(),
        $bObjReturn = false,
        $bOnlyInstalled = false
    ) {
        $mReturn = false;

        // use case - check module is installed in DB
        if (Module::isInstalled($sModuleName)) {
            if (!$bOnlyInstalled) {
                $oModule = Module::getInstanceByName($sModuleName);

                if (!empty($oModule)) {
                    // check if module is activated
                    $aActivated = Db::getInstance()->ExecuteS('SELECT id_module as id, active FROM ' . _DB_PREFIX_ . 'module WHERE name = "' . pSQL($sModuleName) . '" AND active = 1');

                    if (!empty($aActivated[0]['active'])) {
                        $mReturn = true;

                        if (version_compare(_PS_VERSION_, '1.5', '>')) {
                            $aActivated = Db::getInstance()->ExecuteS('SELECT * FROM ' . _DB_PREFIX_ . 'module_shop WHERE id_module = ' . pSQL($aActivated[0]['id']) . ' AND id_shop = ' . Context::getContext()->shop->id);

                            if (empty($aActivated)) {
                                $mReturn = false;
                            }
                        }

                        if ($mReturn) {
                            if (!empty($aCheckedVars)) {
                                foreach ($aCheckedVars as $sVarName) {
                                    $mVar = Configuration::get($sVarName);

                                    if (empty($mVar)) {
                                        $mReturn = false;
                                    }
                                }
                            }
                        }
                    }
                }
                if ($mReturn && $bObjReturn) {
                    $mReturn = $oModule;
                }
            } else {
                $mReturn = true;
            }
        }
        return $mReturn;
    }

    /**
     * write breadcrumbs of product for category
     *
     * @param int $iCatId
     * @param int $iLangId
     * @param string $sPath
     * @param bool $bEncoding
     * @return string
     */
    public static function getProductPath($iCatId, $iLangId, $sPath = '', $bEncoding = true)
    {
        $oCategory = new Category($iCatId);

        return (Validate::isLoadedObject($oCategory) ? strip_tags(self::getPath((int) $oCategory->id, (int) $iLangId, $sPath, $bEncoding)) : '');
    }

    /**
     * write breadcrumbs of product for category
     *
     * Forced to redo the function from Tools here as it works with cookie
     * for language, not a passed parameter in the function
     *
     * @param int $iCatId
     * @param int $iLangId
     * @param string $sPath
     * @param bool $bEncoding
     * @return string
     */
    public static function getPath($iCatId, $iLangId, $sPath = '', $bEncoding = true)
    {
        $mReturn = '';

        if ($iCatId == 1) {
            $mReturn = $sPath;
        } else {
            // get pipe
            $sPipe = ' > ';
            $sFullPath = '';

            /* New way for versions between v1.5 to v1.5.6.0 */
            if (version_compare(_PS_VERSION_, '1.5.6.0', '<')) {
                $aCurrentCategory = Db::getInstance()->getRow(
                    '
					SELECT id_category, level_depth, nleft, nright
					FROM ' . _DB_PREFIX_ . 'category
					WHERE id_category = ' . (int) $iCatId
                );

                if (isset($aCurrentCategory['id_category'])) {
                    $sQuery = 'SELECT c.id_category, cl.name, cl.link_rewrite FROM ' . _DB_PREFIX_ . 'category c ' . Shop::addSqlAssociation(
                        'category',
                        'c',
                        false
                    )
                        . ' LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON (cl.id_category = c.id_category AND cl.`id_lang` = ' . (int) ($iLangId) . Shop::addSqlRestrictionOnLang('cl') . ')'
                        . 'WHERE c.nleft <= ' . (int) $aCurrentCategory['nleft'] . ' AND c.nright >= ' . (int) $aCurrentCategory['nright'] . ' AND cl.id_lang = ' . (int) ($iLangId) . ' AND c.id_category != 1
						ORDER BY c.level_depth ASC
						LIMIT ' . (int) $aCurrentCategory['level_depth'];

                    $aCategories = Db::getInstance()->ExecuteS($sQuery);

                    $iCount = 1;
                    $nCategories = count($aCategories);

                    foreach ($aCategories as $aCategory) {
                        $sFullPath .= ($bEncoding ? htmlentities($aCategory['name'], ENT_NOQUOTES, 'UTF-8') : $aCategory['name']) . (($iCount++ != $nCategories or !empty($sPath)) ? $sPipe : '');
                    }
                    $mReturn = $sFullPath . $sPath;
                }
            } else {
                $aInterval = Category::getInterval($iCatId);
                $aIntervalRoot = Category::getInterval(Context::getContext()->shop->getCategory());

                if (!empty($aInterval) && !empty($aIntervalRoot)) {
                    $sQuery = 'SELECT c.id_category, cl.name, cl.link_rewrite'
                        . ' FROM ' . _DB_PREFIX_ . 'category c '
                        . Shop::addSqlAssociation('category', 'c', false)
                        . ' LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON (cl.id_category = c.id_category' . Shop::addSqlRestrictionOnLang('cl') . ')'
                        . 'WHERE c.nleft <= ' . (int)$aInterval['nleft']
                        . ' AND c.nright >= ' . (int)$aInterval['nright']
                        . ' AND c.nleft >= ' . (int)$aIntervalRoot['nleft']
                        . ' AND c.nright <= ' . (int)$aIntervalRoot['nright']
                        . ' AND cl.id_lang = ' . (int) $iLangId
                        . ' AND c.level_depth > ' . (int) $aIntervalRoot['level_depth']
                        . ' ORDER BY c.level_depth ASC';

                    $aCategories = Db::getInstance()->executeS($sQuery);

                    $iCount = 1;
                    $nCategories = count($aCategories);

                    foreach ($aCategories as $aCategory) {
                        $sFullPath .= ($bEncoding ? htmlentities($aCategory['name'], ENT_NOQUOTES, 'UTF-8') : $aCategory['name']) . (($iCount++ != $nCategories || !empty($sPath)) ? $sPipe : '');
                    }
                    $mReturn = $sFullPath . $sPath;
                }
            }
        }
        return $mReturn;
    }


    /**
     * process categories to generate tree of them
     *
     * @param array $aCategories
     * @param array $aIndexedCat
     * @param array $aCurrentCat
     * @param int $iCurrentIndex
     * @param int $iDefaultId
     * @param bool $bFirstExec
     * @return array
     */
    public static function recursiveCategoryTree(
        array $aCategories,
        array $aIndexedCat,
        $aCurrentCat,
        $iCurrentIndex = 1,
        $iDefaultId = null,
        $bFirstExec = false
    ) {
        // set variables
        static $_aTmpCat;
        static $_aFormatCat;

        if ($bFirstExec) {
            $_aTmpCat = null;
            $_aFormatCat = null;
        }

        if (!isset($_aTmpCat[$aCurrentCat['infos']['id_parent']])) {
            $_aTmpCat[$aCurrentCat['infos']['id_parent']] = 0;
        }
        $_aTmpCat[$aCurrentCat['infos']['id_parent']] += 1;

        // calculate new level
        $aCurrentCat['infos']['iNewLevel'] = $aCurrentCat['infos']['level_depth'] + (version_compare(
            _PS_VERSION_,
            '1.5.0'
        ) != -1 ? 0 : 1);

        // calculate type of gif to display - displays tree in good
        $aCurrentCat['infos']['sGifType'] = (count($aCategories[$aCurrentCat['infos']['id_parent']]) == $_aTmpCat[$aCurrentCat['infos']['id_parent']] ? 'f' : 'b');

        // calculate if checked
        if (in_array($iCurrentIndex, $aIndexedCat)) {
            $aCurrentCat['infos']['bCurrent'] = true;
        } else {
            $aCurrentCat['infos']['bCurrent'] = false;
        }

        // define classname with default cat id
        $aCurrentCat['infos']['mDefaultCat'] = ($iDefaultId === null) ? 'default' : $iCurrentIndex;

        $_aFormatCat[] = $aCurrentCat['infos'];

        if (isset($aCategories[$iCurrentIndex])) {
            foreach ($aCategories[$iCurrentIndex] as $iCatId => $aCat) {
                if ($iCatId != 'infos') {
                    self::recursiveCategoryTree(
                        $aCategories,
                        $aIndexedCat,
                        $aCategories[$iCurrentIndex][$iCatId],
                        $iCatId
                    );
                }
            }
        }
        return $_aFormatCat;
    }

    /**
     * process brands to generate tree of them
     *
     * @param array $aBrands
     * @param array $aIndexedBrands
     * @return array
     */
    public static function recursiveBrandTree(array $aBrands, array $aIndexedBrands)
    {
        // set
        $aFormatBrands = array();

        foreach ($aBrands as $iIndex => $aBrand) {
            $aFormatBrands[] = array(
                'id' => $aBrand['id_manufacturer'],
                'name' => $aBrand['name'],
                'checked' => (in_array($aBrand['id_manufacturer'], $aIndexedBrands) ? true : false)
            );
        }

        return $aFormatBrands;
    }

    /**
     * process suppliers to generate tree of them
     *
     * @param array $aSuppliers
     * @param array $aIndexedSuppliers
     * @return array
     */
    public static function recursiveSupplierTree(array $aSuppliers, array $aIndexedSuppliers)
    {
        // set
        $aFormatSuppliers = array();

        foreach ($aSuppliers as $iIndex => $aSupplier) {
            $aFormatSuppliers[] = array(
                'id' => $aSupplier['id_supplier'],
                'name' => $aSupplier['name'],
                'checked' => (in_array($aSupplier['id_supplier'], $aIndexedSuppliers) ? true : false)
            );
        }

        return $aFormatSuppliers;
    }

    /**
     * round on numeric
     *
     * @param float $fVal
     * @param int $iPrecision
     * @return float
     */
    public static function round($fVal, $iPrecision = 2)
    {
        if (method_exists('Tools', 'ps_round')) {
            $fVal = Tools::ps_round($fVal, $iPrecision);
        } else {
            $fVal = round($fVal, $iPrecision);
        }

        return $fVal;
    }

    /**
     * set host
     *
     * @return string
     */
    public static function setHost()
    {
        if (Configuration::get('PS_SHOP_DOMAIN') != false) {
            $sURL = 'http://' . Configuration::get('PS_SHOP_DOMAIN');
        } else {
            $sURL = 'http://' . $_SERVER['HTTP_HOST'];
        }

        return $sURL;
    }

    /**
     * getBaseLink
     *
     * @return string
     */
    public static function getBaseLink()
    {
        static $baseLink = null;
        if ($baseLink === null) {
            $context = Context::getContext();
            $force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
            $ssl = $force_ssl;
            $base = (($ssl && Configuration::get('PS_SSL_ENABLED')) ? 'https://' . $context->shop->domain_ssl : 'http://' . $context->shop->domain);
            $baseLink = $base . $context->shop->getBaseURI();
        }
        return $baseLink;
    }


    /**
     * set the XML file's prefix
     *
     * @return string
     */
    public static function setXmlFilePrefix()
    {
        return empty(GMerchantCenter::$conf['GMC_FEED_PROTECTION']) ? 'gmerchantcenter' : 'gmerchantcenter' . GMerchantCenter::$conf['GMC_FEED_TOKEN'];
    }

    /**
     * copy module's on-fly php file
     *
     * @return bool
     */
    public static function copyOutputOnFlyFile()
    {
        @copy(_GMC_PATH_ROOT . _GMC_FEED_PHP_NAME, _PS_ROOT_DIR_ . '/' . _GMC_FEED_PHP_NAME);
        return true;
    }

    /**
     * Build file suffix based on language and country ISO code
     *
     * @param string $sLangIso
     * @param string $sCountryIso
     * @param string $sCurrency
     * @param int $iShopId
     * @return string
     */
    public static function buildFileSuffix($sLangIso, $sCountryIso, $sCurrency = '', $iShopId = 0)
    {
        if (Tools::strtolower($sLangIso) == Tools::strtolower($sCountryIso)) {
            $sSuffix = Tools::strtolower($sLangIso);
        } else {
            $sSuffix = Tools::strtolower($sLangIso) . '.' . Tools::strtolower($sCountryIso);
        }
        if (!empty($sCurrency)) {
            $sSuffix .= '.' . $sCurrency;
        }
        $sSuffix .= ($iShopId ? '.shop' . $iShopId : '.shop' . GMerchantCenter::$iShopId);

        return $sSuffix;
    }

    /**
     * returns all available condition
     */
    public static function getConditionType()
    {
        return array(
            'new' => GMerchantCenter::$oModule->l('New', 'module-tools_class'),
            'used' => GMerchantCenter::$oModule->l('Used', 'module-tools_class'),
            'refurbished' => GMerchantCenter::$oModule->l('Refurbished', 'module-tools_class'),
        );
    }

    /**
     * returns all available description
     */
    public static function getDescriptionType()
    {
        return array(
            1 => GMerchantCenter::$oModule->l('Short description', 'module-tools_class'),
            2 => GMerchantCenter::$oModule->l('Long description', 'module-tools_class'),
            3 => GMerchantCenter::$oModule->l('Both', 'module-tools_class'),
            4 => GMerchantCenter::$oModule->l('Meta-description', 'module-tools_class')
        );
    }

    /**
     * set all available attributes managed in google flux
     */
    public static function loadGoogleTags()
    {
        return array(
            '_no_available_for_order' => array(
                'label' => 'no_available_for_order',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported because the "available for order" option is not activated', 'module-tools_class') . '.',
                'faq_id' => 237,
                'anchor' => ''
            ),
            '_no_product_name' => array(
                'label' => 'no_product_name',
                'type' => 'error',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported because the product name is missing', 'module-tools_class') . '.',
                'faq_id' => 210,
                'anchor' => ''
            ),
            '_no_required_data' => array(
                'label' => 'no_required_data',
                'type' => 'error',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported because one of this information is missing: product name or product description or product URL or image URL', 'module-tools_class') . '.',
                'faq_id' => 0,
                'anchor' => ''
            ),
            '_no_export_no_supplier_ref' => array(
                'label' => 'not_export_without_supplier_ref',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported due to missing MPN reference. Please review the configuration of unique product identifiers', 'module-tools_class') . '.',
                'faq_id' => 198,
                'anchor' => ''
            ),
            '_no_export_no_ean_upc' => array(
                'label' => 'not_export_without_EAN13_UPC_ref',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported due to missing EAN/UPC reference. Please review the configuration of unique product identifiers', 'module-tools_class') . '.',
                'faq_id' => 192,
                'anchor' => ''
            ),
            '_no_export_no_stock' => array(
                'label' => 'not_export_no_stock',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported due to out of stock export settings', 'module-tools_class') . '.',
                'faq_id' => 22,
                'anchor' => ''
            ),
            '_no_export_min_price' => array(
                'label' => 'not_export_under_min_price',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Products not exported due to minimum price settings', 'module-tools_class') . '.',
                'faq_id' => 22,
                'anchor' => ''
            ),
            // Product exported but missing information
            'excluded' => array(
                'label' => 'excluded_product_list',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('this product or combination has been excluded from your feed as you defined it in the exclusion rules tab', 'module-tools_class') . '.',
                'faq_id' => 22,
                'anchor' => ''
            ),
            'id' => array(
                'label' => '<g:id>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "ID" tag => This is the unique identifier of the item', 'module-tools_class') . '.',
                'faq_id' => 194,
                'anchor' => 'prod_id'
            ),
            'title' => array(
                'label' => 'title',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "TITLE" tag => This is the title of the item', 'module-tools_class') . '.',
                'faq_id' => 210,
                'anchor' => 'title'
            ),
            'description' => array(
                'label' => 'description',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "DESCRIPTION" tag => This is the description of the item', 'module-tools_class') . '.',
                'faq_id' => 196,
                'anchor' => 'prod_description'
            ),
            'google_product_category' => array(
                'label' => '<g:google_product_category>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "GOOGLE PRODUCT CATEGORY" tag => You have to associate each product default category with an official Google category', 'module-tools_class') . '.',
                'faq_id' => 212,
                'anchor' => 'google_category'
            ),
            'product_type' => array(
                'label' => '<g:product_type>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "PRODUCT TYPE" tag => Unlike the "Google Product Category" tag, the "Product Type" tag contains the information about the category of the product according to your own classification', 'module-tools_class') . '.',
                'faq_id' => 211,
                'anchor' => 'prod_type'
            ),
            'link' => array(
                'label' => 'link',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "LINK" tag => This is the link of the item', 'module-tools_class') . '.',
                'faq_id' => 204,
                'anchor' => 'prod_link'
            ),
            'image_link' => array(
                'label' => '<g:image_link>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "IMAGE LINK" tag => This is the URL of the main image of the product', 'module-tools_class') . '.',
                'faq_id' => 203,
                'anchor' => 'image_link'
            ),
            'condition' => array(
                'label' => '<g:condition>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "CONDITION" tag => This is the condition of the item. There are only 3 accepted values: "new", "refurbished" and "used"', 'module-tools_class') . '.',
                'faq_id' => 195,
                'anchor' => 'prod_condition'
            ),
            'availability' => array(
                'label' => '<g:availability>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "AVAILABILITY" tag => This indicates the availability of the item. There are only 3 accepted values: "in stock", "out of stock" and "preorder"', 'module-tools_class') . '.',
                'faq_id' => 213,
                'anchor' => 'prod_availability'
            ),
            'price' => array(
                'label' => '<g:price>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "PRICE" tag => This is the price of the item', 'module-tools_class') . '.',
                'faq_id' => 190,
                'anchor' => 'prod_price'
            ),
            'gtin' => array(
                'label' => '<g:gtin>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "GTIN" tag => The "Global Trade Item Number" is one of the Unique Product Identifiers', 'module-tools_class') . '.',
                'faq_id' => 192,
                'anchor' => 'prod_gtin'
            ),
            'brand' => array(
                'label' => '<g:brand>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "BRAND" tag => The product brand is one of the Unique Product Identifiers', 'module-tools_class') . '.',
                'faq_id' => 197,
                'anchor' => 'prod_brand'
            ),
            'mpn' => array(
                'label' => '<g:mpn>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "MPN tag=> The "Manufacturer Part Number" of a product is one of the Unique Product Identifiers', 'module-tools_class') . '.',
                'faq_id' => 198,
                'anchor' => 'prod_mpn'
            ),
            'adult' => array(
                'label' => '<g:adult>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "ADULT" tag => This tag indicates that the item is for adults only', 'module-tools_class') . '.',
                'faq_id' => 222,
                'anchor' => 'adult'
            ),
            'gender' => array(
                'label' => '<g:gender>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "GENDER" tag => This tag allows you specify the gender your product is designed for. You can choose between 3 predefined values: "male", "female" or "unisex"', 'module-tools_class') . '.',
                'faq_id' => 209,
                'anchor' => 'gender'
            ),
            'sizeType' => array(
                'label' => '<g:size_type>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "SIZE TYPE" tag => This tag allows you to give an additional information about clothing size. You can choose between 4 predefined values: "regular", petite", "oversize" or "maternity"', 'module-tools_class') . '.',
                'faq_id' => 220,
                'anchor' => 'sizeTyp'
            ),
            'sizeSystem' => array(
                'label' => '<g:size_system>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "SIZE SYSTEM" tag => This tag allows you to indicate which country’s sizing system you use for the item', 'module-tools_class') . '.',
                'faq_id' => 221,
                'anchor' => 'sizeTyp'
            ),
            'age_group' => array(
                'label' => '<g:age_group>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "AGE GROUP" tag => This tag allows you to specify the age group your product is designed for. You can choose between 5 predefined values: "adults", "kids","toddler","infant" or "newborn"', 'module-tools_class') . '.',
                'faq_id' => 202,
                'anchor' => 'age_group'
            ),
            'color' => array(
                'label' => '<g:color>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "COLOR" tag => This defines the dominant color(s) of an item', 'module-tools_class') . '.',
                'faq_id' => 199,
                'anchor' => 'size_color'
            ),
            'size' => array(
                'label' => '<g:size>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "SIZE" tag => This indicates the size of an item', 'module-tools_class') . '.',
                'faq_id' => 201,
                'anchor' => 'size_color'
            ),
            'material' => array(
                'label' => '<g:material>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "MATERIAL" tag => This tag indicates the main fabric or material that the item is made of', 'module-tools_class') . '.',
                'faq_id' => 205,
                'anchor' => 'pattern'
            ),
            'pattern' => array(
                'label' => '<g:pattern>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "PATTERN" tag => This tag indicates the pattern or graphic print on the item', 'module-tools_class') . '.',
                'faq_id' => 206,
                'anchor' => 'pattern'
            ),
            'item_group_id' => array(
                'label' => '<g:item_group_id>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "ITEM GROUP ID" tag => All items that are color/material/pattern/size variants of the same product must have the same item group id', 'module-tools_class') . '.',
                'faq_id' => 0,
                'anchor' => ''
            ),
            'shipping_weight' => array(
                'label' => '<g:shipping_weight>',
                'type' => 'warning',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('The "SHIPPING WEIGHT" tag => This is the weight of the item used to calculate the shipping cost of the item', 'module-tools_class') . '.',
                'faq_id' => 214,
                'anchor' => 'shipping_weight'
            ),
            'shipping' => array(
                'label' => '<g:shipping>',
                'type' => 'error',
                'mandatory' => true,
                'msg' => GMerchantCenter::$oModule->l('The "SHIPPING" tag => The shipping tag lets you override the shipping cost defined in your Merchant Center account for an item', 'module-tools_class') . '.',
                'faq_id' => 51,
                'anchor' => ''
            ),
            // Product exported which do not respect Google prerequisites
            'title_length' => array(
                'label' => 'not_respect_title_length',
                'type' => 'notice',
                'mandatory' => false,
                'msg' => GMerchantCenter::$oModule->l('Google requires your product titles to be no more than 150 characters long', 'module-tools_class') . '.',
                'faq_id' => 210,
                'anchor' => ''
            ),
        );
    }

    /**
     * returns the Google taxonomy file's content
     *
     * @param string $sUrl
     * @return string
     */
    public static function getGoogleFile($sUrl)
    {
        $sContent = false;

        // Let's try first with file_get_contents
        if (ini_get('allow_url_fopen')) {
            $sContent = (method_exists('Tools', 'file_get_contents') ? Tools::file_get_contents($sUrl) : file_get_contents($sUrl));
        }

        // Returns false ? Try with CURL if available
        if ($sContent === false && function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $sUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_VERBOSE => true
            ));

            $sContent = @curl_exec($ch);
            curl_close($ch);
        }

        // Will return false if no method is available, or if either fails
        // This will cause a JavaScript alert to be triggered by the AJAX call
        return $sContent;
    }

    /**
     * returns the generated report files
     *
     * @return array
     */
    public static function getGeneratedReport()
    {
        $aLangCurrencies = array();

        foreach (GMerchantCenter::$aAvailableLanguages as $aLanguage) {
            foreach ($GLOBALS[_GMC_MODULE_NAME . '_AVAILABLE_COUNTRIES'][$aLanguage['iso_code']] as $sCountry => $aLocaleData) {
                foreach ($aLocaleData['currency'] as $sCurrency) {
                    if (Currency::getIdByIsoCode($sCurrency)) {
                        if (self::checkReportFile($aLanguage['iso_code'], $sCountry, $sCurrency)) {
                            $aLangCurrencies[] = $aLanguage['iso_code'] . '_' . $sCountry . '_' . $sCurrency;
                        }
                    }
                }
            }
        }

        return $aLangCurrencies;
    }

    /**
     * format the product title by uncap or not or leave uppercase only first character of each word
     *
     * @param string $sTitle
     * @param string $sBrand
     * @return string
     */
    public static function formatProductTitle($sTitle, $iFormatMode = 0)
    {
        $sResult = '';

        // format title
        if ($iFormatMode == 0) {
            $sResult = self::strToUtf8($sTitle);
        } else {
            $sResult = self::strToLowerUtf8($sTitle);

            if ($iFormatMode == 1) {
                $aResult = explode(' ', $sResult);

                foreach ($aResult as &$sWord) {
                    $sWord = Tools::ucfirst(trim($sWord));
                }

                $sResult = implode(' ', $aResult);
            } else {
                $sResult = Tools::ucfirst(trim($sResult));
            }
        }

        return $sResult;
    }

    /**
     * format the product name with combination
     *
     * @param int $iAttrId
     * @param int $iCurrentLang
     * @param int $iShopId
     * @return string
     */
    public static function getProductCombinationName($iAttrId, $iCurrentLang, $iShopId)
    {
        require_once(_GMC_PATH_LIB . 'module-dao_class.php');

        // set var
        $sProductName = '';

        $aCombinations = BT_GmcModuleDao::getProductComboAttributes($iAttrId, $iCurrentLang, $iShopId);

        if (!empty($aCombinations)) {
            $sExtraName = '';
            foreach ($aCombinations as $c) {
                $sExtraName .= ' ' . Tools::stripslashes($c['name']);
            }
            $sProductName .= $sExtraName;
        }

        return $sProductName;
    }


    /**
     * uncap the product title
     *
     * @param int $iAdvancedProdName
     * @param string $sProdName
     * @param string $sCatName
     * @param string $sManufacturerName
     * @param int $iLength
     * @return string
     */
    public static function truncateProductTitle($iAdvancedProdName, $sProdName, $sCatName, $sManufacturerName, $iLength)
    {
        if (function_exists('mb_substr')) {
            switch ($iAdvancedProdName) {
                case 0:
                    $sProdName = mb_substr($sProdName, 0, $iLength);
                    break;
                case 1:
                    $sProdName = mb_substr($sCatName . ' - ' . $sProdName, 0, $iLength);
                    break;
                case 2:
                    $sProdName = mb_substr($sProdName . ' - ' . $sCatName, 0, $iLength);
                    break;
                case 3:
                    $sBrand = !empty($sManufacturerName) ? $sManufacturerName . ' - ' : '';
                    $sProdName = mb_substr($sBrand . $sProdName, 0, $iLength);
                    break;
                case 4:
                    $sBrand = !empty($sManufacturerName) ? ' - ' . $sManufacturerName : '';
                    $sProdName = mb_substr($sProdName . $sBrand, 0, $iLength);
                    break;
                default:
                    break;
            }
        }

        return Tools::stripslashes($sProdName);
    }

    /**
     * Used by uncapProductTitle. strtolower doesn't work with UTF-8
     * The second solution if no mb_strtolower available is not perfect but will work
     * with most European languages. Worse comes to worse, the person may chose not to uncap
     *
     * @param $sString
     * return string
     */
    public static function strToLowerUtf8($sString)
    {
        return function_exists('mb_strtolower') ? mb_strtolower($sString, 'utf-8') : utf8_encode(Tools::strtolower(utf8_decode($sString)));
    }

    /**
     * Used by uncapProductTitle. strToUtf8 doesn't work with UTF-8
     * The second solution if no mb_convert_encoding available is not perfect but will work
     * with most European languages. Worse comes to worse, the person may chose not to uncap
     *
     * @param $sString
     * return string
     */
    public static function strToUtf8($sString)
    {
        return function_exists('mb_convert_encoding') ? mb_convert_encoding($sString, 'utf-8') : utf8_encode(utf8_decode($sString));
    }

    /**
     * Check file based on language and country ISO code
     *
     * @param string $sIsoLang
     * @param string $sIsoCountry
     * @param string $sCurrencyIso
     * @return bool
     */
    public static function checkReportFile($sIsoLang, $sIsoCountry, $sCurrencyIso)
    {
        $sFilename = _GMC_REPORTING_DIR . 'reporting-' . $sIsoLang . '-' . Tools::strtolower($sIsoCountry) . '-' . $sCurrencyIso . '.txt';

        return (file_exists($sFilename) && filesize($sFilename)) ? true : false;
    }

    /**
     * detect if we use price tax or not for the specific feed
     *
     * @param string $sLangIso
     * @param string $sCountryIso
     * @param string $sCurrency
     * @return bool
     */
    public static function isTax($sLangIso, $sCountryIso)
    {
        // handle tax and shipping fees
        $aFeedTax = (!empty(GMerchantCenter::$conf['GMC_FEED_TAX']) ? GMerchantCenter::$conf['GMC_FEED_TAX'] : array());

        // handle price with tax or not
        if (!empty($aFeedTax)) {
            $bUseTax = array_key_exists(Tools::strtolower($sLangIso) . '_' . Tools::strtoupper($sCountryIso), $aFeedTax) ? $aFeedTax[Tools::strtolower($sLangIso) . '_' . Tools::strtoupper($sCountryIso)] : 1;
        } else {
            $bUseTax = 1;
        }
        return $bUseTax;
    }

    /**
     * clean up MS Word style quotes and other characters Google does not like
     *
     * @param string $str
     * @return string
     */
    public static function cleanUp($str)
    {
        $str = str_replace('<br>', "\n", $str);
        $str = str_replace('<br />', "\n", $str);
        $str = str_replace('</p>', "\n", $str);
        $str = str_replace('<p>', '', $str);

        $quotes = array(
            "\xC2\xAB" => '"', // « (U+00AB) in UTF-8
            "\xC2\xBB" => '"', // » (U+00BB) in UTF-8
            "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
            "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
            "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
            "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
            "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
            "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
            "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
            "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
            "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
            "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
            "\xE2\x80\x94" => '-', // —
        );

        $str = strtr($str, $quotes);
        return trim(strip_tags($str));
    }


    /**
     * format the date for Google prerequisistes
     *
     * @param string $str
     * @return string
     */
    public static function formatDateISO8601($sDate)
    {
        $sDate = new DateTime($sDate);

        return $sDate->format(DateTime::ISO8601);
    }


    /**
     * check the gtin value
     *
     * @param string $sPriority the priority
     * @param array $aProduct the product information
     * @return string
     */
    public static function getGtin($sPriority, $aProduct)
    {
        $sGtin = '';

        if ($sPriority == 'ean') {
            if (
                !empty($aProduct['ean13'])
                && (Tools::strlen($aProduct['ean13']) == 8
                    || Tools::strlen($aProduct['ean13']) == 12
                    || Tools::strlen($aProduct['ean13']) == 13)
            ) {
                $sGtin = $aProduct['ean13'];
            } elseif (
                !empty($aProduct['upc'])
                && (Tools::strlen($aProduct['upc']) == 8
                    || Tools::strlen($aProduct['upc']) == 12
                    || Tools::strlen($aProduct['upc']) == 13)
            ) {
                $sGtin = $aProduct['upc'];
            }
        } else {
            if (
                !empty($aProduct['upc'])
                && (Tools::strlen($aProduct['upc']) == 8
                    || Tools::strlen($aProduct['upc']) == 12
                    || Tools::strlen($aProduct['upc']) == 13)
            ) {
                $sGtin = $aProduct['upc'];
            } elseif (
                !empty($aProduct['ean13'])
                && (Tools::strlen($aProduct['ean13']) == 8
                    || Tools::strlen($aProduct['ean13']) == 12
                    || Tools::strlen($aProduct['ean13']) == 13)
            ) {
                $sGtin = $aProduct['ean13'];
            }
        }

        return $sGtin;
    }

    /**
     * check if multi-shop is activated and if the group or global context is used
     *
     * @return bool
     */
    public static function checkGroupMultiShop()
    {
        return (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')
            && empty(GMerchantCenter::$oCookie->shopContext));
    }


    /**
     * Sanitize product properties formatted as array instead of a string matching to the current language
     * @param $property
     * @param $iLangId
     * @return mixed|string
     */
    public static function sanitizeProductProperty($property, $iLangId)
    {
        $content = '';

        // check if the product name is an array
        if (is_array($property)) {
            if (count($property) == 1) {
                $content = reset($property);
            } elseif (isset($property[$iLangId])) {
                $content = $property[$iLangId];
            }
        } else {
            $content = $property;
        }
        return $content;
    }

    /**
     * added module order state
     *  @param string $sName // The name of the order state
     *  @param string $sColor // The color value of the order state
     *  @param bool $bEmail // the status for email 
     *  @param string $sModuleName // The module name
     *  @param string $sEmailTemplate // Email name template
     *  @param bool $bIsValid // Set the consider as valid option 
     *  @param bool $bIsPaid // Set the paid status
     *  @param bool $bIsShipped // Set the shipped status
     *  @param bool $bIsRefunded // Set refunded status
     *  @param bool $bInvoice // Set the invoice 
     *  @return array
     */
    public static function addOrderState($sName, $sColor, $bEmail, $sModuleName, $sEmailTemplate, $bIsValid = true, $bIsPaid = false, $bIsShipped = false, $bInvoice = false)
    {
        $bExist = false;
        $aStates = OrderState::getOrderStates(GMerchantCenter::$iCurrentLang);

        // check if order state exist
        foreach ($aStates as $aStates) {
            if (in_array($sName, $aStates)) {
                $bExist = true;
                break;
            }
        }

        // If the state does not exist, we create it.
        if (!$bExist) {
            // create new order state
            $order_state = new OrderState();
            $order_state->color = (string) $sColor;
            $order_state->send_email = $bEmail;
            $order_state->module_name = (string) $sModuleName;
            $order_state->template = (string) $sEmailTemplate;
            $order_state->logable = $bIsValid;
            $order_state->paid = $bIsPaid;
            $order_state->shipped = $bIsShipped;
            $order_state->invoice = $bInvoice;
            $order_state->name = array();
            $aLanguages = Language::getLanguages(false);
            foreach ($aLanguages as $aLanguage)
                $order_state->name[$aLanguage['id_lang']] = $sName;

            // Update object
            if (!$order_state->add()) {
                throw new Exception("The order status has not been added", 100);
            }
        }

        return true;
    }

    /**
     * get the dimension in the good format you can check all data about this in https://support.google.com/merchants/answer/6324498?hl=en
     * @param $sWidth
     * @param $sHeight
     * @param $sLenght
     * @return array
     */
    public static function getDimension($sWidth, $sHeight, $sLenght)
    {
        $aDimension = array();

        // Only handle if unit is valid for Google
        if (in_array(Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT')), $GLOBALS['GMC_DIMENSION_UNITS'])) {
            
            // Convert the data 
            $sWidth = (int)number_format($sWidth, 2, '.', '');
            $sHeight = (int)number_format($sHeight, 2, '.', '');
            $sLenght = (int)number_format($sLenght, 2, '.', '');

            // Use case for CM
            if (Configuration::get('PS_DIMENSION_UNIT') == 'cm') {
                if ($sWidth > 1 && $sWidth <= 400 &&  $sHeight > 1 && $sHeight <= 400 && $sLenght > 1 && $sLenght <= 400) {
                    $aDimension['shipping_width'] = $sWidth . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                    $aDimension['shipping_height'] = $sHeight . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                    $aDimension['shipping_length'] = $sLenght . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                }  
            }

            // Use case for inch
            if (Configuration::get('PS_DIMENSION_UNIT') == 'in') {
                if ($sWidth > 1 && $sWidth <= 150 &&  $sHeight > 1 && $sHeight <= 150 && $sLenght > 1 && $sLenght <= 150) {
                    $aDimension['shipping_width'] = $sWidth . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                    $aDimension['shipping_height'] = $sHeight . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                    $aDimension['shipping_length'] = $sLenght . ' ' . Tools::strtolower(Configuration::get('PS_DIMENSION_UNIT'));
                }  
            }
            
            return $aDimension;
        }

    }
}
