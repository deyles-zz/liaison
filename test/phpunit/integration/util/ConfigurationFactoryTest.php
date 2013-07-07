<?php


use Liaison\util\Configuration;
use Liaison\util\ConfigurationException;
use Liaison\util\ConfigurationFactory;
use Liaison\util\ConcreteMemoryConfigurationCache;

require_once(__DIR__ . '/../../../../lib/util/Configuration.php');
require_once(__DIR__ . '/../../../../lib/util/ConfigurationException.php');
require_once(__DIR__ . '/../../../../lib/util/ConfigurationFactory.php');
require_once(__DIR__ . '/../../../../lib/util/ConcreteMemoryConfigurationCache.php');

/**
 * @author dan
 */
class ConfigurationFactoryTest extends PHPUnit_Framework_TestCase {

    public function testFactoryFromFile() {
        $path    = __DIR__ . '/../data/test.ini';
        $cache   = new ConcreteMemoryConfigurationCache();
        $factory = new ConfigurationFactory($cache);
        $configuration = $factory->factoryFromFile($path);
        $this->assertTrue($cache->contains($path));
        $this->assertEquals($configuration, $factory->factoryFromFile($path));
        $this->assertTrue($configuration->contains('animal', 'first_section'));
        $this->assertEquals('BIRD', $configuration->get('animal', 'first_section'));
    }
    
}