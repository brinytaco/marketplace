<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem as Controller;
use Magento\Framework\Phrase;

/**
 * HelpDesk Controller - Adminhtml Case View
 *
 * Uses layout definition from:
 * view/adminhtml/layout/dem_helpdesk_caseitem_create.xml
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class DepartmentList extends Controller
{
    /**
     * Retrieve department options and return as JSON data
     *
     * @return string
     */
    public function execute()
    {
        $result = $this->getResultJson();
        if (!$this->isAjax()) {
            return $result;
        }

        $websiteId = $this->getParam('website_id');
        $options = [];

        try {

            if (!is_null($websiteId)) {
                $website = $this->getWebsiteById($websiteId);
                $this->getCoreRegistry()->register('current_website', $website);
            }

            $departmentSource = $this->getDepartmentSource();
            $deptOptions = $departmentSource->toOptionArray(false);

            foreach ($deptOptions as $deptOption) {
                // Convert phrase to string as needed
                $label = ($deptOption['label'] instanceof Phrase) ? $deptOption['label']->render() : $deptOption['label'];
                $options[] = ['label' => $label, 'value' => $deptOption['value']];
            }

        } catch (\Exception $exception) {
            $result->setHttpResponseCode(\Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR);
        }

        return $result->setData($options);
    }

    /**
     * Get Website instance
     *
     * @return \Magento\Store\Api\Data\WebsiteInterface
     * @codeCoverageIgnore
     */
    protected function getWebsiteById($websiteId)
    {
        return $this->getHelper()->getWebsite($websiteId);
    }
}
