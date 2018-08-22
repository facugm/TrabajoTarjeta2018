<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testPagarSaldoInsuf() {
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $tarjeta->recargar(10);
        
        $colectivo->pagarCon($tarjeta);         //hacemos los dos viajes plus para que
        $colectivo->pagarCon($tarjeta);         //se quede sin viajes y testeamos
        
        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }

    public function testPagarSaldoSuf(){
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $boleto = new Boleto(14.80, $colectivo, $tarjeta);
        $tarjeta->recargar(20);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
    }

    public function testViajesPlus() {
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $plus1 = new Boleto("Viaje Plus", $colectivo, $tarjeta);
        $plus2 = new Boleto("Ultimo Plus", $colectivo, $tarjeta);
        
        $tarjeta->recargar(10);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus1);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus2);

    }

}
