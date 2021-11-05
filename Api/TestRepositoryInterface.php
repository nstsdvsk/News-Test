<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace NewsModule\News\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface TestRepositoryInterface
{

    /**
     * Save test
     * @param \NewsModule\News\Api\Data\TestInterface $test
     * @return \NewsModule\News\Api\Data\TestInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \NewsModule\News\Api\Data\TestInterface $test
    );

    /**
     * Retrieve test
     * @param string $testId
     * @return \NewsModule\News\Api\Data\TestInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($testId);

    /**
     * Retrieve test matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \NewsModule\News\Api\Data\TestSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete test
     * @param \NewsModule\News\Api\Data\TestInterface $test
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \NewsModule\News\Api\Data\TestInterface $test
    );

    /**
     * Delete test by ID
     * @param string $testId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($testId);
}

