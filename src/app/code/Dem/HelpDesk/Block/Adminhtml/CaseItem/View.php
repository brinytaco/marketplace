<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\CaseItem;

use \Magento\Framework\App\ObjectManager;

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

    /**
     * Get case number
     *
     * @return string
     * @since 1.0.0
     */
    public function getCaseNumber()
    {
        return $this->getCase()->getCaseNumber();
    }

    /**
     * Get case website name
     *
     * @return string
     * @since 1.0.0
     */
    public function getWebsiteName()
    {
        return $this->getCase()->getWebsiteName();
    }

    /**
     * Get case department name
     *
     * @return string
     * @since 1.0.0
     */
    public function getDepartmentName()
    {
        return $this->getCase()->getDepartmentName();
    }

    /**
     * Get case subject
     *
     * @return string
     * @since 1.0.0
     */
    public function getSubject()
    {
        return $this->getCase()->getSubject();
    }

    /**
     * Get created at as formatted string
     *
     * @return string
     * @since 1.0.0
     */
    public function getCreatedDate()
    {
        return $this->formatDate(
            $this->getCase()->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    /**
     * Get if creator is customer entity
     *
     * @return bool
     * @since 1.0.0
     */
    public function isCustomerCreator()
    {
        return (!empty($this->getCase()->getCreatorCustomerId()));
    }

    /**
     * Get case creator name
     *
     * @return string
     * @since 1.0.0
     */
    public function getCreatorName()
    {
        return $this->getCase()->getCreatorName();
    }

    /**
     * Get case creator customer edit url
     *
     * @return string
     * @since 1.0.0
     */
    public function getCustomerCreatorUrl()
    {
        return $this->getUrl('customer/index/edit', ['id' => $this->getCase()->getCreatorCustomerId()]);
    }

    /**
     * Get updated by name
     *
     * @return string
     * @since 1.0.0
     */
    public function getUpdaterName()
    {
        return $this->getCase()->getUpdaterName();
    }

    /**
     * Get case updated_at as formatted string
     *
     * @return string
     * @since 1.0.0
     */
    public function getUpdatedDate()
    {
        if ($this->getCase()->getUpdatedAt()) {
            return $this->formatDate(
                $this->getCase()->getUpdatedAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }
        return null;
    }

    /**
     * Get case manager name
     *
     * @return string
     * @since 1.0.0
     */
    public function getCaseManagerName()
    {
        return $this->getCase()->getCaseManagerName();
    }

    public function getStatusItem()
    {
        $objectManager = ObjectManager::getInstance();
        $source = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Status');

        /* @var $statusOptions \Magento\Framework\Data\Collection */
        $statusOptions = $source->getOptions();

        return $statusOptions
            ->getItemByColumnValue('id', $this->getCase()->getStatusId());
    }

    /**
     * Get case priority option label
     *
     * @return string
     * @since 1.0.0
     */
    public function getPriorityItem()
    {
        $objectManager = ObjectManager::getInstance();
        $source = $objectManager->get('Dem\HelpDesk\Model\Source\CaseItem\Priority');

        /* @var $statusOptions \Magento\Framework\Data\Collection */
        $statusOptions = $source->getOptions();

        return $statusOptions
            ->getItemByColumnValue('id', $this->getCase()->getPriority());
    }

    /**
     * Get remote ip of creator
     *
     * @return string
     * @since 1.0.0
     */
    public function getRemoteIp()
    {
        return $this->getCase()->getRemoteIp();
    }

    /**
     * Get http user agent of creator
     *
     * @return string
     * @since 1.0.0
     */
    public function getHttpUserAgent()
    {
        return $this->getCase()->getHttpUserAgent();
    }

    /**
     * Get total user replies count (excluding initial and system)
     *
     * @return int
     * @since 1.0.0
     */
    public function getTotalReplies()
    {
        return (int) $this->getCase()->getTotalReplies();
    }
}
