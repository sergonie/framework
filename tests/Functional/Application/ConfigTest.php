<?php declare(strict_types=1);

namespace Sergonie\Tests\Functional\Application;

use PHPUnit\Framework\TestCase;
use Sergonie\Application\Config;
use Sergonie\Application\Exception\ConfigException;

final class ConfigTest extends TestCase
{
    public function cacheValuesDP(): array
    {
        return [
            'with cache' => [true],
            'without cache' => [false],
        ];
    }

    public function testCanInstantiate(): void
    {
        $config = new Config();
        self::assertInstanceOf(Config::class, $config);
    }

    public function testThatConfigUnderstandsGlobalConstants(): void
    {
        $value = '12345';
        define('TEST_1', $value);

        $config = new Config([
            'test' => '${TEST_1}',
        ]);

        self::assertSame($value, $config->get('test'));
    }

    /** @dataProvider cacheValuesDP */
    public function testNestingValues(bool $use_cache): void
    {
        $config = new Config([], $use_cache);

        $config->set('test.a.b', 1);
        $config->set('test.a.c', null);
        self::assertEquals(['a' => ['b' => 1, 'c' => null]], $config->get('test'));
        self::assertSame(1, $config->get('test.a.b'));
        self::assertNull($config->get('test.a.c'));
    }

    /** @dataProvider cacheValuesDP */
    public function testConfigMerge(bool $use_cache): void
    {
        $a = new Config([
            'testA' => [
                'a' => 1,
            ],
            'testB' => 'b'
        ], $use_cache);

        $b = new Config([
            'testA' => [
                'b' => 2
            ],
            'testB' => 'c',
            'testC' => 2
        ], $use_cache);

        $a->merge($b);

        self::assertSame(
            [
                'testA' => [
                    'a' => 1,
                    'b' => 2
                ],
                'testB' => ['b', 'c'],
                'testC' => 2,
            ],
            $a->toArray()
        );
    }

    public function testToArray(): void
    {
        $config = new Config();
        $config->set('a.b.c' , 1);
        $config->set('b.c', 2);
        $config->set('c', 3);

        self::assertSame([
            'a' => [
                'b' => [
                    'c' => 1,
                ],
            ],
            'b' => [
                'c' => 2,
            ],
            'c' => 3,
        ], $config->toArray());
    }

    public function testExtract(): void
    {
        $config = new Config();
        $config->set('a.b.c' , 123);
        $config->set('a.a.b', 112);
        $config->set('a.a.c', 113);
        $config->set('a.c.a', 131);
        $config->set('b.c', 23);
        $config->set('c', 3);

        self::assertSame(
            [
                'b.c' => 123,
                'a.b' => 112,
                'a.c' => 113,
                'c.a' => 131,
            ],
            $config->extract('a')->toFlatArray()
        );

        self::assertSame(
            [
                'b' => 112,
                'c' => 113,
            ],
            $config->extract('a.a')->toFlatArray()
        );

        self::assertSame(
            [
                'c' => 23,
            ],
            $config->extract('b')->toFlatArray()
        );
    }

    public function testFailOnExtractingNonArrayKey(): void
    {
        $this->expectException(ConfigException::class);
        $config = new Config();
        $config->set('c', 3);
        self::assertSame(
            [
                3,
            ],
            $config->extract('c')->toFlatArray()
        );
    }

    public function testToFlatArray(): void
    {
        $config = new Config();
        $config->set('a.b.c', 1);
        $config->set('b.c', 2);
        $config->set('b.d', 2.1);
        $config->set('c', []);
        $config->set('c.d', ['e' => 1, 'f' => ['g' => 2]]);
        $config->set('c.d.f.h', 10);

        self::assertSame(
            [
                'a.b.c' => 1,
                'b.c' => 2,
                'b.d' => 2.1,
                'c.d.e' => 1,
                'c.d.f.g' => 2,
                'c.d.f.h' => 10,
            ],
            $config->toFlatArray()
        );
    }
}
