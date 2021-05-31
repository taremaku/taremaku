<?php

namespace App\Factory;

use App\Entity\Type;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Type|Proxy createOne(array $attributes = [])
 * @method static Type[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Type|Proxy find($criteria)
 * @method static Type|Proxy findOrCreate(array $attributes)
 * @method static Type|Proxy first(string $sortedField = 'id')
 * @method static Type|Proxy last(string $sortedField = 'id')
 * @method static Type|Proxy random(array $attributes = [])
 * @method static Type|Proxy randomOrCreate(array $attributes = [])
 * @method static Type[]|Proxy[] all()
 * @method static Type[]|Proxy[] findBy(array $attributes)
 * @method static Type[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Type[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Type|Proxy create($attributes = [])
 */
final class TypeFactory extends ModelFactory
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
            // ->afterInstantiate(function(Type $type) {})
        ;
    }

    protected static function getClass(): string
    {
        return Type::class;
    }
}
