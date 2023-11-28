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

namespace Yudiz\BannerSlider\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Yudiz\BannerSlider\Model\BannerSliderFactory;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Yudiz\BannerSlider\Block\Showdata;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;

class BannersliderData implements ResolverInterface
{

    /**
     * @var Yudiz\BannerSlider\Model\BannerSliderFactory
     */
    private $sliderFactory;

    /**
     * @var \Yudiz\BannerSlider\Block\Showdata
     */
    private $showdataBlock;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $scopeConfig;

    /**
     * Constructor.
     *
     * @param Yudiz\BannerSlider\Model\BannerSliderFactory $sliderFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface
     * @param \Yudiz\BannerSlider\Block\Showdata $showdataBlock
     * @param array $data
     */
    public function __construct(
        BannerSliderFactory $sliderFactory,
        ScopeConfigInterface $scopeConfig,
        Showdata $showdataBlock
    ) {
        $this->sliderFactory = $sliderFactory;
        $this->showdataBlock = $showdataBlock;
        $this->scopeConfig = $scopeConfig;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $isEnableModule = $this->showdataBlock->getModuleVisibility();

        if ($isEnableModule == 1) {

            if ($field->getName() === 'YudizBannerSlider') {
                $sliderId = $args['id'];
                $sliderData = [];
                try {
                    $slider = $this->sliderFactory->create();
                    $slider->load($sliderId); // Load the slider by ID
                    $SliderData = $slider->getData();
                    if ($slider->getId()) {
                        if ($SliderData['status'] == 1) {
                            $sliderData = [
                                'slider_id' => $SliderData['slider_id'],
                                'created_at' => $SliderData['creation_time'],
                                'updated_at' => $SliderData['update_time'],
                                'status' => $SliderData['status'],
                                'name' => $SliderData['name'],
                                'description' => $SliderData['description'],
                                'autoplay' => $SliderData['autoplay'],
                                'autoplay_timeout' => $SliderData['autoplay_timeout'],
                                'reverse_slide' => $SliderData['reverse_slide'],
                                'previous_next' => $SliderData['previous_next'],
                                'show_dots' => $SliderData['show_dots'],
                                'margin' => $SliderData['margin'],
                                'effect' => $SliderData['effect'],
                                'controls' => $SliderData['controls'],
                                'bannerdata' => $this->showdataBlock->getbannerdata($sliderId),
                            ];
                            return $sliderData;
                        } else {
                            throw new NoSuchEntityException(__('Slider is Disable Enable the Slider'));
                        }
                    } else {
                        throw new NoSuchEntityException(__('Slider with this ID does not exist'));
                    }
                } catch (NoSuchEntityException $e) {
                    throw new GraphQlInputException(__($e->getMessage()));
                }
            }
        } else {
            throw new GraphQlNoSuchEntityException(__('Module is Disable.Please Enable the Module'));
        }
    }
}
