<?php

namespace App\Factory;

use App\Entity\Following;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Following|Proxy createOne(array $attributes = [])
 * @method static Following[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Following|Proxy find($criteria)
 * @method static Following|Proxy findOrCreate(array $attributes)
 * @method static Following|Proxy first(string $sortedField = 'id')
 * @method static Following|Proxy last(string $sortedField = 'id')
 * @method static Following|Proxy random(array $attributes = [])
 * @method static Following|Proxy randomOrCreate(array $attributes = [])
 * @method static Following[]|Proxy[] all()
 * @method static Following[]|Proxy[] findBy(array $attributes)
 * @method static Following[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Following[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Following|Proxy create($attributes = [])
 */
final class FollowingFactory extends ModelFactory
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
            // ->afterInstantiate(function(Following $following) {})
        ;
    }

    protected static function getClass(): string
    {
        return Following::class;
    }
}
