(function() {
    var $divs = $('#gallery div');
    var $buttons = $('#buttons');
    var tagged = {};

    $divs.each(function() {
        var div = this;
        var tags = $(this).data('tags');

        if (tags) {
            tags.split(',').forEach(function(tagName) {
            if (tagged[tagName] == null) {
                tagged[tagName] = [];                
            }
            tagged[tagName].push(div);
            });
        }
    });

    console.log(tagged);

    $('<button/>', {                                   // Create empty button
        text: 'Show All',                              // Add text 'show all'
        class: 'active',                               // Make it active
        click: function() {                            // Add onclick handler to
          $(this)                                      // Get the clicked on button
            .addClass('active')                        // Add the class of active
            .siblings()                                // Get its siblings
            .removeClass('active');                    // Remove active from siblings
          $divs.show();                                // Show all images
        }
      }).appendTo($buttons);                           // Add to buttons
    
      $.each(tagged, function(tagName) {               // For each tag name
        $('<button/>', {    
          text: tagName,                               // Create empty button
          //text: tagName + ' (' + tagged[tagName].length + ')',      // Add tag name
          click: function() {                          // Add click handler
            $(this)                                    // The button clicked on
              .addClass('active')                      // Make clicked item active
              .siblings()                              // Get its siblings
              .removeClass('active');                  // Remove active from siblings
            $divs                                      // With all of the images
              .hide()                                  // Hide them
              .filter(tagged[tagName])                 // Find ones with this tag
              .show();                                 // Show just those images
          }
        }).appendTo($buttons);                         // Add to the buttons
      });

}());