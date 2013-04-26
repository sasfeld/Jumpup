/*
* Project: JumpUp
* Realm: Frontend
* 
* This is the handlemap module. It is used as controller for the Google map.
*
* gmap\handlemap.js
* Copyright (c) 2013 Martin Schultz & Sascha Feldmann
* license    GNU license
* version    1.0
* since      26.04.2013
 */

define(["gmap/googlemap"], 
       (function(GoogleMap) {
    	   
    	   /*
    	    * Constructor function for this module.
    	    * The google map will be initialized in the constructor.
    	    * - param options, see the google map reference
    	    * - throws an exception if the map isn't loaded
    	    */
    	   var MapController = function(mapsOptions, ctrlOptions) {
    		   try {
    				this.gmap = new GoogleMap( mapsOptions );
    				this.gmap.mapsLoaded(); // initialize maps    				
    			} catch ( e ) {    				
    				throw e;
    			}
    			
    			this.inputStartCoord = ctrlOptions.input_start_coord;
    			this.inputEndCoord = ctrlOptions.input_end_coord;
    			
    	   }
    	   
    	   /*
    	    * Handle the response of Google's DirectionsService.
    	    */
    	   MapController.prototype.handleRouteResponse  = function(directionsResult) {
    		   console.log("Map controller -> handling route response");
    		       		   
    		   /*
    		    * fetch and store coordinate of points
    		    */ 
    		   var singleRoute = directionsResult.routes[0];
    		   // array of LatLng values > could be interesting for us
    		   var overviewPath = directionsResult.overview_path;
    		   console.log("Map controller -> overviewPath: \n"+overviewPath);
    		   
    		   
    		   if(1 == singleRoute.legs.length) { // no waypoints, only start and endpoint
    			   var singleLeg = singleRoute.legs[0];
    			   var startLatLng = singleLeg.start_location;
    			   var endLatLng = singleLeg.end_location;
    			   console.log("Map controller -> startLatLng: \n"+startLatLng);
    			   console.log("Map controller -> endLatLng: \n"+endLatLng);
    			   this.inputStartCoord.val(startLatLng); // fill hidden input field
    			   this.inputStartCoord.val(endLatLng); // fill hidden input field
    			   
    		   }
    	   };
    	   
    	   /*
    	    * Show a single route on the map.
    	    * - param start, the value of the starting point, must be a coordinate or a valid location.
    	    * - param destination, the value of the destination point, must be a coordinate or a valid location.
    	    * - param waypoints, an array of waypoints, must be coordinates or valid locations
    	    */
    	   MapController.prototype.showSingleRoute = function(start, destination, waypoints) {
    		   // remove rendered routes
    		   var dirDisplay = this.gmap.directionsDisplay;
    		   if(null != dirDisplay) {
    			   dirDisplay.setMap(null);
    		   }
    		   // show new route
    		   this.gmap.showRoute( start, destination, $( "#sendBt" ), this.handleRouteResponse );  
    	   };    	   
    	   return MapController; // return constructor function
       })
       );