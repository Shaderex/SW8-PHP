<?php


function aes_key_gen($length = 256)
{
    return bin2hex(uuid_gen($length / 8));
}

/* Returns Raw Binary */
/**
 * @param int $length
 * @return string
 */
function uuid_gen($length = 32)
{
    $fp = @fopen('/dev/urandom', 'rb');
    $result = '';

    if ($fp !== FALSE) {
        $result .= @fread($fp, $length);
        @fclose($fp);
    } else {
        trigger_error('Can not open /dev/urandom.');
    }

    return $result;
}

