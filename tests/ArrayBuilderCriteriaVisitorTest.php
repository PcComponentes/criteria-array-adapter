<?php
declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter\Tests;

use Pccomponentes\Criteria\Domain\Criteria\FilterArrayValue;
use PcComponentes\CriteriaArrayAdapter\Tests\Mocks\MemoryArticleRepository;
use PcComponentes\CriteriaArrayAdapter\Tests\Mocks\ArticleObjectMother;
use Pccomponentes\Criteria\Domain\Criteria\AndFilter;
use Pccomponentes\Criteria\Domain\Criteria\Criteria;
use Pccomponentes\Criteria\Domain\Criteria\Filter;
use Pccomponentes\Criteria\Domain\Criteria\FilterField;
use Pccomponentes\Criteria\Domain\Criteria\FilterOperator;
use Pccomponentes\Criteria\Domain\Criteria\Filters;
use Pccomponentes\Criteria\Domain\Criteria\FilterValue;
use Pccomponentes\Criteria\Domain\Criteria\OrFilter;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class ArrayBuilderCriteriaVisitorTest extends TestCase
{
    private MemoryArticleRepository $repository;

    public function setUp(): void
    {
        $this->repository = new MemoryArticleRepository();

        parent::setUp();
    }

    public function test_unique_filter()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('name'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from($article->name()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_multiple_filters()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('name'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from($article->name()),
                ),
                new Filter(
                    FilterField::from('stock'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from((string) $article->stock()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_or_filter()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new OrFilter(
                    new Filter(
                        FilterField::from('name'),
                        FilterOperator::from(FilterOperator::EQUAL),
                        FilterValue::from($article->name()),
                    ),
                    new Filter(
                        FilterField::from('stock'),
                        FilterOperator::from(FilterOperator::EQUAL),
                        FilterValue::from((string) $article->stock()),
                    ),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_and_filter()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new AndFilter(
                    new Filter(
                        FilterField::from('name'),
                        FilterOperator::from(FilterOperator::EQUAL),
                        FilterValue::from($article->name()),
                    ),
                    new Filter(
                        FilterField::from('stock'),
                        FilterOperator::from(FilterOperator::EQUAL),
                        FilterValue::from((string) $article->stock()),
                    ),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_datetime_filter()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('date'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from($article->date()->format('Y-m-d H:i:s')),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_greater_than_operator()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('stock'),
                    FilterOperator::from(FilterOperator::GT),
                    FilterValue::from((string) ($article->stock() - 1)),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_less_than_operator()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('stock'),
                    FilterOperator::from(FilterOperator::LT),
                    FilterValue::from((string) ($article->stock() + 1)),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_contains_operator()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('name'),
                    FilterOperator::from(FilterOperator::CONTAINS),
                    FilterValue::from($article->name()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_filter_with_value_object()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('id'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from($article->id()->value()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }

    public function test_filter_with_no_result()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('id'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from(Uuid::v4()->value()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEmpty($result);
    }

    public function test_non_existing_filter_should_return_no_result()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('non_existing_filter'),
                    FilterOperator::from(FilterOperator::EQUAL),
                    FilterValue::from(Uuid::v4()->value()),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEmpty($result);
    }

    public function test_not_equal_operator()
    {
        $article = ArticleObjectMother::random();
        $this->repository->save($article);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('stock'),
                    FilterOperator::from(FilterOperator::NOT_EQUAL),
                    FilterValue::from((string) ($article->stock() - 1)),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);
    }


    public function test_in_operator()
    {
        $article = ArticleObjectMother::random();
        $withNamePhone = ArticleObjectMother::withNamePhone();
        $this->repository->save($article);
        $this->repository->save($withNamePhone);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('name'),
                    FilterOperator::from(FilterOperator::IN),
                    FilterArrayValue::from(['benito', 'Phone']),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($withNamePhone, $result[1]);

    }

    public function test_not_in_operator()
    {
        $article = ArticleObjectMother::random();
        $withNamePhone = ArticleObjectMother::withNamePhone();
        $this->repository->save($article);
        $this->repository->save($withNamePhone);

        $criteria = new Criteria(
            new Filters(
                new Filter(
                    FilterField::from('name'),
                    FilterOperator::from(FilterOperator::NOT_IN),
                    FilterArrayValue::from(['Phone']),
                ),
            ),
            null,
            null,
            null,
        );

        $result = $this->repository->filter($criteria);
        $this->assertEquals($article, $result[0]);

    }
}
