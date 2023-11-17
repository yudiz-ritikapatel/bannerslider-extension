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

use Yudiz\BannerSlider\Block\Adminhtml\Slider\Edit\Tab\Renderer\Snippet;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;
    /**
     * @var \Yudiz\BannerSlider\Model\BannerSliderFactory
     */
    protected $sliderFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Yudiz\BannerSlider\Model\BannerSliderFactory $sliderFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Yudiz\BannerSlider\Model\BannerSliderFactory $sliderFactory,
        array $data = []
    ) {
        $this->sliderFactory = $sliderFactory;
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
        $model = $this->_coreRegistry->registry('yudiz_slider');
        $form = $this->_formFactory->create();

        $tableName = "yudiz_slider";
        $bannerslidersCollection = $this->sliderFactory->create()->getCollection();

        $form->setHtmlIdPrefix('slider_');

        if ($model->getSliderId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Slider Content'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('slider_id', 'hidden', ['name' => 'slider_id']);
            $sliderId = $model->getSliderId();
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Slider Content'), 'class' => 'fieldset-wide']
            );
            $sliderIdRaw = $bannerslidersCollection->getConnection()->fetchRow("SHOW TABLE STATUS LIKE '$tableName'");
            $sliderId = $sliderIdRaw['Auto_increment'];
        }

        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'id' => 'status',
                'title' => __('Status'),
                'options' => [0 => __('Disabled'), 1 => __('Enabled')],
                'class' => 'status',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Name'),
                'id' => 'name',
                'title' => __('Name'),
                'required' => true,
            ]
        );

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'id' => 'description',
                'title' => __('Description'),
                'required' => true,
            ]
        );

        $subfieldset = $form->addFieldset('sub_fieldset', [
            'legend' => __('Another way to add sliders to your page'),
            'class' => 'fieldset-wide'
        ]);
        $subfieldset->addField('snippet', Snippet::class, [
            'name' => 'snippet',
            'label' => __('How to use'),
            'title' => __('How to use'),
            'slider_id' => $sliderId,
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Main');
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
