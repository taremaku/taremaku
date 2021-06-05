<?php

namespace App\Factory;

use App\Entity\Genre;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Genre|Proxy createOne(array $attributes = [])
 * @method static Genre[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Genre|Proxy find($criteria)
 * @method static Genre|Proxy findOrCreate(array $attributes)
 * @method static Genre|Proxy first(string $sortedField = 'id')
 * @method static Genre|Proxy last(string $sortedField = 'id')
 * @method static Genre|Proxy random(array $attributes = [])
 * @method static Genre|Proxy randomOrCreate(array $attributes = [])
 * @method static Genre[]|Proxy[] all()
 * @method static Genre[]|Proxy[] findBy(array $attributes)
 * @method static Genre[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Genre[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Genre|Proxy create($attributes = [])
 */
final class GenreFactory extends ModelFactory
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
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Genre $genre) {})
        ;
    }

    protected static function getClass(): string
    {
        return Genre::class;
    }
}
