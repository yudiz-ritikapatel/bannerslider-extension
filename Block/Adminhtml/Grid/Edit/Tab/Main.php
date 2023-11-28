<?php
/**
 * Yudiz
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to a newer
 * version in the future.
 *
 * @category    Yudiz
 * @package     Yudiz_BannerSlider
 * @copyright   Copyright (c) 2023 Yudiz (https://www.Yudiz.com/)
 */

namespace  Yudiz\BannerSlider\Block\Adminhtml\Grid\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic
{

    /**
     * Prepare form fields for banner slider edit page.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('banner_');
        if ($model->getBannerId()) {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Edit Banner'), 'class' => 'fieldset-wide']
            );
            $fieldset->addField('banner_id', 'hidden', ['name' => 'banner_id']);
        } else {
            $fieldset = $form->addFieldset(
                'base_fieldset',
                ['legend' => __('Add Banner'), 'class' => 'fieldset-wide']
            );
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
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $fieldset->addField('start_date', 'date', [
            'name'        => 'start_date',
            'label'       => __('Start Date'),
            'title'       => __('Start Date'),
            'date_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
            'class'       => 'validate-date validate-date-range date-range-custom_theme-from',
            'required' => true,
        ]);

        $fieldset->addField('end_date', 'date', [
            'name'          => 'end_date',
            'label'         => __('End Date'),
            'title'         => __('End Date'),
            'date_format'   => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
            'class'         => 'validate-date validate-date-range date-range-custom_theme-from',
            'required' => true,
        ]);

        $fieldset->addField(
            'description',
            'textarea',
            [
                'name' => 'description',
                'label' => __('Description'),
                'id' => 'description',
                'title' => __('Description'),
                'class' => 'required-entry',
                'required' => true,
            ]
        );

        $mediatype = $fieldset->addField(
            'mediatype',
            'select',
            [
                'name' => 'mediatype',
                'label' => __('Select Media Type'),
                'id' => 'mediatype',
                'title' => __('Select Media Type'),
                'options' => [
                    '0' => __('select '),
                    '1' => __('image/Video'),
                    '2' => __('External video'),
                ],
                'class' => 'mediatype',
            ]
        );
        $fileuploader = $fieldset->addField(
            'uploadfiles',
            'image',
            [
                'name' => 'uploadfiles',
                'id' => 'uploadfiles',
                'label' => __('Upload Files'),
                'title' => __('Upload Files'),
                'note' => 'Allow file type: .png,.jpg,.jpeg,.gif,.mp4',
                'required'  => true,
            ]
        );
        $externalvideo = $fieldset->addField(
            'externalvideo',
            'text',
            [
                'name' => 'externalvideo',
                'label' => __(' External Video'),
                'id' => 'externalvideo',
                'title' => __('External Video'),
                'class' => 'required-entry',
                'note' => __('Paste your Link Here (https://www.google.com)'),
                'required' => true,
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class)
                ->addFieldMap($mediatype->getHtmlId(), $mediatype->getName())
                ->addFieldMap($fileuploader->getHtmlId(), $fileuploader->getName())
                ->addFieldMap($externalvideo->getHtmlId(), $externalvideo->getName())

                ->addFieldDependence($fileuploader->getName(), $mediatype->getName(), '1')
                // Show image field when mediatype is 0
                ->addFieldDependence($externalvideo->getName(), $mediatype->getName(), '2')
            // Show image field when mediatype is 0
        );

        return parent::_prepareForm();
    }
}
