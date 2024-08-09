
window.rcmail && rcmail.addEventListener('init', function(evt) {
  // console.log('test plugin Loaded')

  var form = document.getElementById('test_form');
  var submitButton = document.getElementById('submit_button');
  var input1 = document.getElementById('input1');
  var input2 = document.getElementById('input2');
  var input3 = document.getElementById('input3');
  var inputs = document.querySelectorAll('.form-control');

  // Function to validate form
  function validateForm() {
    console.log('validate')
      // Example validation: check if the input field is not empty
      if (input1.value.trim() !== '' && input2.value.trim() !== '' && input3.value.trim() !== '') {
          submitButton.disabled = false; // Enable the submit button
      } else {
          submitButton.disabled = true; // Keep the submit button disabled
      }
  }

  // Attach event listeners
  inputs.forEach(function(input) {
    input.addEventListener('input', validateForm);
  });

  // Optionally, you can validate the form on page load
  // validateForm();


  rcmail.register_command('plugin.test_submit', function() {
    console.log("test submitted")
    //find objects in html passing the name as a parameter
    var input1 = rcube_find_object('input1'),
    input2 = rcube_find_object('input2'),
    input3 = rcube_find_object('input3');  

    //input controls
    if (input1 && input1.value == '') {
      rcmail.alert_dialog('input1 vuoto', function() {
          input1.focus();
          return true;
      }); 
    }else if (input2 && input2.value == '') {
      rcmail.alert_dialog('input1 vuoto', function() {
          input2.focus();
          return true;
      }); 
    }else if(input3 && input3.value == ''){
      rcmail.alert_dialog('input1 vuoto', function() {
        input3.focus();
        return true;
      }); 
    }else{
      rcmail.alert_dialog('tutti gli input sono stati compilati correttamente')
    }
  })

  if (window.rcmail) {
    // reload page after ca. 3 minutes
    rcmail.reload(3 * 60 * 1000 - 2000);
    return;
  }
})


//  if (window.rcmail) {
//    rcmail.addEventListener('init', function(evt) {
//     var tabtwofactorgauthenticator = $('<li>')
// 		.attr('id', 'test')
// 		.addClass('listitem test');
// 	  var button = $('<a>')
// 		.attr('href', rcmail.env.comm_path + '&_action=plugin.simpleplugin')
// 		.html(rcmail.gettext('simpleplugin', 'simpleplugin'))
// 		.attr('role', 'button') 
// 		.attr('onclick', 'return rcmail.command(\'show\', \'plugin.simpleplugin\', this, event)') 
// 		.attr('tabindex', '0') 
// 		.attr('aria-disabled', 'false')
// 		.appendTo(tabtwofactorgauthenticator);
  
// 	  // Button & Register commands
// 	  rcmail.add_element(tabtwofactorgauthenticator, 'tabs');
//   });
// }
