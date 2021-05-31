<?php

namespace App\Factory;

use App\Entity\Episode;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Episode|Proxy createOne(array $attributes = [])
 * @method static Episode[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Episode|Proxy find($criteria)
 * @method static Episode|Proxy findOrCreate(array $attributes)
 * @method static Episode|Proxy first(string $sortedField = 'id')
 * @method static Episode|Proxy last(string $sortedField = 'id')
 * @method static Episode|Proxy random(array $attributes = [])
 * @method static Episode|Proxy randomOrCreate(array $attributes = [])
 * @method static Episode[]|Proxy[] all()
 * @method static Episode[]|Proxy[] findBy(array $attributes)
 * @method static Episode[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Episode[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Episode|Proxy create($attributes = [])
 */
final class EpisodeFactory extends ModelFactory
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
            'name' => self::faker()->sentence(5),
            'number' => self::faker()->randomDigitNotZero(),
            'runtime' => self::faker()->randomNumber(2),
            'summary' => self::faker()->paragraph(5),
            'airstamp' => self::faker()->dateTime(),
            'image' => self::faker()->imageUrl(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Episode $episode) {})
        ;
    }

    protected static function getClass(): string
    {
        return Episode::class;
    }
}
