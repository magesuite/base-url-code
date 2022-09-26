<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Magento\Framework\Registry $registry */
$registry = $objectManager->get(\Magento\Framework\Registry::class);

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var Magento\Store\Model\Store $store */
$store = $objectManager->create(\Magento\Store\Model\Store::class);
$store->load('second_store_with_url_code');

if ($store->getId()) {
    $storeId = $store->getId();
    $urlRewriteCollectionFactory = $objectManager->get(
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory::class
    );
    /** @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection $urlRewriteCollection */
    $urlRewriteCollection = $urlRewriteCollectionFactory->create();
    $urlRewriteCollection->addFieldToFilter('store_id', ['eq' => $storeId]);
    $urlRewrites = $urlRewriteCollection->getItems();
    /** @var \Magento\UrlRewrite\Model\UrlRewrite $urlRewrite */
    foreach ($urlRewrites as $urlRewrite) {
        try {
            $urlRewrite->delete();
        } catch (\Exception $exception) {
            // already removed
        }
    }

    $store->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
