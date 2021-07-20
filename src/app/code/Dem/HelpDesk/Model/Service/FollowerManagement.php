<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Api\FollowerManagementInterface;
use Dem\HelpDesk\Api\Data\CaseItemInterface;
use Dem\HelpDesk\Api\Data\FollowerInterface;
use Dem\HelpDesk\Exception as HelpDeskException;


/**
 * HelpDesk Model - Follower Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class FollowerManagement implements FollowerManagementInterface
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
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
     * Data constructor.
     *
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Dem\HelpDesk\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Psr\Log\LoggerInterface $logger,
        \Dem\HelpDesk\Helper\Data $helper
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
        $this->helper = $helper;
    }


    /**
     * Create standard follower
     *
     * @param FollowerInterface $follower
     * @param CaseItemInterface $case
     * @param int $authorId
     * @param string $authorType
     * @param string $followerText
     * @param int|null $statusId
     * @param bool|null $isInitial
     * @return FollowerInterface
     */
    public function createFollower(
        FollowerInterface $follower,
        CaseItemInterface $case,
        $userId
    ) {
        $data = [
            'case_id'     => $case->getId(),
            'user_id'     => $userId,
        ];
        $this->validate($data);

        return $follower->addData($data);
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    public function validate(array $data)
    {
        $requiredFields = $this->getRequiredFields();

        if (!count($requiredFields)) {
            return;
        }

        // Required fields not submitted?
        foreach ($requiredFields as $requiredField) {
            if (!array_key_exists($requiredField, $data)) {
                throw new HelpDeskException(__('The follower `%1` cannot be empty', $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The follower `%1` cannot be empty', $field));
            }
        }

    }

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return [];
    }

}
