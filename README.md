# FlightFinder
Flight Finder is a web app to help users find the cheapest flights for trips. Flight Finder allows the user to pick origin, and destination location, departure date, return date, and currency to fit flight preferences.

# File Explanations:
index.html: This file takes in user request information to send to the backend PHP to process.

flight_results.php: This file sends request information from index.html to the SkyScanner API to get results. The results are then parsed and stored into arrays which are used within the bottom html section to display on the web app.

city_search.php: This takes in the initial request of what the user types into the origin and destination fields on index.html, which is then queried in the SkyScanner API and returned to the index.html webpage as suggestions in an autocomplete format.

# Deployment: 
To deploy flight finder, please download XAMPP and enable the local server (open XAMPP manager / control file then go to Manage Servers then make sure Apache Web Server is running). Then download the attached zip file and move the files into the htdocs folder (inside the XAMPP folder). Then for the best experience, use Google Chrome and go to http://localhost/flightapp/index.html to view Flight Finder.

# Explanation: 
To create Flight Finder, I used PHP, HTML, and Javascript. Inputs by the user were taken and used to query the API. The first time for finding origin and destination options (a hidden value (the IATA code) is used to query the API while the name of the airport is shown in the query) through and autocomplete feature and the second time the API is queried is when it takes all input values and searches for flights between the two locations. When querying the API, I had to parse the information from the JSON result then push the information into arrays. Once the information I needed was in the array, I used that to create elements on the Flight Finder results page to display all the options for flights. Please see comments within code files for more information.
