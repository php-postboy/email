<?php

declare(strict_types=1);

namespace Tests\Postboy\Email;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Postboy\Email\Email;

class EmailTest extends TestCase
{
    /**
     * @dataProvider provideConstruct
     */
    public function testConstruct(string $address, ?string $name, string $expected)
    {
        $email = new Email($address, $name);
        Assert::assertSame($expected, (string)$email);
    }

    /**
     * @dataProvider provideCreateFromString
     */
    public function testCreateFromString(string $string, string $address, ?string $name)
    {
        $email = Email::createFromString($string);
        Assert::assertSame($address, $email->getAddress());
        Assert::assertSame($name, $email->getName());
    }

    public function provideConstruct(): array
    {
        return [
            ['test@phpunit.de', null, '<test@phpunit.de>'],
            ['test@phpunit.de', 'test', 'test <test@phpunit.de>'],
            ['test@phpunit.de', 'unit test', '"unit test" <test@phpunit.de>'],
            ['test@phpunit.de', ' ', '<test@phpunit.de>'],
        ];
    }

    public function provideCreateFromString(): array
    {
        return [
            ['test@phpunit.de', 'test@phpunit.de', null],
            ['<test@phpunit.de>', 'test@phpunit.de', null],
            ['test <test@phpunit.de>', 'test@phpunit.de', 'test'],
            ['"unit test" <test@phpunit.de>', 'test@phpunit.de', 'unit test'],
        ];
    }
}
