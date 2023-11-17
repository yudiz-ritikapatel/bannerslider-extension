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
use Magento\Backend\App\Action\Context;
use Yudiz\BannerSlider\Model\BannerSlider;
use Magento\Backend\Model\Session;
use Magento\Framework\App\ResourceConnection;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var Resources
     */
    protected $resources;

    /**
     * @var Session
     */
    protected $backendSession;

    /**
     * @var BannerSlider
     */
    protected $bannerSlider;

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * @var \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory
     */
    protected $sliderCollectionFactory;
    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_mediaDirectory;
    /**
     * @var \\Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_file;
    protected $_fileUploaderFactory;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * \Magento\Backend\Helper\Js $jsHelper
     * @param Action\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\Filesystem\Driver\File
     * @param \Magento\Backend\Helper\Js $jsHelper
     * @param \Session $backendSession
     * @param \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory $sliderCollectionFactory
     * @param \BannerSlider $bannerSlider
     * @param \ResourceConnection $resourceConnection
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Backend\Helper\Js $jsHelper,
        \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory $sliderCollectionFactory,
        BannerSlider $bannerSlider,
        Session $backendSession,
        ResourceConnection $resources
    ) {
        $this->_jsHelper = $jsHelper;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->filesystem = $filesystem;
        $this->_file = $file;
        $this->bannerSlider = $bannerSlider;
        $this->backendSession = $backendSession;
        $this->resources = $resources;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($this->getRequest()->getPostValue()) {

            /** @var \Yudiz\BannerSlider\Model\BannerSlider $model */
            $model = $this->bannerSlider;
            $data = $this->getRequest()->getPostValue();
            $id = $this->getRequest()->getParam('slider_id');
            $assignbanners = $this->getRequest()->getParam('banners');

            if ($assignbanners == '') {
                if ($id == '') {
                    $this->messageManager->addErrorMessage(__('please assign the Banners'));
                    return $this->_redirect('*/*/edit');
                }
            }
            $model->setData($data);
            try {
                $model->save();
                $this->saveBanners($model, $data);
                $this->messageManager->addSuccessMessage(__('Slider Data Saved Successfully.'));
                $this->backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['slider_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the slider.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['slider_id' => $this->getRequest()->getParam('slider_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function saveBanners($model, $post)
    {
        // Attach the attachments to sliders
        if (isset($post['banners'])) {
            $bannerIds = $this->_jsHelper->decodeGridSerializedInput($post['banners']);
            try {
                $oldBanners = (array) $model->getBanners($model);
                $newBanners = (array) $bannerIds;

                $connection = $this->resources->getConnection();
                $table = $this->resources->getTableName(
                    \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider::TBL_ATT_BANNER
                );

                $insert = array_diff($newBanners, $oldBanners);
                $delete = array_diff($oldBanners, $newBanners);

                if ($delete) {
                    $where = ['slider_id = ?' => (int) $model->getId(), 'banner_id IN (?)' => $delete];
                    $connection->delete($table, $where);
                }

                if ($insert) {
                    $data = [];
                    foreach ($insert as $banner_id) {
                        $data[] = ['slider_id' => (int) $model->getId(), 'banner_id' => (int) $banner_id];
                    }
                    $connection->insertMultiple($table, $data);
                }
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the slider.'));
            }
        }
    }
}
