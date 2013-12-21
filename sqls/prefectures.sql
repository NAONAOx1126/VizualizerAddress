CREATE TABLE IF NOT EXISTS `address_prefectures` (
  `prefecture_id` int(11) NOT NULL COMMENT '都道府県ID',
  `prefecture_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '都道府県名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='都道府県テーブル'