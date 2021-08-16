$(function(){
  $("#addTeamMembers").click(function(e){
      e.preventDefault();
      $title = $("#title").val();
      if($title == ""){
          $("#loader").html('Title of team member  is required.').addClass('error');
          return
      }
      $firstName = $("#firstName").val();
      if($firstName == ""){
          $("#loader").html('First name of team member title is required.').addClass('error');
          return
      }
      $lastName = $("#lastName").val();
      if($lastName == ""){
          $("#loader").html('Last name of team member title is required.').addClass('error');
          return
      }
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/team-members", $("#frmTeamMembers").serialize(), function(data){
          $url= '/team-members';
          window.location = $url;
      });
  });

  $("#updateTeamMembers").click(function(e){
      e.preventDefault();
      $title = $("#title").val();
      if($title == ""){
          $("#loader").html('Team member title is required.').addClass('error');
          return
      }
      $firstName = $("#firstName").val();
      if($firstName == ""){
          $("#loader").html('First name of team member title is required.').addClass('error');
          return
      }
      $lastName = $("#lastName").val();
      if($lastName == ""){
          $("#loader").html('Last name of team member title is required.').addClass('error');
          return
      }
      $id = $("#id").val();
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/team-members/"+$id, $("#frmTeamMembers").serialize(), function(data){
        if(data === 'updated') {
          $url= '/team-members';
          window.location = $url;
        }
      });
  });

  /**
   * click delete event
   * 
   * @request aircraft type id
   * 
   * @return string
   */
  $(".deleteTeamMember").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error');
          $.post("/team-members/"+$id, $("#frmDeleteTeamMember").serialize(), function(data){
             if(data=='cant_delete'){
                 $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, this foreign airline cannot be delete. It's in use by one or more foreign airline DACL.").css({color:'green'});
                 return;
             }
             $url = '/team-members';
             window.location = $url;
          })
      }
      return;
  });

})