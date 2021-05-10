<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Api;

use Dem\HelpDesk\Api\Data\UserInterface;

/**
 * HelpDesk Api Interface - User Management
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
interface UserManagementInterface
{

    /**
     * Validate submitted data
     *
     * @param array $data
     * @return void
     * @throws \Dem\HelpDesk\Exception
     */
    public function validate(array $data);

}
