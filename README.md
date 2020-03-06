# Wordpress Migrator

Import contents into your wordpress instance from another wordpress instance through API. (Can also be extended to other CMS, example. Drupal to Wordpress

## Setup

Destination Wordpress Instance (where blogs would be migrated to)

1. Install plugin 'Application Password' - https://wordpress.org/plugins/application-passwords/. This gives us the possibility of adding posts remotely since by default wordpress auth works with cookies

2. Add a new application password credential and insert username & password in .env (WP_USERNAME & WP_PASSWORD)

3. Change all other variables in .env. Each are described in the example file and an example is provided

4. Execute 'php index.php' in terminal or load index.php from browser

**Process is synchronous and is quite slow (especially if importing images and a single blog post have multiple images). Recommended tofirst eveluate the speed it runs, and adjust the amount it imports accordingly**


## Create a new importer

1. Create a new class in importers, implementing interface -> ImporterInterface

2. New Class, GetBlogs method should return an array of Blog classes (using php new type hint we can force this to be an array but not specific type, hence a validationis done while running migration to ensure the type is Blog)

3. Update loadImporter method in migrator.php. Just add a new case statement, to initialize your new importer

Thats It ! :) 


## .env details

HTML_MODIFICATION - 

{
    "img":
    {
        "wrapper": {
            "element":"div",
            "attributes": {
                "class": "feature"       
            }
        },
        "attributes": {
            "class": {
                "add":"img-responsive",
                "remove":"img"
            }
        }
    }
}
