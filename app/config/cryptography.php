<?php
require_once DIR_PATH . 'init.php';

class Base62 {
    private static $charset = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Encode data to Base62
    public static function encode($data) {
        $value = gmp_init(bin2hex($data), 16);
        $result = '';
        $base = strlen(self::$charset);
        while (gmp_cmp($value, 0) > 0) {
            $index = gmp_intval(gmp_mod($value, $base));
            $result = self::$charset[$index] . $result;
            $value = gmp_div_q($value, $base);
        }
        return $result;
    }

    // Decode Base62 data
    public static function decode($data) {
        $value = gmp_init(0);
        $base = strlen(self::$charset);
        for ($i = 0; $i < strlen($data); $i++) {
            $value = gmp_add(gmp_mul($value, $base), strpos(self::$charset, $data[$i]));
        }

        $hex = gmp_strval($value, 16);

        // Pastikan panjang hasil heksadesimal genap
        if (strlen($hex) % 2 !== 0) {
            $hex = '0' . $hex; // Tambahkan 0 di depan jika tidak genap
        }

        return hex2bin($hex);
    }
}

class Cryptography {
    private $key;
    private $cipher;
    private $ivLength;

    public function __construct() {
        $key = 'security_management_system.anwarsptr.com';
        $this->key = hash('sha256', $key, true);
        $this->cipher = 'AES-256-CBC';
        $this->ivLength = openssl_cipher_iv_length($this->cipher);
    }

    // Encrypt function
    public function encrypt($data) {
        $iv = openssl_random_pseudo_bytes($this->ivLength);
        $encrypted = openssl_encrypt($data, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        return Base62::encode($iv . $encrypted); // Encode with Base62
    }

    // Decrypt function
    public function decrypt($encryptedData) {
        $data = Base62::decode($encryptedData); // Decode Base62
        $iv = substr($data, 0, $this->ivLength);
        $encrypted = substr($data, $this->ivLength);
        return openssl_decrypt($encrypted, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
    }
}

function encrypt($string) {
  $crypt = new Cryptography();
  return $crypt->encrypt($string);
}

function decrypt($string) {
  $crypt = new Cryptography();
  return $crypt->decrypt($string);
}
?>
