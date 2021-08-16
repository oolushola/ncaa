$(function(){
  $("#addForeignAirline").click(function(e){
      e.preventDefault();
      $foreign_airline = $("#foreign_airline").val();
      if($foreign_airline == ""){
          $("#loader").html('Foreign airline is required.').addClass('error');
          return
      }
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/foreign-airline", $("#frmForeignAirline").serialize(), function(data){
          if(data == 'exist'){
              $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$foreign_airline} already exists.`);
              return;
          }
          $url= '/foreign-airline';
          window.location = $url;
      });
  });

  $("#updateForeignAirline").click(function(e){
      e.preventDefault();
      $foreign_airline = $("#foreign_airline").val();
      if($foreign_airline == ""){
          $("#loader").html('Foreign airline is required.').addClass('error');
          return
      }
      $id = $("#id").val();
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/foreign-airline/"+$id, $("#frmForeignAirline").serialize(), function(data){
          if(data == 'exist'){
              $("#loader").html(`<i class=\"mdi mdi-alert\"></i> ${$foreign_airline} already exists.`);
              return;
          }
          $url= '/foreign-airline';
          window.location = $url;
      });
  });

  /**
   * click delete event
   * 
   * @request aircraft type id
   * 
   * @return string
   */
  $(".deleteforeignAirline").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error');
          $.post("/foreign-airline/"+$id, $("#frmDeleteforeignAirline").serialize(), function(data){
             if(data=='cant_delete'){
                 $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, this foreign airline cannot be delete. It's in use by one or more foreign airline DACL.").css({color:'green'});
                 return;
             }
             $url = '/foreign-airline';
             window.location = $url;
          })
      }
      return;
  });

})