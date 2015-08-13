
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- legacy_product_attribute_value
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `legacy_product_attribute_value`
(
    `product_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
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

CREATE TABLE IF NOT EXISTS `legacy_product_attribute_value_price`
(
    `product_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    `currency_id` INTEGER NOT NULL,
    `delta` FLOAT DEFAULT 0,
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

CREATE TABLE IF NOT EXISTS `legacy_cart_item_attribute_combination`
(
    `cart_item_id` INTEGER NOT NULL,
    `attribute_id` INTEGER NOT NULL,
    `attribute_av_id` INTEGER NOT NULL,
    PRIMARY KEY (`cart_item_id`,`attribute_id`),
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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
