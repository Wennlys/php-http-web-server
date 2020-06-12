<?php


namespace Source\App;


class Request
{
    /** @var string */
    protected $method = null;
    
    /** @var string */
    protected ?string $uri = null;
    
    /** @var array */
    protected array $parameters = [];
    
    /** @var array */
    protected array $headers = [];

    /**
     * Create new request instance using a string header
     *
     * @param string $header
     *
     * @return Request
     */
    public static function withHeaderString($header)
    {
       $lines = explode("\n", $header);
       [ $method, $uri ] = explode(' ', array_shift($lines));
       
       $headers = [];
       
       foreach($lines as $line) {
           $line = trim($line);
    
           if (strpos($line, ': ') !== false) {
               [$key, $value] = explode(': ', $line);
               $headers[$key] = $value;
           }
       }
       
       return new static($method, $uri, $headers);
    }
    
    /**
     * Request constructor
     *
     * @param string          $method
     * @param string          $uri
     * @param array          $headers
     * @return void
     */
    public function __construct($method, $uri, $headers = [])
    {
       $this->headers = $headers;
       $this->method = strtoupper($method);
       
    
       @list($this->uri, $params) = explode('?', $uri);
       parse_str($params, $this->parameters);
    }
    
    /**
     * Return the request method
     *
     * @return string
     */
    public function method()
    {
       return $this->method;
    }
    
    /**
     * Return the request uri
     *
     * @return string
     */
    public function uri()
    {
       return $this->uri;
    }
    
    /**
     * Return a request header
     *
     * @param      $key
     * @param null $default
     *
     * @return string
     */
    public function header($key, $default = null)
    {
       if (!isset($this->headers[$key])) {
          return $default;
       }
       
       return $this->headers[$key];
    }
    
    /**
     * Return a request parameter
     *
     * @param      $key
     * @param null $default
     *
     * @return string
     */
    public function param($key, $default = null)
    {
       if (!isset($this->parameters[$key])) {
          return $default;
       }
       
       return $this->parameters[$key];
    }
}
