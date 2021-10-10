<?php

class FileStreamer
{

    private $pointer = NULL;
    private $buffer  = NULL;
    
    public function __construct($path)
    {
        if ( file_exists($path) )
        {
            $this->buffer = file_get_contents($path);
            $this->pointer = 0;
        }
    }

    public function read_match( $pattern )
    {
        for ( $i = 0; $i < strlen($pattern); ++$i )
        {
            if ( $pattern[$i] !== $this->get_c() )
            {
                $this->put_back($i);
                return false;
            }
        }
        return true;
    }

    public function read_until($pattern)
    {
        if ( !$pattern || strlen($pattern) === 0 )
            return '';

        $str = '';
        while ( ($ch = $this->get_c()) !== NULL )
        {
            if ($ch === $pattern[0])
            {
                $this->put_back();
                if ( $this->read_match( $pattern ) )
                    return $str;
            }
                
            $str .= $ch;
        }

        return null;
    }

    public function skip_until($pattern)
    {
        if ( !$pattern || strlen($pattern) === 0 )
            return;

        while ( ($ch = $this->get_c()) !== NULL )
        {
            if ($ch === $pattern[0])
            {
                $this->put_back();
                if ( $this->read_match( $pattern ) )
                    return;
            }
        }
    }
    
    public function put_back($n = 1)
    {
        // not tested yet.
        if ( $n < 0  )
            $n = 0;

        if ( $this->pointer - $n < 0 )
            $this->pointer = 0;

        $this->pointer -= $n;
    } 

    public function eof()
    {
        return $this->pointer >= strlen($this->buffer);
    }

    public function get_c() {
        if ( $this->eof() )
            return null;

        return $this->buffer[$this->pointer++];
    }
} // class FileStreamer