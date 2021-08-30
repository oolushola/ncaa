$(function(){
  $("#addApplicantName").click(function(e){
      e.preventDefault();
      $applicantName = $("#applicantName").val();
      if($applicantName == ""){
          $("#loader").html('Applicant name is required.').addClass('error');
          return
      }
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/applicant-name", $("#frmApplicantName").serialize(), function(data){
          if(data === 'saved') {
            $url= '/applicant-name';
            window.location = $url;
          }
      });
  });

  $("#updateApplicantName").click(function(e){
      e.preventDefault();
      $applicantName = $("#applicantName").val();
      if($applicantName == ""){
          $("#loader").html('Applicant name is required.').addClass('error');
          return
      }
      $id = $("#id").val();
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/applicant-name/"+$id, $("#frmApplicantName").serialize(), function(data){
        if(data === 'updated') {
          $url= '/applicant-name';
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
  $(".deleteApplicantName").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error');
          $.post("/applicant-name/"+$id, $("#frmDeleteApplicantName").serialize(), function(data){
             if(data=='cant_delete'){
                 $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, applicant name already in use").css({color:'green'});
                 return;
             }
             $url = '/applicant-name';
             window.location = $url;
          })
      }
      return;
  });

})