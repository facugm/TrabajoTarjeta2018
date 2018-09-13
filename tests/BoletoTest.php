<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testSaldoCero() {
        $valor = 16.80;
	
	    $tiempo = new Tiempo;

	    $colectivo = new Colectivo("102", "Semtur", "420");

	    $tarjeta = new Tarjeta(1, $tiempo);

        $boleto = new Boleto($colectivo, $tarjeta, "Normal");

        $this->assertEquals($boleto->obtenerValor(), $valor);
    }

    public function testObtenerColectivo() {
        $linea = "102 Negra";
        $empresa = "Semtur";
        $numero = 420;

	    $tiempo = new Tiempo;

	    $tarjeta = new Tarjeta(1, $tiempo);

        $colectivo = new Colectivo($linea, $empresa, $numero);

        $boleto = new Boleto($colectivo, $tarjeta, "Normal");

        $this->assertEquals($boleto->obtenerColectivo(), $colectivo);

    }

}
