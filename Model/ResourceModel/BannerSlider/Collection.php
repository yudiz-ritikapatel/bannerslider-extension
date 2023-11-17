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

namespace Yudiz\BannerSlider\Model\ResourceModel\BannerSlider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Yudiz\BannerSlider\Model\BannerSlider;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'slider_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(BannerSlider::class, \Yudiz\BannerSlider\Model\ResourceModel\BannerSlider::class);
    }
}
