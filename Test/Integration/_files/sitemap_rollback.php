<?php
declare(strict_types=1);
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$collection = $objectManager->create(\Magento\Sitemap\Model\ResourceModel\Sitemap\CollectionFactory::class)->create();
$collection->addFieldToFilter('sitemap_filename', 'dummy_sitemap.xml');
$collection->walk('delete');
\Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance()
    ->requireDataFixture('MageSuite_BaseUrlCode::Test/Integration/_files/store_rollback.php');
