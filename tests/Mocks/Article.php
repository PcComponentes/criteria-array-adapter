<?php
declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter\Tests\Mocks;

use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

class Article
{
    private Uuid $id;
    private string $name;
    private float $stock;
    private \DateTimeInterface $date;

    public function __construct(Uuid $id, string $name, int $stock, \DateTimeImmutable $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->stock = $stock;
        $this->date = $date;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function stock(): float
    {
        return $this->stock;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
