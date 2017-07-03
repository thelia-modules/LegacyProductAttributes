
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- legacy_product_attribute_value
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `legacy_product_attribute_value`;

CREATE TABLE `legacy_product_attribute_value`
(
    `product_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    `weight_delta` FLOAT DEFAULT 0 NOT NULL,
    `stock` INTEGER DEFAULT 0,
    `visible` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`product_id`,`attribute_av_id`),
    INDEX `legacy_product_attribute_value_FI_2` (`attribute_av_id`),
    CONSTRAINT `legacy_product_attribute_value_FK_1`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_product_attribute_value_FK_2`
        FOREIGN KEY (`attribute_av_id`)
        REFERENCES `attribute_av` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- legacy_product_attribute_value_price
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `legacy_product_attribute_value_price`;

CREATE TABLE `legacy_product_attribute_value_price`
(
    `product_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    `currency_id` INTEGER NOT NULL,
    `delta` DECIMAL(16,6) DEFAULT 0.000000,
    PRIMARY KEY (`product_id`,`attribute_av_id`,`currency_id`),
    INDEX `legacy_product_attribute_value_price_FI_2` (`attribute_av_id`),
    INDEX `legacy_product_attribute_value_price_FI_3` (`currency_id`),
    CONSTRAINT `legacy_product_attribute_value_price_FK_1`
        FOREIGN KEY (`product_id`)
        REFERENCES `product` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_product_attribute_value_price_FK_2`
        FOREIGN KEY (`attribute_av_id`)
        REFERENCES `attribute_av` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_product_attribute_value_price_FK_3`
        FOREIGN KEY (`currency_id`)
        REFERENCES `currency` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- legacy_cart_item_attribute_combination
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `legacy_cart_item_attribute_combination`;

CREATE TABLE `legacy_cart_item_attribute_combination`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `cart_item_id` INTEGER NOT NULL,
    `attribute_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`,`cart_item_id`,`attribute_id`),
    INDEX `legacy_cart_item_attribute_combination_FI_1` (`cart_item_id`),
    INDEX `legacy_cart_item_attribute_combination_FI_2` (`attribute_id`),
    INDEX `legacy_cart_item_attribute_combination_FI_3` (`attribute_av_id`),
    CONSTRAINT `legacy_cart_item_attribute_combination_FK_1`
        FOREIGN KEY (`cart_item_id`)
        REFERENCES `cart_item` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_cart_item_attribute_combination_FK_2`
        FOREIGN KEY (`attribute_id`)
        REFERENCES `attribute` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_cart_item_attribute_combination_FK_3`
        FOREIGN KEY (`attribute_av_id`)
        REFERENCES `attribute_av` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- legacy_order_product_attribute_combination
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `legacy_order_product_attribute_combination`;

CREATE TABLE `legacy_order_product_attribute_combination`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `order_product_id` INTEGER NOT NULL,
    `product_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    `quantity` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `legacy_order_product_attribute_combination_I_1` (`order_product_id`),
    INDEX `legacy_order_product_attribute_combination_FI_2` (`attribute_av_id`),
    CONSTRAINT `legacy_order_product_attribute_combination_FK_1`
        FOREIGN KEY (`order_product_id`)
        REFERENCES `order_product` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `legacy_order_product_attribute_combination_FK_2`
        FOREIGN KEY (`attribute_av_id`)
        REFERENCES `attribute_av` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
