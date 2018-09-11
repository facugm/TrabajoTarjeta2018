<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testPagarSaldoInsuf() {
        $colectivo = new Colectivo;

	$tiempo = new Tiempo;
        $tarjeta = new Tarjeta("4269", $tiempo );
        
        $tarjeta->recargar(10);
        
        $colectivo->pagarCon($tarjeta);         //hacemos los dos viajes plus para que
        $colectivo->pagarCon($tarjeta);         //se quede sin viajes y testeamos
        
        $this->assertEquals($tarjeta->obtenerSaldo(),10);
        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }

    public function testPagarSaldoSuf(){
        $colectivo = new Colectivo("102", NULL, NULL) ;
	
	$tiempo = new Tiempo;
        $tarjeta = new Tarjeta("4269", $tiempo);
        $boleto = new Boleto(14.80, $colectivo, $tarjeta);
        
        $tarjeta->recargar(20);
        
        //testeamos si al pagar con la tarjeta con saldo suficiente se emite un boleto correcto
        $this->assertEquals($colectivo->pagarCon($tarjeta),$boleto);
        $this->assertEquals($tarjeta->obtenerSaldo(),5.2);
    }

    public function testViajesPlus() {
        $colectivo = new Colectivo("102", NULL, NULL);

	$tiempo = new Tiempo;
        $tarjeta = new Tarjeta("4269", $tiempo);
        $plus1 = new Boleto("Viaje Plus", $colectivo, $tarjeta);    //primero creamos dos boletos, uno siendo un plus normal o primer plus
        $plus2 = new Boleto("Ultimo Plus", $colectivo, $tarjeta);   //y el otro es correspondiente a un segundo o ultimo plus
        
        $tarjeta->recargar(10);                 //recargamos una cantidad insuficiente de dinero en la tarjeta para que esta utilice los viajes plus
        
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus1);     //primero testeamos si se emite correctamente el primer plus
        $this->assertEquals($colectivo->pagarCon($tarjeta),$plus2);     //y luego si se emite correctamente el boleto del ultimo plus

    }

    public function testFranquicias(){
        $colectivo = new Colectivo("102", NULL, NULL);

	$tiempo = new Tiempo;
        $tarjeta = new Tarjeta("4269", $tiempo);
        $compl = new Completo("420", $tiempo);
        $medio = new Medio("12345", $tiempo);
        $boletocomp = new Boleto(0, $colectivo, $compl);
        $boletomedio = new Boleto(7.4, $colectivo, $medio);

        $medio->recargar(10);
        
        $this->assertEquals($boletocomp, $colectivo->pagarCon($compl));
        $this->assertEquals($boletomedio, $colectivo->pagarCon($medio));
        $this->assertEquals($medio->obtenerSaldo(),2.6);
    }

}
