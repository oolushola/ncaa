$(function(){
  $("#addCertificationType").click(function(e){
      e.preventDefault();
      $certificationType = $("#certificationType").val();
      if($certificationType == ""){
          $("#loader").html('Applicant name is required.').addClass('error');
          return
      }
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/certification-type", $("#frmCertificationType").serialize(), function(data){
          if(data === 'saved') {
            $url= '/certification-type';
            window.location = $url;
          }
      });
  });

  $("#updateCertificationType").click(function(e){
      e.preventDefault();
      $certificationType = $("#certificationType").val();
      if($certificationType == ""){
          $("#loader").html('Certification type is required.').addClass('error');
          return
      }
      $id = $("#id").val();
      $("#loader").html('<img src="/images/ajax.gif">Please wait...').addClass('error');
      $.post("/certification-type/"+$id, $("#frmCertificationType").serialize(), function(data){
        if(data === 'updated') {
          $url= '/certification-type';
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
  $(".deleteCertificationType").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to delete "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error');
          $.post("/certification-type/"+$id, $("#frmCertificationType").serialize(), function(data){
             if(data=='cant_delete'){
                 $("#deleteLoader").html("<i class='mdi mdi-alert'></i> Sorry, certification type already in use").css({color:'green'});
                 return;
             }
             $url = '/certification-type';
             window.location = $url;
          })
      }
      return;
  });

})