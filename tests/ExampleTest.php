<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testBasicAssertion()
    {
        $this->assertTrue(true);
    }

    public function testApiResponse()
    {
        // Ejemplo de test para verificar que la API responde
        $response = file_get_contents('http://localhost:8000/api/health');
        $data = json_decode($response, true);
        
        $this->assertNotNull($data);
        $this->assertTrue($data['success']);
        $this->assertEquals('OK', $data['data']['status']);
    }
}