#Wordpress Migration Script

Update/tidy Wordpress databse during migration. This script performs the following tasks:

* Updates absolute URLs in posts, post_meta and options tables.
* Deletes post revisions.
* Removes sample comment(s).
* Closes global and post comments and pingbacks.

##Usage

Open config.php and define the following constants with the relevant information.

* Database name, user and password.
* Old site url.
* New site url.
* Wordpress table prefix.

Upload the migrate directory to the root of the Wordpress site, and navigate to it in the browser e.g. '{DOMAIN_NAME}/migrate'.