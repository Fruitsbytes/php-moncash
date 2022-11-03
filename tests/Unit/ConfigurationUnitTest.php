<?php

namespace Fruitsbytes\Tests\Unit;

use Dotenv\Dotenv;
use Fruitsbytes\PHP\Moncash\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationUnitTest extends TestCase
{

    private array $defaultEnvironmentValues;

    public function setUp(): void
    {
        parent::setUp();
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->safeLoad();


    }


    /**
     * @test
     */
    public function itWillGiveTheDefaultConfigurationIfCreatedWithNoParameters(){
        // Given a new empty Configuration
        $config = new Configuration();

        // When ready the configuration attributs


        // Then we should assert that we get the default values from then env
    }


    /**
     * @test
     * @dataProvider listGoodConfigurations
     */
    public function itAcceptsValidConfigurationsAtInstanciation($config)
    {
        // Given a new Configuration
        $config = new Configuration($config);
        $mode = $_ENV['MONCASH_MODE'];

        // When testing the host mode
        $is_production = $config->isProduction();

        // Then we should assert if the value corresponds to the env settings
        $this->assertEquals($is_production, $mode === 'production',
            " isProduction() of Configuration::class is returning ".var_export($is_production,
                true)." when it should return ".var_export($_ENV['MONCASH_MODE'] === 'production', true));
    }


    public function itWillFailIfInstancedWithBadConfiguration(){

    }

    public function listGoodConfigurations(): array
    {
        return [
            []
        ];
    }

}
