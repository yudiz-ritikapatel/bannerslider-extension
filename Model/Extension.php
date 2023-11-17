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

namespace Yudiz\BannerSlider\Model;

use Magento\Framework\DataObject\IdentityInterface;

class Extension extends \Magento\Framework\Model\AbstractModel implements IdentityInterface
{

    /**
     * CMS page cache tag
     */
    const CACHE_TAG = 'yudiz_extension_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'yudiz_extension_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'yudiz_extension_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Yudiz\BannerSlider\Model\ResourceModel\Extension::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getBanners(\Yudiz\BannerSlider\Model\Extension $object)
    {
        $tbl = $this->getResource()->getTable(\Yudiz\BannerSlider\Model\ResourceModel\Extension::TBL_BANNER);
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['banner_id', 'title', 'image']
        );
        return $this->getResource()->getConnection()->fetchAll($select);
    }
}
