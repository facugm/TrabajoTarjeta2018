<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface {

    protected $valor;
    protected $colectivo;
    protected $tarjeta;
    protected $tipo;
    protected $fecha;
    protected $linea;
    protected $total;
    protected $saldo;
    protected $id;
    protected $tipoBoleto;

    public function __construct($colectivo, $tarjeta, $tipoBoleto) {

        $this->valor = $tarjeta->valorPasaje();

        $this->colectivo = $colectivo;

        $this->tarjeta = $tarjeta;

        $this->tipo = $tarjeta->obtenerTipo();

        $this->fecha = date("d/m/Y H:i:s",$tarjeta->obtenerFecha());

        $this->linea = $colectivo->linea();

        $this->tipoBoleto = $tipoBoleto;

        $this->total = $this->valor + $tarjeta->abonaPlus();

        $this->saldo = $tarjeta->obtenerSaldo();

        $this->id = $tarjeta->obtenerId();



    }

    /**
     * Devuelve el valor del boleto.
     *
     * @return int
     */
    public function obtenerValor() {
        return $this->valor;
    }

    /**
     * Devuelve un objeto que respresenta el colectivo donde se viajó.
     *
     * @return ColectivoInterface
     */
    public function obtenerColectivo() {
        return $this->colectivo;
    }

}
