<?php

use Liaison\util\Configuration;

require_once(__DIR__ . '/../../../../lib/util/Configuration.php');

/**
 * @author deyles
 */
class ConfigurationTest extends PHPUnit_Framework_TestCase {
    
    public function testNamespaces() {
        $configuration = new Configuration();
        $this->assertFalse($configuration->containsNamespace('foo'));
        $this->assertTrue($configuration->createNamespace('foo'));
        $this->assertTrue($configuration->containsNamespace('foo'));
    }
    
    public function testBeanFunctions() {
        $configuration = new Configuration();
        $this->assertFalse($configuration->containsNamespace(Configuration::defaultNamespace));
        $this->assertFalse($configuration->containsNamespace('mynamespace'));
        $this->assertFalse($configuration->contains('foo'));
        $this->assertFalse($configuration->contains('foo', 'mynamespace'));
        $this->assertTrue($configuration->set('foo', 'bar1'));
        $this->assertTrue($configuration->set('foo', 'bar2', 'mynamespace'));        
        $this->assertTrue($configuration->contains('foo'));
        $this->assertTrue($configuration->contains('foo', 'mynamespace'));
        $this->assertTrue($configuration->containsNamespace(Configuration::defaultNamespace));
        $this->assertTrue($configuration->containsNamespace('mynamespace'));
        $this->assertEquals('bar1', $configuration->get('foo'));
        $this->assertEquals('bar2', $configuration->get('foo', 'mynamespace'));
    }
    
    public function testConstructor() {
        $data = array(
            'test1' => array(
                'foo' => 'bar1'
            ),
            'test2' => array(
                'foo' => 'bar2'
            )
        );
        $configuration = new Configuration($data);
        $this->assertTrue($configuration->containsNamespace('test1'));
        $this->assertTrue($configuration->containsNamespace('test2'));
        $this->assertFalse($configuration->containsNamespace('test3'));
        $this->assertEquals('bar1', $configuration->get('foo', 'test1'));
        $this->assertEquals('bar2', $configuration->get('foo', 'test2'));
    }
    
}