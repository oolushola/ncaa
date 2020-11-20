function validateaoccertfile(ftype){
	var pixcount = 0;
var image_file = document.getElementById("aoc_certificate").value
var image_length = document.getElementById("aoc_certificate").value.length;
var pos = image_file.lastIndexOf('.') + 1;
var ext = image_file.substring(pos, image_length);
var final_ext = ext.toLowerCase();
var myftype = ftype;
var myftypes = myftype.split(",");
for (i = 0; i < myftypes.length; i++)
{
    if(myftypes[i] == final_ext)
    {
		pixcount = pixcount + 1;
	document.getElementById("filecheck").value = "1";	
    return true;
    }
}
alert("Sorry, only pdf file are allowed for AOC certificate");
document.getElementById("filecheck").value = "0";	
return false;
    }
    


function validateOpsSpecs(ftype){
    var pixcount = 0;
    var image_file = document.getElementById("ops_specs").value
    var image_length = document.getElementById("ops_specs").value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var myftype = ftype;
    var myftypes = myftype.split(",");
    for (i = 0; i < myftypes.length; i++)
    {
        if(myftypes[i] == final_ext)
        {
            pixcount = pixcount + 1;
        document.getElementById("filecheck").value = "1";	
        return true;
        }
    }
    alert("Sorry, only pdf file allowed for OPS Specs");
    document.getElementById("filecheck").value = "0";	
    return false;
        }


function validatePartG(ftype){
    var pixcount = 0;
    var image_file = document.getElementById("part_g").value
    var image_length = document.getElementById("part_g").value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var myftype = ftype;
    var myftypes = myftype.split(",");
    for (i = 0; i < myftypes.length; i++)
    {
        if(myftypes[i] == final_ext)
        {
            pixcount = pixcount + 1;
        document.getElementById("filecheck").value = "1";	
        return true;
        }
    }
    alert("Sorry, only pdf file allowed allowed for Part G.");
    document.getElementById("filecheck").value = "0";	
    return false;
        }



function validateFile(ftype){
    var pixcount = 0;
    var image_file = document.getElementById("file").value
    var image_length = document.getElementById("file").value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var myftype = ftype;
    var myftypes = myftype.split(",");
    for (i = 0; i < myftypes.length; i++)
    {
        if(myftypes[i] == final_ext)
        {
            pixcount = pixcount + 1;
        document.getElementById("filecheck").value = "1";	
        return true;
        }
    }
    // alert("Sorry, the file type you are uploading is not allowed.");
    document.getElementById("filecheck").value = "0";	
    return false;
}

function validateAMOAprovl(ftype){
    var pixcount = 0;
    var image_file = document.getElementById("amo_pm_aprvl_pg_lep_file").value
    var image_length = document.getElementById("amo_pm_aprvl_pg_lep_file").value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var myftype = ftype;
    var myftypes = myftype.split(",");
    for (i = 0; i < myftypes.length; i++)
    {
        if(myftypes[i] == final_ext)
        {
            pixcount = pixcount + 1;
        document.getElementById("filecheck").value = "1";	
        return true;
        }
    }
    alert("Sorry, only pdf file upload are allowed");
    document.getElementById("filecheck").value = "0";	
    return false;
}