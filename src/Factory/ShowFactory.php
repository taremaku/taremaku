<?php

namespace App\Factory;

use App\Entity\Show;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Show|Proxy createOne(array $attributes = [])
 * @method static Show[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Show|Proxy find($criteria)
 * @method static Show|Proxy findOrCreate(array $attributes)
 * @method static Show|Proxy first(string $sortedField = 'id')
 * @method static Show|Proxy last(string $sortedField = 'id')
 * @method static Show|Proxy random(array $attributes = [])
 * @method static Show|Proxy randomOrCreate(array $attributes = [])
 * @method static Show[]|Proxy[] all()
 * @method static Show[]|Proxy[] findBy(array $attributes)
 * @method static Show[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Show[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Show|Proxy create($attributes = [])
 */
final class ShowFactory extends ModelFactory
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
            'summary' => self::faker()->paragraph(),
            'status' => self::faker()->randomDigit(),
            'poster' => self::faker()->imageUrl(),
            'website' => self::faker()->url,
            'rating' => self::faker()->randomDigit(),
            'language' => self::faker()->word(),
            'runtime' => self::faker()->randomNumber(3),
            'premiered' => self::faker()->dateTimeBetween('-10 years'),
            'idTvmaze' => self::faker()->randomNumber(10),
            'idImdb' => self::faker()->randomNumber(10),
            'apiUpdate' => self::faker()->randomNumber(10)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Show $show) {})
        ;
    }

    protected static function getClass(): string
    {
        return Show::class;
    }
}
