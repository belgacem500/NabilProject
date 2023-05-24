// Toggle password visibility
const userPasswordEl = document.querySelector("#password");
const userPasswordElnew = document.querySelector("#passwordnew");
const togglePasswordEl = document.querySelector("#togglePassword");

togglePasswordEl.addEventListener("click", function () {
  if (this.checked === true) {
    userPasswordEl.setAttribute("type", "text");
    userPasswordElnew.setAttribute("type", "text");
  } else {
    userPasswordEl.setAttribute("type", "password");
    userPasswordElnew.setAttribute("type", "password");
  }
});


function myFunction() {
  // Get the text field
  var copyText = document.getElementById("myInput");
  // Select the text field
  copyText.select();
  copyText.setSelectionRange(0, 99999); // For mobile devices

   // Copy the text inside the text field
  navigator.clipboard.writeText(copyText.value);

  // Alert the copied text
  alert("Copied the text: " + copyText.value);
}
// Function to generate password

//get input by id
var password=document.getElementById("password");

// Functions that generate password
 function genPassword() {
    var chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    var passwordLength = 12;
    var password = "";
 for (var i = 0; i <= passwordLength; i++) {
   var randomNumber = Math.floor(Math.random() * chars.length);
   password += chars.substring(randomNumber, randomNumber +1);
  }
        document.getElementById("password").value = password;
 }