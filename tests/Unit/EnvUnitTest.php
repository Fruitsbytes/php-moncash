<?php

declare(strict_types=1);

namespace Fruitsbytes\Tests\Unit;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class EnvUnitTest extends TestCase
{
    /** @test */
    public function itCanReadCredentialsEnvironmentVariables()
    {
        //Given a set of required environment variables
        $envNames = ['MONCASH_CLIENT_ID', 'MONCASH_CLIENT_SECRET', 'MONCASH_BUSINESS_KEY'];

        // When try to read them
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->safeLoad();
        foreach ($envNames as $envName) {
            $envValue = $_ENV[$envName];

            // Then whe assert that none of the returned values are an empty string
            $this->assertNotFalse($envValue, "Could not find environment variable $envName.");
        }

    }
}
