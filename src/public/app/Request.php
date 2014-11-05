<?php

class Request
{

    /**
     *
     * @var string
     */
    private $uri;

    /**
     * GET, POST, PUT , DELETE
     * 
     * @var string
     */
    private $method;

    /**
     *
     * @var array
     */
    private $params = array();
    /**
     * 
     * @var unknown
     */
    private $xhr=false;

    public function __construct()
    {
        $this->setUri(
            'http:// ' . 
            $_SERVER['HTTP_HOST'] . 
            $_SERVER['REQUEST_URI']
            );
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->params = $_REQUEST;
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $this->xhr = true;
        }
    }

    /**
     *
     * @return the $uri
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     *
     * @param string $uri            
     */
    public function setUri($uri)
    {
        $urlParts = parse_url($uri);
        $this->uri = trim($urlParts['path'], '/');
    }

    /**
     *
     * @return the $method
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     *
     * @param string $method            
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     *
     * @return the $params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     *
     * @param string $index            
     * @throws InvalidArgumentException if $index unknow
     * @return multitype:
     */
    public function getParam($index)
    {
        if (! array_key_exists($index, $this->params)) {
            throw new InvalidArgumentException("The index: $index does not exist !");
        }
        return $this->params[$index];
    }

    /**
     *
     * @param multitype: $params            
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
    /**
     * 
     * @return boolean
     */
    public function isXhr()
    {
        return (bool) $this->xhr;
    }
}