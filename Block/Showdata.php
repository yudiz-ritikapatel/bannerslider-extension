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

namespace Yudiz\BannerSlider\Block;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * This class is responsible for displaying banner and slider data on frontend.
 */
class Showdata extends Template
{
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $file;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory
     */
    protected $sliderCollectionFactory;

    /**
     * @var \Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory
     */
    protected $collectionBannerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param Context $context
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory $sliderCollectionFactory
     * @param \Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory $collectionBannerFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Magento\Store\Model\ScopeInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\CollectionFactory $sliderCollectionFactory,
        \Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory $collectionBannerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        File $file,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->collectionBannerFactory = $collectionBannerFactory;
        $this->_resource = $resource;
        $this->file = $file;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Get banner collection by banner id
     *
     * @param int|array $banner_id
     * @return \Yudiz\BannerSlider\Model\ResourceModel\Extension\Collection
     */
    public function getBannerCollection($banner_id)
    {
        $collections = $this->collectionBannerFactory->create();
        $collections->addFieldToFilter('banner_id', ['in' => $banner_id]);
        $collections->addFieldToFilter('status', 1);
        $collections->addFieldToFilter('start_date', ['lteq' => date('Y-m-d H:i:s')])
        ->addFieldToFilter('end_date', ['gteq' => date('Y-m-d H:i:s')]);
        return $collections;
    }

    /**
     * Get slider data by slider id
     *
     * @param int $sliderId
     * @return \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider\Collection
     */
    public function getSliderCollection($sliderId)
    {
        $collection = $this->sliderCollectionFactory->create();
        $second_table_name = $this->_resource->getTableName('yudiz_slider_banner_attachment');
        $collection->getSelect()->join(
            ['second' => $second_table_name],
            'main_table.slider_id = second.slider_id'
        );
        $collection->addFieldToFilter('main_table.slider_id', $sliderId);
        $collection->addFieldToFilter('main_table.status', 1);

        return $collection;
    }

    public function getMediaPath()
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }

    public function getPathInformation($filePath)
    {
        $fileExtension = $this->file->getPathInfo($filePath, \PATHINFO_EXTENSION);
        return $fileExtension['extension'];
    }

    public function getModuleVisibility()
    {
        $isEnableModule = $this->scopeConfig->getValue(
            "bannerslider/general/enable_bannerslider",
            ScopeInterface::SCOPE_STORE
        );
        return $isEnableModule;
    }

    public function getSlider()
    {
        $sliderId = $this->getData('slider_id');
        $sliderCollection = $this->getSliderCollection($sliderId);

        return $sliderCollection->getData();
    }
    
    public function getBanner()
    {
        $sliderDetails = $this->getSlider();
        $bannerIds = array_column($sliderDetails, 'banner_id');
        $bannerCollection = $this->getBannerCollection($bannerIds);
    
        return $bannerCollection->getData();
    }
    
    public function getIsYoutubeVideo()
    {
        $bannerCollections = $this->getBanner();
        $mediaTypes = array_column($bannerCollections, 'mediatype');
        $isYoutubeVideo = in_array("2", $mediaTypes) ? 1 : 0;
    
        return $isYoutubeVideo;
    }
    
    public function getBannerData($sliderId)
    {
        $sliderCollection = $this->getSliderCollection($sliderId);
        $bannerIds = array_column($sliderCollection->getData(), 'banner_id');
        $bannerCollection = $this->getBannerCollection($bannerIds);
    
        return $bannerCollection->getData();
    }
}
