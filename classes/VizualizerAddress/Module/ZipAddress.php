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
 * 郵便番号から住所を検索するためのクラスです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAddress_Module_ZipAddress extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        $post = Vizualizer::request();
        if (!empty($post["search_zip"])) {
            if ($params->check("key") && isset($post[$params->get("key")])) {
                $zip1Key = $params->get("zip1", "zip1");
                $zip2Key = $params->get("zip2", "zip2");
                $prefKey = $params->get("prefecture", "prefecture");
                $address1Key = $params->get("address1", "address1");

                // 郵便番号を住所情報に変換
                $loader = new Vizualizer_Plugin("Address");
                $zip = $loader->loadModel("Zip");
                $zip->findByCode($post[$zip1Key] . $post[$zip2Key]);

                // 都道府県をIDに変換
                $prefecture = $loader->loadModel("Prefecture");
                $prefecture->findByName($zip->state);
                $zip->state_id = $prefecture->prefecture_id;

                // 結果を格納
                $post->set($prefKey, $zip->state_id);
                $post->set($address1Key, $zip->city . $zip->town);

                $this->removeInput("search_zip");
                $this->reload();
            }
        }
    }
}
