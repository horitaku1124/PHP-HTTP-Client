<?php
class HTTPClient{
	const HTTP_PORT = 80;
	const BUFFER_SIZE = 1024;
	private $recieveRest = null;
	public function extractURL($url) {
		$uri = parse_url($url);
		$queryParams = array();
		if(isset($uri["query"])) {
			parse_str($uri["query"], $queryParams);
			$uri["query"] = $queryParams;
		}
		if(!isset($uri["port"])) {
			$uri["port"] = self::HTTP_PORT;
		}
		return $uri;
	}
	public function head($url) {
		$uri = $this->extractURL($url);
		$host = $uri["host"];
		$path = $uri["path"];
		$port = $uri["port"];
		$conn = fsockopen($host, $port, $errno, $errstr, 30);
		if (!$conn) {
			throw new Exception("Error: $errstr ($errno)");
		} else {
			$out = "HEAD $path HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($conn, $out);
			$headers = $this->readHeaders($conn);
			fclose($conn);
			return $headers;
		}
	}
	public function get($url) {
		$uri = $this->extractURL($url);
		$host = $uri["host"];
		$path = $uri["path"];
		$port = $uri["port"];
		$conn = fsockopen($host, $port, $errno, $errstr, 30);
		if (!$conn) {
			throw new Exception("Error: $errstr ($errno)");
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($conn, $out);
			$headers = $this->readHeaders($conn);
			print_r($headers);
			$body = $this->readBody($conn, $headers);
			fclose($conn);
			return $body;
		}
	}
	
	public function readHeaders($conn) {
		$headers = array();
		$buffer = "";
		$isHTTPStatus = true;
		while (!feof($conn)) {
			$str = fgets($conn, self::BUFFER_SIZE);
			//var_dump($str);
			for($i = 0;$i < strlen($str);$i++) {
				if($str[$i] == "\r") {
					$next = ($i + 1) < strlen($str) ? $str[$i + 1] : "";
					if($next == "\n") {
						if($buffer == "") {
							$rest = substr($str, $i + 2);
							if($rest === false || $rest == "") {
								$this->recieveRest = null;
							} else {
								$this->recieveRest = $rest;
							}
							return $headers;
						} else {
							$buffer = trim($buffer);
							if($isHTTPStatus) {
								if(preg_match('/^HTTP\/\d\.\d (\d{3}) ([a-zA-Z ]+)+/', $buffer, $matches)) {
									$headers["HTTP_STATUS"] = $matches[1];
								}
								$isHTTPStatus = false;
							} else {
								$line = explode(": ", $buffer);
								if(count($line) == 2) {
									$headers[$line[0]] = $line[1];
								}
							}
							$buffer = "";
							$i++;
						}
					}
				} else {
					$buffer .= $str[$i];
				}
			}
		}
		return $headers;
	}
	public function readBody($conn, $header) {
		$headers = array();
		$body = "";
		if(isset($this->recieveRest)) {
			$body = $this->recieveRest;
		}
		while (!feof($conn)) {
			$body .= fgets($conn, self::BUFFER_SIZE);
		}
		return $body;
	}
}