<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class TarjetaTest extends TestCase {

    /**
     * Comprueba que la tarjeta aumenta su saldo cuando se carga saldo válido.
     */
    public function testCargaSaldo() {
        $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo); 
        
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
        $tiempo = new Tiempo;
        $tarjeta1 = new Tarjeta(1, $tiempo);
        $tarjeta2 = new Tarjeta(2, $tiempo);

        $this->assertTrue($tarjeta1->recargar(510.15));
        $this->assertEquals($tarjeta1->obtenerSaldo(), 592.08);
        
        $this->assertTrue($tarjeta2->recargar(962.59));
        $this->assertEquals($tarjeta2->obtenerSaldo(), 1184.17);
    }

    /**
     * Comprueba que la tarjeta no puede cargar saldos invalidos.
     */
    public function testCargaSaldoInvalido() {
        $tiempo = new Tiempo;
        $tarjeta = new Tarjeta(1, $tiempo);

        $this->assertFalse($tarjeta->recargar(15));
        $this->assertEquals($tarjeta->obtenerSaldo(), 0);
  }

    public function testLimiteTiempoMedio(){ //Comprueba que se puedan emitir dos medios recién al haber pasado 5 minutos
        $tiempo = new TiempoFalso;
        $medio = new Medio(1, $tiempo);
        $colectivo = new Colectivo("102", "Negra", "Semtur", 2);

        $medio->recargar(20);
        
        $this->assertEquals($colectivo->pagarCon($medio), new Boleto($colectivo, $medio, "Normal")); // se comprueba que se emite medio normal
        $tiempo->avanzar(150); //y al pasar dos minutos y medio

        $this->assertFalse($colectivo->pagarCon($medio)); //no puede pagar

        $tiempo->avanzar(180); //pero al pasar otros 3 minutos

        $this->assertEquals($colectivo->pagarCon($medio), new Boleto($colectivo, $medio, "Normal")); //se emite un medio normal sin problemas
  }

    public function testLimiteMedioUni(){
        $tiempo = new TiempoFalso;
        $uni = new MedioUniversitario(1, $tiempo);
        $colectivo = new Colectivo("102", "Negra", "Semtur", 3);

        $uni->recargar(50);


        $this->assertEquals($colectivo->pagarCon($uni), $medio1 = new Boleto($colectivo, $uni, "Normal"));
        $this->assertEquals($medio1->obtenerValor(), 8.4);  //pago medio boleto

        $tiempo->avanzar(3600); //avanzar una hora

        $this->assertEquals($colectivo->pagarCon($uni), $medio2 = new Boleto($colectivo, $uni, "Normal"));
        $this->assertEquals($medio2->obtenerValor(), 8.4); //pago segundo medio boleto
        

        $tiempo->avanzar(3600); //avanzamos una hora en el tiempo

        $this->assertEquals($colectivo->pagarCon($uni), $boleto = new Boleto($colectivo, $uni, "Normal"));
        $this->assertEquals($boleto->obtenerValor(), 16.8); // y pagamos un boleto normal porque ya usamos los 2 medios que teniamos disponibles

        $tiempo->avanzar(86400);//avanzamos un dia en el tiempo

        $this->assertEquals($colectivo->pagarCon($uni), $boleto = new Boleto($colectivo, $uni, "Normal"));
        $this->assertEquals($boleto->obtenerValor(), 8.4); // se emite el primer medio ya que paso un dia
    }

    public function testTrasbordoNormal(){
        $tiempo = new TiempoFalso;
        $tarjeta = new Tarjeta(1, $tiempo);
        $negra102 = new Colectivo ("102", "Negra", "Semtur", 2);
        $roja102 = new Colectivo ("102", "Roja", "Semtur", 3);
        $negra103 = new Colectivo ("103", "Negra", "Semtur", 23);
        $negra102diferente = new Colectivo ("102", "Negra", "Semtur", 65);

        $tarjeta->recargar(100);
        $negra102->pagarCon($tarjeta);

        $tiempo->avanzar(4500); //Avanzamos 90 minutos

        //Test horario nocturno, pueden pasar hasta 90 minutos
        $this->assertEquals($roja102->pagarCon($tarjeta), new Boleto($roja102, $tarjeta, "Trasbordo"));
        $this->assertEquals($tarjeta->obtenerSaldo(), 77.66);
        $this->assertNotEquals($roja102->pagarCon($tarjeta), new Boleto($roja102, $tarjeta, "Trasbordo"));

    }

    public function testTrasbrodoMedio(){
        $tiempo = new TiempoFalso;
        $medio = new Medio(1, $tiempo);
                                                                                                                            
        $negra102 = new Colectivo ("102", "Negra", "Semtur", 2);
        $roja102 = new Colectivo ("102", "Roja", "Semtur", 3);
        $negra103 = new Colectivo ("103", "Negra", "Semtur", 23);
        $negra102diferente = new Colectivo ("102", "Negra", "Semtur", 65);
        
        $medio->recargar(50);
        $negra102->pagarCon($medio);

        $tiempo->avanzar(2400); //avanzamos 40 minutos
        
        $this->assertEquals($roja102->pagarCon($medio), new Boleto($roja102, $medio, "Trasbordo"));
        $this->assertEquals($medio->obtenerSaldo(), 38.83);                                
    }            
   
}
