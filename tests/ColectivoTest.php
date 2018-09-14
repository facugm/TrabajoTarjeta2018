<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class ColectivoTest extends TestCase {

    public function testPagarSaldoInsuf() {
        $colectivo = new Colectivo(102, "Semtur", 420);

	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo );
        
        $tarjeta->recargar(10);
        
        $colectivo->pagarCon($tarjeta);   //hacemos los dos viajes plus para que
        $colectivo->pagarCon($tarjeta);   //se quede sin viajes y testeamos
        
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
        $tarjeta2 = new Tarjeta(2, $tiempo);

        $tarjeta->recargar(10);
        $colectivo->pagarCon($tarjeta);
        $tarjeta->recargar(10);
        
        $this->assertEquals($colectivo->pagarCon($tarjeta), new Boleto($colectivo, $tarjeta, "Ultimo Plus"));
        
        $tarjeta2->recargar(10); //recargamos una cantidad insuficiente de dinero en la tarjeta para que esta utilice los viajes plus
        
        $this->assertEquals($colectivo->pagarCon($tarjeta2),new Boleto($colectivo, $tarjeta2, "Viaje Plus"));     //primero testeamos si se emite correctamente el primer plus
        $this->assertEquals($colectivo->pagarCon($tarjeta2),new Boleto($colectivo, $tarjeta2, "Ultimo Plus"));     //y luego si se emite correctamente el boleto del ultimo plus

    }

    public function testFranquicias(){
        $colectivo = new Colectivo("102", NULL, NULL);

	    $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);
        $compl = new Completo(0, $tiempo);
        $medio = new Medio(2, $tiempo);
        $medioUni = new MedioUniversitario(3, $tiempo);
        
        $medio->recargar(10);
        $medioUni->recargar(10);
        
        $this->assertEquals($colectivo->pagarCon($compl), new Boleto($colectivo, $compl, "Normal"));
        $this->assertEquals($colectivo->pagarCon($medio), new Boleto($colectivo, $medio, "Normal"));
        $this->assertEquals($colectivo->pagarCon($medioUni), new Boleto($colectivo, $medioUni, "Normal"));
        $this->assertEquals($medio->obtenerSaldo(),1.6);
        $this->assertEquals($medioUni->obtenerSaldo(),1.6);

        $colectivo->pagarCon($medio);  //Genero Viaje Plus
        $colectivo->pagarCon($medioUni);

        $medio->recargar(10);  //Cargo como para pagar un medio
        $medioUni->recargar(10);

        //Pero no puedo porque debo un plus

        $this->assertEquals($colectivo->pagarCon($medio), new Boleto($colectivo, $medio, "Ultimo Plus"));
        $this->assertEquals($colectivo->pagarCon($medioUni), new Boleto($colectivo, $medioUni, "Ultimo Plus"));
    }

    public function testDatosColectivo(){
        $colectivo = new Colectivo(102, "Semtur", 2);

        $this->assertEquals($colectivo->linea(), 102);
        $this->assertEquals($colectivo->empresa(), "Semtur");
        $this->assertEquals($colectivo->numero(), 2);
    }

    public function testDebePlusNormal(){
        $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);
        $colectivo = new Colectivo(102, "Semtur", 40);

        $tarjeta->recargar(10);  
        $colectivo->pagarCon($tarjeta);  //usamos un viaje plus


        $tarjeta->recargar(30); //comprobamos que se abona un viaje plus que debia y que la descripcion coincide
        $this->assertEquals($colectivo->pagarCon($tarjeta), $abono1 = new Boleto($colectivo, $tarjeta, "Normal"));
        $this->assertEquals($abono1->obtenerDescripcion(), "Abona Viajes Plus 16.8 y");
        $this->assertEquals($tarjeta->obtenerSaldo(), 6.4);


        $colectivo->pagarCon($tarjeta); //pagamos dos veces utilizando los viajes plus
        $colectivo->pagarCon($tarjeta);

        $this->assertFalse($colectivo->pagarCon($tarjeta)); //comprobamos que con el mismo saldo no podemos viajar

        $tarjeta->recargar(30);
        $this->assertFalse($colectivo->pagarCon($tarjeta)); //y con saldo insuficiente tampoco podemos

        $tarjeta->recargar(20); //solo cuando carguemos el valor de nuestro boleto + los plus que debamos, se emitira un boleto
        $this->assertEquals($colectivo->pagarCon($tarjeta), $abono2 = new Boleto($colectivo, $tarjeta, "Normal"));
        $this->assertEquals($abono2->obtenerDescripcion(), "Abona Viajes Plus 33.6 y");
        $this->assertEquals($tarjeta->obtenerSaldo(), 6.0 );

    }

    public function testDebePlusMedio(){
        $tiempo = new Tiempo;
        $medio = new Medio(1, $tiempo);
        $colectivo = new Colectivo(102, "Semtur", 40);

        $medio->recargar(10);
        $colectivo->pagarCon($medio); //boleto normal
        $colectivo->pagarCon($medio); //viaje plus 1

        $medio->recargar(30);  //cargamos suficiente para pagar el plus y abonamos el plus que debiamos y el medio boleto normal
        $this->assertEquals($colectivo->pagarCon($medio), $abono1 = new Boleto($colectivo, $medio, "Normal"));
        $this->assertEquals($abono1->obtenerDescripcion(), "Abona Viajes Plus 16.8 y");
        $this->assertEquals($medio->obtenerSaldo(), 6.4 );

        $colectivo->pagarCon($medio);
        $colectivo->pagarCon($medio); //usamos los 2 plus

        $this->assertFalse($colectivo->pagarCon($medio)); //ahora no podremos pagar

        $medio->recargar(50);
        $this->assertEquals($colectivo->pagarCon($medio), $abono2 = new Boleto($colectivo, $medio, "Normal"));
        $this->assertEquals($abono2->obtenerDescripcion(), "Abona Viajes Plus 33.6 y");
        $this->assertEquals($medio->obtenerSaldo(), 14.4 );

    }

}
