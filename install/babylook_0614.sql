-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017 年 6 月 14 日 21:21
-- サーバのバージョン： 5.5.52-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `babylook`
--
DROP DATABASE `babylook`;
CREATE DATABASE IF NOT EXISTS `babylook` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `babylook`;

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--
-- 作成日時： 2017 年 4 月 25 日 06:04
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `body` text CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_number` text CHARACTER SET utf8 COMMENT 'ユニークなイメージ番号',
  `img_ext` varchar(12) CHARACTER SET utf8 DEFAULT NULL COMMENT '拡張子',
  `img_size` int(11) unsigned DEFAULT NULL COMMENT '容量',
  `original_name` text COLLATE utf8_unicode_ci COMMENT '元々のファイル名'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- テーブルのデータのダンプ `articles`
--

INSERT INTO `articles` (`id`, `title`, `body`, `created`, `modified`, `user_id`, `image_number`, `img_ext`, `img_size`, `original_name`) VALUES
(1, '投稿1', '2', '2017-04-28 13:51:36', '2017-04-28 13:51:36', 1, '445ae8f8dc7e1353a7f64fdac87eb552', 'jpg', 30916, '02.jpg'),
(2, 'test haruka', 'haruka', '2017-04-30 04:07:11', '2017-04-30 04:07:11', 3, 'd53517123650bd48e5691ac71346e205', 'jpg', 587895, '04.jpg');

-- --------------------------------------------------------

--
-- テーブルの構造 `mission_masters`
--
-- 作成日時： 2017 年 6 月 06 日 07:49
--

DROP TABLE IF EXISTS `mission_masters`;
CREATE TABLE IF NOT EXISTS `mission_masters` (
  `id` int(255) NOT NULL COMMENT 'ミッションの通し番号。各ミッションの開始、終了はこの番号で見る',
  `mission_name` varchar(45) CHARACTER SET utf8 NOT NULL COMMENT 'ミッションの表示名\nEX：掲示板に自己紹介文を書こう！など',
  `mission_description` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT 'ミッションの実行方法がここに書き込まれる。\nex:掲示板にアクセスして、自己紹介文を１００文字以上で書こう！そうするとギャラリーが閲覧できるようになるよ！',
  `mission_type` int(11) DEFAULT NULL COMMENT 'ミッションの属性「他人に干渉する物なら1、自分自身の行動なら2、投稿系なら3、感想系なら4」等のように準備して、ミッション表示時のアイコンなどに使えるようにする。\n将来的な拡張を考え、現在はnull可能としておく',
  `mission_want_progres` int(11) NOT NULL DEFAULT '1' COMMENT 'ミッション完了と看做すのに必要な行動回数',
  `created` datetime DEFAULT NULL COMMENT 'ミッション設定日時',
  `modified` datetime DEFAULT NULL COMMENT 'ミッション変更日時（使わないかも）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザーに課せられるミッションの表示名と説明文、通し番号';

--
-- テーブルのデータのダンプ `mission_masters`
--

INSERT INTO `mission_masters` (`id`, `mission_name`, `mission_description`, `mission_type`, `mission_want_progres`, `created`, `modified`) VALUES
(1, '作品一覧を見てみよう', 'BabylookPortal2へようこそ！まずはメニューの作品一覧リンクをクリックして、投稿されている作品をチェックしましょう！', 2, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `scores`
--
-- 作成日時： 2017 年 4 月 27 日 06:01
--

DROP TABLE IF EXISTS `scores`;
CREATE TABLE IF NOT EXISTS `scores` (
  `id` int(10) unsigned NOT NULL,
  `exam_user_id` int(10) unsigned NOT NULL,
  `target_user_id` int(10) unsigned NOT NULL,
  `articles_id` int(10) unsigned NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='イイネされた時のログ';

--
-- テーブルのデータのダンプ `scores`
--

INSERT INTO `scores` (`id`, `exam_user_id`, `target_user_id`, `articles_id`, `created`) VALUES
(1, 2, 1, 1, NULL),
(2, 2, 1, 1, NULL),
(3, 2, 1, 1, NULL),
(4, 2, 1, 1, NULL),
(5, 2, 1, 1, NULL),
(6, 1, 1, 1, NULL),
(7, 1, 1, 1, NULL),
(8, 1, 1, 1, NULL),
(9, 1, 1, 1, NULL),
(10, 1, 1, 1, NULL),
(11, 3, 1, 1, '2017-04-30 02:32:58'),
(12, 3, 1, 1, '2017-04-30 04:07:30'),
(13, 3, 1, 1, '2017-04-30 04:07:55'),
(14, 3, 1, 1, '2017-04-30 04:08:21'),
(15, 3, 1, 1, '2017-04-30 04:08:29');

-- --------------------------------------------------------

--
-- テーブルの構造 `trophies`
--
-- 作成日時： 2017 年 4 月 28 日 12:02
--

DROP TABLE IF EXISTS `trophies`;
CREATE TABLE IF NOT EXISTS `trophies` (
  `id` int(10) unsigned NOT NULL COMMENT '連番',
  `give_score` int(11) DEFAULT NULL COMMENT 'イイネを与えた回数',
  `take_score` int(11) DEFAULT NULL COMMENT 'イイネを貰った回数',
  `trophie_name` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT 'トロフィーの名称'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='トロフィーの種類を管理するマスターテーブル';

--
-- テーブルのデータのダンプ `trophies`
--

INSERT INTO `trophies` (`id`, `give_score`, `take_score`, `trophie_name`) VALUES
(1, 5, 0, 'レビュアーLv1'),
(2, 0, 5, 'クリエイターLv1'),
(3, 5, 5, 'アクティブユーザーLv1'),
(4, 0, 10, 'クリエイターLv2');

-- --------------------------------------------------------

--
-- テーブルの構造 `user_mission_statuses`
--
-- 作成日時： 2017 年 6 月 06 日 08:17
--

DROP TABLE IF EXISTS `user_mission_statuses`;
CREATE TABLE IF NOT EXISTS `user_mission_statuses` (
  `id` int(10) unsigned NOT NULL,
  `mission_id` int(10) unsigned NOT NULL COMMENT 'ミッションの番号（要、マスタテーブル確認）',
  `user_id` int(10) unsigned NOT NULL COMMENT 'このミッションがどのユーザーに割り当てられているのか',
  `mission_progress` tinyint(3) DEFAULT '0' COMMENT 'このミッションの進捗度。カウントアップ時にマスタ側で指定した回数と同時になった場合、mission_completedを1にする（必須）',
  `mission_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL COMMENT '発行日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日'
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザーにミッションが発行されているか、進行中か、完了済みかをチェックする。（要、プログラム側での発行順序協議）';

--
-- テーブルのデータのダンプ `user_mission_statuses`
--

INSERT INTO `user_mission_statuses` (`id`, `mission_id`, `user_id`, `mission_progress`, `mission_completed`, `created`, `modified`) VALUES
(1, 1, 1, 1, 1, NULL, NULL),
(2, 1, 2, 0, 0, NULL, NULL),
(3, 1, 12, 0, 0, NULL, NULL),
(4, 1, 13, 0, 0, NULL, NULL),
(5, 1, 14, 0, 0, NULL, NULL),
(6, 1, 15, 0, 0, NULL, NULL),
(7, 1, 16, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--
-- 作成日時： 2017 年 6 月 14 日 12:06
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `oauth_token` varchar(255) DEFAULT NULL,
  `oauth_token_secret` varchar(255) DEFAULT NULL,
  `twittter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created`, `modified`, `mail`, `oauth_token`, `oauth_token_secret`, `twittter_id`) VALUES
(1, 'root', '$2y$10$1YGYEXpx3yTyErfsP4gA6.vssx9xYPffGCz6AyuFUbh2uY0S3MELK', 'admin', NULL, NULL, 'saki.kirarinhouse@gmail.com', NULL, NULL, NULL),
(2, 'saki', '$2y$10$iG0BRskhfSEPXyjwXnTghueIb2qVqHmB5vltneZsEqOh8NWwR3ZmO', 'author', NULL, NULL, 'test2@test.com', NULL, NULL, NULL),
(12, 'あいうえお', '$2y$10$51hOQGD8n2Fhdb624uNZI.MYn393Jtam/.vvAjqjAOZjyAnsxj9ca', 'admin', NULL, NULL, 'test3@test.com', NULL, NULL, NULL),
(13, 'ほげらった', '$2y$10$j1i2b6ULPUnJr0EIQzLRBeDEDY2PF2.9eFChOmrgr5ibGjGntPJFy', 'admin', NULL, NULL, 'test4@test.com', 'hogehogehogehoge', NULL, NULL),
(14, 'ほげらった２', '$2y$10$AZQakHmhH1lIIC8kZe4ywuDOjDvx..ijRk5f1SalkuPRkoihxyR7.', 'admin', NULL, NULL, 'tes5@test.com', 'hogehogehogehoge', 'hogehogehogehoge', NULL),
(15, 'dfsfsdfsdf', '$2y$10$2qlV49WCWP/Ngxxu81JEb.G9yjtFF2hZ3ctnLeL8D.kl88MPqn2eK', 'admin', NULL, NULL, 'test6@test.com', 'hogehogehogehoge', 'hogehogehogehoge', NULL),
(16, 'hogehoge', '$2y$10$DoWFz2DXhBTCMXD9mh7GeeKCGIt18ZCuujQq2CrfnBMhsKQfMh8jm', 'admin', NULL, NULL, '', NULL, NULL, NULL),
(19, '99999@99999', '$2y$10$KzSfxBCXtw7TSaN1VgCw.OpUbSUVtwnQPzrQGCFSpSSSjZbxsnFs.', 'admin', NULL, NULL, '', NULL, NULL, NULL),
(20, 'hhhh', '$2y$10$Nncyi/EJqPOVzNgW0UUEt.XU3ApzHTas3s.r06GnJjKc.RSoCGvcK', 'admin', NULL, NULL, '', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mission_masters`
--
ALTER TABLE `mission_masters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_user_id` (`exam_user_id`,`target_user_id`,`articles_id`);

--
-- Indexes for table `trophies`
--
ALTER TABLE `trophies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_mission_statuses`
--
ALTER TABLE `user_mission_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `trophies`
--
ALTER TABLE `trophies`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '連番',AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_mission_statuses`
--
ALTER TABLE `user_mission_statuses`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
