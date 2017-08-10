<?php

namespace ZanPHP\ThriftSerialization;

use Kdt\Iron\Nova\Foundation\Traits\InstanceManager;


class Packer
{
    const CLIENT = 1;
    const SERVER = 2;

    /*
     * Instance mgr
     */
    use InstanceManager;

    /**
     * @var string
     */
    public $successKey = 'success';

    /**
     * @var Packer
     */
    private $packer = null;

    /**
     * Packer constructor.
     */
    public function __construct()
    {
        $this->packer = new Native();
    }

    /**
     * @param $successStruct
     * @param $exceptionStruct
     * @param $successData
     * @param $exceptionData
     * @return array
     */
    public function struct($successStruct, $exceptionStruct, $successData = null, $exceptionData = null)
    {
        // init success struct
        $successStruct['var'] = $this->successKey;
        $successStruct['value'] = $successData;

        if (is_array($exceptionStruct)) {
            // init exception struct
            foreach ($exceptionStruct as $eK => $eSpec) {
                if (is_object($exceptionData) && $eSpec['class'] === '\\'.get_class($exceptionData)) {
                    $exceptionStruct[$eK]['value'] = $exceptionData;
                } else {
                    $exceptionStruct[$eK]['value'] = null;
                }
            }
        } elseif (null === $exceptionData){

        } else {
            $exceptionStruct = [];
        }

        // merge struct
        return array_merge([$successStruct], $exceptionStruct);
    }

    /**
     * @param $type
     * @param $name
     * @param $args
     * @param $side
     * @return string
     */
    public function encode($type, $name, $args, $side)
    {
        return $this->packer->encode($type, $name, $args, $side);
    }

    /**
     * @param $data
     * @param $args
     * @param $side
     * @return array
     */
    public function decode($data, $args, $side)
    {
        return $this->packer->decode($data, $args, $side);
    }
}