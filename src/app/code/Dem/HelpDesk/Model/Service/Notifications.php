<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model\Service;

use Dem\HelpDesk\Api\Data\CaseItemInterface;

/**
 * HelpDesk Api Interface - Notifications
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Notifications implements \Dem\HelpDesk\Api\NotificationInterface
{

    /**
     * Send new case notifications
     *
     * Send notifications (emails) to:
     *   - creator
     *   - case manager
     *   - followers (dept followers initially)
     *
     * @param CaseItemInterface $case
     * @return $this
     * @throws HelpDeskException
     * @since 1.0.0
     */
    public function sendNewCaseNotifications(CaseItemInterface $case)
    {
        /** @todo: Actually do something here */
        return $this;
    }

}
