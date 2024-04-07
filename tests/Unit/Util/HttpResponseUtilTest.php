<?php

namespace App\Tests\Unit\Util;

use App\Util\HttpResponseUtil;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HttpResponseUtilTest extends KernelTestCase
{
    private HttpResponseUtil $httpResponseUtil;

    protected function setUp(): void
    {
        $this->httpResponseUtil = $this->getContainer()->get(HttpResponseUtil::class);
    }

    /**
     * @dataProvider validHttpCodes
     */
    public function testIsValidResponseCodeReturnsTrueWhenHttpCodeIsValid(int $validHttpCode)
    {
        $this->assertTrue($this->httpResponseUtil->isValidHttpCode($validHttpCode));
    }

    /**
     * @dataProvider invalidHttpCodes
     */
    public function testIsValidResponseCodeReturnsFalseWhenHttpCodeIsInvalid(int $invalidHttpCode)
    {
        $this->assertFalse($this->httpResponseUtil->isValidHttpCode($invalidHttpCode));
    }

    private function validHttpCodes()
    {
        yield '10*' => [100];
        yield '20*' => [200];
        yield '30*' => [300];
        yield '40*' => [400];
        yield '50*' => [500];
    }

    private function invalidHttpCodes()
    {
        yield '0' => [0];
        yield '10' => [10];
        yield '130' => [130];
        yield '1000' => [1000];
    }
}
