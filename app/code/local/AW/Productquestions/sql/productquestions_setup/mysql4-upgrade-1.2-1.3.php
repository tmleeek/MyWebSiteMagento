<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Productquestions
 * @copyright  Copyright (c) 2008-2010 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */

$installer = $this;
$installer->startSetup();

try
{
    $installer->run("

ALTER TABLE {$this->getTable('productquestions/productquestions')}
    ADD `question_store_ids` VARCHAR( 255 ) NOT NULL DEFAULT '0' COMMENT 'displayed on' AFTER `question_store_id` ;


-- DROP TABLE IF EXISTS {$this->getTable('productquestions/helpfulness')};

CREATE TABLE {$this->getTable('productquestions/helpfulness')} (
 `question_id` int(10) unsigned NOT NULL default '0',
 `vote_count` int(10) unsigned NOT NULL default '0',
 `vote_sum` int(10) unsigned NOT NULL default '0',
 PRIMARY KEY  (`question_id`),
 CONSTRAINT `FK_helpfulness` FOREIGN KEY (`question_id`) REFERENCES `{$this->getTable('productquestions/productquestions')}` (`question_id`) ON DELETE CASCADE
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
}
catch (Exception $e) { Mage::logException($e); }

$installer->endSetup();
