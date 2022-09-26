<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Model;

class StoreResolver
{
    protected ?\Magento\Store\Api\Data\StoreInterface $store = null;

    protected \Magento\Store\Model\StoreManagerInterface $storeManager;

    protected \Magento\Framework\App\RequestInterface $request;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->storeManager = $storeManager;
        $this->request = $request;
    }

    public function initStore(string $urlCode): self
    {
        foreach ($this->storeManager->getStores() as $store) {
            if (!$store->getIsActive() || $store->getData('url_code') !== $urlCode) {
                continue;
            }

            $baseUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);

            if (strpos((string)$this->request->getUriString(), $baseUrl) === false) {
                continue;
            }

            $this->setCurrentStore($store);
            break;
        }

        if (!$this->store) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Unable to init store with provided url code: %1.', $urlCode)
            );
        }

        return $this;
    }

    public function initByCurrentStore(): self
    {
        $currentStore = $this->storeManager->getStore();

        foreach ($this->storeManager->getStores() as $store) {
            if (strpos($store->getBaseUrl(), $this->request->getHttpHost()) === false) {
                continue;
            }

            if ($store->getGroup()->getDefaultStoreId() !== $store->getId()) {
                continue;
            }

            $this->setCurrentStore(
                $currentStore->getId() != $store->getId()
                    ? $store
                    : $currentStore
            );
            break;
        }

        if (!$this->store) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Unable to init default store.')
            );
        }

        return $this;
    }

    protected function setCurrentStore(\Magento\Store\Api\Data\StoreInterface $store): void
    {
        $this->store = $store;
        $this->storeManager->setCurrentStore($store);
    }

    public function getStore(): \Magento\Store\Api\Data\StoreInterface
    {
        if ($this->store === null) {
            $this->initByCurrentStore();
        }

        return $this->store;
    }

    public function unsetStore(): self
    {
        $this->store = null;

        return $this;
    }
}
