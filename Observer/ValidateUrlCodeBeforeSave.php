<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Observer;

class ValidateUrlCodeBeforeSave implements \Magento\Framework\Event\ObserverInterface
{
    protected \MageSuite\BaseUrlCode\Model\UrlCodeValidator $urlCodeValidator;

    public function __construct(\MageSuite\BaseUrlCode\Model\UrlCodeValidator $urlCodeValidator)
    {
        $this->urlCodeValidator = $urlCodeValidator;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $observer->getStore();
        $urlCode = (string)$store->getUrlCode();

        if (strlen($urlCode) > 0 && !$this->urlCodeValidator->validate($urlCode)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Store URL code must match the pattern %1. Example: de-de', \MageSuite\BaseUrlCode\Model\UrlCodeValidator::PATTERN)
            );
        }
    }
}
