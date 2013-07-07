<?php


use Liaison\util\ConcreteMemoryConfigurationCache;
use Liaison\util\Configuration;

require_once(__DIR__ . '/../../../../lib/util/ConcreteMemoryConfigurationCache.php');
require_once(__DIR__ . '/../../../../lib/util/Configuration.php');

/**
 * @author deyles
 */
class ConcreteMemoryConfigurationCacheTest extends PHPUnit_Framework_TestCase {

    public function testCache() {
        $config = new Configuration();
        $config->set('foo', 'bar');
        $cache = new ConcreteMemoryConfigurationCache();
        $this->assertFalse($cache->contains('foo'));
        $this->assertTrue($cache->set('foo', $config));
        $this->assertTrue($cache->contains('foo'));
        $this->assertEquals($config, $cache->get('foo'));
    }
    
}