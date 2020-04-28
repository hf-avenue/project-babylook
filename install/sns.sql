-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2020 年 4 朁E28 日 14:11
-- サーバのバージョン： 10.1.31-MariaDB
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sns`
--
CREATE DATABASE IF NOT EXISTS `sns` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sns`;

-- --------------------------------------------------------

--
-- テーブルの構造 `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `body` text CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_number` text CHARACTER SET utf8 COMMENT 'ユニークなイメージ番号',
  `img_ext` varchar(12) CHARACTER SET utf8 DEFAULT NULL COMMENT '拡張子',
  `img_size` int(11) UNSIGNED DEFAULT NULL COMMENT '容量',
  `original_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '元々のファイル名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `articles`
--

INSERT INTO `articles` (`id`, `title`, `body`, `created`, `modified`, `user_id`, `image_number`, `img_ext`, `img_size`, `original_name`) VALUES(2, 'テスト画像', 'これは\r\nテストです', '2020-04-28 03:33:40', '2020-04-28 03:33:40', 3, '51c3d42bb9b141b758cea4cab79c0aa5', 'png', 1555525, '01.png');

-- --------------------------------------------------------

--
-- テーブルの構造 `mission_masters`
--

DROP TABLE IF EXISTS `mission_masters`;
CREATE TABLE `mission_masters` (
  `id` int(255) NOT NULL COMMENT 'ミッションの通し番号。各ミッションの開始、終了はこの番号で見る',
  `mission_name` varchar(45) CHARACTER SET utf8 NOT NULL COMMENT 'ミッションの表示名\nEX：掲示板に自己紹介文を書こう！など',
  `mission_description` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'ミッションの実行方法がここに書き込まれる。\nex:掲示板にアクセスして、自己紹介文を１００文字以上で書こう！そうするとギャラリーが閲覧できるようになるよ！',
  `mission_type` int(11) DEFAULT NULL COMMENT 'ミッションの属性「他人に干渉する物なら1、自分自身の行動なら2、投稿系なら3、感想系なら4」等のように準備して、ミッション表示時のアイコンなどに使えるようにする。\n将来的な拡張を考え、現在はnull可能としておく',
  `mission_want_progres` int(11) NOT NULL DEFAULT '1' COMMENT 'ミッション完了と看做すのに必要な行動回数',
  `created` datetime DEFAULT NULL COMMENT 'ミッション設定日時',
  `modified` datetime DEFAULT NULL COMMENT 'ミッション変更日時（使わないかも）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ユーザーに課せられるミッションの表示名と説明文、通し番号';

-- --------------------------------------------------------

--
-- テーブルの構造 `scores`
--

DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
  `id` int(10) UNSIGNED NOT NULL,
  `exam_user_id` int(10) UNSIGNED NOT NULL,
  `target_user_id` int(10) UNSIGNED NOT NULL,
  `articles_id` int(10) UNSIGNED NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='イイネされた時のログ';

-- --------------------------------------------------------

--
-- テーブルの構造 `trophies`
--

DROP TABLE IF EXISTS `trophies`;
CREATE TABLE `trophies` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '連番',
  `give_score` int(11) DEFAULT NULL COMMENT 'イイネを与えた回数',
  `take_score` int(11) DEFAULT NULL COMMENT 'イイネを貰った回数',
  `trophie_name` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT 'トロフィーの名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='トロフィーの種類を管理するマスターテーブル';

-- --------------------------------------------------------

--
-- テーブルの構造 `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `role` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- テーブルのデータのダンプ `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `role`, `mail`, `created`, `modified`) VALUES(3, 'hf-avenue', '$2y$10$i4x4Jnp0CG72oH3ALs.66OCr318UrcPU362JZLlSlOI5i4IzEcCjK', NULL, 'hasidume.factory@gmail.com', '2020-04-28 03:27:45', '2020-04-28 03:27:45');

-- --------------------------------------------------------

--
-- テーブルの構造 `user_mission_statuses`
--

DROP TABLE IF EXISTS `user_mission_statuses`;
CREATE TABLE `user_mission_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `mission_id` int(10) UNSIGNED NOT NULL COMMENT 'ミッションの番号（要、マスタテーブル確認）',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'このミッションがどのユーザーに割り当てられているのか',
  `mission_progress` tinyint(3) DEFAULT '0' COMMENT 'このミッションの進捗度。カウントアップ時にマスタ側で指定した回数と同時になった場合、mission_completedを1にする（必須）',
  `mission_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL COMMENT '発行日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ユーザーにミッションが発行されているか、進行中か、完了済みかをチェックする。（要、プログラム側での発行順序協議）';

--
-- テーブルのデータのダンプ `user_mission_statuses`
--

INSERT INTO `user_mission_statuses` (`id`, `mission_id`, `user_id`, `mission_progress`, `mission_completed`, `created`, `modified`) VALUES(42, 1, 3, 0, 0, '2020-04-28 03:28:15', '2020-04-28 03:28:15');

-- --------------------------------------------------------

--
-- テーブルの構造 `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ユーザーの自己紹介関係（将来はウェブサイトURL、アイコン等も拡張予定）';

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_mission_statuses`
--
ALTER TABLE `user_mission_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_UNIQUE` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `trophies`
--
ALTER TABLE `trophies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '連番';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_mission_statuses`
--
ALTER TABLE `user_mission_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
