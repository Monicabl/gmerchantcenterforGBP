CREATE TABLE IF NOT EXISTS `PREFIX_gmc_categories` (`id_category` int(11) NOT NULL, `id_shop` int(11) NOT NULL DEFAULT "1",  UNIQUE KEY `id_category` (`id_category`, `id_shop`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `PREFIX_gmc_taxonomy_categories` ADD IF NOT EXISTS `txt_taxonomy` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `id_taxonomy`;
UPDATE `PREFIX_gmc_taxonomy_categories` gtc SET gtc.`txt_taxonomy` = (SELECT gt.value from PREFIX_gmc_taxonomy gt WHERE gt.id_taxonomy = gtc.id_taxonomy);
ALTER TABLE `PREFIX_gmc_taxonomy` ADD FULLTEXT `ft_index` (`value`);
ALTER TABLE `PREFIX_gmc_taxonomy_categories` DROP IF EXISTS `id_taxonomy`;