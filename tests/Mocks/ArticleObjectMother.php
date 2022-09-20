<?php

declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter\Tests\Mocks;

use DateTimeImmutable;
use Faker\Factory;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

class ArticleObjectMother extends Article
{
    const TAG_INCLUDED = 'TAG1';
    const TAG_NOT_INCLUDED = 'TAG2';

    public static function withNamePhone(): Article
    {
        $withNamePhone = self::random();
        $withNamePhone->name = 'Phone';
        return $withNamePhone;
    }

    public static function random(): Article
    {
        $faker = Factory::create();

        return new Article(
            Uuid::v4(),
            $faker->text(100),
            $faker->numberBetween(0, 1000),
            DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 year', 'now')),
            [self::TAG_INCLUDED]
        );
    }
}
