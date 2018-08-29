<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tarjeta = new Tarjeta; 
        
        $this->assertTrue($tarjeta->recargar(10));
        $this->assertEquals($tarjeta->obtenerSaldo(), 10);
        
        $this->assertTrue($tarjeta->recargar(20));
        $this->assertEquals($tarjeta->obtenerSaldo(), 30);
        
        $this->assertTrue($tarjeta->recargar(30));
        $this->assertEquals($tarjeta->obtenerSaldo(), 60);

        $this->assertTrue($tarjeta->recargar(50));
        $this->assertEquals($tarjeta->obtenerSaldo(), 110);
        
        $this->assertTrue($tarjeta->recargar(100));
        $this->assertEquals($tarjeta->obtenerSaldo(), 210);        
    }

    //Comprueba que la tarjeta se carga con el adicional
    public function testCargasConAdicional(){
        $tarjeta1 = new Tarjeta;
        $tarjeta2 = new Tarjeta;

        $this->assertTrue($tarjeta1->recargar(510.15));
        $this->assertEquals($tarjeta1->obtenerSaldo(), 592.08);
        
        $this->assertTrue($tarjeta2->recargar(962.59));
        $this->assertEquals($tarjeta2->obtenerSaldo(), 1184.17);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
      $tarjeta = new Tarjeta;

      $this->assertFalse($tarjeta->recargar(15));
      $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

    public function testCargaPlus(){
        $tarjeta1 = new Tarjeta;
        $tarjeta2 = new Tarjeta;
        $colectivo = new Colectivo;

        //aqui comprobamos que se descuente adecuadamente la cantidad de plus del monto a recargar
        //tarjeta1 tendra 1 viaje plus usado
        $tarjeta1->recargar(10);
        $colectivo->pagarCon($tarjeta1);
        $this->assertEquals($tarjeta1->recargar(20), 5.20);

        //tarjeta2 tiene los 2 viajes plus usados
        $tarjeta2->recargar(10);
        $colectivo->pagarCon($tarjeta2);
        $colectivo->pagarCon($tarjeta2);
        $this->assertEquals($tarjeta2->recargar(30), 0.40);

    }
}
