<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Test\Integration\Model;

class StoreResolverTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \MageSuite\BaseUrlCode\Model\StoreResolver
     */
    protected $storeResolver;

    protected function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->storeRepository = $this->objectManager->get(\Magento\Store\Api\StoreRepositoryInterface::class);
        $this->storeResolver = $this->objectManager->get(\MageSuite\BaseUrlCode\Model\StoreResolver::class);
    }

    /**
     * @covers \MageSuite\BaseUrlCode\Plugin\Magento\Framework\Url\ScopeInterface\AdjustStoreCodeInUrl
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoConfigFixture second_store_with_url_code_store web/url/use_store 1
     * @magentoDataFixture MageSuite_BaseUrlCode::Test/Integration/_files/store.php
     */
    public function testStoreUrlCodeIsAddedToUrl()
    {
        $store = $this->storeRepository->get('second_store_with_url_code');
        $this->assertEquals('http://localhost/en-us/', $store->getBaseUrl());
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoConfigFixture web/url/use_store 1
     * @magentoDataFixture MageSuite_BaseUrlCode::Test/Integration/_files/store.php
     */
    public function testInitStoreMethodReturnsProperStore()
    {
        $request = $this->objectManager->get(\Magento\Framework\App\RequestInterface::class);
        $request->setUri('http://localhost/index.php/en-us/');
        $store = $this->storeRepository->get('second_store_with_url_code');
        $expectedStore = $this->storeResolver->initStore('en-us');
        $this->assertEquals($store->getCode(), $expectedStore->getStore()->getCode());
        $this->storeResolver->unsetStore();
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     */
    public function testInitStoreMethodReturnsException()
    {
        $this->expectException(\Magento\Framework\Exception\NoSuchEntityException::class);
        $this->expectExceptionMessage('Unable to init store with provided url code: en-en');
        $this->storeResolver->initStore('en-en');
    }
}
