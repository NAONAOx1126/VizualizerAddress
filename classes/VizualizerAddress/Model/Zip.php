<?php

/**
 * Copyright (C) 2012 Vizualizer All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Naohisa Minagawa <info@vizualizer.jp>
 * @copyright Copyright (c) 2010, Vizualizer
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
 */

/**
 * 郵便番号のデータモデルです。
 *
 * @package VizualizerAddress
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAddress_Model_Zip extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     */
    function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("address");
        parent::__construct($loader->loadTable("Zips"), $values);
    }

    /**
     * 郵便番号でデータを検索する。
     */
    function findByCode($code)
    {
        $this->findBy(array("zipcode" => $code));
        if ($this->town == "以下に掲載がない場合") {
            $this->town = "";
            $this->town_kana = "";
        }
    }

    /**
     * モデル自体を漢字住所の文字列として扱えるようにする。
     */
    function __toString()
    {
        return $this->state . $this->city . $this->town;
    }
}
