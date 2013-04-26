var map;
var infowindow;
var directionsService;
var directionsDisplay;
var geocoder;
var infoWindow;
var marker;
// The list of languages is taken from:
// https://developers.google.com/maps/faq#languagesupport.
var supportedLanguages = {
	'ar' : 'ARABIC',
	'eu' : 'BASQUE',
	'bg' : 'BULGARIAN',
	'bn' : 'BENGALI',
	'ca' : 'CATALAN',
	'cs' : 'CZECH',
	'da' : 'DANISH',
	'de' : 'GERMAN',
	'el' : 'GREEK',
	'en' : 'ENGLISH',
	'en-AU' : 'ENGLISH (AUSTRALIAN)',
	'en-GB' : 'ENGLISH (GREAT BRITAIN)',
	'es' : 'SPANISH',
	'eu' : 'BASQUE',
	'fa' : 'FARSI',
	'fi' : 'FINNISH',
	'fil' : 'FILIPINO',
	'fr' : 'FRENCH',
	'gl' : 'GALICIAN',
	'gu' : 'GUJARATI',
	'hi' : 'HINDI',
	'hr' : 'CROATIAN',
	'hu' : 'HUNGARIAN',
	'id' : 'INDONESIAN',
	'it' : 'ITALIAN',
	'iw' : 'HEBREW',
	'ja' : 'JAPANESE',
	'kn' : 'KANNADA',
	'ko' : 'KOREAN',
	'lt' : 'LITHUANIAN',
	'lv' : 'LATVIAN',
	'ml' : 'MALAYALAM',
	'mr' : 'MARATHI',
	'nl' : 'DUTCH',
	'no' : 'NORWEGIAN',
	'pl' : 'POLISH',
	'pt' : 'PORTUGUESE',
	'pt-BR' : 'PORTUGUESE (BRAZIL)',
	'pt-PT' : 'PORTUGUESE (PORTUGAL)',
	'ro' : 'ROMANIAN',
	'ru' : 'RUSSIAN',
	'sk' : 'SLOVAK',
	'sl' : 'SLOVENIAN',
	'sr' : 'SERBIAN',
	'sv' : 'SWEDISH',
	'tl' : 'TAGALOG',
	'ta' : 'TAMIL',
	'te' : 'TELUGU',
	'th' : 'THAI',
	'tr' : 'TURKISH',
	'uk' : 'UKRAINIAN',
	'vi' : 'VIETNAMESE',
	'zh-CN' : 'CHINESE (SIMPLIFIED)',
	'zh-TW' : 'CHINESE (TRADITIONAL)'
};

function initialize() {
	// Populate the dropdown.
	var selectLanguage = document.getElementById('language');
	selectLanguage.options[0] = new Option('Choose language to load the map', '',
			true, true);
	var i = 1;
	for ( var langCode in supportedLanguages) {
		var language = supportedLanguages[langCode];
		selectLanguage.options[i] = new Option(language, langCode, false, false);
		i += 1;
	}
	selectLanguage.onchange = function() {
		var langCode = this.options[this.selectedIndex].value;
		if (langCode && supportedLanguages[langCode]) {
			var currentLanguage = supportedLanguages[langCode];
			document.getElementById('chosen_lang').innerHTML += currentLanguage;
			document.getElementById('start_div').className = 'hidden';
			document.getElementById('map_div').className = 'visible';
			loadMap(langCode);
		}
	};
}

function mapsLoaded() {
	var refreshLink = document.getElementById('refresh');
	refreshLink.addEventListener('click', function() {
		window.location.reload();
	});
	directionsService = new google.maps.DirectionsService();
	geocoder = new google.maps.Geocoder();
	infowindow = new google.maps.InfoWindow();
	var torun = new google.maps.LatLng(53.01357, 18.597665);
	map = new google.maps.Map(document.getElementById('map_canvas'), {
		mapTypeId : google.maps.MapTypeId.ROADMAP,
		center : torun,
		zoom : 5
	});
	google.maps.event
			.addListener(map, 'click', function(e) {
				geocoder
						.geocode({
							'latLng' : e.latLng
						}, function(results, status) {
							if (status == google.maps.GeocoderStatus.OK) {
								if (results[0]) {
									if (marker) {
										marker.setPosition(e.latLng);
									} else {
										marker = new google.maps.Marker({
											position : e.latLng,
											map : map
										});
									}
									infowindow.setContent(results[0].formatted_address);
									infowindow.open(map, marker);
								} else {
									document.getElementById('geocoding').innerHTML = 'No results found';
								}
							} else {
								document.getElementById('geocoding').innerHTML = 'Geocoder failed due to: '
										+ status;
							}
						});
			});
	showDirections();
}

function showDirections() {
	directionsDisplay = new google.maps.DirectionsRenderer({
		map : map,
		preserveViewport : true,
		draggable : true
	});
	directionsDisplay.setPanel(document.getElementById('textbox'));
	var sampleRequest = {
		origin : 'Warsaw, Poland',
		destination : 'Berlin, Germany',
		travelMode : google.maps.TravelMode.DRIVING,
		unitSystem : google.maps.UnitSystem.METRIC
	};
	directionsService.route(sampleRequest, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
		}
	});
}

function loadMap(langCode) {
	google.load('maps', '3.7', {
		'other_params' : 'sensor=false&libraries=places&language=' + langCode,
		'callback' : mapsLoaded
	});
}