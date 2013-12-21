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
 * 都道府県のデータモデルです。。
 *
 * @package VizualizerAddress
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAddress_Model_Prefecture extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     */
    function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("address");
        parent::__construct($loader->loadTable("Prefectures"), $values);
    }

    /**
     * 主キーでデータを検索する。
     */
    function findByPrimaryKey($prefecture_id)
    {
        $this->findBy(array("prefecture_id" => $prefecture_id));
    }

    /**
     * 都道府県名でデータを検索する。
     */
    function findByName($prefecture_name)
    {
        $this->findBy(array("prefecture_name" => $prefecture_name));
    }

    /**
     * モデル自体を都道府県の名前文字列として扱えるようにする。
     */
    function __toString()
    {
        return $this->prefecture_name;
    }
}
