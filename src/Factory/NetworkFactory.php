<?php

namespace App\Factory;

use App\Entity\Network;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Network|Proxy createOne(array $attributes = [])
 * @method static Network[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Network|Proxy find($criteria)
 * @method static Network|Proxy findOrCreate(array $attributes)
 * @method static Network|Proxy first(string $sortedField = 'id')
 * @method static Network|Proxy last(string $sortedField = 'id')
 * @method static Network|Proxy random(array $attributes = [])
 * @method static Network|Proxy randomOrCreate(array $attributes = [])
 * @method static Network[]|Proxy[] all()
 * @method static Network[]|Proxy[] findBy(array $attributes)
 * @method static Network[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Network[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Network|Proxy create($attributes = [])
 */
final class NetworkFactory extends ModelFactory
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
            'name' => self::faker()->word()
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Network $network) {})
        ;
    }

    protected static function getClass(): string
    {
        return Network::class;
    }
}
