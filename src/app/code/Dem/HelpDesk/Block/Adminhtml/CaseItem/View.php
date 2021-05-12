<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem;

/**
 * HelpDesk Block - Adminhtml CaseItem View
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
     * Unset the header text
     *
     * @var string
     */
    protected $_headerText = '';

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
        $this->_controller = 'adminhtml_case';
        $this->_mode = 'view';

        parent::_construct();

        $this->setId('helpdesk_case_view');
    }

    /**
     * Prepare URL rewrite editing layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBackButton();
        return parent::_prepareLayout();
    }

    /**
     * @return void
     */
    protected function _addBackButton()
    {
        $this->addButton(
            'back',
            [
                'label' => __('Back'),
                'onclick' => 'setLocation(\'' . $this->getBackUrl() . '\')',
                'class' => 'back'
            ],
            $level = 0,
            $sortOrder = 0,
            $region = 'toolbar'
        );
    }

    /**
     * Retrieve registered Case model
     *
     * @return \Dem\HelpDesk\Model\CaseItem
     * @since 1.0.0
     */
    public function getCase()
    {
        return $this->_coreRegistry->registry(\Dem\HelpDesk\Model\CaseItem::CURRENT_KEY);
    }

    /**
     * Return back url for view grid
     *
     * @return string
     * @since 1.0.0
     */
    public function getBackUrl()
    {
        return $this->getUrl('helpdesk/caseitem/');
    }

    public function getCaseNumber()
    {
        return $this->getCase()->getCaseNumber();
    }

    public function getCreatedDate()
    {
        return $this->formatDate(
            $this->getCase()->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    public function isCustomerCreator()
    {
        return (!empty($this->getCase()->getCreatorCustomerId()));
    }

    public function getCreatorName()
    {
        return $this->getCase()->getCreatorName();
    }

    public function getCustomerCreatorUrl()
    {
        return $this->getUrl('customer/index/edit', ['id' => $this->getCase()->getCreatorCustomerId()]);
    }

    public function getWebsiteName()
    {
        return $this->getCase()->getWebsiteName();
    }

    public function getDepartmentName()
    {
        return $this->getCase()->getDepartmentName();
    }

    public function getPriority()
    {
        return $this->getCase()->getPriority();
    }

    public function getRemoteIp()
    {
        return $this->getCase()->getRemoteIp();
    }

    public function getHttpUserAgent()
    {
        return $this->getCase()->getHttpUserAgent();
    }

    public function getSubject()
    {
        return $this->getCase()->getSubject();
    }

    public function getUpdatedByName()
    {
        return $this->getCase()->getUpdatedBy();
    }

    public function getUpdatedDate()
    {
        return $this->formatDate(
            $this->getCase()->getUpdatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    public function getCaseManagerName()
    {
        return $this->getCase()->getCaseManagerName();
    }

    public function getStatus()
    {
        return $this->getCase()->getStatus();
    }

    public function getTotalReplies()
    {
        return $this->getCase()->getTotalReplies();
    }
}
