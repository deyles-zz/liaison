<?php


use Liaison\util\Configuration;
use Liaison\rpc\RemoteProcedureCallNetworkDelegate;
use Liaison\rpc\RemoteProcedureCallException;
use Liaison\rpc\ConcreteXmlRpcAdaptor;

require_once(__DIR__ . '/../../../../lib/util/Configuration.php');
require_once(__DIR__ . '/../../../../lib/rpc/RemoteProcedureCallNetworkDelegate.php');
require_once(__DIR__ . '/../../../../lib/rpc/RemoteProcedureCallException.php');
require_once(__DIR__ . '/../../../../lib/rpc/ConcreteXmlRpcAdaptor.php');

class StubRpcAdaptor extends ConcreteXmlRpcAdaptor {

    protected $response;
    
    public function __construct($response) {
        $this->response = $response;
    }
    
    public function write($socket, $message) {
        return strlen($message);
    }
    
    public function read($socket) {
        return $this->response;
    }
    
    public function connect($hostname, $port) {
        return 1;
    }
    
    public function disconnect($socket) {
        return true;
    } 
    
}

/**
 * @author dan
 */
class RemoteProcedureCallNetworkDelegateTest extends PHPUnit_Framework_TestCase {
    
    public function testBadConfiguration() {
        $this->setExpectedException('Liaison\rpc\RemoteProcedureCallException');
        $configuration = new Configuration();
        $adaptor       = new StubRpcAdaptor($this->getSuccessfulResponse());
        $delegate      = new RemoteProcedureCallNetworkDelegate($configuration, $adaptor);
        $delegate->connect();
    }

    public function testGoodConfiguration() {
        $configuration = new Configuration(array(
            'rpc' => array(
                'host' => '127.0.0.1',
                'port' => '9999'
            )
        ));
        $adaptor  = new StubRpcAdaptor($this->getSuccessfulResponse());
        $delegate = new RemoteProcedureCallNetworkDelegate($configuration, $adaptor);
        $this->assertTrue($delegate->connect());
        $this->assertTrue($delegate->disconnect());
    }
    
    public function testSuccessfulCall() {
        $configuration = new Configuration(array(
            'rpc' => array(
                'host' => '127.0.0.1',
                'port' => '9999'
            )
        ));
        $adaptor  = new StubRpcAdaptor($this->getSuccessfulResponse());
        $delegate = new RemoteProcedureCallNetworkDelegate($configuration, $adaptor);
        $response = $delegate->call('foo', 'bar', array('foo' => 'bar'));
        $this->assertEquals(18.24668429131, $response);
    }
    
    public function testUnsuccessfulCall() {
        $this->setExpectedException('Liaison\rpc\RemoteProcedureCallException');        
        $configuration = new Configuration(array(
            'rpc' => array(
                'host' => '127.0.0.1',
                'port' => '9999'
            )
        ));
        $adaptor  = new StubRpcAdaptor($this->getFailureResponse());
        $delegate = new RemoteProcedureCallNetworkDelegate($configuration, $adaptor);
        $response = $delegate->call('foo', 'bar', array('foo' => 'bar'));
    }
    
    
    protected function getSuccessfulResponse() {
        return "<?xml version=\"1.0\"?>
<methodResponse>
   <params>
      <param>
         <value><double>18.24668429131</double></value>
      </param>
   </params>
</methodResponse>";
    }
    
    protected function getFailureResponse() {
        return "<?xml version=\"1.0\"?>
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>4</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>Too many parameters.</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse>";
    }
    
}