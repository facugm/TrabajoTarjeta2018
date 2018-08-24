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
        
        //testeamos si al pagar con la tarjeta con saldo suficiente se emite un boleto correcto
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
    }

    public function testViajesPlus() {
        $colectivo = new Colectivo;
        $tarjeta = new Tarjeta;
        $plus1 = new Boleto("Viaje Plus", $colectivo, $tarjeta);    //primero creamos dos boletos, uno siendo un plus normal o primer plus
        $plus2 = new Boleto("Ultimo Plus", $colectivo, $tarjeta);   //y el otro es correspondiente a un segundo o ultimo plus
        
        $tarjeta->recargar(10);         //recargamos una cantidad insuficiente de dinero en la tarjeta para que esta utilice los viajes plus
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus1);     //primero testeamos si se emite correctamente el primer plus
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus2);     //y luego si se emite correctamente el boleto del ultimo plus

    }

}
