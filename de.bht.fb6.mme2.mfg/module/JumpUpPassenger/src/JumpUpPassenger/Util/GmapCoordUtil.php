<?php
namespace JumpUpPassenger\Util;

use JumpUpDriver\Models\Trip;

/**
 *
 *
 *
 * This util class offers util methods to work with google's coordinates.
 *
 * For example, we offer a method to decode the coordinate string and to calculate distances.
 *
 * @package JumpUpPassenger\Util
 * @subpackage
 *
 *
 *
 *
 * @copyright Copyright (c) 2013 Sascha Feldmann (http://saschafeldmann.de)
 * @license GNU license
 * @version 1.0
 * @since 17.06.2013
 *       
 */
class GmapCoordUtil
{

    /**
     * Radius of the earth's equator.
     *
     * @var unknown
     */
    const EQUATOR_RADIUS = 6378.137;

    /**
     * key to access the latLng array for the latitute (BREITENGRAD)
     *
     * @var int
     */
    const LAT = 0;

    /**
     * key to access the latLng array for the longitude (LÄNGENGRAD)
     *
     * @var int
     */
    const LNG = 1;

    /**
     * Calculate the distance between two points given as coordinates.
     *
     * @param array $coord1
     *            an array with the two elements LAT and LNG. Set those via the constants above.
     * @param array $coord2
     *            an array with the two elements LAT and LNG. Set those via the constants above.
     * @return int the distance in kilometers between the given points
     */
    public static function calculateDistance(array $coord1, array $coord2)
    {
        $point1LatRad = deg2rad($coord1[self::LAT]);
        $point1LngRad = deg2rad($coord1[self::LNG]);
        $point2LatRad = deg2rad($coord2[self::LAT]);
        $point2LngRad = deg2rad($coord2[self::LNG]);
        
        // ARCCOS[ SIN(Breite1)*SIN(Breite2) + COS(Breite1)*COS(Breite2)*COS(Länge2-Länge1) ]
        $distanceBetween = self::EQUATOR_RADIUS * acos(sin($point1LatRad) * sin($point2LatRad) + cos($point1LatRad) * cos($point2LatRad) * cos($point2LngRad - $point1LngRad));
        return $distanceBetween;
    }

    /**
     * Get an latLng array for a given input string.
     *
     * @param String $inputCoord
     *            as delegated by googleMaps in the frontend (looks like: "(LAT,LNG)"
     * @return array with two elements (LAT and LNG). access via the constant keys above
     */
    public static function toLatLng($inputCoord)
    {
        $cleanString = str_replace("(", "", $inputCoord);
        $cleanString = str_replace(")", "", $cleanString);
        
        $returnArray = explode(",", $cleanString);
        return $returnArray;
    }

    /**
     * Calculate the recommended price for the passenger for a given trip and the given coords of the passenger.
     * 
     * @param Trip $trip
     *            the depending trip.
     * @param unknown $startCoord
     *            the startCoord (passenger's location) as given by googleMap.
     * @param unknown $endCoord
     *            the endCoord (passenger's desired destination) as given by googleMap.
     * @return the recommended price.
     */
    public static function calcPriceForPassenger(Trip $trip, $startCoord, $endCoord)
    {
        $startCoordLatLng = self::toLatLng($startCoord);
        $endCoordLatLng = self::toLatLng($endCoord);
        $distance = self::calculateDistance($startCoordLatLng, $endCoordLatLng); // double: kilometer
                
        // recommendedPrice = (priceSum / tripDistance in km) * distance of passenger's trip (normal rule of three)        
        $priceForTrip = (($trip->getPrice() / ($trip->getDistance()/1000)) * $distance) / $trip->getMaxSeats();
        // add 20 percent ;)
        $priceForTrip = $priceForTrip + 0.2 * $priceForTrip;
        // round to 2-digits after comma
        $priceForTrip = round($priceForTrip);
        return $priceForTrip;
    }
}

?>