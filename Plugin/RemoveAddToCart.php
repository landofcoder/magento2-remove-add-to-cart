<?php

namespace Lof\RemoveAddToCart\Plugin;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Http\Context;
use Magento\Store\Model\ScopeInterface;

class RemoveAddToCart
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

    /**
     * @return bool
     */
    public function afterIsSalable()
    {
        if ($this->isDisableAddToCart()) {
            return false;
        }
        return true;
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
