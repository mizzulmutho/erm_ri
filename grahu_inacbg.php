<?php
/// make binary representasion of $key
function inacbg_encrypt($data, $key) {
	$key = hex2bin($key);

/// check key length, must be 256 bit or 32 bytes
	if (mb_strlen($key,"8bit") !== 32) {
		throw new Exception("Needs a 256-bit key!");
	}

/// create initialization vector
	$iv_size = openssl_cipher_iv_length("aes-256-cbc");
//$iv = random_bytes($iv_size); // dengan catatan dibawah
$iv = openssl_random_pseudo_bytes($iv_size); // dengan catatan dibawah
/// encrypt
$encrypted = openssl_encrypt($data,"aes-256-cbc",$key,OPENSSL_RAW_DATA,$iv );
/// create signature, against padding oracle attacks
$signature = mb_substr(hash_hmac("sha256",$encrypted,$key,true),0,10,"8bit");
/// combine all, encode, and format
$encoded = chunk_split(base64_encode($signature.$iv.$encrypted));
return $encoded;
}

// Decryption Function
function inacbg_decrypt($str, $strkey){
/// make binary representation of $key
	$key = hex2bin($strkey);
/// check key length, must be 256 bit or 32 bytes
	if (mb_strlen($key, "8bit") !== 32) {
		throw new Exception("Needs a 256-bit key!");
	}

/// calculate iv size
	$iv_size = openssl_cipher_iv_length("aes-256-cbc");
/// breakdown parts
	$decoded = base64_decode($str);
	$signature = mb_substr($decoded,0,10,"8bit");
	$iv = mb_substr($decoded,10,$iv_size,"8bit");
	$encrypted = mb_substr($decoded,$iv_size+10,NULL,"8bit");
/// check signature, against padding oracle attack
	$calc_signature = mb_substr(hash_hmac("sha256",$encrypted,$key,true),0,10,"8bit");
	if(!inacbg_compare($signature,$calc_signature)) {
return "SIGNATURE_NOT_MATCH"; /// signature doesn't match
}
$decrypted = openssl_decrypt($encrypted,"aes-256-cbc",$key,OPENSSL_RAW_DATA,$iv);
return $decrypted;
}

/// Compare Function
function inacbg_compare($a, $b) {
/// compare individually to prevent timing attacks
/// compare length
	if (strlen($a) !== strlen($b)) return false;
/// compare individual
	$result = 0;
	for($i = 0; $i < strlen($a); $i ++) {
		$result |= ord($a[$i]) ^ ord($b[$i]);
	}
	return $result == 0;
}

// contoh encryption key, bukan aktual
$key = "88b36171b9c2f71a43e81fd6ac71d4b4f18b2c263f4fc78a202acd8e578a871e";
//$url_ina = "http://192.168.11.44/E-Klaim/ws.php";
$url_ina = "http://192.168.10.112/E-Klaim/ws.php";
?>