> The final code will be pushed the next weeks.


# The Coders' Survey

We have built this simple little survey CMS for the [»Coders´ Survey«](http://coders-survey.com). It's a simple multiple choice poll tool. Your questions can be answered eather with checkboxes or radios.

The CMS features a creepy web based backend admin area to enter your questions and generate results.


## Licence

Feel free to rip, mix and burn it.


## System Requirements

* a webserver running PHP 5.4
* a MySql database


### Credits / Dependencies

* Some parts of Twitter Bootstrap (CSS+JS) have been borrowed
* The PHP Slim Framework has been misused
* jQuery is used of course
* jQueryUI is used for the admin area


## Installation

# Put the code somewhere on a web server
# Import the .sql database file
# Enter the admin area @ <your url>/admin
# The default login for the admin is: XXX/XXX
# Change the default login credentials
# Set up your survey in the admin
# Profit


## Templating

Sorry, there is no empty skin.


## Configuration

Rename the file "config.php.example" to "config.php" and enter all your credentials there.


## Enter the Admin Area

* Set the admin credentials in: ???
* Access the backend via YOUR-URL/admin


### Using the Admin Area

 In this first level you can create, edit, delete and order the "pages". "Pages" are actually sections with an extra headline. They help to structure the poll. You can also run your whole survey with just one "page". In the second level you can edit the "page" title and create, edit delete and order "topics". "Topics" are the questions, but we thought it would be too simple when we just call em like that. In the third level however you can edit your "topics" and define the possible answers. Ah, by the way: answers are called "options", not answers. And there are also options for each "topic", like: what kind of answer is possible and if the topic is mandatory. We named these options "settings".

Not confused yet? Watch this: When you got your survey ready it is "open". That means: users can give their input. However you can still come back here and edit everything. Then you can "close" the survey. "Closing" means that users can't give any input any more. But that doesn't mean that they can see the results of the survey. You need to generate the results first. While you do this, you can define "groups". "Groups" are actaully a kind of filter. Let's say your survey has 100 "topics" (questions). Now you choose the gender question ("topic") in the "Generate Survey" dialouge to be a "group" (filter). So you see the results with three different views: Answers by all, answers by the boys, answers by the girls. 


## Styling the look & feel

Sorry, the CSS file is packed. But you can edit the Less-CSS files in the less folder.
