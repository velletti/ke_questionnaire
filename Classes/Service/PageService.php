<?php
namespace Kennziffer\KeQuestionnaire\Service;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class PageService
{
    public function __construct(private readonly ConnectionPool $connectionPool)
    {
    }
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

        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('pages');
        $rows = $queryBuilder
            ->select('uid')
            ->from('pages')
            ->where($queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($parentPage, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $pids[] = (int)$row['uid'];
            $this->fetchSubPages((int)$row['uid'], $depth - 1, $pids);
        }
    }
}