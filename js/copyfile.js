//function to copy the link of download ( only work on https )

function myFunction(clickedObject) {

    var copyText = clickedObject.getAttribute('data-link');
     // Copy the text inside the text field
    navigator.clipboard.writeText(copyText);
  
    // Alert the copied text
    alert("Copied the text: " + copyText);
  }
  