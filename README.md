Link Crawl App
=====

*App for TVPage Coding task*

## Introduction

Feel free to test it out on the [The Link Crawler App](http://dev.ptpcg.com/tvp). 

The Link Crawler App allows you to enter a valid url, and get a list of all domains that the url is linking to.

If you set the crawl depth to 1 or higher, the crawler will also obtain links from other linked urls (with the same site)

A report is displayed after crawling has completed, this includes a list of domains and a graph showing how many links were found per domain.

Your crawl history is stored via a token, so history is stored even for guest users. 
Guest history is lost when the php session expires.

History can be retained if a user registers with an email and password.
If user is new, all current guest history will be assigned to the user after registration. 

When you create an account, the data is stored via the Fllat database on the server.
When you log in, the data is retrieved from the database.

Data is stored in flat files w/ JSON format using the [Fllat Class](https://github.com/alfredxing/fllat/)

##Libraries
-Fllat | Flat database Class(php)
-jQuery 
-Angular.js
-Bourbon 
-Bourbon Neat
-Font Awesome
-jStorage
-SweetAlert

##Server Dependencies
-Web Server (Nginx, Apache, etc)
-PHP
-Mcrypt


## To-Do
-Add indicator for active section into navi links
-Add validation for email, domains
-Remove console.log and .warn for testing from code
-Clean up extreneous files from lib & php-lib


