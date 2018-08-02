<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {

    public function testSaldoCero() {
        $valor = 14.80;

        $boleto = new Boleto($valor, NULL, NULL);

        $this->assertEquals($boleto->obtenerValor(), $valor);
    }

    public function testObtenerColectivo() {
        $linea = "102 Negra";
        $empresa = "Semtur";
        $numero = 420;

        $colectivo = new Colectivo($linea, $empresa, $numero);

        $boleto = new Boleto(NULL, $colectivo, NULL);

        $this->assertEquals($boleto->obtenerColectivo(), $colectivo);

    }

}
