#Wordpress Database Migration

Update/tidy Wordpress database after domain migration. This script performs the following tasks:

* Updates absolute URLs in posts, post_meta and options tables.
* Deletes post revisions.
* Removes sample comments.
* Closes global and individual post comments and pingbacks.

##Usage

1. Upload the *migrate* directory to the root of your website.

2. Navigate to the script in your browser e.g. '{DOMAIN_NAME}/migrate'.

3. Define the following peices of information in the form or *config.php*:

- Database name
- Database user
- Database password
- Old site URL
- New site URL
- Wordpress table prefix

4. Submit the form to complete the process.