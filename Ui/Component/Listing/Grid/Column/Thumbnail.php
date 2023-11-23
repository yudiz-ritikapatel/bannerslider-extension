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

namespace Yudiz\BannerSlider\Ui\Component\Listing\Grid\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    const ALT_FIELD = 'name';

    private $_getModel;

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @var Yudiz\BannerSlider\Model\Image
     */
    private $imageHelper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Yudiz\BannerSlider\Model\Image $imageHelper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Yudiz\BannerSlider\Model\Image $imageHelper,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->imageHelper = $imageHelper;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    // ...
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $url = '';
                $label = '';

                if ($item['mediatype'] == 1 && !empty($item['uploadfiles'])) {
                    $url = $this->imageHelper->getBaseUrl() . $item['uploadfiles'];
                    if (strpos($item['uploadfiles'], 'mp4') !== false) {
                        $label = 'Video';
                    } elseif (strpos($item['uploadfiles'], 'gif') !== false) {
                        $label = 'Giphy Image';
                    } else {
                        $label = 'Image';
                    }
                } else {
                    $url = $item['externalvideo'];
                    $label = $item['externalvideo'];
                }

                $item[$fieldName] = [
                    $item['mediatype'] == 1 ? 'uploadfiles' : 'externalvideo' => [
                        'href' => $url,
                        'label' => $label,
                        'target' => '_blank'
                    ],
                ];
            }
        }
        return $dataSource;
    }
}
