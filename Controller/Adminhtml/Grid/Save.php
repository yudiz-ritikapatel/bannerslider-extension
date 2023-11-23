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

use Yudiz\BannerSlider\Helper\Data;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Yudiz\BannerSlider\Model\ExtensionFactory
     */
    protected $extensionFactory;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * @var  Yudiz\BannerSlider\Helper\Data
     */
    protected $helperData;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Yudiz\BannerSlider\Model\ExtensionFactory $extensionFactory
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param Yudiz\BannerSlider\Helper\Data $helperData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Yudiz\BannerSlider\Model\ExtensionFactory $extensionFactory,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        Data $helperData
    ) {
        parent::__construct($context);
        $this->extensionFactory = $extensionFactory;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->helperData = $helperData;
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $filedata = $this->getRequest()->getFiles('uploadfiles');
        $fileName = ($filedata && array_key_exists('name', $filedata)) ? $filedata['name'] : null;
        if (!$data) {
            $this->_redirect('bannerslider/grid/addrow');
            return;
        }
        try {
            $rowData = $this->extensionFactory->create();
            $rowData->setData($data);
            if (isset($data['banner_id'])) {
                $this->updateBannerData($data, $filedata, $rowData, $fileName);
            } else {
                $this->createNewRecord($data, $filedata, $rowData, $fileName);
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('bannerslider/grid/index');
    }

    private function updateBannerData($data, $filedata, $rowData, $fileName)
    {
        // Update existing record
        if ($fileName) {
            $this->processFileName($data, $filedata, $rowData);
        } elseif (isset($data['uploadfiles']['delete']) && $data['uploadfiles']['delete'] == 1) {
            $this->messageManager->addErrorMessage(__("You Can Not delete the image because of Required Field."));
            $this->_redirect('*/*/index');
        } else {
            $this->handleMediaType($data, $rowData);
        }
        $rowData->setBannerId($data['banner_id']);
        $rowData->save();
        $this->handleSuccessRedirect($rowData);
    }

    private function createNewRecord($data, $filedata, $rowData, $fileName)
    {
        // Create a new record
        if ($fileName) {
            $this->processFileName($data, $filedata, $rowData);
        } elseif ($data['mediatype'] == 2) {
            $this->handleMediaType($data, $rowData);
        } elseif ($data['mediatype'] == 0) {
            $this->messageManager->addErrorMessage(__('Select Media Type'));
            $this->_redirect('*/*/addrow');
            return;
        }
        $rowData->save();
        $this->handleSuccessRedirect($rowData);
    }

    private function processFileName(&$data, $filedata, $rowData)
    {
        if ($data['mediatype'] == 1) {
            $filePath ="Yudiz/BannerSlider/". $this->helperData->getImageUploader();
            $data['uploadfiles'] = $filePath;
        } elseif ($data['mediatype'] == 2) {
            $filePath = $data['externalvideo'];
            $data['externalvideo'] = $filePath;
        }
        $rowData->setData($data);
    }

    private function handleMediaType($data, $rowData)
    {
        if ($data['mediatype'] == 1) {
            $data['uploadfiles'] = $data['uploadfiles']['value'];
        } elseif ($data['mediatype'] == 2) {
            $filePath = $data['externalvideo'];
            $data['externalvideo'] = $filePath;
            $data['uploadfiles'] = null;
        }
        $rowData->setData($data);
    }

    private function handleSuccessRedirect($rowData)
    {
        $this->messageManager->addSuccessMessage(
            isset($data['banner_id'])
                ? __("Banner Content Data Updated Successfully.")
                : __("Banner Content Data has been Added successfully.")
        );
        if ($this->getRequest()->getParam('back')) {
            $this->_redirect('*/*/addrow', ['id' => $rowData->getId(), '_current' => true]);
        } else {
            $this->_redirect('*/*/index');
        }
    }
}
