<?php
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var \Magento\Store\Model\Store $store */
$store = $objectManager->create(\Magento\Store\Model\Store::class);
$storeManager = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);

if (!$store->load('second_store_with_url_code', 'code')->getId()) {
    $websiteId = $storeManager->getWebsite()->getId();
    $groupId = $storeManager->getWebsite()->getDefaultGroupId();
    $store->setCode(
        'second_store_with_url_code'
    )->setWebsiteId(
        $websiteId
    )->setGroupId(
        $groupId
    )->setName(
        'Fixture Store with URL Code'
    )->setSortOrder(
        10
    )->setIsActive(
        1
    )->setUrlCode(
        'en-us'
    );
    $store->save();
}
