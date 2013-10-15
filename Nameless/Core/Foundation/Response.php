<?php

namespace Nameless\Core\Foundation;

class Response
{
	protected $headers;

	protected $content;

	protected $protocol_version;

	protected $status_code;

	protected $status_message;

	protected $charset;

	protected $statuses = array
	(
		100 => "Continue",
		101 => "Switching Protocols",
		102 => "Processing", // RFC2518
		200 => "OK",
		201 => "Created",
		202 => "Accepted",
		203 => "Non-Authoritative Information",
		204 => "No Content",
		205 => "Reset Content",
		206 => "Partial Content",
		207 => "Multi-Status", // RFC4918
		208 => "Already Reported", // RFC5842
		226 => "IM Used", // RFC3229
		300 => "Multiple Choices",
		301 => "Moved Permanently",
		302 => "Found",
		303 => "See Other",
		304 => "Not Modified",
		305 => "Use Proxy",
		306 => "Reserved",
		307 => "Temporary Redirect",
		308 => "Permanent Redirect", // RFC-reschke-http-status-308-07
		400 => "Bad Request",
		401 => "Unauthorized",
		402 => "Payment Required",
		403 => "Forbidden",
		404 => "Not Found",
		405 => "Method Not Allowed",
		406 => "Not Acceptable",
		407 => "Proxy Authentication Required",
		408 => "Request Timeout",
		409 => "Conflict",
		410 => "Gone",
		411 => "Length Required",
		412 => "Precondition Failed",
		413 => "Request Entity Too Large",
		414 => "Request-URI Too Long",
		415 => "Unsupported Media Type",
		416 => "Requested Range Not Satisfiable",
		417 => "Expectation Failed",
		418 => "I'm a teapot", // RFC2324
		422 => "Unprocessable Entity", // RFC4918
		423 => "Locked", // RFC4918
		424 => "Failed Dependency", // RFC4918
		425 => "Reserved for WebDAV advanced collections expired proposal", // RFC2817
		426 => "Upgrade Required", // RFC2817
		428 => "Precondition Required", // RFC6585
		429 => "Too Many Requests", // RFC6585
		431 => "Request Header Fields Too Large", // RFC6585
		500 => "Internal Server Error",
		501 => "Not Implemented",
		502 => "Bad Gateway",
		503 => "Service Unavailable",
		504 => "Gateway Timeout",
		505 => "HTTP Version Not Supported",
		506 => "Variant Also Negotiates (Experimental)", // RFC2295
		507 => "Insufficient Storage", // RFC4918
		508 => "Loop Detected", // RFC5842
		510 => "Not Extended", // RFC2774
		511 => "Network Authentication Required", // RFC6585
	);

	public function __construct ($content = '', $status_code = 200, array $headers = array(), $protocol_version = '1.0')
	{
		$this
			->setContent($content)
			->setStatusCode($status_code)
			->setProtocolVersion($protocol_version)
			->setHeader($headers);

		if (!$this->hasHeader('Date'))
		{
			$this->setDate(new \DateTime(NULL, new \DateTimeZone('UTC')));
		}
	}

	public function __toString ()
	{
		return
			'HTTP/' . $this->getProtocolVersion() . ' ' . $this->getStatusCode() . ' ' . $this->getStatusMessage() . "\r\n" .
			$this->headers . "\r\n" .
			$this->getContent();
	}

	public function setDate (\DateTime $date)
	{
		$date->setTimezone(new \DateTimeZone('UTC'));
		$this->setHeader('Date', $date->format('D, d M Y H:i:s') . ' GMT');
		return $this;
	}

	public function setHeader ($name, $header = NULL)
	{
		if (is_array($name) && is_null($header))
		{
			$this->headers = $name;
		}
		elseif (!is_null($header))
		{
			$this->headers[$name] = $header;
		}
		else
		{
			throw new \InvalidArgumentException('The method arguments is not valid.');
		}
		return $this;
	}

	public function getHeader ($name = NULL)
	{
		if (!is_null($name))
		{
			return $this->headers[$name];
		}
		return $this->headers;
	}

	public function hasHeader ($name)
	{
		return array_key_exists($name, $this->headers);
	}

	public function setContent ($content)
	{
		$this->content = (string)$content;
		return $this;
	}

	public function getContent ()
	{
		return $this->content;
	}

	public function setProtocolVersion ($version)
	{
		$this->protocol_version = $version;
		return $this;
	}

	public function getProtocolVersion ()
	{
		return $this->protocol_version;
	}

	public function setStatusCode ($code, $message = '')
	{
		$this->status_code = (integer)$code;

		if (100 > $this->status_code || 600 <= $this->status_code)
		{
			throw new \InvalidArgumentException(sprintf('The HTTP status code "%s" is not valid.', $code));
		}

		if (!$message)
		{
			$this->status_message = isset(self::$statuses[$this->status_code]) ? self::$statuses[$this->status_code] : '';
			return $this;
		}

		$this->status_message = (string)$message;
		return $this;
	}

	public function getStatusCode ()
	{
		return $this->status_code;
	}

	public function getStatusMessage ()
	{
		return $this->status_message;
	}
}
