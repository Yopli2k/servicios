<?php
/**
 * This file is part of Servicios plugin for FacturaScripts
 * Copyright (C) 2020 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace FacturaScripts\Plugins\Servicios\Model;

use FacturaScripts\Core\Model\Base;

/**
 * Description of ServicioCliente
 *
 * @author Carlos Garcia Gomez <carlos@facturascripts.com>
 */
class ServicioCliente extends Base\ModelOnChangeClass
{

    use Base\ModelTrait;

    /**
     *
     * @var string
     */
    public $codagente;

    /**
     *
     * @var string
     */
    public $codalmacen;

    /**
     *
     * @var string
     */
    public $codcliente;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var bool
     */
    public $editable;

    /**
     *
     * @var string
     */
    public $fecha;

    /**
     *
     * @var string
     */
    public $fechafin;

    /**
     *
     * @var string
     */
    public $fechainicio;

    /**
     *
     * @var string
     */
    public $hora;

    /**
     *
     * @var string
     */
    public $horafin;

    /**
     *
     * @var string
     */
    public $horainicio;

    /**
     *
     * @var int
     */
    public $idempresa;

    /**
     *
     * @var int
     */
    public $idestado;

    /**
     *
     * @var int
     */
    public $idservicio;

    /**
     *
     * @var string
     */
    public $nick;

    /**
     *
     * @var string
     */
    public $numserie;

    /**
     *
     * @var string
     */
    public $observaciones;

    /**
     *
     * @var int
     */
    public $prioridad;

    /**
     *
     * @var string
     */
    public $referencia;

    public function clear()
    {
        parent::clear();
        $this->fecha = \date(self::DATE_STYLE);
        $this->hora = \date(self::HOUR_STYLE);

        /// set default status
        foreach ($this->getAvailableStatus() as $status) {
            if ($status->predeterminado) {
                $this->idestado = $status->id;
                $this->editable = $status->editable;
                break;
            }
        }
    }

    /**
     * 
     * @return EstadoServicioCliente[]
     */
    public function getAvailableStatus()
    {
        $status = new EstadoServicioCliente();
        return $status->all([], [], 0, 0);
    }

    /**
     * 
     * @return EstadoServicioCliente
     */
    public function getStatus()
    {
        $status = new EstadoServicioCliente();
        $status->loadFromCode($this->idestado);
        return $status;
    }

    /**
     * 
     * @return string
     */
    public function install()
    {
        /// neede dependencies
        new EstadoServicioCliente();

        return parent::install();
    }

    /**
     * 
     * @return string
     */
    public static function primaryColumn(): string
    {
        return 'idservicio';
    }

    /**
     * 
     * @return string
     */
    public static function tableName(): string
    {
        return 'nservicioscli';
    }

    /**
     * 
     * @return bool
     */
    public function test()
    {
        $utils = $this->toolBox()->utils();
        $fields = ['descripcion', 'numserie', 'observaciones', 'referencia'];
        foreach ($fields as $key) {
            $this->{$key} = $utils->noHtml($this->{$key});
        }

        return parent::test();
    }

    /**
     * 
     * @param string $field
     *
     * @return bool
     */
    protected function onChange($field)
    {
        switch ($field) {
            case 'idestado':
                $this->editable = $this->getStatus()->editable;
                return true;
        }

        return parent::onChange($field);
    }

    /**
     * 
     * @param array $fields
     */
    protected function setPreviousData(array $fields = [])
    {
        $more = ['idestado'];
        parent::setPreviousData(\array_merge($more, $fields));
    }
}
