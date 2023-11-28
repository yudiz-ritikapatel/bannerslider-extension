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

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory;
use Yudiz\BannerSlider\Model\Extension;

class MassStatus extends \Magento\Backend\App\Action
{
    /**
     * @var Magento\Ui\Component\MassAction\Filter;
     */
    protected $filter;
    /**
     * @var Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var  Yudiz\BannerSlider\Model\Extension;
     */
    protected $gridmodel;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param Magento\Ui\Component\MassAction\Filter $filter
     * @param Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        Extension $gridmodel
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->gridmodel = $gridmodel;
        parent::__construct($context);
    }

    public function execute()
    {
        $jobData = $this->collectionFactory->create();

        foreach ($jobData as $value) {
            $templateId[] = $value['banner_id'];
        }
        $parameterData = $this->getRequest()->getParams('status');
        $selectedAppsid = $this->getRequest()->getParams('status');
        if (array_key_exists("selected", $parameterData)) {
            $selectedAppsid = $parameterData['selected'];
        }
        if (array_key_exists("excluded", $parameterData)) {
            if ($parameterData['excluded'] == 'false') {
                $selectedAppsid = $templateId;
            } else {
                $selectedAppsid = array_diff($templateId, $parameterData['excluded']);
            }
        }
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('banner_id', ['in' => $selectedAppsid]);
        $status = 0;
        foreach ($collection as $item) {
            $this->setStatus($item->getBannerId(), $this->getRequest()->getParam('status'));
            $status++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 Banner were updated.', $status));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    public function setStatus($id, $Param)
    {
        $item = $this->gridmodel->load($id);
        $item->setStatus($Param)->save();
    }
}
