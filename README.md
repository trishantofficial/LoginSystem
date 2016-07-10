# LoginSystem

An Authentication-System Framework for web applications in PHP.
It follows the object-oriented paradigm and integration to mysql database, in order to deal with the large number of online clients.
It prevents Intermediate SQL Injection attacks through passing prepared statements to the database.
It also prevents Cross-Site Request Forgery by using random(MD5) tokens to disallow unauthorized access.
It stores passwords in SHA-256 Encryption for further security.
It supports social media logins.
The classes have various static methods that provide easier functionality for creating and accessing other pages.