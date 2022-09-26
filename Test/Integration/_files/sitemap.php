<?php
declare(strict_types=1);
\Magento\TestFramework\Workaround\Override\Fixture\Resolver::getInstance()
    ->requireDataFixture('MageSuite_BaseUrlCode::Test/Integration/_files/store.php');
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$storeRepository = $objectManager->get(\Magento\Store\Api\StoreRepositoryInterface::class);
$store = $storeRepository->get('second_store_with_url_code');
$sitemap = $objectManager->create(\Magento\Sitemap\Model\SitemapFactory::class)->create();
$sitemap->setStoreId($store->getId())
    ->setSitemapFilename('dummy_sitemap.xml')
    ->setSitemapPath('/')
    ->save();
