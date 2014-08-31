<?php

/*
 * Copyright (C) 2014 Nicolas Passaquet <nicolas.passaquet@gmail.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace Algorithm\ManagerBundle\Crypto;

/**
 * Description of AlgoCrypt
 *
 */
class AlgoCrypt
{
    private $key        = NULL;
    private $iv         = NULL;
    private $iv_size    = NULL;

    public function init($key = "")
    {
        $this->key = ($key != "") ? $key : "";

        $this->algorithm = MCRYPT_DES;
        $this->mode = MCRYPT_MODE_ECB;

        $this->iv_size = mcrypt_get_iv_size($this->algorithm, $this->mode);
        $this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);

        return "ok";
    }

    public function encrypt($data)
    {
        $size = mcrypt_get_block_size($this->algorithm, $this->mode);
        $data = $this->pkcs5_pad($data, $size);
        return base64_encode(mcrypt_encrypt($this->algorithm, $this->key, $data, $this->mode, $this->iv));
    }

    public function decrypt($data)
    {
        return $this->pkcs5_unpad(rtrim(mcrypt_decrypt($this->algorithm, $this->key, base64_decode($data), $this->mode, $this->iv)));
    }

    private function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }
}
