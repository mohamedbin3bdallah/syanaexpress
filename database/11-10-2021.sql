ALTER TABLE `artist` ADD `company` VARCHAR(255) NULL AFTER `country_id`, ADD `nationality` INT NOT NULL DEFAULT '1' AFTER `company`;

ALTER TABLE `payment_methods` ADD `type` INT NULL DEFAULT '0' COMMENT '1. Cash 0. Online 2. Wallet' AFTER `logo`;


/* 18/10/2021 */

--
-- Table structure for table `artist_wallet_transactions`
--

CREATE TABLE `artist_wallet_transactions` (
                                              `id` int(10) UNSIGNED NOT NULL,
                                              `artist_id` int(11) NOT NULL,
                                              `artist_wallet_transaction_types_id` int(11) NOT NULL,
                                              `amount` float NOT NULL,
                                              `created_by` int(11) DEFAULT NULL,
                                              `updated_by` int(11) DEFAULT NULL,
                                              `created_at` timestamp NULL DEFAULT NULL,
                                              `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `artist_wallet_transaction_types`
--

CREATE TABLE `artist_wallet_transaction_types` (
                                                   `id` int(10) UNSIGNED NOT NULL,
                                                   `name_en` varchar(255) NOT NULL,
                                                   `name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artist_wallet_transaction_types`
--

INSERT INTO `artist_wallet_transaction_types` (`id`, `name_en`, `name_ar`) VALUES
(1, 'Order Amount', 'مبلغ الطلب'),
(2, 'Point Transfer', 'تحويل النقاط'),
(3, 'Extra Credit', 'رصيد إضافي');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artist_wallet_transactions`
--
ALTER TABLE `artist_wallet_transactions`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artist_wallet_transaction_types`
--
ALTER TABLE `artist_wallet_transaction_types`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artist_wallet_transactions`
--
ALTER TABLE `artist_wallet_transactions`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artist_wallet_transaction_types`
--
ALTER TABLE `artist_wallet_transaction_types`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;














/* 25/10/2021   */


CREATE TABLE `artist_points` (
                                 `id` int(10) UNSIGNED NOT NULL,
                                 `artist_id` int(11) NOT NULL,
                                 `points` int(11) NOT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `artist_point_transactions`
--

CREATE TABLE `artist_point_transactions` (
                                             `id` int(10) UNSIGNED NOT NULL,
                                             `artist_id` int(11) NOT NULL,
                                             `artist_point_transaction_types_id` int(11) NOT NULL,
                                             `points` int(11) NOT NULL,
                                             `created_by` int(11) DEFAULT NULL,
                                             `updated_by` int(11) DEFAULT NULL,
                                             `created_at` timestamp NULL DEFAULT NULL,
                                             `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `artist_point_transaction_types`
--

CREATE TABLE `artist_point_transaction_types` (
                                                  `id` int(10) UNSIGNED NOT NULL,
                                                  `name_en` varchar(255) NOT NULL,
                                                  `name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artist_point_transaction_types`
--

INSERT INTO `artist_point_transaction_types` (`id`, `name_en`, `name_ar`) VALUES
(1, 'Add Points', 'اضافة نقاط'),
(2, 'Points Discount', 'خصم نقاط'),
(3, 'Redeem Points', 'استبدال النقاط');

-- --------------------------------------------------------

--
-- Table structure for table `artist_wallet_transfer_cash_requests`
--

CREATE TABLE `artist_wallet_transfer_cash_requests` (
                                                        `id` int(10) UNSIGNED NOT NULL,
                                                        `artist_id` int(11) NOT NULL,
                                                        `amount` int(11) NOT NULL,
                                                        `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0:pend, 1:accept, 2:reject',
                                                        `created_by` int(11) DEFAULT NULL,
                                                        `updated_by` int(11) DEFAULT NULL,
                                                        `created_at` timestamp NULL DEFAULT NULL,
                                                        `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artist_points`
--
ALTER TABLE `artist_points`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artist_point_transactions`
--
ALTER TABLE `artist_point_transactions`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artist_point_transaction_types`
--
ALTER TABLE `artist_point_transaction_types`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `artist_wallet_transfer_cash_requests`
--
ALTER TABLE `artist_wallet_transfer_cash_requests`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artist_points`
--
ALTER TABLE `artist_points`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artist_point_transactions`
--
ALTER TABLE `artist_point_transactions`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artist_point_transaction_types`
--
ALTER TABLE `artist_point_transaction_types`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `artist_wallet_transfer_cash_requests`
--
ALTER TABLE `artist_wallet_transfer_cash_requests`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;



CREATE TABLE `point_rewards` (
                                 `id` int(10) UNSIGNED NOT NULL,
                                 `country_id` int(11) NOT NULL,
                                 `point_reward_type_id` int(11) NOT NULL,
                                 `points_count` int(11) NOT NULL,
                                 `rewarded_balance` float NOT NULL,
                                 `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0:not active, 1:active',
                                 `created_by` int(11) DEFAULT NULL,
                                 `updated_by` int(11) DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `point_reward_types`
--

CREATE TABLE `point_reward_types` (
                                      `id` int(10) UNSIGNED NOT NULL,
                                      `name_en` varchar(255) NOT NULL,
                                      `name_ar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `point_rewards`
--
ALTER TABLE `point_rewards`
    ADD PRIMARY KEY (`id`);

--
-- Indexes for table `point_reward_types`
--
ALTER TABLE `point_reward_types`
    ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `point_rewards`
--
ALTER TABLE `point_rewards`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_reward_types`
--
ALTER TABLE `point_reward_types`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

