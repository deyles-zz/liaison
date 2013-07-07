<?php


use Liaison\rpc\RpcHttpRequest;

require_once(__DIR__ . '/../../../../lib/rpc/RpcHttpRequest.php');

/**
 * @author dan
 */
class RpcHttpRequestTest extends PHPUnit_Framework_TestCase {

    public function testRequest() {
        $request = new RpcHttpRequest('POST', '/foo', 'bar');
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/foo', $request->getUrl());
        $this->assertEquals('bar', $request->getBody());
        
        $text = "POST /foo HTTP/1.1\r\nContent-Length: 3\r\n\r\nbar";
        $this->assertEquals($text, $request->toString());
    }
    
}