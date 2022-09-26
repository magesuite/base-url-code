<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Helper;

class Configuration
{
    public const XML_PATH_WEB_URL_DEFAULT_URL_CODE = 'web/url/default_url_code';

    protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDefaultUrlCode(): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_WEB_URL_DEFAULT_URL_CODE);
    }

    public function isStoreCodeInUrl(): bool
    {
        return $this->scopeConfig->isSetFlag(\Magento\Store\Model\Store::XML_PATH_STORE_IN_URL);
    }
}
