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

use Magento\Backend\Block\Widget\Form\Generic;

class Effect extends Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
     * @var \Yudiz\BannerSlider\Helper\Data $helper
     */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Yudiz\BannerSlider\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \Yudiz\BannerSlider\Model\BannerSlider */
        $model = $this->_coreRegistry->registry('yudiz_slider');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('slider_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Information')]);

        if ($model->getId()) {
            $fieldset->addField('slider_id', 'hidden', ['name' => 'slider_id']);
        }

        $autoplay =  $fieldset->addField(
            'autoplay',
            'select',
            [
                'name' => 'autoplay',
                'label' => __('Auto Slide'),
                'id' => 'autoplay',
                'title' => __('Auto Slide'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'class' => 'autoplay',
            ]
        );
        $autoplayTimeout = $fieldset->addField(
            'autoplay_timeout',
            'select',
            [
                'name' => 'autoplay_timeout',
                'label' => __('Set AutoPlay Timeout(seconds)'),
                'id' => 'autoplay_timeout',
                'title' => __('Set AutoPlay Timeout(seconds)'),
                'options' => [
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10,
                ],
                'class' => 'autoplay_timeout',
            ]
        );
        $fieldset->addField(
            'reverse_slide',
            'select',
            [
                'name' => 'reverse_slide',
                'label' => __('Reverse Slide(L to R)'),
                'id' => 'reverse_slide',
                'title' => __('Reverse Slide(L to R)'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'note' => __('Enable Auto Slider'),
                'class' => 'reverse_slide',
            ]
        );
        $fieldset->addField(
            'previous_next',
            'select',
            [
                'name' => 'previous_next',
                'label' => __('Show Previous/Next Button'),
                'id' => 'previous_next',
                'title' => __('Show Previous/Next Button'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'class' => 'previous_next',
            ]
        );
        $fieldset->addField(
            'show_dots',
            'select',
            [
                'name' => 'show_dots',
                'label' => __('Show Dots Navigation Button'),
                'id' => 'show_dots',
                'title' => __('Show Dots Navigation Button'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'class' => 'show_dots',
            ]
        );
        $fieldset->addField(
            'margin',
            'select',
            [
                'name' => 'margin',
                'label' => __('Set Slider Margin'),
                'id' => 'margin',
                'title' => __('Set Slider Margin'),
                'options' => [
                    1 => 1,
                    5 => 5,
                    7 => 7,
                    10 => 10,
                    13 => 13,
                    15 => 15,
                    20 => 20,
                    25 => 25,
                    30 => 30,
                    35 => 35,
                    40 => 40,

                ],
                'class' => 'margin',
            ]
        );
        $fieldset->addField(
            'effect',
            'select',
            [
                'name' => 'effect',
                'label' => __('Fade In/Fade Out Effect'),
                'id' => 'effect',
                'title' => __('Fade In/Fade Out Effect'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'class' => 'effect',
            ]
        );
        $fieldset->addField(
            'controls',
            'select',
            [
                'name' => 'controls',
                'label' => __('Enable Controls in Youtube Video'),
                'id' => 'controls',
                'title' => __('Enable Controls in Youtube Video'),
                'options' => [0 => __('No'), 1 => __('Yes')],
                'class' => 'controls',
                'value'=>1           ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class)
                ->addFieldMap($autoplay->getHtmlId(), $autoplay->getName())
                ->addFieldMap($autoplayTimeout->getHtmlId(), $autoplayTimeout->getName())
                ->addFieldDependence($autoplayTimeout->getName(), $autoplay->getName(), 1)
        );

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Effects');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Effects');
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
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
