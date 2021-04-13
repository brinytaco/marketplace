<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Caseitem;

/**
 * HelpDesk Block - Adminhtml Caseitem View
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class View extends \Magento\Backend\Block\Widget\Container
{
    /**
     * Block group
     *
     * @var string
     */
    protected $_blockGroup = 'Dem_HelpDesk';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     * @return void
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'case_id';
        $this->_controller = 'adminhtml_caseitem';
        $this->_mode = 'view';

        parent::_construct();

        $this->removeButton('delete');
        $this->removeButton('reset');
        $this->removeButton('save');
        $this->setId('helpdesk_case_view');
    }

    /**
     * Retrieve registered Case model
     *
     * @return \Dem\HelpDesk\Model\Caseitem
     */
    public function getCase()
    {
        return $this->_coreRegistry->registry(\Dem\HelpDesk\Model\Caseitem::CURRENT_KEY);
    }

    /**
     * Retrieve Case Identifier
     *
     * @return int
     */
    public function getCaseId()
    {
        return $this->getCase() ? $this->getCase()->getId() : null;
    }

    /**
     * Get header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __(
            'Case # %s',
            $this->getCase()->getCaseNumber(),
        );
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Return back url for view grid
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('helpdesk/case/');
    }
}
