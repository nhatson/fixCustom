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
namespace Bss\AutoCancelOrder\Controller\Adminhtml\CancelLog;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Bss\AutoCancelOrder\Model\ResourceModel\CancelLog\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ResponseInterface;
use Bss\AutoCancelOrder\Model\CancelLogRepository as LogRepository;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $logCollectionFactory;

    /**
     * @var LogRepository
     */
    protected $logRepository;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $logCollectionFactory
     * @param LogRepository $logRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $logCollectionFactory,
        LogRepository $logRepository
    ) {
        $this->filter = $filter;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->logRepository = $logRepository;
        parent::__construct($context);
    }

    /**
     * Delete logs
     *
     * @return \Magento\Framework\Controller\ResultFactory
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->logCollectionFactory->create());

        $recordDeleted = 0;
        $errors = [];
        foreach ($collection->getAllIds() as $logId) {
            try {
                $this->logRepository->deleteById($logId);
                $recordDeleted++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $recordDeleted)
        );

        foreach ($errors as $error) {
            $this->messageManager->addSuccess(
                __($error)
            );
        }
 
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    /**
     * Check right for controller
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Bss_AutoCancelOrder::cancel_log_delete');
    }
}
