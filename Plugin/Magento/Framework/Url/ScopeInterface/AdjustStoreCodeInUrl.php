<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Plugin\Magento\Framework\Url\ScopeInterface;

class AdjustStoreCodeInUrl
{
    protected \Magento\Framework\App\State $state;

    public function __construct(\Magento\Framework\App\State $state)
    {
        $this->state = $state;
    }

    public function aroundGetBaseUrl(
        \Magento\Framework\Url\ScopeInterface $store,
        \Closure $proceed,
        $type = \Magento\Framework\UrlInterface::URL_TYPE_LINK,
        $secure = null
    ): string {
        if ($store->getCode() === \Magento\Store\Model\Store::ADMIN_CODE) {
            return $proceed($type, $secure);
        }

        try {
            /** @see \Magento\Store\App\Response\Redirect::isInternalUrl **/
            if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML
                && $type == \Magento\Framework\UrlInterface::URL_TYPE_DIRECT_LINK) {
                return $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, $secure);
            }
            // phpcs:ignore Magento2.CodeAnalysis.EmptyBlock
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
        }

        return \Magento\Framework\UrlInterface::URL_TYPE_LINK !== $type
            ? $proceed($type, $secure)
            : $this->getStoreUrl($store, $secure);
    }

    protected function getStoreUrl(\Magento\Framework\Url\ScopeInterface $store, $secure): string
    {
        $baseUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, $secure);

        if (!$store->getConfig(\Magento\Store\Model\Store::XML_PATH_STORE_IN_URL)) {
            return $baseUrl;
        }

        $store->getConfig(\MageSuite\BaseUrlCode\Helper\Configuration::XML_PATH_WEB_URL_DEFAULT_URL_CODE);
        $urlCode = $store->getUrlCode();

        if (empty($urlCode)) {
            $urlCode = $store->getConfig(\MageSuite\BaseUrlCode\Helper\Configuration::XML_PATH_WEB_URL_DEFAULT_URL_CODE);
        }

        return sprintf('%s%s/', $baseUrl, $urlCode);
    }
}
