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

namespace Yudiz\BannerSlider\Block\Adminhtml\Slider\Edit\Tab;

use Yudiz\BannerSlider\Model\BannerSliderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Yudiz\BannerSlider\Model\BannerSlider;

class Banners extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory
     */
    protected $extensionCollectionFactory;
    /**
     * @var bannerSlider
     */
    protected $bannerSlider;
    /**
     * slider factory
     *
     * @var sliderFactory
     */
    protected $sliderFactory;

    /**
     * @var  \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param sliderFactory $attachmentFactory
     * @param  BannerSlider $bannerSlider
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        BannerSliderFactory $sliderFactory,
        StoreManagerInterface $storeManager,
        \Yudiz\BannerSlider\Model\ResourceModel\Extension\CollectionFactory $extensionCollectionFactory,
        BannerSlider $bannerSlider,
        array $data = []
    ) {
        $this->sliderFactory = $sliderFactory;
        $this->extensionCollectionFactory = $extensionCollectionFactory;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->bannerSlider = $bannerSlider;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('bannersGrid');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('slider_id')) {
            $this->setDefaultFilter(['in_banner' => 1]);
        }
    }

    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_banner') {
            $bannerIds = $this->_getSelectedBanners();

            if (empty($bannerIds)) {
                $bannerIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('banner_id', ['in' => $bannerIds]);
            } else {
                if ($bannerIds) {
                    $this->getCollection()->addFieldToFilter('banner_id', ['in' => $bannerIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * prepare collection
     */
    protected function _prepareCollection()
    {
        $collection = $this->extensionCollectionFactory->create();
        $collection->addFieldToFilter('status', 1);
        $collection->addFieldToFilter('start_date', ['lteq' => date('Y-m-d H:i:s')])
        ->addFieldToFilter('end_date', ['gteq' => date('Y-m-d H:i:s')]);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        /* @var $model \Yudiz\BannerSlider\Model\Slider */
        $model = $this->bannerSlider;

        $this->addColumn(
            'in_banner',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_banner',
                'align' => 'center',
                'index' => 'banner_id',
                'values' => $this->_getSelectedBanners(),
            ]
        );

        $this->addColumn(
            'banner_id',
            [
                'header' => __('Banner ID'),
                'type' => 'number',
                'index' => 'banner_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'Name',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'Description',
            [
                'header' => __('Description'),
                'index' => 'description',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );

        $this->addColumn(
            'media_content',
            [
                'header' => __('Image/Video/ExternalVideo'),
                'class' => 'xxx',
                'width' => '50px',
                'frame_callback' => [$this, 'renderMediaContent'],
            ]
        );
        return parent::_prepareColumns();
    }

    /**
     * Callback to render the media content based on mediatype
     *
     * @param string $value
     * @param \Yudiz\BannerSlider\Model\BannerSlider $row
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @param boolean $isExport
     * @return string
     */
    public function renderMediaContent($value, $row, $column, $isExport)
    {
        $mediatype = $row->getData('mediatype');
        if ($mediatype == 1) {
            $uploadfiles = $row->getData('uploadfiles');
            if ($uploadfiles) {
                $store = $this->storeManager->getStore();
                $mediaUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                $url = $mediaUrl . $uploadfiles;
                if (strpos($uploadfiles, 'mp4') !== false) {
                    $label = 'Video';
                } elseif (strpos($uploadfiles, 'gif') !== false) {
                    $label = 'Giphy Image';
                } else {
                    $label = 'Image';
                }
                return '<a href="' . $url . '" target="_blank">' . $label . '</a>';
            }
        } elseif ($mediatype == 2) {
            $externalvideo = $row->getData('externalvideo');
            if ($externalvideo) {
                return '<a href="' . $externalvideo . '" target="_blank">' . $externalvideo . '</a>';
            }
        }
        return '';
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/bannersgrid', ['_current' => true]);
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    protected function _getSelectedBanners()
    {
        $slider = $this->getSlider();
        return $slider->getBanners($slider);
    }

    /**
     * Retrieve selected banners
     *
     * @return array
     */
    public function getSelectedBanners()
    {
        $slider = $this->getSlider();
        $selected = $slider->getBanners($slider);

        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    protected function getSlider()
    {
        $sliderId = $this->getRequest()->getParam('slider_id');
        $slider   = $this->sliderFactory->create();
        if ($sliderId) {
            $slider->load($sliderId);
        }
        return $slider;
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
}
