-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2021 at 12:50 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easytradev3`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state_id` int(11) NOT NULL,
  `pincode` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `name_ar`, `state_id`, `pincode`, `updated_at`) VALUES
(1, 'Heliopolis', 'مصر الجديده', 1, NULL, NULL),
(2, 'Nasr City', 'مدينه نصر', 1, NULL, NULL),
(3, 'El-Maadi', 'المعادي', 1, NULL, NULL),
(4, 'New Cairo', 'التجمع الخامس', 1, NULL, NULL),
(5, 'Hadayek El-Kobba', 'حدائق القبة', 1, NULL, NULL),
(6, 'El-Obour City', 'مدينة العبور', 1, NULL, NULL),
(7, 'El-Manyal', 'المنيل', 1, NULL, NULL),
(8, 'Shoubra', 'شبرا', 1, NULL, NULL),
(9, 'West El-Balad', 'وسط البلد', 1, NULL, NULL),
(10, '10th of Ramada', 'العاشر من رمضان', 1, NULL, NULL),
(11, 'Ain Shams', 'عين شمس', 1, NULL, NULL),
(12, 'El-Abbasia', 'العباسية', 1, NULL, NULL),
(13, 'El-Mokattam', 'المقطم', 1, NULL, NULL),
(14, 'El-Rehab', 'الرحاب', 1, NULL, NULL),
(15, 'El-Sayeda Zainab', 'السيدة زينب', 1, NULL, NULL),
(16, 'El-Shorouk', 'الشروق', 1, NULL, NULL),
(17, 'El-Zaitou', 'الزيتون', 1, NULL, NULL),
(18, 'El-Zamalek', 'الزمالك', 1, NULL, NULL),
(19, 'Helwa', 'حلوان', 1, NULL, NULL),
(20, 'Madinaty', 'مدينتي', 1, NULL, NULL),
(21, 'Masr El-Kadima', 'مصر القديمة', 1, NULL, NULL),
(22, 'Dokki and Mohandessin', 'الدقي و المهندسين', 2, NULL, NULL),
(23, '6th of October', '6 اكتوبر', 2, NULL, NULL),
(24, 'El-Haram', 'الهرم', 2, NULL, NULL),
(25, 'Faisal', 'فيصل', 2, NULL, NULL),
(26, 'El-Sheikh Zayed', 'الشيخ زايد', 2, NULL, NULL),
(27, 'Hadayek El-Ahram', 'حدائق الأهرام', 2, NULL, NULL),
(28, 'Al-Ajuza', 'العجوزة', 2, NULL, NULL),
(29, 'El-Giza', 'ميدان الجيزة', 2, NULL, NULL),
(30, 'Mahatet El-Raml', 'محطة الرمل', 3, NULL, NULL),
(31, 'Roshdy', 'رشدي', 3, NULL, NULL),
(32, 'Smouha', 'سموحة', 3, NULL, NULL),
(33, 'Sporting', 'سبورتينج', 3, NULL, NULL),
(34, 'Sidy Gaber', 'سيدي جابر', 3, NULL, NULL),
(35, 'Lora', 'لوران', 3, NULL, NULL),
(36, 'Camp Caesar', 'كامب شيزار', 3, NULL, NULL),
(37, 'El-Ibrahimia', 'الإبراهيمية', 3, NULL, NULL),
(38, 'Janaklees', 'جانكليس', 3, NULL, NULL),
(39, 'Abou Keir', 'ابو قير', 3, NULL, NULL),
(40, 'Bahary', 'بحري', 3, NULL, NULL),
(41, 'Bakos', 'باكوس', 3, NULL, NULL),
(42, 'Bulkly', 'بولكلي', 3, NULL, NULL),
(43, 'Cleopatra', 'كليوباترا', 3, NULL, NULL),
(44, 'El-Agamy', 'العجمي', 3, NULL, NULL),
(45, 'El-Amreya', 'العامرية', 3, NULL, NULL),
(46, 'El-Asafra', 'العصافرة', 3, NULL, NULL),
(47, 'El-Azarita', 'الأزاريطه', 3, NULL, NULL),
(48, 'El-Mansheyah', 'المنشية', 3, NULL, NULL),
(49, 'El-Montazah', 'المنتزه', 3, NULL, NULL),
(50, 'El-Seyouf', 'السيوف', 3, NULL, NULL),
(51, 'El-Shatby', 'الشاطبي', 3, NULL, NULL),
(52, 'El-Werdeya', 'الورديان', 3, NULL, NULL),
(53, 'Fouad Street', 'شارع فؤاد', 3, NULL, NULL),
(54, 'Glym', 'جليم', 3, NULL, NULL),
(55, 'Kafr Abdouh', 'كفر عبده', 3, NULL, NULL),
(56, 'King Mariout', 'كينج ماريوط', 3, NULL, NULL),
(57, 'Miamy', 'ميامي', 3, NULL, NULL),
(58, 'Moharam Bek', 'محرم بيك', 3, NULL, NULL),
(59, 'Moustafa Kamel', 'مصطفى كامل', 3, NULL, NULL),
(60, 'North Coast', 'الساحل الشمالي', 3, NULL, NULL),
(61, 'Saba Basha', 'سابا باشا', 3, NULL, NULL),
(62, 'San Stefano', 'سان ستيفانو', 3, NULL, NULL),
(63, 'Sidy Bishr', 'سيدي بشر', 3, NULL, NULL),
(64, 'Stanley', 'ستانلي', 3, NULL, NULL),
(65, 'Victoria', 'فيكتوريا', 3, NULL, NULL),
(66, 'Zizenia', 'زيزينيا', 3, NULL, NULL),
(67, 'Shoubra El-Kheima', 'شبرا الخيمة', 4, NULL, NULL),
(68, 'Mahalla', 'المحلة الكبرى', 5, NULL, NULL),
(69, 'Tanta', 'طنطا', 5, NULL, NULL),
(70, 'Shibin El-Kom', 'شبين الكوم', 6, NULL, NULL),
(71, 'Menouf', 'منوف', 6, NULL, NULL),
(72, 'Sadat City', 'مدينة السادات', 6, NULL, NULL),
(73, 'Ibsheway', 'ابشواي', 7, NULL, NULL),
(74, 'El-Mansoura', 'المنصورة', 8, NULL, NULL),
(75, 'El-Zagazig', 'الزقازيق', 9, NULL, NULL),
(76, 'Damanhur', 'دمنهور', 10, NULL, NULL),
(77, 'Marsa Matrouh', 'مرسى مطروح', 12, NULL, NULL),
(78, 'El Marg', 'المرج', 1, NULL, NULL),
(79, 'El Salam', 'السلام', 1, NULL, NULL),
(80, 'El Matareya', 'المطرية', 1, NULL, NULL),
(81, 'El Nuzhah (Airport)', 'النزهة (المطار)', 1, NULL, NULL),
(82, 'El Waili', 'الوايلي', 1, NULL, NULL),
(83, 'El Zawiyah El Hamra', 'الزاوية الحمراء', 1, NULL, NULL),
(84, 'El Sharabeya', 'الشرابية', 1, NULL, NULL),
(85, 'El Sahel', 'الساحل', 1, NULL, NULL),
(86, 'Rud El Farag', 'روض الفرج', 1, NULL, NULL),
(87, 'Bulaq', 'بولاق', 1, NULL, NULL),
(88, 'Azbakeya', 'الأزبكية', 1, NULL, NULL),
(89, 'Manshiyat Naser', 'منشأة ناصر ', 1, NULL, NULL),
(90, 'Qasr El Nil', 'قصر النيل', 1, NULL, NULL),
(91, 'Zamalek', 'الزمالك', 1, NULL, NULL),
(92, 'Abdeen', 'عابدين', 1, NULL, NULL),
(93, 'El Muski', 'الموسكي', 1, NULL, NULL),
(94, 'Bab El Shariyah', 'باب الشعرية', 1, NULL, NULL),
(95, 'El Zahir', 'الأزهر', 1, NULL, NULL),
(96, 'El Gamaliyah', 'الجمالية ', 1, NULL, NULL),
(97, 'El Darb El Ahmar', 'الدرب الأحمر', 1, NULL, NULL),
(98, 'El Khalifa', 'الخليفة', 1, NULL, NULL),
(99, 'El Basatin', 'البساتين', 1, NULL, NULL),
(100, 'Turah', 'طره', 1, NULL, NULL),
(101, '15 Mayu', '15 مايو', 1, NULL, NULL),
(102, 'El Tabin', 'التبين ', 1, NULL, NULL),
(103, 'Badr City (includes New Heliop', 'مدينة بدر (تشمل مدينة هليوبليس الجديدة)', 1, NULL, NULL),
(104, 'Kharga', 'الخارجة', 14, NULL, NULL),
(105, 'Farafra', 'الفرافرة ', 14, NULL, NULL),
(106, 'Dakhla', 'الداخلة', 14, NULL, NULL),
(107, 'Baris', 'باريس', 14, NULL, NULL),
(108, 'Suez', 'السويس', 15, NULL, NULL),
(109, 'Alarbaein', 'الأربعين', 15, NULL, NULL),
(110, 'Aljanayun', 'الجناين', 15, NULL, NULL),
(111, 'Faisal', 'فيصل', 15, NULL, NULL),
(112, 'Dahab', 'دهب', 16, NULL, NULL),
(113, 'El tor', 'الطور', 16, NULL, NULL),
(114, 'Nuweiba', 'نويبع ', 16, NULL, NULL),
(115, 'Saint Catherine', 'سانت كاترين ', 16, NULL, NULL),
(116, 'Sharm El Sheikh', 'شرم الشيخ ', 16, NULL, NULL),
(117, 'Taba', 'طابا ', 16, NULL, NULL),
(118, 'Akhmim', 'أخميم', 17, NULL, NULL),
(119, 'Dar El Salam', 'دار السلام', 17, NULL, NULL),
(120, 'El Balyana', 'البليانة', 17, NULL, NULL),
(121, 'El Maragha', 'المراغة', 17, NULL, NULL),
(122, 'Girga', 'جرجا', 17, NULL, NULL),
(123, 'Juhayna', 'جهينة', 17, NULL, NULL),
(124, 'Sohag', 'سوهاج', 17, NULL, NULL),
(125, 'Tahta', 'طهطا', 17, NULL, NULL),
(126, '10th of Ramadan', 'العاشر من رمضان.', 9, NULL, NULL),
(127, 'Abu Hammad', 'أبو حماد.', 9, NULL, NULL),
(128, 'Abu Kebir', 'أبو كبير.', 9, NULL, NULL),
(129, 'Awlad Saqr', 'اولاد صقر.', 9, NULL, NULL),
(130, 'Bilbeis', 'بلبيس', 9, NULL, NULL),
(131, 'Diyarb Negm', 'ديرب نجم.', 9, NULL, NULL),
(132, 'El Husseiniya', 'الحسينية.', 9, NULL, NULL),
(133, 'El Ibrahimiya', 'الإبراهيمية', 9, NULL, NULL),
(134, 'El Qurein', 'القرين.', 9, NULL, NULL),
(135, 'Faqous', 'فاقوس.', 9, NULL, NULL),
(136, 'Hihya', 'ههيا', 9, NULL, NULL),
(137, 'Kafr Saqr', 'كفر صقر.', 9, NULL, NULL),
(138, 'Mashtool El Souk', 'مشتول السوق', 9, NULL, NULL),
(139, 'Minya El Qamh', 'منياالقمح', 9, NULL, NULL),
(140, 'New Salhia', 'الصالحية الجديدة.', 9, NULL, NULL),
(141, 'Anfoushi', 'الانفوشى', 3, NULL, NULL),
(142, 'Dekhela', 'الدخيلة', 3, NULL, NULL),
(143, 'El Atareen', 'العطارين', 3, NULL, NULL),
(144, 'El Gomrok', 'الجمرك', 3, NULL, NULL),
(145, 'El Labban', 'اللبان', 3, NULL, NULL),
(146, 'El Maamora', 'المعمورة', 3, NULL, NULL),
(147, 'El Mandara', 'المندرة', 3, NULL, NULL),
(148, 'El Max', 'الماكس', 3, NULL, NULL),
(149, 'El Qabary', 'القبري', 3, NULL, NULL),
(150, 'El Saraya ', 'السرايا ', 3, NULL, NULL),
(151, 'Hadara', 'الحضرة', 3, NULL, NULL),
(152, 'Kafr Abdu', 'كفر عبده', 3, NULL, NULL),
(153, 'Karmoz', 'كرموز', 3, NULL, NULL),
(154, 'Kom El Deka', 'كوم الدكة', 3, NULL, NULL),
(155, 'Tharwat', 'ثروت', 3, NULL, NULL),
(156, 'Zezenia', 'زيزينيا', 3, NULL, NULL),
(157, 'Aswan', 'أسوان ', 18, NULL, NULL),
(158, 'Edfu', 'إدفو ', 18, NULL, NULL),
(159, 'Kom Ombo', 'كوم أمبو', 18, NULL, NULL),
(160, 'Draow', 'دراو', 18, NULL, NULL),
(161, 'Abnoub', 'أبنوب', 19, NULL, NULL),
(162, 'Asyut', 'أسيوط', 19, NULL, NULL),
(163, 'New Assiut', 'أسيوط الجديدة', 19, NULL, NULL),
(164, 'Badari', 'البداري', 19, NULL, NULL),
(165, 'Dayrout', 'ديروط', 19, NULL, NULL),
(166, 'Sidfa', 'صدفا', 19, NULL, NULL),
(167, 'The Ghanaim', 'الغنايم', 19, NULL, NULL),
(168, 'Qusiya', 'القوصية', 19, NULL, NULL),
(169, 'Manfalut', 'منفلوط', 19, NULL, NULL),
(170, 'Abu al-Matamir', 'أبو المطامير', 10, NULL, NULL),
(171, 'Abu Homs', 'أبو حمص', 10, NULL, NULL),
(172, 'Edco', 'إدكو', 10, NULL, NULL),
(173, 'Itai Baroud', 'إيتاي البارود', 10, NULL, NULL),
(174, 'Badr', 'بدر', 10, NULL, NULL),
(175, 'Delengat', 'الدلنجات', 10, NULL, NULL),
(176, 'Rahmaniyah', 'الرحمانية', 10, NULL, NULL),
(177, 'Rashid', 'رشيد', 10, NULL, NULL),
(178, 'Shubrakhit', 'شبراخيت', 10, NULL, NULL),
(179, 'Kafr El Dawar', 'كفر الدوار', 10, NULL, NULL),
(180, 'Com Hamada', 'كوم حمادة', 10, NULL, NULL),
(181, 'Mahmoudiyah', 'المحمودية', 10, NULL, NULL),
(182, 'New Nubaria', 'النوبارية الجديدة', 10, NULL, NULL),
(183, 'Wadi Natru', 'وادي النطرون', 10, NULL, NULL),
(184, 'Iihnasia', 'إهناسيا', 13, NULL, NULL),
(185, 'BBA', 'ببا', 13, NULL, NULL),
(186, 'New Beni Suef', 'بني سويف الجديدة', 13, NULL, NULL),
(187, 'Somasta', 'سمسطا', 13, NULL, NULL),
(188, 'Fashn', 'الفشن', 13, NULL, NULL),
(189, 'Naser', 'ناصر', 13, NULL, NULL),
(190, 'Al Wasta', 'الواسطى ', 13, NULL, NULL),
(191, 'Aga', 'أجا ', 8, NULL, NULL),
(192, 'Bilqas', 'بلقس ', 8, NULL, NULL),
(193, 'Damas', 'داماس', 8, NULL, NULL),
(194, 'Dikirnis', 'دكرنس', 8, NULL, NULL),
(195, 'El Gamaliya', 'الجمالية', 8, NULL, NULL),
(196, 'El Matareya', 'المطرية', 8, NULL, NULL),
(197, 'El Senbellawein', 'السنبلاوين', 8, NULL, NULL),
(198, 'Gamasa', 'جمصة', 8, NULL, NULL),
(199, 'Manzala', 'المنزلة', 8, NULL, NULL),
(200, 'Mit Elkorama', 'ميت الكوراما', 8, NULL, NULL),
(201, 'Mit Ghamr', 'ميت غمر', 8, NULL, NULL),
(202, 'Mit Salsil', 'ميت سالسيل', 8, NULL, NULL),
(203, 'Nabaroh', 'نباروه ', 8, NULL, NULL),
(204, 'Sherbin', 'شربين', 8, NULL, NULL),
(205, 'Talkha', 'طلخا', 8, NULL, NULL),
(206, 'Damietta', 'دمياط', 11, NULL, NULL),
(207, 'New Damietta', 'دمياط الجديدة', 11, NULL, NULL),
(208, 'Alruwda', 'الروضة ', 11, NULL, NULL),
(209, 'Zarqa', 'الزرقا ', 11, NULL, NULL),
(210, 'Alsarw', 'السرو ', 11, NULL, NULL),
(211, 'eizbat albaraj', 'عزبة البرج', 11, NULL, NULL),
(212, 'Faraskour', 'فارسكور', 11, NULL, NULL),
(213, 'Kafr El - Batikh', 'كفر البطيخ', 11, NULL, NULL),
(214, 'Meet Abu Ghaleb', 'ميت أبو غالب', 11, NULL, NULL),
(215, 'Fayoum', 'الفيوم', 7, NULL, NULL),
(216, 'Snores', 'سنورس', 7, NULL, NULL),
(217, 'Atsa', 'اطسا', 7, NULL, NULL),
(218, 'Tamiya', 'طامية', 7, NULL, NULL),
(219, 'Yusuf Alsadiq', 'يوسف الصديق', 7, NULL, NULL),
(220, 'Kafr El Zayat', 'كفر الزيات', 5, NULL, NULL),
(221, 'Santa', 'السنطة', 5, NULL, NULL),
(222, 'Al Mahalla Al Kobra', 'المحلة الكبرى', 5, NULL, NULL),
(223, 'Basion', 'بسيون', 5, NULL, NULL),
(224, 'Zefta', 'زفتى', 5, NULL, NULL),
(225, 'Samannoud', 'سمنود', 5, NULL, NULL),
(226, 'Kutour', 'قطور', 5, NULL, NULL),
(227, 'Dokki', 'الدقي', 2, NULL, NULL),
(228, 'Bulaq ad Dakrur', 'بولاق الدكرور', 2, NULL, NULL),
(229, 'Imbabah', 'إمبابة', 2, NULL, NULL),
(230, 'Omrania', 'العمرانية', 2, NULL, NULL),
(231, 'Monib', 'المنيب', 2, NULL, NULL),
(232, 'Kafr Tuhurmus', 'كفر طهرمس', 2, NULL, NULL),
(233, 'Altal alkabir', 'التل الكبير', 20, NULL, NULL),
(234, 'Fayd', 'فايد', 20, NULL, NULL),
(235, 'Alqintarat shrq', 'القنطرة شرق', 20, NULL, NULL),
(236, 'Alqintarat gharb', 'القنطرة غرب', 20, NULL, NULL),
(237, 'Abuswir', 'أبوصوير', 20, NULL, NULL),
(238, 'Alqasasin', 'القصاصين', 20, NULL, NULL),
(239, 'kafar Alshaykh', 'كفر الشيخ', 21, NULL, NULL),
(240, 'Sayidi Ghazi.', 'سيدي غازي.', 21, NULL, NULL),
(241, 'Dusuq.', 'دسوق.', 21, NULL, NULL),
(242, 'Fawh.', 'فوه.', 21, NULL, NULL),
(243, 'Baltim', ' بلطيم ', 21, NULL, NULL),
(244, 'Albarils.', ' البرلس.', 21, NULL, NULL),
(245, 'Bayla.', 'بيلا.', 21, NULL, NULL),
(246, 'Sayidi Salim.', 'سيدي سالم.', 21, NULL, NULL),
(247, 'Alhamul.', 'الحامول.', 21, NULL, NULL),
(248, 'Qalin.', 'قلين.', 21, NULL, NULL),
(249, 'Mutubis', 'مطوبس', 21, NULL, NULL),
(250, 'Alriyad', 'الرياض', 21, NULL, NULL),
(251, 'Armant', 'أرمنت', 22, NULL, NULL),
(252, 'Luxor', 'الأقصر', 22, NULL, NULL),
(253, 'New Luxor', 'الأقصر الجديدة', 22, NULL, NULL),
(254, 'Esna', 'إسنا', 22, NULL, NULL),
(255, 'Al Bayadiya', 'البياضية ', 22, NULL, NULL),
(256, 'Al Zinia', 'الزينية', 22, NULL, NULL),
(257, 'AL Tud', 'الطود ', 22, NULL, NULL),
(258, 'New Tiba ', 'طيبة الجديدة', 22, NULL, NULL),
(259, 'Alhammam', 'الحمام‏ ', 12, NULL, NULL),
(260, 'Salloum', 'السلوم‏ ', 12, NULL, NULL),
(261, 'Sidi Brani', 'سيدي براني‏ ', 12, NULL, NULL),
(262, 'Siwa', 'سيوة‏ ', 12, NULL, NULL),
(263, 'Al Dabaa', 'الضبعة‏ ', 12, NULL, NULL),
(264, 'Alalmein', 'العلمين‏ ', 12, NULL, NULL),
(265, 'Marsa Matruh', 'مرسى مطروح‏ ', 12, NULL, NULL),
(266, 'Najila', 'النجيلة‏ ', 12, NULL, NULL),
(267, 'Magagha', 'مغاغة', 23, NULL, NULL),
(268, 'Bani Mazar', 'بني مزار', 23, NULL, NULL),
(269, 'Mattay', 'مطاي', 23, NULL, NULL),
(270, 'Samalut', 'سمالوط', 23, NULL, NULL),
(271, 'Menia', 'المنيا', 23, NULL, NULL),
(272, 'Abu Qurqas', 'أبو قرقاص', 23, NULL, NULL),
(273, 'Malawy', 'ملوي', 23, NULL, NULL),
(274, 'Deir Mawas', 'دير مواس', 23, NULL, NULL),
(275, 'AlAdwa', 'العدوة', 23, NULL, NULL),
(276, 'Alshuhadaa', 'الشهداء', 6, NULL, NULL),
(277, 'Ashmon', 'أشمون', 6, NULL, NULL),
(278, 'Albajur', 'الباجور', 6, NULL, NULL),
(279, 'Barikat alsbe', 'بركة السبع', 6, NULL, NULL),
(280, 'Sars El-Layan', 'سرس الليان', 6, NULL, NULL),
(281, 'Quesna', 'قويسنا', 6, NULL, NULL),
(282, 'tala', 'تلا', 6, NULL, NULL),
(283, 'Biir aleabd', 'بئر العبد', 24, NULL, NULL),
(284, 'Nakhl', 'نخل', 24, NULL, NULL),
(285, 'Alhasana', 'الحسنة', 24, NULL, NULL),
(286, 'Alearish', 'العريش', 24, NULL, NULL),
(287, 'Alshaykh zawid', 'الشيخ زويد', 24, NULL, NULL),
(288, 'Rafah', 'رفح', 24, NULL, NULL),
(289, 'Port Fowad', 'بورفؤاد', 25, NULL, NULL),
(290, 'Hayi Aljanub', 'حي الجنوب', 25, NULL, NULL),
(291, 'Hayi Alarab', 'حي العرب', 25, NULL, NULL),
(292, 'Hayi Alshrq', 'حي الشرق', 25, NULL, NULL),
(293, 'Hayi Aldawahi', 'حي الضواحى', 25, NULL, NULL),
(294, 'Hayi Alzuhuwr', 'حي الزهور', 25, NULL, NULL),
(295, 'Hayi Almunakh', 'حي المناخ', 25, NULL, NULL),
(296, 'Hayi Gharb', 'حي غرب', 25, NULL, NULL),
(297, 'Alqanatir alkhayria', 'القناطر الخيرية', 4, NULL, NULL),
(298, 'Banha', 'بنها', 4, NULL, NULL),
(299, 'Bahtim', 'بهتيم', 4, NULL, NULL),
(300, 'Alkhusus', 'الخصوص', 4, NULL, NULL),
(301, 'Shabin Alqanatir', 'شبين القناطر', 4, NULL, NULL),
(302, 'Tukh', 'طوخ', 4, NULL, NULL),
(303, 'Aloubur', 'العبور ', 4, NULL, NULL),
(304, 'Qalyub', 'قليوب', 4, NULL, NULL),
(305, 'Qaha', 'قها', 4, NULL, NULL),
(306, 'Kafar Shakar', 'كفر شكر', 4, NULL, NULL),
(307, 'Abu Tesht', 'أبو تشت', 26, NULL, NULL),
(308, 'Farshout', 'فرشوط', 26, NULL, NULL),
(309, 'Nag Hammadi', 'نجع حمادي', 26, NULL, NULL),
(310, 'Alwaqf', 'الوقف', 26, NULL, NULL),
(311, 'Deshna', 'دشنا', 26, NULL, NULL),
(312, 'Qena', 'قنا ', 26, NULL, NULL),
(313, 'Qaft', 'قفط', 26, NULL, NULL),
(314, 'Qus', 'قوص', 26, NULL, NULL),
(315, 'Naqada', 'نقادة', 26, NULL, NULL),
(316, 'Ras Ghareb', 'رأس غارب', 27, NULL, NULL),
(317, 'Hurghada', 'الغردقة', 27, NULL, NULL),
(318, 'Alqusair', 'القصير', 27, NULL, NULL),
(319, 'Safaga', 'سفاجا', 27, NULL, NULL),
(320, 'Marsa Alam', 'مرسى علم', 27, NULL, NULL),
(321, 'Bernice', 'برنيس ', 27, NULL, NULL),
(322, 'Shalatin', 'شلاتين', 27, NULL, NULL),
(323, 'Halaib', 'حلايب ', 27, NULL, NULL),
(324, 'Cairo', 'القاهرة', 1, NULL, NULL),
(325, 'Alexandria', 'الإسكندرية', 3, NULL, NULL),
(326, 'Gharbia', 'الغربية', 5, NULL, NULL),
(327, 'Beni Suaif', 'بني سويف', 13, NULL, NULL),
(328, 'El-Sharqia', 'الشرقية', 9, NULL, NULL),
(329, 'Port Said', 'بور سعيد', 25, NULL, NULL),
(330, 'Ismailia', 'الإسماعيلية', 20, NULL, NULL),
(331, 'El-Dakahlia', 'الدقهلية', 8, NULL, NULL),
(332, 'El-Beheira', 'البحيرة', 10, NULL, NULL),
(333, 'Qalyubia', 'القليوبية', 4, NULL, NULL),
(334, 'Menoufia', 'المنوفية', 6, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `iso` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ar` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso3` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  `phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `iso`, `name`, `name_ar`, `iso3`, `numcode`, `phonecode`) VALUES
(1, 'EG', 'EGYPT', 'مصر', 'EGY', 818, 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



ALTER TABLE `category` ADD `parent_id` INT(11) NOT NULL DEFAULT '0' AFTER `cat_name`, ADD INDEX `category_parent_id` (`parent_id`);

ALTER TABLE `category` ADD `country_id` INT NULL DEFAULT NULL AFTER `parent_id`, ADD `city_id` INT NULL DEFAULT NULL AFTER `country_id`, ADD INDEX `category_country_id` (`country_id`), ADD INDEX `category_city_id` (`city_id`);




/* 13/8/2021 */

ALTER TABLE `countries` ADD `active` INT NOT NULL DEFAULT '1' AFTER `phonecode`, ADD INDEX `active` (`active`);

ALTER TABLE `cities` ADD `country_id` INT NULL DEFAULT NULL AFTER `name_ar`, ADD `active` INT NOT NULL DEFAULT '1' AFTER `country_id`, ADD INDEX `country_id` (`country_id`), ADD INDEX `active` (`active`);

UPDATE `cities` SET `country_id`=1

ALTER TABLE `user` CHANGE `city` `city_id` INT(11) NULL;

ALTER TABLE `user` CHANGE `country` `country_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `category` ADD `details` TEXT NULL DEFAULT NULL AFTER `image`;


/*  17/8/2021  */
ALTER TABLE `artist` CHANGE `city` `city_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `artist` CHANGE `country` `country_id` INT(11) NULL DEFAULT '1';


ALTER TABLE `category`
DROP `city_id`;


CREATE TABLE `syana_express`.`artist_category` ( `id` INT NOT NULL AUTO_INCREMENT , `artist_id` INT NOT NULL , `category_id` INT NOT NULL , `created_at` TIMESTAMP NOT NULL , `updated_at` DATETIME NULL , `created_by` INT NULL , `updated_by` INT NULL , PRIMARY KEY (`id`), INDEX `artist_id` (`artist_id`), INDEX `category_id` (`category_id`)) ENGINE = InnoDB;

ALTER TABLE `countries` ADD `sort` INT NOT NULL DEFAULT '0' AFTER `name_ar`, ADD `icon` TEXT NOT NULL AFTER `sort`;

UPDATE `countries` SET `iso` = 'SA', `name` = 'Kingdom Saudi Arabia', `name_ar` = 'السعودية', `sort` = '1', `icon` = 'http://142.93.166.71/uploads/sa.jpg', `iso3` = 'KSA', `phonecode` = '966' WHERE `countries`.`id` = 1
    INSERT INTO `countries` (`id`, `iso`, `name`, `name_ar`, `iso3`, `numcode`, `phonecode`, `icon`, `sort`) VALUES
    (2, 'EG', 'EGYPT', 'مصر', 'EGY', 818, 20, 'http://142.93.166.71/uploads/eg.jpg', 2);

INSERT INTO `countries` (`id`, `iso`, `name`, `name_ar`, `iso3`, `numcode`, `phonecode`, `icon`, `sort`) VALUES
(3, 'NG', 'Nigeria', 'نيجيريا', 'EGY', 818, 234, 'http://142.93.166.71/uploads/ng.jpg', 3);
INSERT INTO `countries` (`id`, `iso`, `name`, `name_ar`, `iso3`, `numcode`, `phonecode`, `icon`, `sort`) VALUES
(4, 'AE', 'United Arab Emirates', 'الامارات العربية المتحدة', 'UAE', 818, 971, 'http://142.93.166.71/uploads/uae.jpg', 4);

UPDATE `cities` SET `country_id`=2;

ALTER TABLE `cities`
DROP `state_id`,
  DROP `pincode`;


insert into cities (name, name_ar, country_id, active) values
('Abhā', 'Abhā', '1', '1'), ('Abqaiq', 'Abqaiq', '1', '1'), ('Al-Baḥah', 'Al-Baḥah', '1', '1'), ('Al-Dammām', 'Al-Dammām', '1', '1'), ('Al-Hufūf', 'Al-Hufūf', '1', '1'), ('Al-Jawf', 'Al-Jawf', '1', '1'), ('Al-Kharj (oasis)', 'Al-Kharj (oasis)', '1', '1'), ('Al-Khubar', 'Al-Khubar', '1', '1'), ('Al-Qaṭīf', 'Al-Qaṭīf', '1', '1'), ('Al-Ṭaʾif', 'Al-Ṭaʾif', '1', '1'), ('ʿArʿar', 'ʿArʿar', '1', '1'), ('Buraydah', 'Buraydah', '1', '1'), ('Dhahran', 'Dhahran', '1', '1'), ('Ḥāʾil', 'Ḥāʾil', '1', '1'), ('Jiddah', 'Jiddah', '1', '1'), ('Jīzān', 'Jīzān', '1', '1'), ('Khamīs Mushayt', 'Khamīs Mushayt', '1', '1'), ('King Khalīd Military City', 'King Khalīd Military City', '1', '1'), ('Mecca', 'Mecca', '1', '1'), ('Medina', 'Medina', '1', '1'), ('Najrān', 'Najrān', '1', '1'), ('Ras Tanura', 'Ras Tanura', '1', '1'), ('Riyadh', 'Riyadh', '1', '1'), ('Sakākā', 'Sakākā', '1', '1'), ('Tabūk', 'Tabūk', '1', '1'), ('Yanbuʿ', 'Yanbuʿ', '1', '1');

ALTER TABLE `cities` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);




/* 22/8/2021  */

CREATE TABLE user_addresses
(
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    longitude varchar(100),
    latitude varchar(100),
    city_id int,
    address text,
    building varchar(50),
    floor varchar(50),
    apartment varchar(50),
    landmark text,
    created_at timestamp DEFAULT current_timestamp(),
    updated_at datetime,
    created_by int,
    updated_by int
);
CREATE UNIQUE INDEX user_addresses_id_uindex ON user_addresses (id);
CREATE INDEX user_addresses_user_id_index ON user_addresses (user_id);
CREATE INDEX user_addresses_city_id_index ON user_addresses (city_id);




/* 24/8/2021 */

CREATE TABLE `payment_methods` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `logo` TEXT NOT NULL , `active` TINYINT NOT NULL , `details` TEXT NOT NULL , `created_by` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_by` INT NOT NULL , `updated_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `payment_methods` CHANGE `created_by` `created_by` INT(11) NULL;
ALTER TABLE `payment_methods` CHANGE `updated_by` `updated_by` INT(11) NULL;
ALTER TABLE `payment_methods` CHANGE `updated_at` `updated_at` DATETIME NULL;
ALTER TABLE `payment_methods` CHANGE `details` `details` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `payment_methods` ADD `name_ar` VARCHAR(255) NOT NULL AFTER `name`;

ALTER TABLE `payment_methods` ADD `country_id` INT NOT NULL DEFAULT '1' AFTER `id`, ADD INDEX `country_id` (`country_id`);


INSERT INTO `payment_methods` (`id`, `name`, `name_ar`, `logo`, `active`, `details`, `created_by`, `created_at`, `updated_by`, `updated_at`) VALUES (NULL, 'Mada', 'مدى', '/uploads/payment_methods/mada.png', '1', NULL, NULL, current_timestamp(), NULL, '2021-08-24 23:21:00.000000'), (NULL, 'VISA', 'فيزا', '/uploads/payment_methods/visa.png', '1', NULL, NULL, current_timestamp(), NULL, '2021-08-24 23:21:00.000000'), (NULL, 'My Fatoora', 'ماى فاتورة', '/uploads/payment_methods/myfatoora.png', '1', NULL, NULL, current_timestamp(), NULL, '2021-08-24 23:21:00.000000')


CREATE TABLE `booking_orders` ( `id` INT NOT NULL , `user_id` INT NOT NULL , `artist_id` INT NULL , `booking_date` DATE NOT NULL , `booking_time` TIME NOT NULL , `notes` TEXT NOT NULL , `category_id` INT NOT NULL , `total_amount` INT NOT NULL , `status_id` INT NOT NULL , `declined_by` INT NULL COMMENT ' 1. artist 2. customer' , `country_id` INT NOT NULL , `commission_type` INT NOT NULL COMMENT '0. Category 1. Flat' , `flat_type` INT NOT NULL COMMENT '1.Percentage 2. Flat Cost ' , `user_address_id` INT NOT NULL , `details` TEXT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_by` INT NOT NULL , `updated_at` DATETIME NULL , `updated_by` INT NULL , INDEX `user_id` (`user_id`), INDEX `artist_id` (`artist_id`), INDEX `booking_date` (`booking_date`), INDEX `booking_time` (`booking_time`), INDEX `category_id` (`category_id`), INDEX `status_id` (`status_id`), INDEX `declined_by` (`declined_by`), INDEX `user_address_id` (`user_address_id`)) ENGINE = InnoDB;
ALTER TABLE `booking_orders` CHANGE `country_id` `country_id` INT(11) NOT NULL AFTER `artist_id`, CHANGE `category_id` `category_id` INT(11) NOT NULL AFTER `country_id`, CHANGE `status_id` `status_id` INT(11) NOT NULL AFTER `booking_time`, CHANGE `declined_by` `declined_by` INT(11) NULL DEFAULT NULL COMMENT ' 1. artist 2. customer' AFTER `status_id`, CHANGE `user_address_id` `user_address_id` INT(11) NOT NULL AFTER `declined_by`, CHANGE `total_amount` `total_amount` INT(11) NOT NULL AFTER `user_address_id`, CHANGE `commission_type` `commission_type` INT(11) NOT NULL COMMENT '0. Category 1. Flat' AFTER `total_amount`, CHANGE `flat_type` `flat_type` INT(11) NOT NULL COMMENT '1.Percentage 2. Flat Cost ' AFTER `commission_type`

CREATE TABLE  `booking_order_status` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `name_ar` VARCHAR(255) NOT NULL , `active` INT(5) NOT NULL DEFAULT '1' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_by` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

CREATE TABLE  `booking_orders_logs` ( `id` INT NOT NULL AUTO_INCREMENT , `booking_order_id` INT NOT NULL , `log_type_id` INT NOT NULL , `status_id` INT NOT NULL , `details` TEXT NULL , `created_by` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), INDEX `booking_order_id` (`booking_order_id`), INDEX `log_type_id` (`log_type_id`), INDEX `status_id` (`status_id`), INDEX `created_at` (`created_at`), INDEX `created_by` (`created_by`)) ENGINE = InnoDB;

CREATE TABLE `booking_orders_log_types` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NOT NULL , `name_ar` VARCHAR(255) NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `created_by` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `booking_order_status` CHANGE `created_by` `created_by` INT(11) NULL;

INSERT INTO `booking_order_status` (`id`, `name`, `name_ar`, `active`, `created_at`, `created_by`) VALUES ('0', 'Pending', 'معلق', '1', current_timestamp(), ''), ('1', 'Accepted', 'تم قبول الطلب', '1', current_timestamp(), ''), ('2', 'Declined', 'تم رفض الطلب', '1', current_timestamp(), ''), ('3', 'In Progress', 'جارى تنفيذ الطلب', '1', current_timestamp(), ''), ('4', 'Completed', 'تم التنفيذ', '1', current_timestamp(), '')

ALTER TABLE `booking_orders_log_types` CHANGE `created_by` `created_by` INT(11) NULL;

INSERT INTO `booking_orders_log_types` (`id`, `name`, `name_ar`, `created_at`, `created_by`) VALUES ('1', 'New Creation', 'إنشاء طلب جديد', current_timestamp(), NULL), ('2', 'Order Status Changed', 'تغيير حالة الطلب', current_timestamp(), NULL), ('3', 'Order Info Update', 'تحديث الطلب', current_timestamp(), NULL);

CREATE TABLE  `booking_order_items` ( `id` INT NOT NULL AUTO_INCREMENT , `booking_order_id` INT NOT NULL , `category_id` INT NOT NULL , `quantity` INT NOT NULL , `cost_per_item` DECIMAL(10,0) NOT NULL , `created_by` INT NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`), INDEX `booking_order_id` (`booking_order_id`), INDEX `category_id` (`category_id`)) ENGINE = InnoDB;

ALTER TABLE `booking_orders` ADD `payment_method_id` INT NOT NULL AFTER `status_id`, ADD INDEX `payment_method_id` (`payment_method_id`);


ALTER TABLE `booking_orders` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);



/* 25/8/2021 */
ALTER TABLE `currency_setting` ADD `currency_name_ar` VARCHAR(255) NOT NULL AFTER `currency_name`, ADD `country_id` INT NOT NULL AFTER `currency_name_ar`, ADD INDEX `country_id` (`country_id`);
ALTER TABLE `currency_setting` CHANGE `currency_name_ar` `currency_name_ar` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

INSERT INTO `currency_setting` (`id`, `currency_symbol`, `currency_name`, `currency_name_ar`, `country_id`, `code`, `status`) VALUES (NULL, 'EGP', 'Egyptian Pound', 'جنية مصرى', '2', 'EGP', '1');


/* 4/9/2021 */

RENAME TABLE `artist_booking` TO `artist_booking_backup`;
RENAME TABLE  `booking_orders` TO `artist_booking`;
INSERT INTO `booking_orders_log_types` (`id`, `name`, `name_ar`, `created_at`, `created_by`) VALUES ('4', 'Artist has been Selected', 'تم اختيار البطل', '2021-08-25 00:44:17', NULL)ك
ALTER TABLE `artist_booking` ADD `start_time` BIGINT NULL AFTER `notes`, ADD `end_time` BIGINT NULL AFTER `start_time`;
ALTER TABLE `artist_booking` ADD `decline_reason` TEXT NULL AFTER `declined_by`;

ALTER TABLE `artist_booking` CHANGE `total_amount` `price` DECIMAL(10,2) NOT NULL;


/* 10/9/2021 */

ALTER TABLE `booking_invoice` CHANGE `currency_type` `currency_type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE `booking_invoice` CHANGE `final_amount` `final_amount` DECIMAL(10,2) NOT NULL;
ALTER TABLE `booking_invoice` CHANGE `payment_type` `payment_method_id` INT(11) NOT NULL;

ALTER TABLE `booking_invoice` ADD `payment_key_id` VARCHAR(255) NOT NULL AFTER `payment_method_id`, ADD `payment_key_type` VARCHAR(255) NOT NULL AFTER `payment_key_id`;

ALTER TABLE `booking_invoice` ADD `gateway_payment_id` VARCHAR(255) NULL AFTER `payment_key_type`;

ALTER TABLE `booking_invoice` CHANGE `payment_key_id` `payment_key_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `booking_invoice` CHANGE `payment_key_type` `payment_key_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;