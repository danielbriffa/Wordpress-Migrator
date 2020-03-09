# Wordpress Migrator

Import contents into your wordpress instance from another wordpress instance through API. (Can also be extended to other CMS, example. Drupal to Wordpress

### Features

1. Import content from an external instance

2. Find attachments and import them as well (while migrating). This will change the URLs in the content,so they would load fully from your new site

3. Change DOM of your migrated content. 
    - Add and / remove attributes for selected elements. 
    - Wrap an element with another element type (example wrap all your images in a div)

### Setup

Destination Wordpress Instance (where blogs would be migrated to)

1. Install plugin 'Application Password' - https://wordpress.org/plugins/application-passwords/. This gives us the possibility of adding posts remotely since by default wordpress auth works with cookies

2. Add a new application password credential and insert username & password in .env (WP_USERNAME & WP_PASSWORD)

3. Change all other variables in .env. Each are described in the example file and an example is provided

4. Execute 'php index.php' in terminal or load index.php from browser

**Process is synchronous and speed is dependent on connection, which sometimes can be slow (especially if importing images and a single blog post have multiple images). Recommended to first eveluate the speed it runs, and adjust the amount it imports accordingly**


### Extending it and Create a new importer

1. Create a new class in importers, implementing interface -> ImporterInterface

2. New Class, GetBlogs method should return an array of Blog classes (using php new type hint we can force this to be an array but not specific type, hence a validation is done while running migration to ensure the type is Blog)

3. Update loadImporter method in migrator.php. Just add a new case statement, to initialize your new importer

Any help needed, mail me and would be glad to help

Thats It ! :) 


### .env details

HTML_MODIFICATION - 

```javascript
{
    "img":
    {
        "wrapper": { // all images would be wrapped in a div element having class 'feature'
            "element":"div",
            "attributes": {
                "class": "feature"       
            }
        },
        "attributes": { // all images will be added class 'img-responsive', and 'img' class removed
            "class": {
                "add":"img-responsive",
                "remove":"img"
            }
        }
    }
}
```