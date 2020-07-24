<?php
namespace PcComponentes\CriteriaDBALAdapter\Tests\Mocks;

use Cassandra\Date;
use PcComponentes\Ddd\Domain\Model\ValueObject\DateTimeValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\FloatValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\IntValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\StringValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

class Article
{

    private Uuid $id;
    private string $name;
    private float $stock;
    private \DateTimeInterface $date;


    public function __construct(
        Uuid $id,
        string $name,
        int $stock,
        \DateTimeImmutable $date)
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

    public function stock(): int
    {
        return $this->stock;
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }

}
