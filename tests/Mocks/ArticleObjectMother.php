<?php
declare(strict_types=1);

namespace PcComponentes\CriteriaArrayAdapter\Tests\Mocks;

use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use Faker\Factory;

class ArticleObjectMother
{
    const TAG_INCLUDED = 'TAG1';
    const TAG_NOT_INCLUDED = 'TAG2';

    public static function random(): Article
    {
        $faker = Factory::create();

        return new Article(
            Uuid::v4(),
            $faker->text(100),
            $faker->numberBetween(0, 1000),
            \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-10 year', 'now')),
            [self::TAG_INCLUDED]
        );
    }
}
