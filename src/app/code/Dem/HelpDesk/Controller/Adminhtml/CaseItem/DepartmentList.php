<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;

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
class DepartmentList extends CaseItem
{
    /**
     * Retrieve department options and return as JSON data
     *
     * @return string
     */
    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $websiteId = $this->getRequest()->getParam('website_id');

        /* @var $this->helper \Dem\HelpDesk\Helper\Data */
        if (!is_null($websiteId)) {
            $website = $this->helper->getWebsite($websiteId);
            $this->coreRegistry->register('current_website', $website);
        }

        /* @var $departmentSource \Dem\HelpDesk\Model\Source\CaseItem\Department */
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $departmentSource = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Department');

        $deptOptions = $departmentSource->toOptionArray(false);

        $options = [];
        foreach ($deptOptions as $deptOption) {
            // Convert phrase to string as needed
            $label = ($deptOption['label'] instanceof \Magento\Framework\Phrase) ? $deptOption['label']->render() : $deptOption['label'];
            $options[] = ['label' => $label, 'value' => $deptOption['value']];
        }

        if ($this->getRequest()->isAjax()) {
            return $result->setData($options);
        }
    }
}
