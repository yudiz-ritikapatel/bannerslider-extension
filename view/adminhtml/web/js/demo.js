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
require(
    [
        'jquery',
        'mage/translate',
    ],
    function ($) {
        $("#slider_images_delete").css("display", "none");
        $(".delete-image").find('label').css("display", "none");
        $(document).on('click', '.bannerslider-slider-edit input[type="checkbox"]', function() { 
            $('input[type="checkbox"]').not(this).prop('checked', false);
            var check_val = $("input:checkbox:checked").val();
            $('input[name="banners"]').val("");
            $('input[name="banners"]').val(check_val); 
        });

           
    }
);