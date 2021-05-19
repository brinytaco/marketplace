<?php
declare(strict_types=1);

namespace Dem\Base\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;

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
abstract class AbstractAction extends Action
{

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $requestHttp;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $redirectFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $pageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\Controller\Result\Page
     */
    protected $resultPage;

    /**
     * @var \Magento\Framework\Controller\Result\Json
     */
    protected $resultJson;

    /**
     * @var \Magento\Backend\Model\View\Result\Redirect
     */
    protected $resultRedirect;

    /**
     * Data constructor.
     *
     * @param Action\Context $context
     * @param Registry $coreRegistry
     * @param LoggerInterface $logger
     * @param Http $requestHttp
     * @param PageFactory $pageFactory
     * @param JsonFactory $jsonFactory
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Action\Context $context,
        Registry $coreRegistry,
        LoggerInterface $logger,
        Http $requestHttp,
        PageFactory $pageFactory,
        JsonFactory $jsonFactory,
        LayoutFactory $layoutFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->logger = $logger;
        $this->requestHttp = $requestHttp;
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->redirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    /**
     * Get \Magento\Framework\App\Request\Http which has standard methods
     *
     * @return \Magento\Framework\App\Request\Http
     * @since 1.0.0
     */
    public function getRequestHttp()
    {
        return $this->requestHttp;
    }

    /**
     * Get an action parameter
     *
     * @param string $key
     * @param mixed $default Default value to use if key not found
     * @return string
     * @since 1.0.0
     */
    public function getParam($name, $default = null)
    {
        return $this->getRequestHttp()->getParam($name);
    }

    /**
     * Get all action parameters
     *
     * @return string[]
     * @since 1.0.0
     */
    public function getParams()
    {
        return $this->getRequestHttp()->getParams();
    }

    /**
     * Check is Request from AJAX
     *
     * @return boolean
     * @since 1.0.0
     */
    public function isAjax()
    {
        return $this->getRequestHttp()->isAjax();
    }

    /**
     * Is this a POST method request?
     *
     * @return bool
     * @since 1.0.0
     */
    public function isPost()
    {
        return $this->getRequestHttp()->isPost();
    }

    /**
     * Check is valid post request
     *
     * @return bool
     * @since 1.0.0
     */
    public function isValidPostRequest()
    {
        return ($this->isPost() && $this->validateFormKey());
    }

    /**
     * Check is valid form key
     *
     * @return bool
     * @since 1.0.0
     */
    public function validateFormKey()
    {
        return ($this->_formKeyValidator->validate($this->getRequest()));
    }

    /**
     * Get Page Title Object
     *
     * @return \Magento\Framework\View\Page\Title
     * @since 1.0.0
     */
    protected function getPageTitle()
    {
        return $this->getResultPage()->getConfig()->getTitle();
    }

    /**
     * Get Page instance
     *
     * @return \Magento\Framework\View\Result\Page
     * @since 1.0.0
     */
    protected function getResultPage()
    {
        if (!isset($this->resultPage)) {
            $this->resultPage = $this->pageFactory->create();
        }
        return $this->resultPage;
    }

    /**
     * Get Json instance
     *
     * @return \Magento\Framework\Controller\Result\Json
     * @since 1.0.0
     */
    protected function getResultJson()
    {
        if (!isset($this->resultJson)) {
            $this->resultJson = $this->jsonFactory->create();
        }
        return $this->resultJson;
    }

    /**
     * Get Layout instance
     *
     * @return \Magento\Framework\View\LayoutInterface
     * @since 1.0.0
     */
    protected function getLayout()
    {
        return $this->getResultPage()->getLayout();
    }

    /**
     * Get Registry instance
     *
     * @return \Magento\Framework\Registry
     * @since 1.0.0
     */
    protected function getCoreRegistry()
    {
        return $this->coreRegistry;
    }

    /**
     * Get Helper instance
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @since 1.0.0
     */
    protected function getRedirect()
    {
        if (!isset($this->resultRedirect)) {
            $this->resultRedirect = $this->redirectFactory->create();
        }
        return $this->resultRedirect;
    }

    /**
     * Get MessageManagerInterface instance
     *
     * @return \Magento\Framework\Message\ManagerInterface
     * @since 1.0.0
     */
    protected function getMessageManager()
    {
        return $this->messageManager;
    }
}
