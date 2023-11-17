<?php

/**
 * Yudiz
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Yudiz
 * @package     Yudiz_BannerSlider
 * @copyright   Copyright (c) 2023 Yudiz (https://www.Yudiz.com/)
 */

namespace Yudiz\BannerSlider\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class AddRow extends Action
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $coreRegistry;
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $extensionFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Yudiz\BannerSlider\Model\ExtensionFactory $extensionFactory
    ) {
        parent::__construct($context);
        $this->coreRegistry = $coreRegistry;
        $this->extensionFactory = $extensionFactory;
    }

    public function execute()
    {
        $rowId = (int) $this->getRequest()->getParam('banner_id');
        $rowData = $this->extensionFactory->create();
        if ($rowId) {
            $rowData = $rowData->load($rowId);
            $rowTitle = $rowData->getTitle();
            if (!$rowData->getBannerId()) {
                $this->messageManager->addErrorMessage(__('Banner content data no longer exist.'));
                $this->_redirect('bannerslider/grid/rowdata');
                return;
            }
        }

        $this->coreRegistry->register('row_data', $rowData);
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title = $rowId ? __('Edit Banner Content') . $rowTitle : __('Add Banner Content');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Yudiz_BannerSlider::add_row');
    }
}
