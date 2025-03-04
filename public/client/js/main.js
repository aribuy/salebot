(function (window, document, $, undefined) {
  "use strict";

  var saleBotJs = {
    i: function (e) {
      saleBotJs.d();
      saleBotJs.methods();
    },

    d: function (e) {
      (this._window = $(window)),
        (this._document = $(document)),
        (this._body = $("body")),
        (this._html = $("html"));
    },

    methods: function (e) {
      saleBotJs.headerSticky();
      saleBotJs.authorCardActive();
      saleBotJs.saveCardActive();
      saleBotJs.audioPlayerActivation();
      saleBotJs.emojiActivation();
      saleBotJs.audioPeeker();
      saleBotJs.mapActivation();
      saleBotJs.map2Activation();
      saleBotJs.magnigyPopup();
    },

    headerSticky: function () {
      $(window).scroll(function () {
          if ($(this).scrollTop() > 250) {
              $('.header-sticky').addClass('sticky')
          } else {
              $('.header-sticky').removeClass('sticky')
          }
      })
    },

    authorCardActive: function () {
      var selector = '.single-sp-author-card';

      $(selector).on('click', function(){
          $(selector).removeClass('active');
          $(this).addClass('active');
      });
    },

    saveCardActive: function () {
        var isSavedOpen = false;
    
        // Function to close the popup box
        function closePopupBox() {
            $('.savad-item-area').removeClass('show-item');
            isSavedOpen = false;
        }
    
        $('.button-saved').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission behavior
            $('.savad-item-area').toggleClass('show-item', !isSavedOpen);
            isSavedOpen = !isSavedOpen;
        });
    
        // Close the popup box when clicking outside of it
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.button-saved').length && !$(e.target).closest('.savad-item-area').length) {
                closePopupBox();
            }
        });
    },

    audioPlayerActivation: function () {
      document.addEventListener('DOMContentLoaded', () => {
          const player = new Plyr('#player');
      });
    },

    emojiActivation: function () {
        $('.picker').lsxEmojiPicker({
          width: 220,
          height: 200,
          twemoji:true,
          onSelect:function(emoji){
            console.log(emoji);
          }
        });
    },

    audioPeeker: function () {
        // Add event listener to mic button to toggle audio peeker area
        $('.mic-button-activatin').on('click', function (e) {
            e.preventDefault(); // Prevent default behavior of the button
            $('.audio-peeker-area').addClass('active');
        });
    
        // Add event listener to close button to remove active class from audio peeker area
        $('.close-audio-peeker').on('click', function (e) {
            e.preventDefault(); // Prevent default behavior of the button
            $('.audio-peeker-area').removeClass('active');
        });
    
        // Move other functionality inside $(document).ready() to ensure it executes after the DOM is loaded
        $(document).ready(function() {
            const audioPlayer = new Plyr('#audioPlayer');
            let mediaRecorder;
            let chunks = [];
    
            // Add event listeners for start and stop recording buttons
            $('#startRecording').on('click', startRecording);
            $('#stopRecording').on('click', stopRecording);
    
            function startRecording() {
                navigator.mediaDevices.getUserMedia({ audio: true })
                    .then(stream => {
                        mediaRecorder = new MediaRecorder(stream);
                        mediaRecorder.start();
    
                        mediaRecorder.ondataavailable = function(e) {
                            chunks.push(e.data);
                        }
    
                        mediaRecorder.onstop = function() {
                            const blob = new Blob(chunks, { type: 'audio/wav' });
                            chunks = [];
                            const audioURL = URL.createObjectURL(blob);
                            audioPlayer.source = {
                                type: 'audio',
                                sources: [{
                                    src: audioURL,
                                    type: 'audio/wav',
                                }],
                            };
                        }
                    })
                    .catch(err => console.error('Error accessing microphone:', err));
            }
    
            function stopRecording() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    mediaRecorder.stop();
                }
            }
        });
    },
    

    mapActivation: function () {
      var worldMapData = [
          {
              "Id": "10020",
              "PropertyCode": "HELHAK",
              "address": "Siltasaarenkatu 14",
              "latitude": 60.1791466,
              "longitude": 24.9473743,
              "GMapIconImage": "/assets/markers/marker.png",
              "type": "Hotel",
              "hotelName": "Cumulus Hakaniemi Helsinki"
          },
          {
              "Id": "10080",
              "PropertyCode": "HELKAI",
              "address": "Kaisaniemenkatu 7",
              "latitude": 60.1716867,
              "longitude": 24.9458183,
              "GMapIconImage": "/assets/markers/marker.png",
              "type": "Hotel",
              "hotelName": "Cumulus Kaisaniemi Helsinki"
          },
          {
              "Id": "10170",
              "PropertyCode": "HELMEI",
              "address": "Tukholmankatu 2",
              "latitude": 60.1910171,
              "longitude": 24.9090258,
              "GMapIconImage": "/assets/markers/marker.png",
              "type": "Hotel",
              "hotelName": "Cumulus Meilahti Helsinki"
          },
          {
              "Id": "10090",
              "PropertyCode": "HELOLY",
              "address": "Läntinen Brahenkatu 2",
              "latitude": 60.1868253,
              "longitude": 24.946055,
              "GMapIconImage": "/assets/markers/marker.png",
              "type": "Hotel",
              "hotelName": "Cumulus Kallio Helsinki"
          },
          {
              "Id": "10280",
              "PropertyCode": "HELSEU",
              "address": "Kaivokatu 12",
              "latitude": 60.1700957,
              "longitude": 24.9377173,
              "GMapIconImage": "/assets/markers/marker.png",
              "type": "Hotel",
              "hotelName": "Hotel Seurahuone Helsinki"
          }
      ];
  
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: new google.maps.LatLng(60.1791466, 24.9473743),
          mapTypeId: google.maps.MapTypeId.ROADMAP
      });
  
      var marker, i, markerContent,
          infowindow = new google.maps.InfoWindow();
  
      for (i = 0; i < worldMapData.length; i++) {
          marker = new google.maps.Marker({
              position: new google.maps.LatLng(worldMapData[i].latitude, worldMapData[i].longitude),
              map: map
          });
  
          google.maps.event.addListener(marker, 'click', (function (marker, i) {
              return function () {
                  markerContent = '<div><b>Hotel Name: </b> ' +
                      worldMapData[i].hotelName +
                      '</div><div><b>Address: </b>' +
                      worldMapData[i].address + '</div>';
  
                  infowindow.setContent(markerContent);
                  infowindow.open(map, marker);
              }
          })(marker, i));
      }
    },

    map2Activation: function () {
        var worldMapData2 = [
            {
                "Id": "1",
                "PropertyCode": "MirpurDOHS",
                "address": "Mirpur DOHS, Dhaka",
                "latitude": 23.8224,
                "longitude": 90.3673,
                "GMapIconImage": "/assets/markers/marker.png",
                "type": "Hotel",
                "hotelName": "Sample Hotel Mirpur DOHS"
            }
            // Add more data if needed
        ];
    
        var map = new google.maps.Map(document.getElementById('map2'), {
            zoom: 12,
            center: new google.maps.LatLng(23.8224, 90.3673),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    
        var marker, i, markerContent,
            infowindow = new google.maps.InfoWindow();
    
        for (i = 0; i < worldMapData2.length; i++) {
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(worldMapData2[i].latitude, worldMapData2[i].longitude),
                map: map
            });
    
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
                return function () {
                    markerContent = '<div><b>Hotel Name: </b> ' +
                        worldMapData2[i].hotelName +
                        '</div><div><b>Address: </b>' +
                        worldMapData2[i].address + '</div>';
    
                    infowindow.setContent(markerContent);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    },

    magnigyPopup: function () {
        $(document).on('ready', function () {
            $('.popup-video').magnificPopup({
                type: 'iframe'
            });
        });
        $(document).ready(function() {
            $('.image-link').magnificPopup({
                type: 'image',
                gallery:{
                    enabled:true
                }
            });
        });
    },

  };
  saleBotJs.i();
})(window, document, jQuery);




document.querySelectorAll('.dropdown-toggle').forEach(function (dropdown) {
    dropdown.addEventListener('click', function () {
        dropdown.nextElementSibling.classList.toggle('show');
    });
});