
$(document).ready(function () {
  $("select").material_select();
  $(".dropdown-button").dropdown();
});

//function to handle the opening of the create client popup
function createClient() {
  var popup = document.getElementById("popup");
  popup.classList.toggle("hide");
}

//used to fill in the clients data(from the table) in form 
function getUser() {
  $("tr").on("click", function (e) {
    e.preventDefault();
    $(this).toggleClass("grey-text");
    data = $(this)
      .closest("tr")
      .find("td")
      .siblings()
      .map(function () {
        return $(this).text();
      })
      .toArray();

    var currIndex = 0;
    $(document).ready(function () {
      $(".input").each(function () {
        $(this).val(data[currIndex++]);
      });
    });

}



//used to fill in the contacts data(from the table) in form 
function getContact() {
  $("tr").on("click", function () {
    $(this).toggleClass("grey-text");
    data = $(this)
      .closest("tr")
      .find("td")
      .siblings()
      .map(function () {
        return $(this).text();
      })
      .toArray();


    var currIndex = 0;
    $("#contact-form").ready(function () {
      $(".input2").each(function () {
        $(this).val(data[currIndex++]);
      });
    });
  });
}
