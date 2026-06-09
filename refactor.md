refactor the whole package 

only keep the health / version and database methods via get 

keep all dtos and reuse them for the 


the rest gets changed to authentication with a token and they are hitting the api endopoint not the session ones. Remove everything thats related to sessions and not needed (Requests Responses Tests) for the API.

I put the Url and API Key in the Env for you. Test Every Endpoint Live with this Keys.

all the methods that are currently in the package should be also usable (I need this Endpoints for other apps!)