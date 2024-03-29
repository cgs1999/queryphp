<?php

    /**
     * SabreAMF_Serializer 
     * 
     * @package SabreAMF 
     * @version $Id: Serializer.php 248 2009-09-24 19:09:23Z mike.benoit $
     * @copyright Copyright (C) 2006-2009 Rooftop Solutions. All rights reserved.
     * @author Evert Pot (http://www.rooftopsolutions.nl/) 
     * @licence http://www.freebsd.org/copyright/license.html  BSD License (4 Clause) 
     */


    require_once 'SabreAMF/ClassMapper.php';
    require_once 'SabreAMF/OutputStream.php'; 

    /**
     * Abstract Serializer
     *
     * This is the abstract serializer class. This is used by the AMF0 and AMF3 serializers as a base class
     */
    abstract class SabreAMF_Serializer {

        /**
         * stream 
         * 
         * @var SabreAMF_OutputStream 
         */
        protected $stream;

        /**
         * __construct 
         * 
         * @param SabreAMF_OutputStream $stream 
         * @return void
         */
        public function __construct(SabreAMF_OutputStream $stream) {

            $this->stream = $stream;

        }

        /**
         * writeAMFData 
         * 
         * @param mixed $data 
         * @param int $forcetype 
         * @return mixed 
         */
        public abstract function writeAMFData($data,$forcetype=null); 

        /**
         * getStream
         *
         * @return SabreAMF_OutputStream
         */
        public function getStream() {

            return $this->stream;

        }

        /**
         * getRemoteClassName 
         * 
         * @param string $localClass 
         * @return mixed 
         */
        protected function getRemoteClassName($localClass) {

            return SabreAMF_ClassMapper::getRemoteClass($localClass);

        } 

       /**
         * Checks wether the provided array has string keys and if it's not sparse.
         *
         * @param array $arr
         * @return bool
         */
        protected function isPureArray(array $array ) {
            $i=0;
            foreach($array as $k=>$v) {
                if ( $k !== $i ) {
                   return false;
                }
                $i++;
            }

            return true;
        }

    }


