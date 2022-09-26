<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Observer;

class AddFieldToStoreEditView implements \Magento\Framework\Event\ObserverInterface
{
    protected \Magento\Framework\Registry $registry;

    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function execute(\Magento\Framework\Event\Observer $observer): void
    {
        /** @var \Magento\Backend\Block\System\Store\Edit\AbstractForm $block */
        $block = $observer->getBlock();
        $storeModel = $this->registry->registry('store_data');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $block->getForm();
        $fieldset = $form->getForm()->getElement('store_fieldset');

        if (empty($fieldset)) {
            return;
        }

        $fieldset->addField(
            'url_code',
            'text',
            [
                'name' => 'store[url_code]',
                'value' => $storeModel->getUrlCode(),
                'label' => __('URL Code'),
                'class' => 'cs-csfeature__logo',
                'note' => __('Must match the pattern %1. Example: de-de', \MageSuite\BaseUrlCode\Model\UrlCodeValidator::PATTERN)
            ]
        );
    }
}
