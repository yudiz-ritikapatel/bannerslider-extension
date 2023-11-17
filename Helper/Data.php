<?php

/**
 * Yudiz
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to a newer
 * version in the future.
 *
 * @category    Yudiz
 * @package     Yudiz_BannerSlider
 * @copyright   Copyright (c) 2023 Yudiz (https://www.Yudiz.com/)
 */

namespace Yudiz\BannerSlider\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $uploaderFactory;
    /**
     * Path to store config if the front-end path is used
     */
    const XML_PATH_BANNER = 'banner/';

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        parent::__construct($context);
        $this->_backendUrl = $backendUrl;
        $this->storeManager = $storeManager;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
    }

    /**
     * Get banners tab URL in admin
     * @return string
     */
    public function getBannersGridUrl()
    {
        return $this->_backendUrl->getUrl('bannerslider/slider/banners', ['_current' => true]);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_BANNER . 'general/' . $code, $storeId);
    }

    public function getImageUploader()
    {
        try {
            $uploaderFactory = $this->_fileUploaderFactory->create(['fileId' => 'uploadfiles']);
            $this->prepareUploader($uploaderFactory);

            $mediaDirectory = $this->getMediaDirectory('Yudiz/BannerSlider');
            $result = $uploaderFactory->validateFile();

            $this->validateUploadedFile($result, $uploaderFactory, $mediaDirectory);

            return $uploaderFactory->getUploadedFileName();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()));
        }
    }

    private function prepareUploader($uploaderFactory)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'mp4'];
        $uploaderFactory->setAllowedExtensions($allowedExtensions)
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(false)
            ->setAllowCreateFolders(true);
    }

    private function getMediaDirectory($subDirectory)
    {
        return $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath($subDirectory);
    }

    private function validateUploadedFile($result, $uploaderFactory, $mediaDirectory)
    {
        if ($result['error'] == 0) {
            if ($result['size'] < 10000000) {
                if (!$uploaderFactory->save($mediaDirectory)) {
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Failed to save the file.')
                    );
                }
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Upload file size should be less than 10MB.')
                );
            }
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File upload error: %1', $result['error'])
            );
        }
    }
}
