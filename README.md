#Setup

Destination Wordpress Instance (where blogs would be migrated to)
1. Install plugin 'Application Password' - https://wordpress.org/plugins/application-passwords/. This gives us the possibility of adding posts remotely since by default wordpress auth works with cookies
2. Add a new application password credential and insert username & password in .env (WP_USERNAME & WP_PASSWORD)
3. Change all other variables in .env. Each are described in the example file and an example is provided