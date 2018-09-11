<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testSaldoCero() {
        $valor = 14.80;
	
	$tiempo = new Tiempo;

	$colectivo = new Colectivo("102", "Semtur", "420");

	$tarjeta = new Tarjeta(4269, $tiempo);

        $boleto = new Boleto($valor, $colectivo, $tarjeta);

        $this->assertEquals($boleto->obtenerValor(), $valor);
    }

    public function testObtenerColectivo() {
        $linea = "102 Negra";
        $empresa = "Semtur";
        $numero = 420;

	$tiempo = new Tiempo;

	$tarjeta = new Tarjeta(4269, $tiempo);

        $colectivo = new Colectivo($linea, $empresa, $numero);

        $boleto = new Boleto(NULL, $colectivo, $tarjeta);

        $this->assertEquals($boleto->obtenerColectivo(), $colectivo);

    }

}
