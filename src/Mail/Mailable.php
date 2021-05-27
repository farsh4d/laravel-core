<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Mail;


use Illuminate\Mail\Mailable as Mail;

/**
 * Class Mailable
 *
 * @package Modules\Core\Mail
 */
abstract class Mailable extends Mail
{
    /**
     * @var int
     */
    protected $_data = [];


    /**
     * Mailable constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->_data = $data;
    }


    /**
     * To build a message via View or anything else
     *
     * @return Mailable
     */
    abstract public function build();


    /**
     * Magic method to set variables
     *
     * @param $property
     * @param $value
     *
     * @return mixed
     */
    public function __set($property, $value)
    {
        return $this->_data[$property] = $value;
    }


    /**
     * Magic method to get variables
     *
     * @param $property
     *
     * @return mixed|null
     */
    public function __get($property)
    {
        return $this->_data[$property] ?? null;
    }
}