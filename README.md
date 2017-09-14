# distilledbeer

MVC SOA PHP app that connects to the brewerydb API to provide a random beer and a search by beer and brewery.

URLs:
- / random beer page
- /index.php/search search page

SETUP:
- run `composer install` to install the dependencies

NOTES:

The app has been built by writing a custom small framework using some Symfony components and using composer's autoloader.

A  few improvements could be done:
- rewrite /index.php/url to /url
- make the app a sigle page application with frontend in Angular/React, making the PHP app an API to serve the required data.
- Some design patterns have been implemented: Factory, Adapter and more could be implemented.
For example, Observer pattern, triggering events, for example on dispatch of a request;
Chain of responsibility pattern for the form validation, which now is contained in one single class.
- The call to the random API is a bit bruteforce and blocks the request until it has completed, could be improved, for example, by making the call asynchronous.
- The app handles invalid urls but does not have an exception handling, one could be added at application level.
- Handle the end of the free API quota. For example, each randomly fetched beer could be saved in a database and then random beers could be selected from the local db until the quota is refreshed, to keep the service up.
One drawback is that the local data can become obsolete so it should be updated.
- add a DI container to handle dependencies better.
- the SFramework could be located in a separated github repo and imported through composer but it's very rustic so no point.
- more tests!
- use PHP7! Even if I know the new features that PHP7 offers, I still have PHP 5.6 on my laptop. Putting the app in a docker container with PHP7 and the webserver setup and running could be great.
- and probably more...
