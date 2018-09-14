CREATE TABLE IF NOT EXISTS `ac_msgs` (
  `id` int(11) NOT NULL,
  `m_frm` int(11) NOT NULL,
  `m_to` int(11) NOT NULL,
  `msg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` enum('0','1') NOT NULL DEFAULT '0',
  `frm_del` tinyint(1) NOT NULL DEFAULT '0',
  `to_del` tinyint(1) NOT NULL DEFAULT '0',
  `dt_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ac_msgs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ac_msgs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE IF NOT EXISTS `ac_blck_usr` (
  `usr_id` int(11) DEFAULT NULL,
  `blckd_usr_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ac_blck_usr`
  ADD UNIQUE KEY `user_id` (`usr_id`,`blckd_usr_id`);


CREATE TABLE IF NOT EXISTS `ac_usrs_msgs` (
  `id` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `msg_id` int(11) NOT NULL,
  `dt_upd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `ac_usrs_msgs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ac_usrs_msgs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;