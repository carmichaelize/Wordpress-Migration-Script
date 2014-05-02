#Wordpress Database Migration

Update/tidy Wordpress database after domain migration. This script performs the following tasks:

* Updates absolute URLs in posts, post_meta and options tables.
* Deletes post revisions.
* Removes sample comments.
* Closes global and individual post comments and pingbacks.

##Usage

1. Upload the *migrate* directory to the root of your website.
2. Navigate to the script in your browser e.g. *'{DOMAIN_NAME}/migrate'*.
3. Define database name, database user, database password, old site URL, new site URL and wordpress table prefix in the form or *config.php*:
4. Submit the form to complete the process.
5. **Important!** Remember to delete the migrate directory when finished.