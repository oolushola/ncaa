$(function($){
  $("#addFcop").click(function(event){
      event.preventDefault();
      if(validatFcopRequest()==false) return;
      $("#frmFcop").submit();
  });

  $("#updateFcop").click(function(event){  
      event.preventDefault();
      if(validatFcopRequest()==false) return;
      $("#frmFcop").submit();
  });

  $('.part18').click(function() {
    $("#part18").val($(this).val())
  })
  $('.part17').click(function() {
    $("#part17").val($(this).val())
  })
  $('.part10').click(function() {
    $("#part10").val($(this).val())
  })
  $('.fcopStatus').click(function() {
    $("#fcopStatus").val($(this).val())
  })

  $("#changePhoto").click(function() {
      $(this).css({display:'none'});
      $("#file").removeAttr('disabled', 'disabled');
      $("#photoChecker").val(1);
  })

  function validatFcopRequest() {
      $foreignAirline = $("#foreignAirline").val();
      if($foreignAirline == ''){
        $("#loader").html("Foreign airline is required").addClass('error');
        return false;
      }

      $licenceNumber = $("#licenceNumber").val();
      if($licenceNumber == ""){
        $("#loader").html("Licence number  is required").addClass('error');
        return false;
      }

      $file = $("#file").val();
      $photoChecker = $("#photoChecker").val();
      if($file == "" && $photoChecker == 0) {
        $("#loader").html("Fcop certificate upload is required").addClass('error');
        return false;
      }
      else{
        if($file == "" && $photoChecker == 1){
            $("#loader").html("Fcop certificate upload is required").addClass('error');
            return false;
        }
        if($file !=""){
            var ftype = $("#ftype").val();
            validateFile(ftype);
            var filecheck = $("#filecheck").val();
            if(filecheck == "0"){return;}
        }
      }

      $part18 = $("#part18").val();
      if($part18 == ""){
        $("#loader").html("Part 18 is required").addClass('error');
        return false;
      }

      $part10 = $("#part10").val();
      if($part10 == ""){
        $("#loader").html("Part 10 is required").addClass('error');
        return false;
      }

      $fcopStatus = $("#fcopStatus").val();
      if($fcopStatus == ""){
        $("#loader").html("Fcop status is required").addClass('error');
        return false;
      }
      $fcopIssuedDate = $("#fcopIssuedDate").val();
      if($fcopIssuedDate == ""){
        $("#loader").html("Fcop issue date is required").addClass('error');
        return false;
      }
      $comments = $("#comments").val();
      if($comments == ""){
        $("#loader").html("Comments is required").addClass('error');
        return false;
      }
      $("#loader").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error')
  }

  $("#frmFcop").ajaxForm(function(data) {
      if(data == 'exists') {
          $("#loader").html("This record already exists").addClass('error');
          return false;
      }
      else {
          if(data.trim() == 'saved' || data.trim() == "updated") {
              $("#loader").html("Record Succesfully "+data).addClass('error');
              window.location = '/economic-licence/fcop';
          }
      }
  })

  //Delete an FCOP
  $(".deleteFcop").click(function(){
      $id = $(this).attr("value");
      $name = $(this).attr("title");
      $ask = confirm("Are you sure you want to "+$name.toLowerCase()+"?");
      if($ask){
          $("#deleteLoader")
          .html(
              '<img src=\'/images/ajax.gif\'>please wait...'
          )
          .addClass('error')
          .css({float:'right'});
          $.post("/ato/"+$id, $("#frmDeleteFcop").serialize(), function(data){
             if(data=='deleted'){
                 $url = '/ato';
                 window.location = $url;
             }
          })
      }
      return;
  });

  // Get an FCOP Status
  $("#sortFcopStatus").change(function() {
    const fcopStatus = $(this).val();
    $("#contentDropper").html('<img src=\'/images/ajax.gif\'>please wait...').addClass('error')
    $.get('/fcop-by-status', { fcopStatus: fcopStatus  }, function(data) {
      $("#contentDropper").html(data).removeClass('error')
    })
  })

  $("#downloadFcop").click(function(){
      var name = Math.random().toString().substring(7);
      $("#exportTableData").table2excel({
          filename:`fcop-${name}.xls`
      });
  });

});