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
        $html .= '</span><code style="background-color:#dfe7f5;font-weight: bold;">  
                  <br/>{{block class="Yudiz\BannerSlider\Block\Showdata"
                  slider_id="' . $sliderId . '" template="Yudiz_BannerSlider::index.phtml"}}</code><p>';
        $html .= __('<span> 
                   If you want to add a slider using CMS Page/Static Block, then use this.</span>');
        $html .= '</p></li><li><span>';
        $html .= __('Template .phtml file');
        $html .= '</span><code  style="background-color:#dfe7f5;font-weight: bold;"><br/>';
        $html .= $this->_escaper->escapeHtml('$block = $this->getLayout()
                 ->createBlock(\'Yudiz\BannerSlider\Block\ShowData\');');
        $html .= '</br>';
        $html .= $this->_escaper->escapeHtml('$block->setData(\'slider_id\', ' . $sliderId . ');');
        $html .= '</br>';
        $html .= $this->_escaper->escapeHtml('echo $block->setTemplate
        (\'Yudiz_BannerSlider::index.phtml\')->toHtml();');
        $html .= '</br>';
        $html .= '</code><p>';
        $html .= __('<span>
        If you want to add a slider using PHTML, then use this.</span>');
        $html .= '</p></li></ul>';
        
        return $html;
    }
}
