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
namespace Bss\AutoCancelOrder\Cron;

class CancelOrder
{
    /**
     * @var \Bss\AutoCancelOrder\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Bss\AutoCancelOrder\Helper\CancelOrderImplementation
     */
    protected $cancelHelper;

    /**
     * Initialize dependencies.
     *
     * @param \Bss\AutoCancelOrder\Helper\Data $dataHelper
     * @param \Bss\AutoCancelOrder\Helper\CancelOrderImplementation $cancelHelper
     */
    public function __construct(
        \Bss\AutoCancelOrder\Helper\Data $dataHelper,
        \Bss\AutoCancelOrder\Helper\CancelOrderImplementation $cancelHelper
    ) {
        $this->dataHelper = $dataHelper;
        $this->cancelHelper = $cancelHelper;
    }

    /**
     * Execute auto cancel order in cronjob
     *
     * @return void
     */
    public function execute()
    {
        if ($this->dataHelper->isEnabled()) {
            $cancelDate = $this->dataHelper->getCancelDate();
            $cancelStatuses = explode(",", $this->dataHelper->getActiveOrderStatus());
            $cancelPaymentMethods = $this->dataHelper->getActivePaymentMethod();

            $this->cancelHelper->processCancel($cancelStatuses, $cancelDate, $cancelPaymentMethods);
        }
    }
}
