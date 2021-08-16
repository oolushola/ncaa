$(function(){
  $("#addCpm").click(function(e){
      e.preventDefault();
      $firstName = $("#firstName").val();
      if($firstName == ""){
          $("#loader").html('First name of cpm is required.').addClass('error');
          return
      }
      $lastName = $("#lastName").val();
      if($lastName == ""){
          $("#loader").html('Last name of cpm is required.').addClass('error');
          return
      }
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/cpm", $("#frmCpm").serialize(), function(data){
          $url= '/cpm';
          window.location = $url;
      });
  });

  $("#updateCpm").click(function(e){
      e.preventDefault();
      $firstName = $("#firstName").val();
      if($firstName == ""){
          $("#loader").html('First name of cpm is required.').addClass('error');
          return
      }
      $lastName = $("#lastName").val();
      if($lastName == ""){
          $("#loader").html('Last name of cpm is required.').addClass('error');
          return
      }
      $id = $("#id").val();
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/cpm/"+$id, $("#frmCpm").serialize(), function(data){
        if(data === 'updated') {
          $url= '/cpm';
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
  $(".deleteCpm").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error');
          $.post("/cpm/"+$id, $("#frmDeleteCpm").serialize(), function(data){
             if(data=='cant_delete'){
                 $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, this foreign airline cannot be delete. It's in use by one or more foreign airline DACL.").css({color:'green'});
                 return;
             }
             $url = '/cpm';
             window.location = $url;
          })
      }
      return;
  });

})