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

namespace Yudiz\BannerSlider\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * @var Magento\Framework\Controller\ResultFactory
     */
    protected $resultPageFactory;

    /**
     * @var  Magento\Framework\UrlInterface
     */
    private $url;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\Controller\ResultFactory $resultPageFactory
     * @param array $data
     */
    public function __construct(
        UrlInterface $url,
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->url = $url;
    }

    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
