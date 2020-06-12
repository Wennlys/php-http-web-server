<?php


namespace Source\App;


class Response
{
    /** @var array */
    protected static array $statusCodes = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    ];

    /** @var int */
    protected int $status = 200;

    /** @var string */
    protected string $body = '';

    /** @var array */
    protected array $headers = [];

    /**
     *
     * @param string $body
     * @param int    $status
     *
     * @return void
     */
    public function __construct($body, $status = null)
    {
      if (!is_null($status)) {
        $this->status = $status;
      }

      $this->body = $body;

      $this->header('Date', gmdate('D, d M Y H:i:s T'));
      $this->header('Content-Type', 'text/html; charset=utf-8');
      $this->header('Server', 'PHPServer');
    }

    /**
     * @param int      $status
     * @return Response
     */
    public static function error($status)
    {
      return new static("<h1>PHPServer: ".$status." - ".static::$statusCodes[$status]."</h1>", $status);
    }

    /**
     * @return string
     */
    public function body()
    {
      return $this->body;
    }

    /**
     * @param string       $key
     * @param string       $value
     * @return void
     */
    public function header($key, $value)
    {
      $this->headers[ucfirst($key)] = $value;
    }

    /**
     * @return string
     */
    public function buildHeaderString()
    {
      $lines = [];

      $lines[] = "HTTP/1.1 " . $this->status . " " . static::$statusCodes[$this->status];

      foreach($this->headers as $key => $value) {
        $lines[] = $key.": ".$value;
      }

      return implode(" \r\n", $lines)."\r\n\r\n";
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
      return $this->buildHeaderString().$this->body();
    }
}
