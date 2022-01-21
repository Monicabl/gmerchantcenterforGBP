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

class BT_GmcModuleDao
{
    /**
     * search matching product names for autocomplete
     *
     * @param string $sSearch
     * @param bool $bCombination
     * @param string $sExcludedList
     * @return array
     */
    public static function searchProducts($sSearch, $bCombination = false,  $sExcludedList = '')
    {
        if ($sExcludedList != '0,' && !empty($sExcludedList)) {
            $sExcludeIds = implode(',', array_map('intval', explode(',', $sExcludedList)));
        }

        $sQuery = 'SELECT p.`id_product`, pl.`name`' . ($bCombination ? ',pa.`id_product_attribute`' : '')
            . ' FROM ' . _DB_PREFIX_ . 'product p'
            . Shop::addSqlAssociation('product', 'p', false)
            . ($bCombination ? ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (p.id_product = pa.id_product)' : '')
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.id_product = pl.id_product ' . Shop::addSqlRestrictionOnLang('pl') . ')'
            . ' WHERE pl.name LIKE \'%' . pSQL($sSearch) . '%\' AND pl.id_lang = ' . (int) GMerchantCenter::$iCurrentLang;

        if (empty(GMerchantCenter::$conf['GMERCHANTCENTER_P_COMBOS'])) {
            $sQuery .= (!empty($sExcludeIds) ? ' AND p.id_product NOT IN (' . $sExcludeIds . ') ' : ' ');
        }

        $aResult = Db::getInstance()->ExecuteS($sQuery);

        return $aResult;
    }

    /**
     * get all properties of product
     *
     * @param int $iProductId
     * @return array
     */
    public static function getProduct($iProductId)
    {
        $aProduct = array();

        $sQuery = 'SELECT p.*, pa.id_product_attribute,pl.*, il.*, m.name AS manufacturer_name, s.name AS supplier_name,'
            . ' ps.product_supplier_reference AS supplier_reference'
            . ' FROM ' . _DB_PREFIX_ . 'product as p '
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute as pa ON (p.id_product = pa.id_product AND default_on = 1)'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'product_lang as pl ON (p.id_product = pl.id_product AND pl.id_lang = ' . (int) GMerchantCenter::$iCurrentLang . Shop::addSqlRestrictionOnLang('pl') . ')'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'image as i ON (i.id_product = p.id_product AND i.cover = 1)'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'image_lang as il ON (i.id_image = il.id_image AND il.id_lang = ' . (int) GMerchantCenter::$iCurrentLang . ')'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer as m ON m.id_manufacturer = p.id_manufacturer'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'supplier as s ON s.id_supplier = p.id_supplier'
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'product_supplier as ps ON (p.id_product = ps.id_product AND pa.id_product_attribute = ps.id_product_attribute)'
            . ' WHERE p.id_product = ' . (int) $iProductId;

        $aAttributes = Db::getInstance()->ExecuteS($sQuery);

        if (!empty($aAttributes[0])) {
            // get properties
            $aProduct = Product::getProductProperties(GMerchantCenter::$iCurrentLang, $aAttributes[0]);

            if (empty($aProduct)) {
                $aProduct = array();
            } else {
                $aProduct['supplier_reference'] = $aAttributes[0]['supplier_reference'];
            }
        }

        return $aProduct;
    }


    /**
     * count the number of product by combination or not
     *
     * @param int $iShopId
     * @param bool $bCombination
     * @return int
     */
    public static function countProducts($iShopId, $bCombination = false)
    {
        $sQuery = 'SELECT COUNT(p.id_product) as cnt'
            . ' FROM ' . _DB_PREFIX_ . 'product p'
            . Shop::addSqlAssociation('product', 'p', false)
            . ($bCombination ? ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (p.id_product = pa.id_product)' : '')
            . ' WHERE product_shop.active = 1';

        $aResult = Db::getInstance()->getRow($sQuery);

        return !empty($aResult['cnt']) ? $aResult['cnt'] : 0;
    }

    /**
     * count the number of product or return all product IDs to export
     *
     * @param int $iShopId
     * @param bool $bExportMode
     * @param bool $bCountMode
     * @param int $iFloor
     * @param int $iStep
     * @return mixed
     */
    public static function getProductIds($iShopId, $bExportMode = 0, $bCountMode = false, $iFloor = null, $iStep = null)
    {
        $sQuery = 'SELECT '
            . ($bCountMode ? 'COUNT(DISTINCT(p.id_product)) as cnt ' : 'DISTINCT(p.id_product) as id')
            . ' FROM ' . _DB_PREFIX_ . 'product p '
            . Shop::addSqlAssociation('product', 'p', false)
            . (!$bExportMode ? ' LEFT JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (p.id_product = cp.id_product)' : ' LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` man ON (p.id_manufacturer = man.id_manufacturer)')
            . ' WHERE product_shop.active = 1'
            . ' AND ' . (!$bExportMode ? 'cp.`id_category`' : 'man.`id_manufacturer`') . ' IN (SELECT id_' . (!$bExportMode ? 'category' : 'brands') . ' FROM `' . _DB_PREFIX_ . 'gmc_' . (!$bExportMode ? 'categories' : 'brands') . '` gc ' . ((Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) ? ' WHERE gc.`id_shop` = ' . (int) $iShopId : '') . ')';

        // range or not
        if ($iFloor !== null && !empty($iStep)) {
            $sQuery .= ' LIMIT ' . $iFloor . ', ' . $iStep;
        }
        // count products number
        if ($bCountMode) {
            $aResult = Db::getInstance()->getRow($sQuery);

            $mReturn = $aResult['cnt'] ? $aResult['cnt'] : 0;
        } // return product IDs
        else {
            $mReturn = Db::getInstance()->ExecuteS($sQuery);
        }
        return $mReturn;
    }

    /**
     * returns specific attributes
     *
     * @param int $iProdId
     * @param mixed $mGroupAttributeId
     * @param int $iLangId
     * @param int $iProdAttrId
     * @return array
     */
    public static function getProductAttribute($iProdId, $mGroupAttributeId, $iLangId, $iProdAttrId = 0)
    {
        $sQuery = 'SELECT distinct(al.`name`)'
            . ' FROM ' . _DB_PREFIX_ . 'product_attribute pa '
            . Shop::addSqlAssociation('product_attribute', 'pa', false)
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON a.`id_attribute` = al.`id_attribute`'
            . ' WHERE pa.`id_product` = ' . (int) $iProdId
            . (($iProdAttrId) ? ' AND pac.`id_product_attribute` = ' . (int) $iProdAttrId : '')
            . ' AND al.`id_lang` = ' . (int) $iLangId
            . ' AND ag.`id_attribute_group` IN (' . pSQL($mGroupAttributeId) . ')'
            . ' ORDER BY al.`name`'
            . ' LIMIT 0, 30';


        $aResult = Db::getInstance()->ExecuteS($sQuery);

        return !empty($aResult) ? $aResult : array();
    }

    /**
     * returns specific feature
     *
     * @param int $iProdId
     * @param int $iFeatureId
     * @param int $iLangId
     * @return string
     */
    public static function getProductFeature($iProdId, $iFeatureId, $iLangId)
    {
        $sQuery = 'SELECT fvl.`value`'
            . ' FROM ' . _DB_PREFIX_ . 'feature_value_lang fvl '
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'feature_value` fv ON fvl.`id_feature_value` = fv.`id_feature_value`'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'feature_product` fp ON fv.`id_feature_value` = fp.`id_feature_value`'
            . ' WHERE fp.`id_product` = ' . (int) $iProdId
            . ' AND fvl.`id_lang` = ' . (int) $iLangId
            . ' AND fp.`id_feature` = ' . (int) $iFeatureId;

        $aResult = Db::getInstance()->getRow($sQuery);

        return !empty($aResult['value']) ? $aResult['value'] : '';
    }


    /**
     * returns the product's combinations
     *
     * @param int $iShopId
     * @param int $iProductId
     * @return mixed
     */
    public static function getProductCombination($iShopId, $iProductId)
    {
        $sQuery = 'SELECT *, pa.id_product_attribute, pas.id_shop, sa.`quantity` as combo_quantity'
            . ' FROM ' . _DB_PREFIX_ . 'product_attribute pa '
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pas ON (pa.id_product_attribute = pas.id_product_attribute AND pas.id_shop = ' . (int) $iShopId . ')'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON (pas.id_product_attribute = sa.id_product_attribute)'
            . ' WHERE pa.`id_product` = ' . (int) $iProductId
            .  ' AND pas.`id_shop` = ' . (int) $iShopId;

        $aResult = Db::getInstance()->ExecuteS($sQuery);

        return !empty($aResult) ? $aResult : false;
    }

    /**
     * returns the product's combination attributes
     *
     * @param int $iProdAttributeId
     * @param int $iLangId
     * @param int $iShopId
     * @return mixed
     */
    public static function getProductComboAttributes($iProdAttributeId, $iLangId, $iShopId)
    {
        $sQuery = 'SELECT distinct(al.`name`)'
            . ' FROM `' . _DB_PREFIX_ . 'product_attribute_shop` pa'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (pac.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) $iLangId . ')'
            . ' WHERE pac.`id_product_attribute` = ' . (int) ($iProdAttributeId)
            . ' AND pa.id_shop = ' . (int) $iShopId;
        ' ORDER BY al.`name`';

        $aResult = Db::getInstance()->ExecuteS($sQuery);

        return !empty($aResult) ? $aResult : false;
    }

    /**
     * returns home categories
     *
     * @param int $iLangId
     * @return array
     */
    public static function getHomeCategories($iLangId)
    {
        $sQuery = 'SELECT c.id_category, cl.name, cl.id_lang'
            . ' FROM ' . _DB_PREFIX_ . 'category c'
            . Shop::addSqlAssociation('category', 'c', false)
            . ' LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl ON c.id_category = cl.id_category AND cl.id_lang = ' . (int) $iLangId . Shop::addSqlRestrictionOnLang('cl')
            . ' WHERE cl.id_lang = ' . (int) $iLangId . ' AND level_depth < 2 AND c.active = 1'
            . ' ORDER BY level_depth, name';

        return Db::getInstance()->ExecuteS($sQuery);
    }

    /**
     * returns categories to export
     *
     * @param int $iShopId
     * @return array
     */
    public static function getGmcCategories($iShopId)
    {
        // set
        $aCategories = array();

        // get categories
        $aResult = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'gmc_categories` WHERE `id_shop` = ' . (int) $iShopId);

        if (!empty($aResult)) {
            foreach ($aResult as $aCat) {
                $aCategories[] = $aCat['id_category'];
            }
        }

        return $aCategories;
    }

    /**
     * returns brands to export
     *
     * @param int $iShopId
     * @return array
     */
    public static function getGmcBrands($iShopId)
    {
        // set
        $aBrands = array();

        // get brands
        $aResult = Db::getInstance()->ExecuteS('SELECT * FROM `' . _DB_PREFIX_ . 'gmc_brands` WHERE `id_shop` = ' . (int) $iShopId);

        if (!empty($aResult)) {
            foreach ($aResult as $aCat) {
                $aBrands[] = $aCat['id_brands'];
            }
        }

        return $aBrands;
    }

    /**
     * returns specific categories or brands or suppliers for one tag
     *
     * @param int $iShopId
     * @param int $iTagId
     * @param string $sTableType
     * @param string $sFieldType
     * @return array
     */
    public static function getGmcTags($iShopId = null, $iTagId = null, $sTableType = null, $sFieldType = null)
    {
        // set
        $aReturn = array();

        // get specific tags
        $sQuery = 'SELECT * FROM `' . _DB_PREFIX_ . 'gmc_tags' . ($sTableType !== null ? '_' . $sTableType : '') . '` WHERE 1 = 1 ' . ($iShopId !== null ? ' AND id_shop = ' . (int) $iShopId : '') . ($iTagId !== null ? ' AND `id_tag` = ' . (int) $iTagId : '');
        $aResult = Db::getInstance()->ExecuteS($sQuery);

        if (!empty($aResult) && $sFieldType !== null) {
            foreach ($aResult as $aCat) {
                $aReturn[] = $aCat['id_' . $sFieldType];
            }
        } else {
            $aReturn = $aResult;
        }

        return $aReturn;
    }


    /**
     * insert a specific tag
     *
     * @param int $iShopId
     * @param string $sLabelName
     * @param string $sLabelType
     * @return int
     */
    public static function insertGmcTag($iShopId, $sLabelName, $sLabelType)
    {
        Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_tags` (`id_shop`, `name`, `type`) VALUES (' . (int) $iShopId . ',"' . pSQL($sLabelName) . '", "' . pSQL($sLabelType) . '")');

        return Db::getInstance()->Insert_ID();
    }

    /**
     * update a specific tag
     *
     * @param int $iTagId
     * @param string $sLabelName
     * @param string $sLabelType
     * @return bool
     */
    public static function updateGmcTag($iTagId, $sLabelName, $sLabelType)
    {
        return Db::getInstance()->Execute('UPDATE `' . _DB_PREFIX_ . 'gmc_tags` SET `name` = "' . pSQL($sLabelName) . '", `type` = "' . pSQL($sLabelType) . '" WHERE `id_tag` = ' . (int) $iTagId);
    }

    /**
     * insert categories / brands / manufacturers for a specific tag
     *
     * @param int $iTagId
     * @param int $iCatId
     * @param string $sTableName
     * @param string $sFieldType
     * @return int
     */
    public static function insertGmcCatTag($iTagId, $iCatId, $sTableName, $sFieldType)
    {
        Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_tags_' . $sTableName . '` (`id_tag`, `id_' . bqSQL($sFieldType) . '`) VALUES (' . (int) $iTagId . ', ' . (int) $iCatId . ')');
    }

    /**
     * delete a specific tag
     *
     * @param int $iTagId
     * @param array $aLabelList
     * @return bool
     */
    public static function deleteGmcTag($iTagId, array $aLabelList = null)
    {
        if (Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_tags` WHERE `id_tag` = ' . (int) $iTagId)) {
            if (!empty($aLabelList)) {
                foreach ($aLabelList as $sTableName => $sFieldType) {
                    self::deleteGmcCatTag($iTagId, $sTableName);
                }
            }
        }
    }

    /**
     * delete a specific related categories / brands / manufacturers tag
     *
     * @param int $iTagId
     * @param string $sTableType
     * @return bool
     */
    public static function deleteGmcCatTag($iTagId, $sTableType)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_tags_' . pSQL($sTableType) . '` WHERE `id_tag` = ' . (int) $iTagId);
    }

    /**
     * returns Google tags for XML
     *
     * @param int $iProdId
     * @param array $aCat
     * @param int $iManufacturerId
     * @param int $iSupplierId
     * @return array
     */
    public static function getTagsForXml($iProdId, $aCat, $iManufacturerId, $iSupplierId)
    {
        $sIn = implode(',', array_map('intval', $aCat));

        $sQuery = 'SELECT distinct(gt.id_tag), gt.name, gt.type, "cats" as source'
            . ' FROM `' . _DB_PREFIX_ . 'gmc_tags` gt'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'gmc_tags_cats` gtc ON (gt.id_tag = gtc.id_tag)'
            . ' WHERE gtc.id_category in ( ' . pSQL($sIn) . ')'
            . ' UNION'
            . ' SELECT distinct(gt.id_tag), gt.name, gt.type, "brands" as source'
            . ' FROM `' . _DB_PREFIX_ . 'gmc_tags` gt'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'gmc_tags_brands` gtb ON (gt.id_tag = gtb.id_tag)'
            . ' WHERE gtb.id_brand = ' . (int) $iManufacturerId
            . ' UNION ';

        $sQuery .= ' SELECT distinct(gt.id_tag), gt.name, gt.type, "suppliers" as source'
            . ' FROM `' . _DB_PREFIX_ . 'gmc_tags` gt'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'gmc_tags_suppliers` gts ON (gt.id_tag = gts.id_tag)'
            . ' WHERE gts.id_supplier IN (SELECT distinct(id_supplier) FROM `' . _DB_PREFIX_ . 'product_supplier` WHERE id_product = ' . (int) $iProdId . ')';

        $aData = Db::getInstance()->ExecuteS($sQuery);
        $aTags = array('custom_label' => array());

        if (!empty($aData) && is_array($aData)) {
            foreach ($aData as $row) {
                $aTags['custom_label'][] = $row['name'];
            }
        }

        return $aTags;
    }

    /**
     * insert a category in our table gmc_categories
     *
     * @param int $iCategoryId
     * @param int $iShopId
     * @return bool
     */
    public static function insertCategory($iCategoryId, $iShopId)
    {
        return Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_categories` (`id_category`, `id_shop`) values (' . (int) $iCategoryId . ', ' . (int) $iShopId . ')');
    }

    /**
     * insert a brand in our table gmc_brands
     *
     * @param int $iBrandId
     * @param int $iShopId
     * @return bool
     */
    public static function insertBrand($iBrandId, $iShopId)
    {
        return Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_brands` (`id_brands`, `id_shop`) values (' . (int) $iBrandId . ', ' . (int) $iShopId . ')');
    }

    /**
     *  method delete the previous selected categories
     *
     * @param int $iShopId
     * @return bool
     */
    public static function deleteCategories($iShopId)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_categories` WHERE `id_shop` = ' . (int) $iShopId);
    }

    /**
     * delete the previous selected brands
     *
     * @param int $iShopId
     * @return bool
     */
    public static function deleteBrands($iShopId)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_brands` WHERE `id_shop` = ' . (int) $iShopId);
    }

    /**
     * returns shop's categories
     *
     * @param int $iShopId
     * @param int $iLangId
     * @param int $iHomeCatId
     * @return array
     */
    public static function getShopCategories($iShopId, $iLangId, $iHomeCatId = null)
    {
        $sQuery = 'SELECT c.`id_category`, cl.`name`, cl.`id_lang` '
            . ' FROM `' . _DB_PREFIX_ . 'category` c'
            . ' INNER JOIN `' . _DB_PREFIX_ . 'category_shop` cs ON (c.id_category = cs.id_category AND cs.id_shop = ' . (int) $iShopId . ') '
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (c.id_category = cl.id_category AND cl.`id_lang` = ' . (int) $iLangId . Shop::addSqlRestrictionOnLang('cl') . ')'
            . ' WHERE level_depth > 0'
            . ' ORDER BY `level_depth`, `name`';

        $aCategories = Db::getInstance()->ExecuteS($sQuery);

        if ($iHomeCatId !== null) {
            $aTranslations = is_string(GMerchantCenter::$conf['GMC_HOME_CAT']) ? unserialize(GMerchantCenter::$conf['GMC_HOME_CAT']) : GMerchantCenter::$conf['GMC_HOME_CAT'];
        }

        foreach ($aCategories as $k => &$aCat) {
            // set category path
            $aCat['path'] = $aCat['id_category'] == $iHomeCatId ? (!empty($aTranslations[$iLangId]) ? $aTranslations[$iLangId] : $aCat['name']) : BT_GmcModuleTools::getProductPath(
                (int) $aCat['id_category'],
                $iLangId
            );
            $aCat['len'] = strlen($aCat['path']);

            $bHasToDelete = trim($aCat['path']);

            if (empty($bHasToDelete)) {
                unset($aCategories[$k]);
            }
        }

        return $aCategories;
    }

    /**
     * returns google's categories
     *
     * @param int $iShopId
     * @param int $iLangId
     * @param string $sIsoLang
     * @return array
     */
    public static function getGoogleCategories($iShopId, $iCatId, $sIsoLang)
    {
        $sQuery = 'SELECT *'
            . ' FROM `' . _DB_PREFIX_ . 'gmc_taxonomy_categories` gtc'
            . ' WHERE `id_category` = ' . (int) $iCatId
            . ' AND gtc.`lang` = "' . pSQL($sIsoLang) . '"'
            . ' AND id_shop = ' . (int) $iShopId;

        return Db::getInstance()->getRow($sQuery);
    }

    /**
     * delete google categories
     *
     * @param int $iShopId
     * @param string $sIsoCode
     * @return bool
     */
    public static function deleteGoogleCategory($iShopId, $sIsoCode)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_taxonomy_categories` WHERE `lang` = "' . pSQL($sIsoCode) . '" AND id_shop = ' . (int) $iShopId);
    }

    /**
     * add google categories
     *
     * @param int $iShopId
     * @param int $iShopCatId
     * @param string $sGoogleCat
     * @param string $sIsoCode
     * @return bool
     */
    public static function insertGoogleCategory($iShopId, $iShopCatId, $sGoogleCat, $sIsoCode)
    {
        return Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_taxonomy_categories` VALUES (' . (int) $iShopCatId . ',' . (int) $iShopId . ',"' . pSQL($sGoogleCat) . '", "' . pSQL($sIsoCode) . '")');
    }

    /**
     * returns features by category
     *
     * @param int $iCategoryId
     * @param int $iShopId
     * @return string
     */
    public static function getFeaturesByCategory($iCategoryId, $iShopId)
    {
        $saResult = array();

        $aData = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . Tools::strtolower(_GMC_MODULE_NAME) . '_features_by_cat` WHERE `id_cat` = ' . (int) $iCategoryId . ' AND `id_shop` = ' . (int) $iShopId);

        if (!empty($aData) && is_array($aData)) {
            $saResult = unserialize($aData['values']);
        }

        return $saResult;
    }

    /**
     * delete features related to all selected categories
     * @param int $iShopId
     * @param int $iCategoryId
     * @return bool
     */
    public static function deleteFeatureByCat($iShopId, $iCategoryId = null)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . Tools::strtolower(_GMC_MODULE_NAME) . '_features_by_cat` WHERE ' . ($iCategoryId !== null ? '`id_cat` = ' . (int) $iCategoryId : 1) . ' AND `id_shop` = ' . (int) $iShopId);
    }

    /**
     * insert features related to all selected categories
     *
     * @param int $iCategoryId
     * @param array $aData
     * @param int $iShopId
     * @return bool
     */
    public static function insertFeatureByCat($iCategoryId, $aData, $iShopId)
    {
        return Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . Tools::strtolower(_GMC_MODULE_NAME) . '_features_by_cat` (`id_cat`, `id_shop`, `values`) VALUES(' . (int) $iCategoryId . ', ' . (int) $iShopId . ',  \'' . pSQL(serialize($aData)) . '\')');
    }

    /**
     * return available countries supported by Google
     *
     * @param array $aMerchantCountries
     * @return array
     */
    public static function getAvailableTaxonomyCountries(array $aMerchantCountries)
    {
        $aShopCountries = Country::getCountries((int) GMerchantCenter::$oCookie->id_lang, false);
        $aTaxonomy = array();

        foreach ($aMerchantCountries as $sLang => $aCountries) {
            foreach ($aCountries as $sCountryIso => $aLocaleData) {
                $iLangID = Db::getInstance()->getValue('SELECT `id_lang` FROM `' . _DB_PREFIX_ . 'lang` WHERE `active` = 1 AND `iso_code` = \'' . pSQL(strtolower($sLang)) . '\'');

                foreach ($aLocaleData['currency'] as $sCurrency) {
                    if (!empty($iLangID) && Currency::getIdByIsoCode($sCurrency)) {
                        $iCountryId = Country::getByIso($sCountryIso);

                        if (!empty($iCountryId)) {
                            $sCountryName = $aShopCountries[$iCountryId]['name'];

                            if (!array_key_exists($aLocaleData['taxonomy'], $aTaxonomy)) {
                                // fix for brazil
                                if ($aLocaleData['taxonomy'] == 'pt-BR') {
                                    $iLangID = Language::getIdByIso((Language::getIdByIso('pb') ? 'pb' : 'br'));
                                }
                                $aTaxonomy[$aLocaleData['taxonomy']] = array();
                            }
                            $aTaxonomy[$aLocaleData['taxonomy']]['countries'][] = $sCountryName;
                            $aTaxonomy[$aLocaleData['taxonomy']]['id_lang'] = (int) $iLangID;
                        }
                    }
                }
            }
        }

        return $aTaxonomy;
    }

    /**
     * checks if the current country has already been updated
     *
     * @param string $sIsoCode
     * @return bool
     */
    public static function checkTaxonomyUpdate($sIsoCode)
    {
        $aResult = Db::getInstance()->ExecuteS('SELECT COUNT(`id_taxonomy`) as count FROM  ' . _DB_PREFIX_ . 'gmc_taxonomy WHERE lang = "' . pSQL($sIsoCode) . '"');

        return ($aResult[0]['count'] > 1) ? true : false;
    }

    /**
     * delete google taxonomy
     *
     * @param string $sIsoCode
     * @return bool
     */
    public static function deleteGoogleTaxonomy($sIsoCode)
    {
        return Db::getInstance()->Execute('DELETE FROM `' . _DB_PREFIX_ . 'gmc_taxonomy` WHERE `lang` = "' . pSQL($sIsoCode) . '"');
    }

    /**
     * add google taxonomy
     *
     * @param string $sText
     * @param string $sIsoCode
     * @return bool
     */
    public static function insertGoogleTaxonomy($sText, $sIsoCode)
    {
        return Db::getInstance()->Execute('INSERT INTO `' . _DB_PREFIX_ . 'gmc_taxonomy` (`value`, `lang`) VALUES ("' . pSQL($sText) . '", "' . pSQL($sIsoCode) . '")');
    }

    /**
     * check a language as active
     *
     * @param string $sIsoCode
     * @return bool
     */
    public static function checkActiveLanguage($sIsoCode)
    {
        $aResult = Db::getInstance()->ExecuteS('SELECT * from `' . _DB_PREFIX_ . 'lang` where `active` = 1 AND `iso_code` = "' . pSQL($sIsoCode) . '"');

        return !empty($aResult) && count($aResult) ? true : false;
    }

    /**
     * returns available carriers for one country zone
     *
     * @param int $iCountryZone
     * @return array
     */
    public static function getAvailableCarriers($iCountryZone)
    {
        return Carrier::getCarriers((int) GMerchantCenter::$oCookie->id_lang, false, false, (int) $iCountryZone, null, 5);
    }

    /**
     * returns carrier tax rate
     *
     * @param int $iCarrierId
     * @return mixed : int or float
     */
    public static function getCarrierTaxRate($iCarrierId)
    {
        $sQuery = 'SELECT rate '
            . ' FROM `' . _DB_PREFIX_ . 'carrier` c'
            . ' LEFT JOIN `' . _DB_PREFIX_ . 'tax` t ON (c.id_tax = t.id_tax)'
            . ' WHERE c.`id_carrier` = ' . (int) $iCarrierId;

        return Db::getInstance()->getValue($sQuery);
    }

    /**
     * returns the additional shipping cost
     *
     * @param int $iProdId
     * @param int $iShopId
     * @return mixed : int or float
     */
    public static function getAdditionalShippingCost($iProdId, $iShopId)
    {
        $sQuery = 'SELECT additional_shipping_cost '
            . ' FROM `' . _DB_PREFIX_ . 'product_shop` '
            . ' WHERE id_product = ' . (int) $iProdId
            . ' AND id_shop = ' . (int) $iShopId;

        return Db::getInstance()->getValue($sQuery);
    }

    /**
     * returns the good supplier reference
     *
     * @param int $iProdId
     * @param int $iSupplierId
     * @param int $iAttributeProdId
     * @return string
     */
    public static function getProductSupplierReference($iProdId, $iSupplierId, $iAttributeProdId = 0)
    {
        // set vars
        $sRefSupplier = '';

        if ($iSupplierId != 0) {
            $sRefSupplier = ProductSupplier::getProductSupplierReference($iProdId, $iAttributeProdId, $iSupplierId);

            if (empty($sRefSupplier)) {
                $sQuery = 'SELECT product_supplier_reference '
                    . ' FROM `' . _DB_PREFIX_ . 'product_supplier` as ps '
                    . ' INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` as pa ON (pa.id_product_attribute = ps.id_product_attribute AND pa.default_on = 1)'
                    . ' WHERE ps.id_product = ' . (int) $iProdId
                    . ' AND ps.id_supplier = ' . (int) $iSupplierId;

                $sRefSupplier = Db::getInstance()->getValue($sQuery);
            }
        } elseif (!empty($iAttributeProdId)) {
            $sQuery = 'SELECT product_supplier_reference '
                . ' FROM `' . _DB_PREFIX_ . 'product_supplier`'
                . ' WHERE id_product = ' . (int) $iProdId
                . ' AND id_product_attribute = ' . (int) $iAttributeProdId
                . ' AND product_supplier_reference != ""';

            $sRefSupplier = Db::getInstance()->getValue($sQuery);
        }

        return $sRefSupplier;
    }

    /**
     * delete taxonomy
     *
     * @param string $sIsoCode
     * @param array $aWords
     * @return array
     */
    public static function autocompleteSearch($sIsoCode, array $aWords)
    {
        $sQuery = 'SELECT `value`'
            . ' FROM `' . _DB_PREFIX_ . 'gmc_taxonomy`'
            . ' WHERE lang = "' . pSQL($sIsoCode) . '" ';

        foreach ($aWords as $w) {
            $sQuery .= ' AND value LIKE \'%' . pSQL($w) . '%\'';
        }

        return Db::getInstance()->ExecuteS($sQuery);
    }
}
