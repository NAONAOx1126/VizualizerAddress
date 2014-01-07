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

// プラグインの初期化
VizualizerAddress::initialize();

/**
 * プラグインの設定用クラス
 *
 * @package VizualizerAddress
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAddress
{

    /**
     * プラグインの初期化処理を行うメソッドです。
     */
    final public static function initialize()
    {
    }

    /**
     * データベースインストールの処理を行うメソッド
     */
    final public static function install()
    {
        VizualizerAddress_Table_Prefectures::install();
        VizualizerAddress_Table_Zips::install();

        // 最大実行時間を無制限に変更
        ini_set("max_execution_time", 0);

        $connection = Vizualizer_Database_Factory::begin("address");
        try {
            $loader = new Vizualizer_Plugin("Address");
            // 郵便番号データをクリア
            $zips = $loader->loadTable("Zips");
            $truncate = new Vizualizer_Query_Truncate($zips);
            $truncate->execute();

            if(($fp = fopen(dirname(__FILE__) . "/../sqls/KEN_ALL.CSV", "r")) !== FALSE){
                $insert = new Vizualizer_Query_Insert($zips);
                // 郵便番号データを登録
                while(($line = fgets($fp)) !== FALSE){
                    $data = explode(",", str_replace("\"", "", trim(mb_convert_encoding($line, "UTF-8", "Shift_JIS"))));
                    $sqlval = array();
                    $sqlval["code"] = $data[0];
                    $sqlval["old_zipcode"] = $data[1];
                    $sqlval["zipcode"] = $data[2];
                    $sqlval["state_kana"] = $data[3];
                    $sqlval["city_kana"] = $data[4];
                    $sqlval["town_kana"] = $data[5];
                    $sqlval["state"] = $data[6];
                    $sqlval["city"] = $data[7];
                    $sqlval["town"] = $data[8];
                    $sqlval["flg1"] = $data[9];
                    $sqlval["flg2"] = $data[10];
                    $sqlval["flg3"] = $data[11];
                    $sqlval["flg4"] = $data[12];
                    $sqlval["flg5"] = $data[13];
                    $sqlval["flg6"] = $data[14];
                    $result = $insert->execute($sqlval);
                }
            }
            // 都道府県データの削除
            $prefectures = $loader->loadTable("Prefectures");
            $truncate = new Vizualizer_Query_Truncate($prefectures);
            $truncate->execute();

            // 都道府県データを郵便番号データから自動生成
            $sql = "INSERT INTO ".$prefectures."(prefecture_id, prefecture_name) ";
            $sql .= "SELECT SUBSTRING(".$zips->code.", 1, 2), ".$zips->state." FROM ".$zips;
            $sql .= " WHERE ".$zips->flg3." = 0 GROUP BY ".$zips->state." ORDER BY ".$zips->code;
            $connection->query($sql);

            Vizualizer_Database_Factory::commit($connection);
        } catch (Exception $e) {
            Vizualizer_Database_Factory::rollback($connection);
        }

    }
}
