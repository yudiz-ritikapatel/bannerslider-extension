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

namespace Yudiz\BannerSlider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Yudiz\BannerSlider\Model\BannerSlider;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * BannerSlider
     *
     * @var BannerSlider
     */
    protected $bannerSlider;

    /**
     * Delete constructor.
     *
     * @param BannerSlider $bannerSlider
     */
    public function __construct(BannerSlider $bannerSlider)
    {
        $this->bannerSlider = $bannerSlider;
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('slider_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->bannerSlider->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Slider Data deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['slider_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a data to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
