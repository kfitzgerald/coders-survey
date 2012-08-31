# The Coders' Survey

We have built this simple little survey management tool for the [»Coders´ Survey«](http://coders-survey.com). It's a simple multiple choice poll tool. Questions can be answered eather with checkboxes or radios.

The CMS features a creepy web based backend admin area to enter your questions and generate results. It is the result of playing around with some new PHP 5.4 features (namely traits).


# The Coders' Survey

We have built this simple little survey CMS for the [»Coders´ Survey«](http://coders-survey.com). It's a simple multiple choice poll tool. Your questions can be answered eather with checkboxes or radios.

The CMS features a creepy web based backend admin area to enter your questions and generate results.
>>>>>>> 72c4236695001333762a46d9b61096a223658f2f


## Licence

MIT


## System Requirements

* Apache
* PHP 5.4
* MySQL


### Credits / Dependencies

* Some parts of Twitter Bootstrap (CSS+JS) have been borrowed
* The PHP Slim Framework has been misused
* jQuery is used of course
* jQueryUI is used for the admin area


## Installation

# Download archive / clone git
# Upload to your webserver
# Import the database.sql into your MySQL database
# Go to http://your-host/admin
# The default admin credentials are:
#* User: admin
#* Password: admin
# Change the default login credentials
# Create pages, topics and options


## Templating, Styling

Sorry, there is no empty skin. Modify the existing one:

* CSS: /css/styles.css
** Also we kept the less files in /less/*
* PHP templates: lib/CodersSurveyApp/Views/*

## Configuration

Rename the file "config.php.example" to "config.php" and replace the dummy values with your MySQL access credentials.

### Using the Admin Area

 In this first level you can create, edit, delete and order the "pages". "Pages" are actually sections with an extra headline. They help to structure the poll. You can also run your whole survey with just one "page". In the second level you can edit the "page" title and create, edit delete and order "topics". "Topics" are the questions, but we thought it would be too simple when we just call em like that. In the third level however you can edit your "topics" and define the possible answers. Ah, by the way: answers are called "options", not answers. And there are also options for each "topic", like: what kind of answer is possible and if the topic is mandatory. We named these options "settings".

Not confused yet? Watch this: When you got your survey ready it is "open". That means: users can give their input. However you can still come back here and edit everything. Then you can "close" the survey. "Closing" means that users can't give any input any more. But that doesn't mean that they can see the results of the survey. You need to generate the results first. While you do this, you can define "groups". "Groups" are actaully a kind of filter. Let's say your survey has 100 "topics" (questions). Now you choose the gender question ("topic") in the "Generate Survey" dialouge to be a "group" (filter). So you see the results with three different views: Answers by all, answers by the boys, answers by the girls. 

