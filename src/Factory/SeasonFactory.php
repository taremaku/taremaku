<?php

namespace App\Factory;

use App\Domain\Show\Season;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Season|Proxy createOne(array $attributes = [])
 * @method static Season[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Season|Proxy find($criteria)
 * @method static Season|Proxy findOrCreate(array $attributes)
 * @method static Season|Proxy first(string $sortedField = 'id')
 * @method static Season|Proxy last(string $sortedField = 'id')
 * @method static Season|Proxy random(array $attributes = [])
 * @method static Season|Proxy randomOrCreate(array $attributes = [])
 * @method static Season[]|Proxy[] all()
 * @method static Season[]|Proxy[] findBy(array $attributes)
 * @method static Season[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Season[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Season|Proxy create($attributes = [])
 */
final class SeasonFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'number' => self::faker()->randomDigit(),
            'poster' => self::faker()->imageUrl(),
            'episodeCount' => self::faker()->randomNumber(3),
            'premiereDate' => self::faker()->dateTimeBetween('-10 years'),
            'endDate' => self::faker()->dateTimeBetween('-9 years')
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Season $season) {})
        ;
    }

    protected static function getClass(): string
    {
        return Season::class;
    }
}
