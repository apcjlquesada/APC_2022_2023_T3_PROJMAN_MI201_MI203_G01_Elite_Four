navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   profile.classList.remove('active');
}

profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

document.querySelectorAll('.faqs .row .faq .box h3').forEach(faqBox => {
   faqBox.onclick = () =>{
      faqBox.parentElement.classList.toggle('active');
   }
});

var swiper = new Swiper(".mySwiper", {
   slidesPerView: 1,
   spaceBetween: 30,
   loop: true,
   pagination: {
     el: ".swiper-pagination",
     clickable: true,
   },
   navigation: {
     nextEl: ".swiper-button-next",
     prevEl: ".swiper-button-prev",
   },
 });

 var swiper = new Swiper(".myPromos", {
   slidesPerView: 3,
   spaceBetween: 30,
   freeMode: true,
   pagination: {
     el: ".swiper-pagination",
     clickable: true,
   },
 });


// When the user clicks on the button,
// toggle between hiding and showing the dropdown content
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
document.addEventListener("click", function(event) {
  var dropdowns = document.getElementsByClassName("dropdown-content");
  for (var i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show') && !event.target.matches('.dropbtn')) {
      openDropdown.classList.remove('show');
    }
  }
});

 // Toggle Eye Password
function togglePass() {
  const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#id_password');
  
    togglePassword.addEventListener('click', function (e) {
      
  
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('fa-eye');
  });
  
}

// Toggle Eye Confirm Password
function togglePassConfirm() {
  const togglePassword = document.querySelector('#togglePasswordConfirm');
  const password = document.querySelector('#id_passwordconfirm');
  
    togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
  const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
  password.setAttribute('type', type);
  // toggle the eye slash icon
  this.classList.toggle('fa-eye');
  });

}

// Swiper Slider Review
var swiper = new Swiper(".review-slider", {
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 7500,
    disableOnInteraction: false,
  },
  loop:true,
  breakpoints: {
    0: {
        slidesPerView: 4,
    },
    300: {
      slidesPerView: 4,
    },
    500: {
      slidesPerView: 4,
    },
    700: {
      slidesPerView: 4,
    },
  },
});

// Filter Dates

function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.querySelector('.reservation-display-table');
  switching = true;
  while (switching) {
      switching = false;
      rows = table.rows;
      for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].querySelectorAll('td')[6];
          y = rows[i + 1].querySelectorAll('td')[6];
          if (document.querySelector('select').value === 'recent') {
              if (new Date(x.innerHTML) < new Date(y.innerHTML)) {
                  shouldSwitch = true;
                  break;
              }
          } else {
              if (new Date(x.innerHTML) > new Date(y.innerHTML)) {
                  shouldSwitch = true;
                  break;
              }
          }
      }
      if (shouldSwitch) {
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
      }
  }
}

// Gallery View Image








