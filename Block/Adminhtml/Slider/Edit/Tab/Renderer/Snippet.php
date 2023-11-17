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

namespace Yudiz\BannerSlider\Block\Adminhtml\Slider\Edit\Tab\Renderer;

use Magento\Framework\Data\Form\Element\AbstractElement;

class Snippet extends AbstractElement
{
    /**
     * @return string
     */
    public function getElementHtml()
    {
        $sliderId = $this->getSliderId() ?? 1;

        $html = '<ul class="banner-location-display"><li style="padding-bottom:10px;"><span>';
        $html .= __('CMS Page/Static Block');
        $html .= '</span><code>  <br/>{{block class="Yudiz\BannerSlider\Block\Showdata"
                  slider_id="' . $sliderId . '" template="Yudiz_BannerSlider::index.phtml"}}</code><p>';
        $html .= __('<span style="background-color:#dfe7f5;font-weight: bold;"> 
                  You can paste the above line into any page in Magento 2 and set SliderId for it.</span>');
        $html .= '</p></li><li><span>';
        $html .= __('Template .phtml file');
        $html .= '</span><code><br/>';
        $html .= $this->_escaper->escapeHtml('$block = $this->getLayout()
                 ->createBlock(\'Yudiz\BannerSlider\Block\ShowData\');');
        $html .= '</br>';
        $html .= $this->_escaper->escapeHtml('$block->setData(\'slider_id\', ' . $sliderId . ');');
        $html .= '</br>';
        $html .= $this->_escaper->escapeHtml('echo $block->setTemplate
        (\'Yudiz_BannerSlider::index.phtml\')->toHtml();');
        $html .= '</br>';
        $html .= '</code><p>';
        $html .= __('<span style="background-color:#dfe7f5;font-weight: bold;">
         Open a .phtml file and insert where you want to display Banner Slider.</span>');
        $html .= '</p></li></ul>';
        
        return $html;
    }
}
