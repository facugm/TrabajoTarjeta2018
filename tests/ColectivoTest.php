<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testPagarSaldoInsuf() {
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $tarjeta->recargar(10);
        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }

    public function testPagarSaldoSuf(){
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $boleto = new Boleto(14.80, $colectivo, $tarjeta);
        $tarjeta->recargar(20);
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
    }
}
