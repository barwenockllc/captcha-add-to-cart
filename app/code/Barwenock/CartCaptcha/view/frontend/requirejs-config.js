/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package CartCaptcha for Magento 2
 */
var config = {
    config: {
        mixins: {
            'Magento_Catalog/js/catalog-add-to-cart': {
                'Barwenock_CartCaptcha/js/catalog-add-to-cart-mixin': true
            }
        },
    }
};
