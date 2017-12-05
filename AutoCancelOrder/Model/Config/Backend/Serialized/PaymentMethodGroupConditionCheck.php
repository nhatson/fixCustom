<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * BSS Commerce does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BSS Commerce does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   BSS
 * @package    Bss_AutoCancelOrder
 * @author     Extension Team
 * @copyright  Copyright (c) 2016-2017 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AutoCancelOrder\Model\Config\Backend\Serialized;
 
class PaymentMethodGroupConditionCheck extends \Magento\Config\Model\Config\Backend\Serialized\ArraySerialized
{
    /**
     * Validate group before save
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save()
    {
        $methods = [];
        $options = $this->getValue();
        if (!empty($options)) {
            foreach ($options as $option) {
                if (is_array($option)) {
                    if (isset($option['payment_method_group_id'])) {
                        $methods[]= $option['payment_method_group_id'];
                    }
                }
            }
        }

        foreach (array_count_values($methods) as $count) {
            if ($count > 1) {
                throw new \Magento\Framework\Exception\LocalizedException(__("Make sure that each payment method is set with only 1 duration for cancellation!"));
            }
        }

        return parent::save();
    }
}
