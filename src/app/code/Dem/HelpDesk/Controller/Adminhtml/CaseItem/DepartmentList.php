<?php

declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml\CaseItem;

use Dem\HelpDesk\Controller\Adminhtml\CaseItem;
use Dem\HelpDesk\Model\Source\CaseItem\Department;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Phrase;
use Magento\Framework\Controller\Result\Json;

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
        /** @var Json $result */
        $result = $this->resultJsonFactory->create();

        $websiteId = $this->getRequest()->getParam('website_id');
        if (!is_null($websiteId)) {
            $website = $this->helper->getWebsite($websiteId);
            $this->coreRegistry->register('current_website', $website);
        }

        /** @var Department $departmentSource */
        $objectManager = ObjectManager::getInstance();
        $departmentSource = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Department');

        $deptOptions = $departmentSource->toOptionArray(false);

        $options = [];
        foreach ($deptOptions as $deptOption) {
            // Convert phrase to string as needed
            $label = ($deptOption['label'] instanceof Phrase) ? $deptOption['label']->render() : $deptOption['label'];
            $options[] = ['label' => $label, 'value' => $deptOption['value']];
        }

        if ($this->getRequest()->isAjax()) {
            return $result->setData($options);
        }
    }
}
