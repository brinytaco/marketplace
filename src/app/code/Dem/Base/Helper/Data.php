<?php
declare(strict_types=1);

namespace Dem\Base\Helper;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\Helper\Context;

/**
 * Base Adminhtml (Backend) Controller Abstract
 *
 * Dem Admin controllers should extend this one
 *
 * =============================================================================
 *
 * @package    Dem\Base
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 * @codeCoverageIgnore
 *
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @param Context $context
     * @param TimezoneInterface $localeDate
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        TimezoneInterface $localeDate
    ) {
        $this->localeDate = $localeDate;
        parent::__construct($context);
    }

    /**
     * Retrieve formatting date
     *
     * @param null|string|\DateTimeInterface $date
     * @param int $format
     * @param bool $showTime
     * @param null|string $timezone
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function formatDate(
        $date = null,
        $format = \IntlDateFormatter::SHORT,
        $showTime = false,
        $timezone = null
    ) {
        $date = $date instanceof \DateTimeInterface ? $date : new \DateTime($date ?? 'now');
        return $this->localeDate->formatDateTime(
            $date,
            $format,
            $showTime ? $format : \IntlDateFormatter::NONE,
            null,
            $timezone
        );
    }
}
