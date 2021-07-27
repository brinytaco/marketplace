<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Block\Adminhtml\Department\View;

use Dem\Base\Data\SearchResultsProcessor;
use Dem\HelpDesk\Helper\Data as Helper;
use Dem\HelpDesk\Model\Department;
use Dem\HelpDesk\Model\Follower;
use Dem\HelpDesk\Model\Reply;
use Dem\HelpDesk\Model\ResourceModel\Department as Resource;
use Dem\HelpDesk\Model\Source\Department\Website as WebsiteSource;
use Dem\HelpDesk\Model\User as HelpDeskUser;
use Dem\HelpDesk\Model\UserRepository;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Data\Collection;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\User\Model\User;

/**
 * HelpDesk Block - Adminhtml Department View Tab Info
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Tabs extends Template implements TabInterface
{
    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @var \Dem\HelpDesk\Model\ResourceModel\Department
     */
    protected $departmentResource;

    /**
     * @var Dem\HelpDesk\Model\UserRepository
     */
    protected $userRepository;

    /**
     * @var WebsiteSource
     */
    protected $websiteSource;

    /**
     * @var Collection
     */
    protected $websiteOptions;

    /**
     * @var Helper
     */
    protected $helper;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param Department $departmentResource
     * @param UserRepository $userRepository
     * @param WebsiteSource $websiteSource
     * @param Helper $helper
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Department $departmentResource,
        UserRepository $userRepository,
        WebsiteSource $websiteSource,
        Helper $helper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->departmentResource = $departmentResource;
        $this->userRepository = $userRepository;
        $this->websiteSource = $websiteSource;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    /**
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel()
    {
        return '';
    }

    /**
     * @return \Magento\Framework\Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle()
    {
        return '';
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Tab class getter
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     * @codeCoverageIgnore
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded through Ajax call
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    /**
     * Retrieve registered Department model
     *
     * @return \Dem\HelpDesk\Model\Department
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getDepartment()
    {
        return $this->coreRegistry->registry(Department::CURRENT_KEY);
    }

    /**
     * Get WebsiteSource instance
     *
     * @return WebsiteSource
     * @codeCoverageIgnore
     */
    public function getWebsiteSource()
    {
        return $this->websiteSource;
    }

    /**
     * Get Admin User instance
     *
     * @return \Magento\User\Model\User
     * @codeCoverageIgnore
     */
    public function getAdminUser()
    {
        return $this->helper->getBackendSession()->getUser();
    }

    /**
     * Get created at as formatted string
     *
     * @param Department $object
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getCreatedDate(AbstractModel $object)
    {
        return $this->formatDate(
            $object->getCreatedAt(),
            \IntlDateFormatter::MEDIUM,
            true
        );
    }

    /**
     * Get updated_at as formatted string
     *
     * If null, do not return current date
     *
     * @param Department $object
     * @return string
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function getUpdatedDate(AbstractModel $object)
    {
        if ($object->getUpdatedAt()) {
            return $this->formatDate(
                $object->getUpdatedAt(),
                \IntlDateFormatter::MEDIUM,
                true
            );
        }
        return null;
    }

    /**
     * Get Department manager id
     *
     * @return int
     * @codeCoverageIgnore
     */
    protected function getDepartmentManagerId()
    {
        return $this->getDepartment()->getDepartmentManager()->getId();
    }

    /**
     * Get HelpDesk User by id
     *
     * @param int $userId
     * @return HelpDeskUser
     * @codeCoverageIgnore
     */
    protected function getHelpDeskUserById($userId)
    {
        return $this->userRepository->getByField($userId, HelpDeskUser::ADMIN_ID);
    }
}
