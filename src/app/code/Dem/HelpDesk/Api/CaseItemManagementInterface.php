<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\CaseItemInterface;

/**
 * HelpDesk Api Interface - CaseItem Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface CaseItemManagementInterface
{
    /**
     * Create a new case
     *
     * @param \Dem\HelpDesk\Api\CaseItemInterface $case
     * @param array $data
     * @return \Dem\HelpDesk\Api\CaseItemManagementInterface
     * @throws \Dem\HelpDesk\Exception
     */
    public function createCase(
        CaseItemInterface $case,
        array $data
    );

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    public function validate(array $data);

    /**
     * Get required fields array
     *
     * @return array
     */
    public function getRequiredFields();
}
