// Waits for the page to fully load before running any js
$(document).ready(function()
{
  $(".tournament_wrap").click(function() {
    var tournament = $(this).find("h2:first").text();
    window.location.replace("./?page=tournament&tournament=" + tournament);
  });

  $(".tournament_wrap").hover(function() {
    $(this).css('cursor', 'pointer');
  });
});
