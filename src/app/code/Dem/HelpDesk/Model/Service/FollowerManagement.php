<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Exception as HelpDeskException;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\CaseItem;


/**
 * HelpDesk Service Model - Follower Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class FollowerManagement extends AbstractManagement
{
    /**
     * Phrase object name
     * @var string
     */
    protected $objectName = 'follower';

    /**
     * Create standard follower
     *
     * @param Follower $follower
     * @param CaseItem $case
     * @param int $userId
     * @return Follower
     * @since 1.0.0
     */
    public function createFollower(
        Follower $follower,
        CaseItem $case,
        $userId
    ) {
        $data = [
            Follower::CASE_ID => $case->getId(),
            Follower::USER_ID => $userId,
        ];
        $this->validate($data);

        return $follower->addData($data);
    }

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws HelpDeskException
     * @since 1.0.0
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
                throw new HelpDeskException(__('The %1 `%2` cannot be empty', $this->objectName, $requiredField));
            }
        }

        // But if a required field is called, it better have a value
        foreach ($data as $field => $value) {
            $isRequired = (in_array($field, $requiredFields));
            if ($isRequired && $value === '') {
                throw new HelpDeskException(__('The %1 `%2` cannot be empty', $this->objectName, $field));
            }
        }
    }

    /**
     * Get required fields array
     *
     * @return array
     * @since 1.0.0
     */
    public function getRequiredFields()
    {
        return [
            Follower::CASE_ID,
            Follower::USER_ID
        ];
    }

    /**
     * Get editable fields array
     *
     * @return array
     * @since 1.0.0
     */
    public function getEditableFields()
    {
        return [];
    }

}
