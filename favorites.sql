
-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 03, 2013 at 09:48 AM
-- Server version: 5.1.57
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `a1096423_fl`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `category_name` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT 'Main',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` VALUES(14, 9, 'Main');
INSERT INTO `categories` VALUES(15, 9, 'news');
INSERT INTO `categories` VALUES(16, 16, 'Main');
INSERT INTO `categories` VALUES(17, 16, 'adsf');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `category_id` int(20) NOT NULL,
  `page_title` text CHARACTER SET utf8 NOT NULL,
  `url` varchar(10000) CHARACTER SET utf8 NOT NULL,
  `trashed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` VALUES(70, 9, 0, '301 Moved Permanently', 'http://www.btemplates.com/2008/blogger-template-creative-art/', 1);
INSERT INTO `favorites` VALUES(66, 9, 14, 'Creative Art', 'http://btemplates.com/2008/blogger-template-creative-art/', 0);
INSERT INTO `favorites` VALUES(64, 9, 0, 'Google', 'http://www.google.com', 1);
INSERT INTO `favorites` VALUES(71, 9, 0, '301 Moved Permanently', 'http://www.btemplates.com/2008/blogger-template-creative-art/', 1);
INSERT INTO `favorites` VALUES(72, 9, 0, 'Creative Art Blogger template - BTemplates', 'http://btemplates.com/2008/blogger-template-creative-art/', 0);
INSERT INTO `favorites` VALUES(75, 9, 0, '36 Fresh and Beautiful Free Blogger Templates - DzineBlog.com', 'http://dzineblog.com/2009/06/fresh-and-beautifull-blogger-templates.html', 0);
INSERT INTO `favorites` VALUES(76, 9, 0, 'Download 60 Fresh And Creative Blogger Templates For Free', 'http://www.techtreak.com/downloads/download-60-fresh-and-creative-blogger-templates-for-free/', 0);
INSERT INTO `favorites` VALUES(77, 9, 0, 'Free Blog Templates, Sketch Blog Templates', 'http://www.bestfreetemplates.info/webtemplates/freewebtemplates-20.html', 0);
INSERT INTO `favorites` VALUES(78, 9, 0, 'Free Templates for Blogger and Wordpress / Plantillas Gratis para Blogger y Wordpress...', 'http://www.giselejaquenod.com.ar/blog/blogger-templates/', 1);
INSERT INTO `favorites` VALUES(79, 9, 0, 'Blogger Templates', 'http://btemplates.com/', 0);
INSERT INTO `favorites` VALUES(80, 9, 0, '43 Incredibly Useful Collection of Tutorials, Resources, Inspirations etc To Discover The Best...', 'http://appslog.com/blog/1-blog/674-43-incredibly-useful-collection-of-tutorials-resources-inspirations-etc-to-discover-the-best-of-the-web-in-february.html', 1);
INSERT INTO `favorites` VALUES(81, 9, 14, 'Google', 'http://google.com', 1);
INSERT INTO `favorites` VALUES(82, 9, 14, '43 Incredibly Useful Collection of Tutorials, Resources, Inspirations etc To Discover The Best Of The Web In February', 'http://appslog.com/blog/1-blog/674-43-incredibly-useful-collection-of-tutorials-resources-inspirations-etc-to-discover-the-best-of-the-web-in-february.html', 0);
INSERT INTO `favorites` VALUES(83, 9, 14, 'Free Templates for Blogger and Wordpress / Plantillas Gratis para Blogger y Wordpress | Gisele Jaquenod', 'http://www.giselejaquenod.com.ar/blog/blogger-templates/', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) CHARACTER SET utf8 NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, 'mn', '123', 'naderian.mahdi@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(10) NOT NULL,
  `check_new_window` varchar(5) NOT NULL DEFAULT 'false'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` VALUES(1, 0);