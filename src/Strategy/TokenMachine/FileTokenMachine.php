<?php

namespace Fruitsbytes\PHP\MonCash\Strategy\TokenMachine;

use Exception;
use Fruitsbytes\PHP\MonCash\Strategy\OrderIdGenerator\OrderIdGeneratorException;
use Fruitsbytes\PHP\MonCash\Strategy\StrategyException;

class FileTokenMachine implements TokenMachineInterface
{

    const FILE_NAME = 't.ii';


    /**
     * @throws TokenMachineException
     * @throws StrategyException
     */
    public function __construct(
        public int $expiresIn = 59,
    ) {
        $this->check();
    }

    /**
     * @inheritdoc
     * @throws TokenMachineException
     */
    public function check(bool $thorough = false): bool
    {
        /**
         * Check if all required PHP functions are available.
         * - Some Host may be using a trimmed down version of PHP.
         * - In the future one of the function may be deprecated/removed
         */
        foreach (['touch', 'unlink', 'filectime', 'strtotime'] as $fn) {
            if (function_exists($fn) === false) {
                throw new TokenMachineException("Missing required function $fn");
            }
        }

        if (extension_loaded('openssl') === false) {
            throw new TokenMachineException("Missing required extension: ext-openssl");
        }

        if ($thorough) {
            try {
                $this->store('monCash_file_test');
                $this->clear();
            } catch (Exception $e) {
                throw new OrderIdGeneratorException("Could not write to temp folder.", 0, $e);
            }
        }

        return true;
    }

    /** @inheritdoc */
    public function getToken(bool $new = false): string|bool
    {
        if ($new) {
            return false;
        }


        try {
            $fz       = sys_get_temp_dir().'/';
            $filename = sys_get_temp_dir().self::FILE_NAME;

            if (file_exists($filename)) {

                if ($this->isTokenExpired($filename)) {
                    return false;
                }

                $file = fopen($filename, "r");

                $token = $this->decrypt(fread($file, filesize($fz)));
                fclose($file);

                return $token ?? false;
            } else {
                return false;
            }
        } catch (Exception) {
            return false;
        }
    }


    /**
     * Since the File strategy is not keeping a log of past used token, we do not
     * over-engineer it to check the token validity.
     *
     * @deprecated
     * @inheritdoc
     */
    public function isTokenValid(string $token): bool
    {
        return false;
    }

    /** @inheritdoc */
    public function isTokenExpired(string $filename): bool|int
    {
        $creationDate = filectime($filename);
        $cursor       = strtotime("-$this->expiresIn second");

        return $cursor < $creationDate;
    }

    public function store(string $token)
    {
        $fp = fopen(sys_get_temp_dir().'/', self::FILE_NAME, 'w');
        fwrite($fp, $this->encrypt($token));
        fclose($fp);
    }

    public function clear()
    {
        unlink(sys_get_temp_dir().'/'.self::FILE_NAME);
    }

    public function encrypt(string $token): string
    {
        $encryptionKey   = $_ENV['MONCASH_CLIENT_SECRET'];
        $keysalt         = openssl_random_pseudo_bytes(16);
        $key             = hash_pbkdf2("sha512", $encryptionKey, $keysalt, 20000, 32, true);
        $iv              = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-gcm"));
        $tag             = "";
        $encryptedstring = openssl_encrypt($token, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag, "", 16);

        return base64_encode($keysalt.$iv.$encryptedstring.$tag);
    }

    public function decrypt(string $encrypted)
    {
        $encryptionKey   = $_ENV['MONCASH_CLIENT_SECRET'];
        $encryptedstring = base64_decode($encrypted);
        $keysalt         = substr($encryptedstring, 0, 16);
        $key             = hash_pbkdf2("sha512", $encryptionKey, $keysalt, 20000, 32, true);
        $ivlength        = openssl_cipher_iv_length("aes-256-gcm");
        $iv              = substr($encryptedstring, 16, $ivlength);
        $tag             = substr($encryptedstring, -16);
        $data            = substr($encryptedstring, 16 + $ivlength, -16);

        return openssl_decrypt($data, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
    }

}
