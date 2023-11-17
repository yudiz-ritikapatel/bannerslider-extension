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

namespace Yudiz\BannerSlider\Block\Adminhtml\Grid;

class AddRow extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var  \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'row_id';
        $this->_blockGroup = 'Yudiz_BannerSlider';
        $this->_controller = 'adminhtml_grid';
        parent::_construct();
        if ($this->_isAllowedAction('Yudiz_BannerSlider::add_row')) {
            $this->buttonList->update('save', 'label', __('Save Banner Content'));
        } else {
            $this->buttonList->remove('save');
        }
        $this->buttonList->remove('reset');
        $id = $this->getRequest()->getParam('banner_id');
        if (isset($id)) {
            $this->addButton(
                'delete',
                [
                    'label' => __('Delete'),
                    'onclick' => 'deleteConfirm(' . json_encode(__('Are you sure you want to do this?'))
                        . ','
                        . json_encode(
                            $this->getDeleteUrl()
                        )
                        . ')',
                    'class' => 'scalable delete',
                    'level' => -1
                ]
            );
            $this->addButton(
                'saveandcontinue',
                [
                    'label' => __('Save and Continue Edit'),
                    'class' => 'save',
                    'data_attribute' => [
                        'mage-init' => [
                            'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                        ],
                    ],
                    'sort_order' => 80,
                ]
            );
        }

        $this->addButton(
            'reset',
            [
                'label' => __('Reset'),
                'class' => 'reset',
                'on_click' => 'location.reload();',
                'sort_order' => 30,
            ]
        );
    }

    public function getHeaderText()
    {
        return __('Add Banner Content');
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    public function getFormActionUrl()
    {
        if ($this->hasFormActionUrl()) {
            return $this->getData('form_action_url');
        }

        return $this->getUrl('*/*/save');
    }
    public function getDeleteUrl()
    {
        return $this->getUrl(
            'bannerslider/grid/delete',
            [
                '_current' => true,
                'back' => 'edit',
                'active_tab' => '{{tab_id}}'
            ]
        );
    }
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl(
            'bannerslider/grid/save',
            [
                '_current' => true,
                'back' => 'edit',
                'active_tab' => '{{tab_id}}'
            ]
        );
    }
}
