-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2012 at 06:43 PM
-- Server version: 5.5.24
-- PHP Version: 5.4.6-2~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `coders_survey`
--

-- --------------------------------------------------------

--
-- Table structure for table `survey_config`
--

CREATE TABLE IF NOT EXISTS `survey_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `stype` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`stype`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `survey_config`
--

INSERT INTO `survey_config` (`id`, `stype`, `name`, `data`) VALUES
(1, 'auth', 'admin', '$6$21232f297a57a5a7$g5N3xZPKjuU4Osp.60MLTKPk.AVu/4OTyvjAE2NzR9YRkC7VTWWFUaxCQLXVUv6cSdnizc6Os3q4UtTI4DoKN.');

-- --------------------------------------------------------

--
-- Table structure for table `survey_item`
--

CREATE TABLE IF NOT EXISTS `survey_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `stype` enum('survey','page','topic','option','value') NOT NULL,
  `title` varchar(255) NOT NULL,
  `position` smallint(5) unsigned NOT NULL,
  `data` text,
  PRIMARY KEY (`id`,`stype`),
  UNIQUE KEY `parent_id` (`parent_id`,`stype`,`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=244 ;

--
-- Dumping data for table `survey_item`
--

INSERT INTO `survey_item` (`id`, `parent_id`, `stype`, `title`, `position`, `data`) VALUES
(1, 0, 'survey', 'CodersSurvey2012', 2, NULL),
(9, 1, 'page', 'You', 3, NULL),
(11, 9, 'topic', 'What''s your gender?', 1, '{"mandatory":0,"other":0,"type":"radio"}'),
(12, 11, 'option', 'Boy', 2, NULL),
(13, 11, 'option', 'Girl', 1, NULL),
(16, 9, 'topic', 'What''s your country?', 2, '{"mandatory":0,"other":0,"type":"radio"}'),
(18, 9, 'topic', 'Where do you work?', 3, '{"mandatory":0,"other":0,"type":"radio"}'),
(25, 0, 'survey', 'Test Das', 2, NULL),
(35, 16, 'option', 'North America', 1, NULL),
(36, 16, 'option', 'South America', 2, NULL),
(37, 16, 'option', 'Europe', 3, NULL),
(38, 16, 'option', 'Asia', 4, NULL),
(39, 18, 'option', 'City', 1, NULL),
(40, 18, 'option', 'Town', 2, NULL),
(41, 18, 'option', 'Village', 3, NULL),
(42, 9, 'topic', 'How old are you?', 4, '{"mandatory":0,"other":0,"type":"radio"}'),
(43, 42, 'option', '< 20', 1, NULL),
(44, 42, 'option', '20 - 30', 2, NULL),
(45, 42, 'option', '31 - 40', 3, NULL),
(46, 9, 'topic', 'Which size is your company?', 5, '{"mandatory":0,"other":0,"type":"radio"}'),
(47, 46, 'option', 'One man army', 1, NULL),
(48, 46, 'option', '2 â€“ 4 employes', 2, NULL),
(49, 46, 'option', '5 - 10 employes', 3, NULL),
(50, 46, 'option', '11 - 50 employes', 4, NULL),
(51, 9, 'topic', 'How do you make money in the web?', 6, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(53, 51, 'option', 'SaaS â€“ Subscription Based Service', 1, NULL),
(54, 51, 'option', 'Publisher â€“ Adds, Flatr, Crowd Funding', 2, NULL),
(57, 51, 'option', 'Online Shop â€“ Sell goods', 3, NULL),
(58, 51, 'option', 'Contractor â€“ Freelancer ', 4, NULL),
(59, 51, 'option', 'Employed â€“ Paycheck every month', 5, NULL),
(60, 51, 'option', 'You mean one can make money in the interweb?', 6, NULL),
(61, 9, 'topic', 'What about your annual salary?', 7, '{"mandatory":0,"other":0,"type":"radio"}'),
(62, 61, 'option', '< 10k $', 1, NULL),
(63, 61, 'option', '10k - 30k $', 2, NULL),
(64, 61, 'option', '30k - 60k $', 3, NULL),
(65, 61, 'option', '60k - 120k $', 4, NULL),
(79, 1, 'page', 'Your Work', 1, NULL),
(80, 79, 'topic', 'What are you?', 1, '{"mandatory":0,"other":0,"type":"radio"}'),
(81, 80, 'option', 'Designer', 1, NULL),
(82, 80, 'option', 'Developer', 2, NULL),
(83, 80, 'option', 'Both: Designer & Developer', 3, NULL),
(85, 79, 'topic', 'What''s your desktop Operating System?', 2, '{"mandatory":0,"other":0,"type":"radio"}'),
(86, 85, 'option', 'I''m a PC â€“ Windows', 1, NULL),
(87, 85, 'option', 'I''m a Mac â€“ Mac Os X', 2, NULL),
(88, 85, 'option', 'I''m a Penguin â€“ Linux', 3, NULL),
(89, 79, 'topic', 'Which are your skills?', 3, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(90, 89, 'option', 'Agile Development', 1, NULL),
(91, 89, 'option', 'Consulting / Strategy', 3, NULL),
(92, 89, 'option', 'Content Strategy / Editor', 4, NULL),
(93, 89, 'option', 'Information Architecture', 5, NULL),
(94, 89, 'option', 'Marketing', 6, NULL),
(95, 89, 'option', 'Page Load Speed', 8, NULL),
(96, 89, 'option', 'Project Management', 9, NULL),
(97, 89, 'option', 'SCRUM', 10, NULL),
(98, 89, 'option', 'Search Engine Optimization', 11, NULL),
(99, 89, 'option', 'Social Media Expert', 12, NULL),
(100, 89, 'option', 'System Administration', 13, NULL),
(101, 89, 'option', 'Wireframing / Prototyping', 14, NULL),
(102, 79, 'topic', 'Which languages do you write?', 4, '{"mandatory":0,"groupable":0,"other":0,"type":"checkbox"}'),
(103, 102, 'option', 'CSS', 2, NULL),
(104, 102, 'option', 'HTML', 3, NULL),
(105, 102, 'option', 'Java', 4, NULL),
(106, 102, 'option', 'Javascript', 5, NULL),
(107, 102, 'option', 'Perl', 6, NULL),
(108, 102, 'option', 'PHP', 7, NULL),
(109, 102, 'option', 'Python', 8, NULL),
(110, 102, 'option', 'Ruby', 9, NULL),
(111, 102, 'option', 'Scala', 10, NULL),
(112, 79, 'topic', 'Which backend frameworks do you use?', 5, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(113, 112, 'option', 'CakePHP', 1, NULL),
(114, 112, 'option', 'Django', 3, NULL),
(115, 112, 'option', 'FuelPHP', 4, NULL),
(116, 112, 'option', 'Ruby on Rails', 5, NULL),
(117, 112, 'option', 'Silex', 6, NULL),
(118, 112, 'option', 'Sinatra', 7, NULL),
(119, 112, 'option', 'Symfony', 8, NULL),
(120, 112, 'option', 'Yii', 9, NULL),
(121, 112, 'option', 'Zend Framework', 10, NULL),
(122, 89, 'option', 'Mobile Apps â€“ iOs/Android Developement', 7, NULL),
(123, 102, 'option', 'ASP / ASP.NET', 1, NULL),
(124, 112, 'option', 'CodeIgniter', 2, NULL),
(125, 79, 'topic', 'Which one is your text editor / IDE?', 6, '{"mandatory":0,"other":0,"type":"radio"}'),
(126, 125, 'option', 'Adobe Dreamweaver', 1, NULL),
(127, 125, 'option', 'Coda', 2, NULL),
(128, 125, 'option', 'Eclipse', 3, NULL),
(129, 125, 'option', 'eMacs', 4, NULL),
(130, 125, 'option', 'JEdit', 5, NULL),
(131, 125, 'option', 'Notepad++', 6, NULL),
(132, 125, 'option', 'Sublime Text', 7, NULL),
(133, 125, 'option', 'TextMate', 8, NULL),
(134, 125, 'option', 'UltraEdit', 9, NULL),
(135, 125, 'option', 'VIM', 10, NULL),
(136, 125, 'option', 'Xcode', 11, NULL),
(137, 125, 'option', 'Zend Studio', 12, NULL),
(138, 79, 'topic', 'Which new technologies do you use?', 7, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(139, 138, 'option', 'CouchDB', 1, NULL),
(140, 138, 'option', 'HTML5 (in general)', 2, NULL),
(141, 138, 'option', 'Local Storage', 3, NULL),
(142, 138, 'option', 'MongoDB', 4, NULL),
(143, 138, 'option', 'Nginx', 5, NULL),
(144, 138, 'option', 'Node.js', 6, NULL),
(145, 138, 'option', 'Redis', 7, NULL),
(146, 138, 'option', 'Responsive Design (Media Queries)', 8, NULL),
(147, 138, 'option', 'Web Fonts', 9, NULL),
(148, 138, 'option', 'WebGL', 10, NULL),
(149, 138, 'option', 'WebSocket', 11, NULL),
(150, 79, 'topic', 'Which social web services do you use?', 8, '{"mandatory":0,"groupable":0,"other":0,"type":"checkbox"}'),
(151, 150, 'option', 'Dribbble', 1, NULL),
(152, 150, 'option', 'Facebook', 2, NULL),
(153, 150, 'option', 'GitHub', 3, NULL),
(154, 150, 'option', 'Google+', 4, NULL),
(155, 150, 'option', 'Hacker News', 5, NULL),
(156, 150, 'option', 'LinkedIn', 6, NULL),
(157, 150, 'option', 'Quora', 7, NULL),
(158, 150, 'option', 'Stack Overflow', 8, NULL),
(159, 150, 'option', 'Tumblr', 9, NULL),
(160, 150, 'option', 'Twitter', 10, NULL),
(161, 150, 'option', 'Xing', 11, NULL),
(162, 79, 'topic', 'Which productivity tools do you use?', 9, '{"mandatory":0,"groupable":0,"other":0,"type":"checkbox"}'),
(163, 162, 'option', 'CSS Preprocessor (LiveReload, CodeKit,  Crunch! â€¦)', 1, NULL),
(164, 162, 'option', 'File Sharing (DropBox, Yousendit â€¦)', 2, NULL),
(165, 162, 'option', 'Git GUI Client (Git Tower, GitHub Client, GitBox â€¦)', 3, NULL),
(166, 162, 'option', 'Google Tools (Reader, Docs, Alerts â€¦)', 4, NULL),
(167, 162, 'option', 'Online Code Sharing (Dabblet, JSFiddle, JSBin, CodePen, Gist â€¦)', 5, NULL),
(168, 162, 'option', 'Online Invoicing (Freshbooks, Harvest, Blinksale â€¦)', 6, NULL),
(169, 162, 'option', 'Project Management / Issue Tracking (Redmine, Pivotal Tracker, JIRA, Basecamp, Travis-CI â€¦)', 7, NULL),
(170, 79, 'topic', 'Which frontend frameworks and helpers do you use?', 10, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(171, 170, 'option', 'Backbone.js', 1, NULL),
(172, 170, 'option', 'CoffeScript', 2, NULL),
(173, 170, 'option', 'Compass', 3, NULL),
(174, 170, 'option', 'Handlebars', 4, NULL),
(175, 170, 'option', 'HTML5 Boilerplate', 5, NULL),
(176, 170, 'option', 'jQuery', 6, NULL),
(177, 170, 'option', 'JsHint', 7, NULL),
(178, 170, 'option', 'Less', 8, NULL),
(179, 170, 'option', 'Modernizr', 9, NULL),
(180, 170, 'option', 'RequireJS', 10, NULL),
(181, 170, 'option', 'Sass', 11, NULL),
(182, 170, 'option', 'Twitter Bootstrap', 12, NULL),
(183, 170, 'option', 'Underscore', 13, NULL),
(184, 170, 'option', 'Zurb Foundation', 14, NULL),
(185, 16, 'option', 'Elsewhere', 5, NULL),
(186, 42, 'option', '41 - 50', 4, NULL),
(187, 42, 'option', '> 50', 5, NULL),
(188, 46, 'option', '51 - 100 employes', 5, NULL),
(189, 46, 'option', '101 - 1000 employes', 6, NULL),
(190, 46, 'option', '> 1000 employes', 7, NULL),
(191, 9, 'topic', 'How important is school and college for your current job?', 8, '{"mandatory":0,"other":0,"type":"radio"}'),
(192, 191, 'option', 'Important â€“ I practice here what I learned there', 1, NULL),
(193, 191, 'option', 'Not so important â€“ At least I know the author of Lorem Ipsum', 2, NULL),
(194, 191, 'option', 'Irrelevant â€“ A total waste of time', 3, NULL),
(195, 9, 'topic', 'What''s your political attitude?', 9, '{"mandatory":0,"other":0,"type":"radio"}'),
(196, 195, 'option', 'Left Wing', 1, NULL),
(197, 195, 'option', 'Right Wing', 2, NULL),
(198, 195, 'option', 'I donÂ´t care about politics', 5, NULL),
(199, 195, 'option', 'Moderate', 3, NULL),
(200, 195, 'option', 'Pirate', 4, NULL),
(201, 9, 'topic', 'Do you go to Web Events (conferences, unconferences, camps)?', 10, '{"mandatory":0,"other":0,"type":"radio"}'),
(202, 201, 'option', 'Yes', 1, NULL),
(203, 201, 'option', 'No', 2, NULL),
(204, 9, 'topic', 'What do you hate?', 11, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(205, 204, 'option', 'Deadlines', 1, NULL),
(206, 204, 'option', 'Print Designers', 2, NULL),
(207, 204, 'option', 'Internet Explorer LTE 9', 3, NULL),
(208, 204, 'option', 'Enterprise Solutions', 4, NULL),
(209, 204, 'option', 'Project Managers', 5, NULL),
(210, 204, 'option', 'Clients', 6, NULL),
(211, 204, 'option', 'Online Surveys', 7, NULL),
(212, 1, 'page', 'Your Hosting', 2, NULL),
(213, 212, 'topic', 'How many web projects are you managing at the moment?', 1, '{"mandatory":0,"other":0,"type":"radio"}'),
(214, 213, 'option', '1', 1, NULL),
(215, 213, 'option', '2 - 3', 2, NULL),
(216, 213, 'option', '4 - 6', 3, NULL),
(217, 213, 'option', '7 - 10', 4, NULL),
(218, 213, 'option', '> 10', 5, NULL),
(219, 212, 'topic', 'Where are your current projects hosted?', 2, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(220, 219, 'option', 'Shared Hosting (e.g. GoDaddy, 1and1, OVH, Domain Factory â€¦)', 1, NULL),
(221, 219, 'option', 'Virtual/Dedicated Private Server', 2, NULL),
(222, 219, 'option', 'Own Infrastrature / Managed Hosting / Private Cloud', 3, NULL),
(223, 219, 'option', 'Cloud PaaS (e.g. Heroku, AppFog, Pagodabox â€¦)', 4, NULL),
(224, 219, 'option', 'Cloud IaaS (e.g. Amazon Web Services, Rackspace Cloud, Windows Azure â€¦)', 5, NULL),
(225, 219, 'option', 'I don''t know/care', 6, NULL),
(226, 212, 'topic', 'Is your next project going to be hosted in the cloud?', 3, '{"mandatory":0,"other":0,"type":"checkbox"}'),
(227, 226, 'option', 'Yes, on a PaaS', 1, NULL),
(228, 226, 'option', 'Yes, on a IaaS', 2, NULL),
(229, 226, 'option', 'No, I think cloud hosting is too complicated', 3, NULL),
(230, 226, 'option', 'No, I think cloud hosting is too expensive', 4, NULL),
(231, 226, 'option', 'No, i have privacy concerns', 5, NULL),
(232, 226, 'option', 'I don''t care/know', 6, NULL),
(233, 89, 'option', 'Big Data', 2, NULL),
(234, 79, 'topic', 'What''s your Mobile OS?', 11, '{"mandatory":0,"other":0,"type":"radio"}'),
(235, 234, 'option', 'iOs', 3, NULL),
(236, 234, 'option', 'Android', 1, NULL),
(237, 234, 'option', 'Windows Mobile', 4, NULL),
(238, 234, 'option', 'BlackBarry OS', 2, NULL),
(239, 61, 'option', '> 120k $', 5, NULL),
(240, 125, 'option', 'BBEdit', 13, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `survey_result`
--

CREATE TABLE IF NOT EXISTS `survey_result` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL,
  `ref` varchar(25) NOT NULL,
  `position` varchar(21) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `survey_id` (`survey_id`,`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `survey_result`
--

-- --------------------------------------------------------

--
-- Table structure for table `survey_walkthrough`
--

CREATE TABLE IF NOT EXISTS `survey_walkthrough` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  `ip` varchar(39) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `survey_md5` (`survey_id`,`ip`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
