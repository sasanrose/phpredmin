<?php

/*
 * This file is part of the "PHP Redis Admin" package.
 *
 * (c) Faktiva (http://faktiva.com)
 *
 * NOTICE OF LICENSE
 * This source file is subject to the CC BY-SA 4.0 license that is
 * available at the URL https://creativecommons.org/licenses/by-sa/4.0/
 *
 * DISCLAIMER
 * This code is provided as is without any warranty.
 * No promise of being safe or secure
 *
 * @author   Sasan Rose <sasan.rose@gmail.com>
 * @author   Emiliano 'AlberT' Gabrielli <albert@faktiva.com>
 * @license  https://creativecommons.org/licenses/by-sa/4.0/  CC-BY-SA-4.0
 * @source   https://github.com/faktiva/php-redis-admin
 */

class Info_Model extends Model
{
    public function getDbs($info)
    {
        $result = array();
        $keys   = array_keys($info);
        $dbs    = preg_grep('/^db[0-9]+?$/', $keys);

        foreach ($dbs as $db) {
            if (preg_match('/^db([0-9]+)$/', $db, $matches)) {
                $result[] = $matches[1];
            }
        }

        return $result;
    }
}
