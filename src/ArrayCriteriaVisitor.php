<?php
declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter;

use Pccomponentes\Criteria\Domain\Criteria\AndFilter;
use Pccomponentes\Criteria\Domain\Criteria\Criteria;
use Pccomponentes\Criteria\Domain\Criteria\Filter;
use Pccomponentes\Criteria\Domain\Criteria\FilterOperator;
use Pccomponentes\Criteria\Domain\Criteria\FilterVisitorInterface;
use Pccomponentes\Criteria\Domain\Criteria\OrFilter;

class ArrayCriteriaVisitor implements FilterVisitorInterface
{
    private array $dataSet;

    public function __construct(array $dataSet)
    {
        $this->dataSet = $dataSet;
    }

    public function __invoke(Criteria $criteria): array
    {
        $result = $this->dataSet;

        foreach ($criteria->filters() as $filter) {
            $result = $this->intersect($result, $filter->accept($this));
        }

        return $result;
    }

    public function visitAnd(AndFilter $filter)
    {
        return $this->intersect($filter->left()->accept($this), $filter->right()->accept($this));
    }

    public function visitOr(OrFilter $filter)
    {
        return $filter->left()->accept($this) + $filter->right()->accept($this);
    }

    public function visitFilter(Filter $filter)
    {
        return \array_filter(
            $this->dataSet,
            static function ($item) use ($filter) {
                if (\method_exists($item, $filter->field()->value())) {
                    $field = $filter->field()->value();
                    $itemValue = true === \is_object($item->$field()) && \method_exists($item->$field(), 'value')
                        ? $item->$field()->value()
                        : $item->$field();

                    if (FilterOperator::EQUAL === $filter->operator()->value()) {
                        return $itemValue == self::cast($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::NOT_EQUAL === $filter->operator()->value()) {
                        return $itemValue != self::cast($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::GT === $filter->operator()->value()) {
                        return $itemValue > self::cast($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::LT === $filter->operator()->value()) {
                        return $itemValue < self::cast($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::CONTAINS === $filter->operator()->value()) {
                        return false !== \strpos($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::IN === $filter->operator()->value()) {
                        return false !== in_array($itemValue, $filter->value()->value());
                    }

                    if (FilterOperator::NOT_IN === $filter->operator()->value()) {
                        return true !== in_array($itemValue, $filter->value()->value());
                    }
                }

                return false;
            },
        );
    }

    private static function cast($type, $value)
    {
        if ($type instanceof \DateTimeInterface) {
            return new \DateTimeImmutable($value);
        }

        $castedValue = $value;
        \settype($castedValue, \gettype($type));

        return $castedValue;
    }

    private function intersect(array $firstArray, array $secondArray): array
    {
        $result = [];
        $intersectKeys = \array_intersect(\array_keys($firstArray), \array_keys($secondArray));

        foreach ($intersectKeys as $key) {
            $result[$key] = $firstArray[$key];
        }

        return $result;
    }
}
