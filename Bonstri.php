<?php
// all function in this file

class bonstri {

	var $host = 'localhost';
	var $username = 'root';
	var $password = '';
	var $database = 'bonstri';
	var $koneksi;

	public function __construct() {
				$this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database) or die("Failed to Connect Db");
	}

	public function anon($ip,$tanggal) {
		$query = mysqli_query($this->koneksi,"INSERT INTO tb_ipadd VALUES (DEFAULT,'$ip','$tanggal')");
		return $query;
	}

	public function beforeauth($sesi) {
		$query = mysqli_query($this->koneksi,"DELETE FROM tb_users WHERE users = '$sesi'");
		return $query;
	}

	public function auth($sesi,$phone) {
		$query = mysqli_query($this->koneksi,"INSERT INTO tb_users VALUES (DEFAULT,'$sesi','$phone')");
		return $query;
	}

	public function phone($sesi) {
		$query = mysqli_query($this->koneksi,"SELECT * FROM tb_users WHERE users = '$sesi'");
		$data = mysqli_fetch_assoc($query);
		$_SESSION['user'] = $sesi;
		$_SESSION['phone'] = $data['phone'];
	}




	public function Ngecurl($host,$header,$body,$method) {
		$ngecurl = curl_init();
		curl_setopt($ngecurl, CURLOPT_URL, $host);
  		curl_setopt($ngecurl, CURLOPT_HTTPHEADER, $header);	
  		curl_setopt($ngecurl, CURLOPT_HEADER, true);
  		curl_setopt($ngecurl, CURLOPT_CUSTOMREQUEST, $method);
  		curl_setopt($ngecurl, CURLOPT_RETURNTRANSFER, true);
  		curl_setopt($ngecurl, CURLOPT_ENCODING, 'gzip');
  		curl_setopt($ngecurl, CURLOPT_POSTFIELDS, $body);
  		curl_setopt($ngecurl, CURLOPT_COOKIEJAR, 'cookie.txt');
  		curl_setopt($ngecurl, CURLOPT_COOKIEFILE, 'cookie.txt');
  		curl_setopt($ngecurl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ngecurl, CURLOPT_SSL_VERIFYPEER, true);
  		$req = curl_exec($ngecurl);
  		$req = explode("\r\n\r\n", $req);
  		return $req;
	} 

	public function minta_otp($msisdn,$imei) {
		$body = array("msisdn"=>$msisdn);
		$body = json_encode($body);
		$long = strlen($body);
		$header = array(
			"Host:bonstri.tri.co.id" ,
			"Connection:keep-alive" ,
			"Content-Length:" . $long ,
			"Accept:application/json, text/plain, */*" ,
			"Origin:http://bonstri.tri.co.id" ,
			"User-Agent:Mozilla/5.0 (Linux; Android 9; Redmi Note 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.99 Mobile Safari/537.36" ,
			"Content-Type:application/json" ,
			"Referer:http://bonstri.tri.co.id/login?returnUrl=%2Fhome" ,
			"Accept-Encoding:gzip, deflate" ,
			"Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
		);
		$response = $this->Ngecurl('http://bonstri.tri.co.id/api/v1/login/request-otp',$header,$body,'POST');
		return $response;
	}

	public function login($msisdn,$otp) {
		$body = "grant_type".'='."password".'&'."username".'=' . $msisdn . '&'."password".'='.$otp;
		$long = strlen($body);
		$header = array(
			"Host:bonstri.tri.co.id" ,
			"Connection:keep-alive" ,
			"Content-Length:" . $long ,
			"Accept:application/json, text/plain, */*" ,
			"Origin:http://bonstri.tri.co.id" ,
			"Authorization:Basic Ym9uc3RyaTpib25zdHJpc2VjcmV0" ,
			"User-Agent:Mozilla/5.0 (Linux; Android 9; Redmi Note 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.99 Mobile Safari/537.36" ,
			"Content-Type:application/x-www-form-urlencoded" ,
			"Referer:http://bonstri.tri.co.id/login?returnUrl=%2Fhome" ,
			"Accept-Encoding:gzip, deflate" ,
			"Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
		);
		$response = $this->Ngecurl('http://bonstri.tri.co.id/api/v1/login/validate-otp',$header,$body,'POST');
		return $response[1];
	}

	public function gali($harta) {
		$body = '{}';
		$header = array(
			"Host:bonstri.tri.co.id" ,
			"Connection:keep-alive" ,
			"Content-Length:2" ,
			"Accept:application/json, text/plain, */*" ,
			"Origin:http://bonstri.tri.co.id" ,
			"Authorization:Bearer " . $harta,
			"User-Agent:Mozilla/5.0 (Linux; Android 9; Redmi Note 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.99 Mobile Safari/537.36" ,
			"Content-Type:application/json" ,
			"Referer:http://bonstri.tri.co.id/voucherku" ,
			"Accept-Encoding:gzip, deflate" ,
			"Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
		);
		$response = $this->Ngecurl('http://bonstri.tri.co.id/api/v1/voucherku/voucher-history',$header,$body,'POST');
		return $response[1];
	}

	public function ngeloot($harta,$id,$id1) {
		$body = array("rewardId"=>$id1,"rewardTransactionId=>$id");
		$body = json_encode($body);
		$long = strlen($body);
		$header = array(
			"Host:bonstri.tri.co.id" ,
			"Connection:keep-alive" ,
			"Content-Length:" . $long ,
			"Accept:application/json, text/plain, */*" ,
			"Origin:http://bonstri.tri.co.id" ,
			"Authorization:Bearer " . $harta ,
			"User-Agent:Mozilla/5.0 (Linux; Android 9; Redmi Note 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.99 Mobile Safari/537.36" ,
			"Content-Type:application/json" ,
			"Referer:http://bonstri.tri.co.id/voucherku" ,
			"Accept-Encoding:gzip, deflate" ,
			"Accept-Language:id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7"
		);
		$response = $this->Ngecurl("http://bonstri.tri.co.id/api/v1/voucherku/get-voucher-code",$header,$body,'POST');
		return $response[1];
	}

}


?>