<?php

class NullClass {} // class NullClass

class ki_scheme
{

    private $scheme_name;
    private $scheme_filename;
    private $callback;

    public function __construct($scheme_name, $scheme_filename, $callback)
    {
        $this->scheme_name = $scheme_name;
        $this->scheme_filename = $scheme_filename;
        $this->callback = $callback;
    }

    public function Invoke( $args ) {
        if ( $this->callback )
            $this->callback->call(new NullClass(), $args[0]);
    }

    public function getSchemeName() {
        return $this->scheme_name;
    }

    public function getSchemeFileName() {
        return $this->scheme_filename;
    }

    public function getCallback() {
        return $this->callback;
    }
} // class ki_scheme