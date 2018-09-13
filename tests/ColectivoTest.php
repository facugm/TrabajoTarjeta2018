<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testPagarSaldoInsuf() {
        $colectivo = new Colectivo(102, "Semtur", 420);

	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo );
        
        $tarjeta->recargar(10);
        
        $colectivo->pagarCon($tarjeta);         //hacemos los dos viajes plus para que
        $colectivo->pagarCon($tarjeta);         //se quede sin viajes y testeamos
        
        $this->assertEquals($tarjeta->obtenerSaldo(),10);
        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }

    public function testPagarSaldoSuf(){
        $colectivo = new Colectivo("102", "Semtur", "420") ;
	
	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);

        $tarjeta->recargar(20);
        
        //testeamos si al pagar con la tarjeta con saldo suficiente se emite un boleto correcto
        $this->assertEquals($colectivo->pagarCon($tarjeta), new Boleto($colectivo, $tarjeta, "Normal"));
        $this->assertEquals($tarjeta->obtenerSaldo(),3.2);
    }

    public function testViajesPlus() {
        $colectivo = new Colectivo("102", NULL, NULL);

	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);
        //$plus1 = new Boleto($colectivo, $tarjeta, "Viaje Plus");    //primero creamos dos boletos, uno siendo un plus normal o primer plus
        //$plus2 = new Boleto($colectivo, $tarjeta, "Ultimo Plus");   //y el otro es correspondiente a un segundo o ultimo plus
        
        $tarjeta->recargar(10);                 //recargamos una cantidad insuficiente de dinero en la tarjeta para que esta utilice los viajes plus
        
        $this->assertEquals($colectivo->pagarCon($tarjeta),new Boleto($colectivo, $tarjeta, "Viaje Plus"));     //primero testeamos si se emite correctamente el primer plus
        $this->assertEquals($colectivo->pagarCon($tarjeta),new Boleto($colectivo, $tarjeta, "Ultimo Plus"));     //y luego si se emite correctamente el boleto del ultimo plus

    }

    public function testFranquicias(){
        $colectivo = new Colectivo("102", NULL, NULL);

	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);
        $compl = new Completo(0, $tiempo);
        $medio = new Medio(2, $tiempo);
        
        $medio->recargar(10);
        
        $this->assertEquals($colectivo->pagarCon($compl), new Boleto($colectivo, $compl, "Normal"));
        $this->assertEquals($colectivo->pagarCon($medio), new Boleto($colectivo, $medio, "Normal"));
        $this->assertEquals($medio->obtenerSaldo(),1.6);
    }

}
