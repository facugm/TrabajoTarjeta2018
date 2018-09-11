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
    protected $abonoPlus;

    public function __construct($valor, $colectivo, $tarjeta) {

        $this->valor = $valor;

        $this->colectivo = $colectivo;

        $this->tarjeta = $tarjeta;

        $this->tipo = $tarjeta->obtenerTipo();

        $this->fecha = date("d/m/Y H:i:s",$tarjeta->obtenerFecha());

        $this->linea = $colectivo->linea();

        $this->total = $tarjeta->totalPagado();

        $this->saldo = $tarjeta->obtenerSaldo();

        $this->id = $tarjeta->obtenerId();

        $this->abonoPlus = $tarjeta->abonoPlus();


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
