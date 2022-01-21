ALTER TABLE `PREFIX_gmc_features_by_cat` DROP PRIMARY KEY;
ALTER TABLE `PREFIX_gmc_features_by_cat` ADD `id_shop` INT NOT NULL DEFAULT "1", ADD UNIQUE (`id_cat`, `id_shop`);
ALTER TABLE `PREFIX_gmc_features_by_cat` ADD CONSTRAINT PK_Person PRIMARY KEY (`id_cat`, `id_shop`);