<?php
declare(strict_types=1);

namespace Dem\HelpDesk\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Dem\HelpDesk\Api\Data\ReplyInterface;
use Dem\HelpDesk\Api\Data\ReplySearchResultInterfaceFactory;
use Dem\HelpDesk\Api\ReplyRepositoryInterface;
use Dem\HelpDesk\Model\ResourceModel\Reply as Resource;
use Dem\HelpDesk\Model\ResourceModel\Reply\CollectionFactory;

/**
 * HelpDesk Model Repository - Reply
 *
 * =============================================================================
 *
 * @package    Dem\HelpDesk
 * @copyright  Copyright (c) 2021 Direct Edge Media (http://directedgemedia.com)
 * @author     Toby Crain
 * @since      1.0.0
 *
 */
class ReplyRepository implements ReplyRepositoryInterface
{
    /**
     * @var ReplyFactory
     */
    private $replyFactory;

    /**
     * @var Resource
     */
    private $replyResource;

    /**
     * @var ReplyCollectionFactory
     */
    private $replyCollectionFactory;

    /**
     * @var ReplySearchResultInterfaceFactory
     */
    private $searchResultFactory;
    
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    public function __construct(
        ReplyFactory $replyFactory,
        Resource $replyResource,
        CollectionFactory $replyCollectionFactory,
        ReplySearchResultInterfaceFactory $replySearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->replyFactory = $replyFactory;
        $this->replyResource = $replyResource;
        $this->replyCollectionFactory = $replyCollectionFactory;
        $this->searchResultFactory = $replySearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id)
    {
        $reply = $this->replyFactory->create();
        $this->replyResource->load($reply, $id);
        if (!$reply->getId()) {
            throw new NoSuchEntityException(__('Unable to find reply with ID "%1"', $id));
        }
        return $reply;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dem\HelpDesk\Api\Data\ReplySearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->replyCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(ReplyInterface $reply)
    {
        $this->replyResource->save($reply);
        return $reply;
    }

    /**
     * @param \Dem\HelpDesk\Api\Data\ReplyInterface $reply
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(ReplyInterface $reply)
    {
        try {
            $this->replyResource->delete($reply);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the reply: %1', $exception->getMessage())
            );
        }

        return true;

    }

    /**
     * @param int $id
     * @return \Dem\HelpDesk\Api\Data\ReplyInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($id)
    {
        $reply = $this->replyFactory->create();
        $this->replyResource->load($reply, $id);
        if (!$reply->getId()) {
            throw new NoSuchEntityException(__('Unable to find reply with ID "%1"', $id));
        }
        return $this->delete($reply);
    }
}
