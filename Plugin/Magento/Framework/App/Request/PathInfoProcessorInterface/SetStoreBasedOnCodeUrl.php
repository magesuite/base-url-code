<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Plugin\Magento\Framework\App\Request\PathInfoProcessorInterface;

class SetStoreBasedOnCodeUrl
{
    protected \MageSuite\BaseUrlCode\Helper\Configuration $configuration;

    protected \MageSuite\BaseUrlCode\Model\StoreResolver $storeResolver;

    protected \MageSuite\BaseUrlCode\Model\UrlCodeValidator $urlCodeValidator;

    public function __construct(
        \MageSuite\BaseUrlCode\Helper\Configuration $configuration,
        \MageSuite\BaseUrlCode\Model\StoreResolver $storeResolver,
        \MageSuite\BaseUrlCode\Model\UrlCodeValidator $urlCodeValidator
    ) {
        $this->configuration = $configuration;
        $this->storeResolver = $storeResolver;
        $this->urlCodeValidator = $urlCodeValidator;
    }

    public function beforeProcess(
        \Magento\Framework\App\Request\PathInfoProcessorInterface $subject,
        \Magento\Framework\App\RequestInterface $request,
        string $pathInfo
    ): array {
        if (!$this->configuration->isStoreCodeInUrl()
            || $subject instanceof \Magento\Backend\App\Request\PathInfoProcessor) {
            return [$request, $pathInfo];
        }

        $defaultPathInfo = sprintf('/%s/', $this->configuration->getDefaultUrlCode());

        return [$request, !$pathInfo ? $defaultPathInfo : $pathInfo];
    }

    public function aroundProcess(
        \Magento\Framework\App\Request\PathInfoProcessorInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request,
        string $pathInfo
    ): string {
        if (!$this->configuration->isStoreCodeInUrl()
            || $subject instanceof \Magento\Backend\App\Request\PathInfoProcessor) {
            return $proceed($request, $pathInfo);
        }

        $parts = explode('/', ltrim($pathInfo, '/'), 2);
        $urlCode = $parts[0];

        try {
            if (!$this->urlCodeValidator->validate($urlCode)) {
                $this->storeResolver->getStore();
                return $proceed($request, $pathInfo);
            }

            $this->storeResolver->initStore($urlCode);
            $storeCode = $this->storeResolver->getStore()->getCode();
            $pathInfo = sprintf('/%s/%s', $storeCode, $parts[1] ?? '');

            return $proceed($request, $pathInfo);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $request->setActionName('noroute');

            return $pathInfo;
        }
    }
}
