$(document).ready(function(){function a(a){$.ajax({method:"POST",url:"import-file",data:a,complete:function(a,b){},success:function(a,b,c){200==c.status?swal("File Imported","The was imported successfully!","success"):swal("Oops..","There was a problem, when try to import the file.","error")},error:function(a,b,c){}})}$(document).on("click",".import-file",function(b){b.preventDefault();var c={url:$(this).data("url"),id_file:$(this).data("id-file")};swal({title:"Import File to Data Sources",text:"Please type a label for selected file",type:"input",showCancelButton:!0,closeOnConfirm:!1,animation:"slide-from-top",inputPlaceholder:"Label"},function(b){return b===!1?!1:""===b?(swal.showInputError("Please type a label"),!1):void a(c)})})});