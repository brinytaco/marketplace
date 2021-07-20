<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

/**
 * HelpDesk Api Interface - Follower Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface FollowerManagementInterface
{
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
