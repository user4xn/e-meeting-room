<?php

function selectEncrypted($field, $table='')
{
    $select = $table != NULL ? '`'.$table.'`.`'.$field.'`' : '`'.$field.'`';

    return "CONVERT(AES_DECRYPT(FROM_bASE64(".$select."), '".substr(hash('sha256', env('APP_KEY', null)), 0, 16)."') USING UTF8MB4)";
}
