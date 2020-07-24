<?php

namespace PcComponentes\CriteriaDBALAdapter\Tests\Mocks;

use PcComponentes\Ddd\Domain\Model\ValueObject\DateTimeValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\FloatValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\IntValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\StringValueObject;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Faker\Factory;

class ArticleObjectMother
{
    public static function random(): Article
    {
        $faker = Factory::create();
        return new Article(
            Uuid::v4(),
            $faker->text(100),
            $faker->numberBetween(0, 1000),
            \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 year', 'now')),
            (object) ['amount' => $faker->numberBetween(0,1000), 'currency' => $faker->currencyCode],
        );
    }
}
