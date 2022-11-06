<?php

namespace Fruitsbytes\Tests\Unit;

use Exception;
use Fruitsbytes\PHP\MonCash\APIException;
use Fruitsbytes\PHP\MonCash\Retry;
use PhpParser\Node\Expr\Closure;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers  Retry
 */
class RetryTest extends TestCase
{
    /**
     * @test
     * @dataProvider getBadAttemptsValues
     * @covers ::__construct
     */
    public function itShouldNotAcceptAttemptsOutsideTheInterval(int $attempts)
    {
        $this->expectException(APIException::class);
        if ($attempts > 10) {
            $this->expectExceptionMessage('Max number of attempts is 10');
        }
        if ($attempts < 2) {
            $this->expectExceptionMessage('Min number of attempts is 2 else its does not need to be retried');
        }
        new Retry(function () {
        }, $attempts);
    }

    public function getBadAttemptsValues(): array
    {
        return [
            [0],
            [1],
            [11],
            [100000]
        ];
    }

    /**
     * @test
     * @covers ::call
     */
    public function itChouldHandleReturnValues()
    {
        // When wanting to read the return
    }


    /**
     * @test
     * @dataProvider getBadClosures
     * @throws APIException
     * @covers ::__construct
     */
    public function itShouldOnlyAcceptClosure($closure)
    {
        $escaped = preg_quote('Fruitsbytes\PHP\Moncash\Retry::__construct(): Argument #1 ($closure) must be of type Closure');

        $this->expectError();
        $this->expectException(TypeError::class);
        $this->expectExceptionMessageMatches("/^$escaped/");
        new Retry($closure);
    }

    public function getBadClosures(): array
    {
        return [
            ['I am bad'],
            ['Utils/testFunction'],
            [new Closure()],
        ];
    }

    /**
     * @test
     * @covers ::call
     */
    public function itShouldGiveAnExceptionIfAttemptsExceded()
    {
        $number = 0;

        $retry = new Retry(function () use (&$number) {
            return $number++ > 5;
        }, 5);

        $this->expectException(APIException::class);
        $this->expectExceptionMessage('Max number of attempts  exceeded for this call');
        $retry->call();

        echo "Test  Final .... $number \r\n";
    }

    /**
     * @test
     * @covers ::call
     */
    public function itShouldBeAbleToPassAValueByReference()
    {
        $serverResponse = '{}';

        try {
            $retry = new Retry(function () use (&$serverResponse) {
                $serverResponse = '{"foo"=>"bar"}';
                return true;
            });
            $retry->call();
        } catch (Exception $e) {
            //
        }

        $this->assertEquals('{"foo"=>"bar"}', $serverResponse, "Canot Pass result by reference. $serverResponse != {\"foo\"=>\"bar\"}");

    }


}
