<?php
namespace Kennziffer\KeQuestionnaire\Service;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageService
{
    /**
     * Get a list of PIDs starting from a given page and limited by a depth.
     *
     * @param int $startPage The starting page ID.
     * @param int $limit The depth limit for fetching subpages.
     * @return array The list of page IDs.
     */
    public function getPidList(int $startPage, int $limit): array
    {
        $pids = [$startPage];
        $this->fetchSubPages($startPage, $limit, $pids);
        return $pids;
    }

    /**
     * Recursively fetch subpages up to the given depth.
     *
     * @param int $parentPage The parent page ID.
     * @param int $depth The remaining depth to fetch.
     * @param array $pids The list of collected page IDs.
     */
    protected function fetchSubPages(int $parentPage, int $depth, array &$pids): void
    {
        if ($depth <= 0) {
            return;
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('pages');
        $rows = $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($parentPage, \PDO::PARAM_INT)))
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $pids[] = (int)$row['uid'];
            $this->fetchSubPages((int)$row['uid'], $depth - 1, $pids);
        }
    }
}