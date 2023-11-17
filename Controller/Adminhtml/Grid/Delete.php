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
use Magento\Backend\App\Action\Context;

/**
 * Controller for deleting a banner from the grid in the admin panel.
 */
class Delete extends Action
{
    /**
     * @var \Yudiz\BannerSlider\Model\Extension $extensionfactory
     */
    public $extensionfactory;

    /**
     * Backend session
     *
     * @var backendSession
     */
    protected $backendSession;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Yudiz\BannerSlider\Model\Extension $extensionfactory
     */
    public function __construct(
        Action\Context $context,
        \Yudiz\BannerSlider\Model\Extension $extensionfactory,
        \Magento\Backend\Model\Session $backendSession
    ) {
        $this->extensionfactory = $extensionfactory;
        $this->backendSession = $backendSession;
        parent::__construct($context);
    }

    /**
     * Check if the user is allowed to delete a banner.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Yudiz_BannerSlider::grid_delete');
    }

    /**
     * Delete a banner from the grid.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('banner_id');
        if ($id) {
            try {
                $model = $this->backendSession->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the item.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                return $resultRedirect->setPath('*/*/edit', ['banner_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a item to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
