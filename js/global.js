// Waits for the page to fully load before running any js
$(document).ready(function()
{
  // Login dropdown button
  $("#loginbtn").click(function()
  {
    var panel = $("#login_drop");
    var btn = $("#loginbtn");

    // Slide login panel up if it already is displayed, or slides it down if it's not
    // Also change the color of the button
    if (panel.css("display") == "block")
    {
      panel.slideUp(250);
      btn.css({"background":"#181b2b","color":"#fff"});
    }
    else
    {
      panel.slideDown(250);
      btn.css({"background":"orange", "color":"#000"});
    }
  });

  // Show registration form when "create account" is clicked
  $("#create_account").click(function()
  {
    $('#login_form').css("display", "none");
    $('#registration_form').css("display","block");
    $('#rbtn').css("display", "none");
    $('#lbtn').css("display", "block");
  });

  // Show login form when "Already have an account? Log in" is clicked
  $('#log_in').click(function()
  {
    $('#login_form').css("display", "block");
    $('#registration_form').css("display", "none");
    $('#rbtn').css("display", "block");
    $('#lbtn').css("display", "none");
  });
});
