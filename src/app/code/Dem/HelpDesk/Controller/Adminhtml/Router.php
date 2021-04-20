<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Controller\Adminhtml;

use Magento\Framework\App\RouterInterface;

/**
 * HelpDesk Custom Router
 *
 * Converts "case" controller request to the proper "caseItem" controller,
 * since "case" cannot be an actual class/file name.
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 */
class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     * @return void
     */
    public function __construct(
            \Magento\Framework\App\ActionFactory $actionFactory,
            \Magento\Framework\App\ResponseInterface $response)
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }

    /**
     * Match request and rewrite as needed
     *
     * If "helpdesk" module request and "case" controller set,
     * reset controller to "caseItem" for proper routing.
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|void
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $pathInfo = $request->getPathInfo();
        if (preg_match('/helpdesk\/case/', $pathInfo) && !preg_match('/helpdesk\/caseitem/i', $pathInfo)) {
            $request->setControllerName('caseItem');
        }

        if ($request->getControllerName() == '') {
            $request->setModuleName('admin');
            $request->setControllerName('index');
        }

        return $this
            ->actionFactory
            ->create('Magento\Framework\App\Action\Forward', ['request' => $request]);
    }

}
