<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Test\Integration\Model;

class SitemapTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Sitemap\Model\ResourceModel\Sitemap\CollectionFactory
     */
    protected $sitemapCollectionFactory;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->sitemapCollectionFactory = $objectManager->create(\Magento\Sitemap\Model\ResourceModel\Sitemap\CollectionFactory::class);
        $this->mediaDirectory = $objectManager->get(\Magento\Framework\Filesystem::class)
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoAppArea frontend
     * @magentoDataFixture MageSuite_BaseUrlCode::Test/Integration/_files/sitemap.php
     * @magentoConfigFixture second_store_with_url_code_store web/url/use_store 1
     * @magentoConfigFixture second_store_with_url_code_store web/unsecure/base_url http://example.com/
     * @magentoConfigFixture second_store_with_url_code_store web/unsecure/base_link_url http://example.com/
     */
    public function testStoreUrlInSitemap()
    {
        $collection = $this->sitemapCollectionFactory->create()
            ->addFieldToFilter('sitemap_filename', 'dummy_sitemap.xml');
        /** @var \Magento\Sitemap\Model\Sitemap $sitemap */
        $sitemap = $collection->getFirstItem();
        $sitemap->generateXml();
        $filePath = $this->mediaDirectory->getAbsolutePath('dummy_sitemap.xml');
        $sitemapXml = file_get_contents($filePath);
        $this->assertStringContainsString('http://example.com/en-us/', $sitemapXml);
    }
}
