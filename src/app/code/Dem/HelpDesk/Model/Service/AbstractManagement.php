<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Helper\Data as Helper;
use Magento\Framework\Registry;
use Magento\Framework\Event\Manager;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ObjectManager;


/**
 * HelpDesk Service Model - User Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
abstract class AbstractManagement
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\Event\Manager
     */
    protected $eventManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Dem\HelpDesk\Helper\Data
     */
    protected $helper;

    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = '';

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
     */
    abstract public function validate(array $data);

    /**
     * Get required fields array
     *
     * @return array
     * @since 1.0.0
     */
    abstract public function getRequiredFields();


    /**
     * Get editable fields array
     *
     * @return array
     * @since 1.0.0
     */
    abstract public function getEditableFields();

    /**
     * Get ObjectManager instance
     *
     * @return \Magento\Framework\App\ObjectManager
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * Get Helpdesk helper instance
     *
     * @return \Dem\HelpDesk\Helper\Data
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get Registry instance
     *
     * @return \Magento\Framework\Registry
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getRegistry()
    {
        return $this->coreRegistry;
    }

    /**
     * Get EventManager instance
     *
     * @return \Magento\Framework\Event\Manager
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Get Logger instance
     *
     * @return \Psr\Log\LoggerInterface
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getLogger()
    {
        return $this->logger;
    }

}
