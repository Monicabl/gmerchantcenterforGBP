DROP TABLE IF EXISTS `PREFIX_gmc_brands`;
CREATE TABLE IF NOT EXISTS `PREFIX_gmc_brands` (`id_brands` int(11) NOT NULL, `id_shop` int(11) NOT NULL DEFAULT "1",  UNIQUE KEY `id_brands` (`id_brands`, `id_shop`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
