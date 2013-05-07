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

define(["gmap/googlemap","jquery","gmap/overviewPathStrategy"], 
       (function(GoogleMap, $, OverviewPathStrategy) {
    	// --> hidden input fields which needs to be stored in DB
    		const REF_ADDTRIP_INPUT_STARTCOORD = 'input[name="startCoordinate"]';
    		const REF_ADDTRIP_INPUT_ENDCOORD = 'input[name="endCoordinate"]';
    		const REF_ADDTRIP_INPUT_DURATION = 'input[name="duration"]';
    		const REF_ADDTRIP_INPUT_DISTANCE = 'input[name="distance"]';
    		const REF_ADDTRIP_INPUT_OVERVIEW_PATH = 'input[name="overviewPath"]';
    		const REF_ADDTRIP_INPUT_VIA_WAYPOINTS = 'input[name="viaWaypoints"]';
    	   
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
        			this.inputStartCoord = ctrlOptions.input_start_coord || window.REF_ADDTRIP_INPUT_STARTCOORD;
        			this.inputEndCoord = ctrlOptions.input_end_coord || window.REF_ADDTRIP_INPUT_ENDCOORD;
    			} catch ( e ) {    				
    				throw e;
    			}; 
    			
    	   };
    	   
    	   /*
    	    * Handle the response of Google's DirectionsService.
    	    * @deprecated
    	    */
    	   MapController.prototype.handleRouteResponse  = function(directionsResult) {
    		   console.log("Map controller -> handling route response");
    		       		   
    		   /*
    		    * fetch and store coordinate of points
    		    */ 
    		   var inputStartCoord =  $ ( REF_ADDTRIP_INPUT_STARTCOORD );
    		   var inputEndCoord = $ ( 	REF_ADDTRIP_INPUT_ENDCOORD);
    		   var inputDuration = $ ( 	REF_ADDTRIP_INPUT_DURATION);
    		   var inputDistance = $ ( 	REF_ADDTRIP_INPUT_DISTANCE);
    		   var inputOverviewPath = $ ( 	REF_ADDTRIP_INPUT_OVERVIEW_PATH);
    		   var inputViaWaypoints = $ ( 	REF_ADDTRIP_INPUT_VIA_WAYPOINTS);
    		   
    		   var singleRoute = directionsResult.routes[0];    		 
    		   
    		   
    		   if(1 == singleRoute.legs.length) { // no waypoints, only start and endpoint
    			   var singleLeg = singleRoute.legs[0];
    			   var startLatLng = singleLeg.start_location;
    			   var endLatLng = singleLeg.end_location;
    			   var duration = singleLeg.duration.value; // seconds
    			   var distance = singleLeg.distance.value; // meter
    			   var overviewPath = singleRoute.overview_path;     	
    			   var viaWaypoints = singleLeg.via_waypoints;
    			   /*
    			    * ..:: OverviewPath strategy ::..
    			    */
    			   // change: active strategy in module overviewPathStrategy (return type)
    			   var overviewStrategy = new OverviewPathStrategy();
    			   var overviewString = overviewStrategy.execute(overviewPath);
    			   /*
    			    * ..::::::::::::::::::::::::::::..
    			    */
    			   /*
    			    * ..:: ViaWaypoints ::..
    			    */
    			   var waypointsStringConcat = "";
    			   for ( var waypointIndex = 0; waypointIndex < viaWaypoints.length; waypointIndex++) {
    				   // kb = latitude / breite; lb = longitude / länge
    				   waypointsStringConcat += viaWaypoints[waypointIndex].lat() + "," + viaWaypoints[waypointIndex].lng() + ";";
    			   }
    			   /*
    			    * ..::::::::::::::::::..
    			    */
    			   
    			   console.log("Map controller -> startLatLng: \n"+startLatLng);
    			   console.log("Map controller -> endLatLng: \n"+endLatLng);
    			   console.log("Map controller -> duration: \n"+duration);
    			   console.log("Map controller -> overviewPath: \n"+overviewString);
    			   console.log("Map controller -> viaWaypoints: \n"+waypointsStringConcat);
    			   
    			   /*
    			    * ..:: fill hidden input fields ::..
    			    */
    			   inputStartCoord.val(startLatLng); 
    			   inputEndCoord.val(endLatLng); 
    			   inputDuration.val(duration);
    			   inputDistance.val(distance);
    			   inputOverviewPath.val(overviewString);
    			   inputViaWaypoints.val(waypointsStringConcat);
    			   /*
    			    * ..::::::::::::::::::::::::::::::..
    			    */
    			   console.log("value of input field: "+inputStartCoord.val());
    		   }; 
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
//    		   this.gmap.showRoute( start, destination, $( "#sendBt" ), this.handleRouteResponse );  
    		  
    		   this.gmap.showRoute( start, destination, this.handleRouteResponse);  
    	   };    	   
    	   return MapController; // return constructor function
       })
       );