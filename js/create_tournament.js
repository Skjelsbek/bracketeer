// Waits for the page to fully load before running any js
$(document).ready(function()
{
  // Adding placeholder with line shifts to participants textarea
  var placeholder = 'Add\nBuddies\nDownward\nLike\nThis\n:)';
  $('#participantsta').attr('placeholder', placeholder);

  // Appending select options to team_size select
  for (var i = 1; i <= 20; i++)
  {
    $('#team_size').append('<option value="' + i + '">' + i + '</option>');
  }

  $("#ctf").submit(function(e)
  {
    if (!$('#tname').val().length > 0)
    {
      $('#tname').css('border-color', '#FF0000');
      e.preventDefault(e);
    }
    else
    {
      $('#tname').css('border', '2px inset #EBE9ED');
    }

    if (!$('#sname').val().length > 0)
    {
      $('#sname').css('border-color', '#FF0000');
      e.preventDefault(e);
    }
    else
    {
      $('#sname').css('border', '2px inset #EBE9ED');
    }

    if (!$('#participantsta').val().length > 0)
    {
      $('#participantsta').css('border-color', '#FF0000');
      e.preventDefault(e);
    }
    else
    {
      $('#participantsta').css('border', '2px inset #EBE9ED');
    }

    if ($('#team_size').val() == "Team Size")
    {
      $('#team_size').css('border-color', '#FF0000');
      e.preventDefault(e);
    }
    else {
      $('#team_size').css('border', '2px inset #EBE9ED');
    }
  });
});
