<?php
declare(strict_types=1);

namespace MageSuite\BaseUrlCode\Test\Unit\Model;

class UrlCodeValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\BaseUrlCode\Model\UrlCodeValidator
     */
    protected $urlCodeValidator;

    protected function setUp(): void
    {
        $this->urlCodeValidator = new \MageSuite\BaseUrlCode\Model\UrlCodeValidator();
    }

    /**
     * @dataProvider provider
     */
    public function testStoreUrlCode($urlCode, $expected)
    {
        $this->assertEquals($expected, $this->urlCodeValidator->validate($urlCode));
    }

    /**
     * @return array[]
     */
    public function provider(): array
    {
        return [
            ['de-de', true],
            ['de_de', false],
            ['eur_de', false],
            ['en-de', true],
            ['de-eur', false]
        ];
    }
}
