/**
 * @author Marina
 */
 $(document).ready(function() {

 	ymaps.ready(init);
    var myMap, // карта
    	myPlacemark1; // 1 метка 
    	
	// Инициализация карты
	
	function init(){   

		myMap = new ymaps.Map("map", {
			center: [44.600150, 33.522512],
			zoom: 16
		});   

        // 1 метка
        
        myPlacemark1 = new ymaps.Placemark(
        	[44.600150, 33.522512], {},
        	{
	            // Иконка кластера
	            iconLayout: 'default#image',
	            iconImageHref: '/images/mark.png',
	            iconImageSize: [30, 47],
	            iconImageOffset: [-10, -47],
	            balloonAutoPanCheckZoomRange: true     
	        }
	        );
        
        myMap.geoObjects.add(myPlacemark1);	        
        myMap.behaviors.disable('scrollZoom');
    }
    
    
});