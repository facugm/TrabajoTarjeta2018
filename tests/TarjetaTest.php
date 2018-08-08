<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tarjeta = new Tarjeta;
        $tarjeta1 = new Tarjeta;
        $tarjeta2 = new Tarjeta;
        $tarjeta3 = new Tarjeta;
        $tarjeta4 = new Tarjeta;
        $tarjeta5 = new Tarjeta; 
        
        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);
        
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 30);

        $this->assertTrue($tarjeta1->recargar(510.15));
        $this->assertEquals($tarjeta1->obtenerSaldo(), 592.08);
        
        $this->assertTrue($tarjeta2->recargar(962.59));
        $this->assertEquals($tarjeta2->obtenerSaldo(), 1184.17);
        
        $this->assertTrue($tarjeta3->recargar(30));
        $this->assertEquals($tarjeta3->obtenerSaldo(), 30);

        $this->assertTrue($tarjeta4->recargar(50));
        $this->assertEquals($tarjeta4->obtenerSaldo(), 50);
        
        $this->assertTrue($tarjeta5->recargar(100));
        $this->assertEquals($tarjeta5->obtenerSaldo(), 100);        
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
      $tarjeta = new Tarjeta;

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }
}
