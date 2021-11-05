<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use NewsModule\News\Api\Data\TestInterfaceFactory;
use NewsModule\News\Api\Data\TestSearchResultsInterfaceFactory;
use NewsModule\News\Api\TestRepositoryInterface;
use NewsModule\News\Model\ResourceModel\Test as ResourceTest;
use NewsModule\News\Model\ResourceModel\Test\CollectionFactory as TestCollectionFactory;

class TestRepository implements TestRepositoryInterface
{

    protected $resource;

    protected $testFactory;

    protected $testCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataTestFactory;

    protected $extensionAttributesJoinProcessor;

    private $storeManager;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceTest $resource
     * @param TestFactory $testFactory
     * @param TestInterfaceFactory $dataTestFactory
     * @param TestCollectionFactory $testCollectionFactory
     * @param TestSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTest $resource,
        TestFactory $testFactory,
        TestInterfaceFactory $dataTestFactory,
        TestCollectionFactory $testCollectionFactory,
        TestSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->testFactory = $testFactory;
        $this->testCollectionFactory = $testCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTestFactory = $dataTestFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \NewsModule\News\Api\Data\TestInterface $test
    ) {
        /* if (empty($test->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $test->setStoreId($storeId);
        } */
        
        $testData = $this->extensibleDataObjectConverter->toNestedArray(
            $test,
            [],
            \NewsModule\News\Api\Data\TestInterface::class
        );
        
        $testModel = $this->testFactory->create()->setData($testData);
        
        try {
            $this->resource->save($testModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the test: %1',
                $exception->getMessage()
            ));
        }
        return $testModel;
    }

    /**
     * {@inheritdoc}
     */
    public function get($testId)
    {
        $test = $this->testFactory->create();
        $this->resource->load($test, $testId);
        if (!$test->getId()) {
            throw new NoSuchEntityException(__('test with id "%1" does not exist.', $testId));
        }
        return $test;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->testCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \NewsModule\News\Api\Data\TestInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \NewsModule\News\Api\Data\TestInterface $test
    ) {
        try {
            $testModel = $this->testFactory->create();
            $this->resource->load($testModel, $test->getTestId());
            $this->resource->delete($testModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the test: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($testId)
    {
        return $this->delete($this->get($testId));
    }
}

