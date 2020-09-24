<?php
declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter\Tests\Mocks;

use PcComponentes\CriteriaArrayAdapter\ArrayCriteriaVisitor;
use Pccomponentes\Criteria\Domain\Criteria\Criteria;

class MemoryArticleRepository
{
    private array $users;

    public function save(Article $user): void
    {
        $this->users[] = $user;
    }

    public function filter(Criteria $criteria): array
    {
        $filter = new ArrayCriteriaVisitor($this->users);

        return $filter($criteria);
    }
}
