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
