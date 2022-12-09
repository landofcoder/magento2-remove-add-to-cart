<?php
namespace Lof\RemoveAddToCart\Plugin\Model\Quote;

use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Context as CustomerContext;

class BeforeAddProduct
{
    protected $scopeConfig;
    protected $context;
    const CONFIG = 'catalog/frontend/remove_add_to_cart_for_guest';
    const CONFIG_ALL = 'catalog/frontend/remove_add_to_cart_for_all';

    /**
     * RemoveAddToCart constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Context $context
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Context $context)
    {
        $this->scopeConfig = $scopeConfig;
        $this->context = $context;
    }

    public function beforeAddProduct(Cart $subject, $productInfo, $requestInfo = null)
    {
        // TODO: Add the logic you want to implement to prevent some products to be added to the cart
        if ($this->isDisableAddToCart()) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('You are not allowed to add this product to your cart.')
            );
        }
    }

    /**
     * @return bool
     */
    protected function isDisableAddToCart()
    {
        if ($this->scopeConfig->getValue(self::CONFIG_ALL, ScopeInterface::SCOPE_STORE)) {
            return true;
        } else {
            if ($this->scopeConfig->getValue(self::CONFIG, ScopeInterface::SCOPE_STORE)) {
                if ($this->context->getValue(CustomerContext::CONTEXT_AUTH)) {
                    return false;
                }
                return true;
            }
        }
        return false;
    }
}
