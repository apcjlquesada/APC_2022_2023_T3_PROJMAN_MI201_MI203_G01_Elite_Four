let body = document.body;
let sideBar = document.querySelector('.side-bar');

document.querySelector('#menu-btn').onclick = () =>{
   sideBar.classList.toggle('active');
   body.classList.toggle('active');
}

document.querySelector('#close-btn').onclick = () =>{
   sideBar.classList.remove('active');
   body.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');

   if(window.innerWidth < 1200){
      sideBar.classList.remove('active');
      body.classList.remove('active');
   }
}

/* Drop Down */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}


// Toggle Password 
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
function togglePassConfirms() {
  const togglePassConfirms = document.querySelector('#togglePasswordConfirm');
    const confirmpassword = document.querySelector('#id_passwordconfirm');
    togglePassConfirms.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'cpassword' ? 'text' : 'cpassword';
      confirmpassword.setAttribute('type', type);
      // toggle the eye slash icon
      this.classList.toggle('fa-eye');
  });
}


/* Modal Pop up */

// Get the modal
var modal = document.getElementById("ReplyModal");

// Get the button that opens the modal
var btn = document.querySelectorAll(".dropdown-content3 a")[0];

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

//search

$(document).ready(function() {
  $('#search-form').on('submit', function(e) {
    e.preventDefault();
    var searchQuery = $('#search-input').val();
    $.ajax({
      type: 'GET',
      url: 'search.php',
      data: {search_query: searchQuery},
      success: function(response) {
        $('#search-results').html(response);
      }
    });
  });

  $('#search-input').on('input', function() {
    if ($(this).val() === '') {
      $.ajax({
        type: 'GET',
        url: 'search.php',
        data: {search_query: ''},
        success: function(response) {
          $('#search-results').html(response);
        }
      });
    }
  });
});

// PAGINATION
$(document).ready(function() {
  var currentPage = 1; // Set the current page number here
  var totalPages = 10; // Set the total number of pages here
  var searchQuery = ""; // Set the search query string here
  
  function generatePages() {
      $(".pagination .pages").empty();
      
      for (var i = 1; i <= totalPages; i++) {
          if (i == currentPage) {
              $(".pagination .pages").append('<li class="active"><a href="#">' + i + '</a></li>');
          } else {
              $(".pagination .pages").append('<li><a href="#" data-page="' + i + '">' + i + '</a></li>');
          }
      }
  }
  
  generatePages();
  
  $(".pagination .pages").on("click", "a", function(e) {
      e.preventDefault();
      currentPage = parseInt($(this).attr("data-page"));
      $("#search-form").attr("action",+ currentPage);
      $("#search-form").submit();
  });
  
  $(".pagination .prev").on("click", function(e) {
      e.preventDefault();
      if (currentPage > 1) {
          currentPage--;
          $("#search-form").attr("action", + currentPage);
          $("#search-form").submit();
      }
  });
  
  $(".pagination .next").on("click", function(e) {
      e.preventDefault();
      if (currentPage < totalPages) {
          currentPage++;
          $("#search-form").attr("action",  + currentPage);
          $("#search-form").submit();
      }
  });
});

// Filter date



function sortTable() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.querySelector('.reservations-display-table');
  switching = true;
  while (switching) {
      switching = false;
      rows = table.rows;
      for (i = 1; i < (rows.length - 1); i++) {
          shouldSwitch = false;
          x = rows[i].querySelectorAll('td')[6];
          y = rows[i + 1].querySelectorAll('td')[6];
          if (document.querySelector('select').value === 'recentUpdated') {
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

// Sort names A-Z

function sortName() {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.querySelector('.table-display-table');
  switching = true;
  while (switching) {
    switching = false;
    rows = table.rows;
    for (i = 1; i < (rows.length - 1); i++) {
      shouldSwitch = false;
      x = rows[i].querySelectorAll('td')[1];
      y = rows[i + 1].querySelectorAll('td')[1];
      if (document.querySelector('select').value === 'a') {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          shouldSwitch = true;
          break;
        }
      } else {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
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

// download report 


    document.querySelector('.btn-filter').addEventListener('click', function(event) {
        event.preventDefault();

        // Get the selected month from the form
        var selectedMonth = document.getElementById('start5').value;

        // Submit the form to the server-side script that generates the CSV file
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'generate_csv.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                // Create a download link for the generated CSV file
                var downloadLink = document.createElement('a');
                downloadLink.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(xhr.responseText);
                downloadLink.download = 'data.csv';
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }
        };
        xhr.send('selected_month=' + selectedMonth);
    });

