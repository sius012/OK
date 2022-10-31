
function selectJos(namaelemet){

    let hasClick = false;
    $(namaelemet).wrap('<div class="faker"></div>');
    $(document).on('click', function(){
       $(".fakeselect").hide();
    });

    $(namaelemet).closest(".faker").next().children("input").click(function(e){
    
      alert('hai');
    });

   
    
    $(namaelemet).attr("readonly","readonly");
    $(namaelemet).closest(".faker").click(function(e){
        e.stopPropagation();
        if(!hasClick){
            $(".fakeselect").remove();
       
 
        
       let isi;
       let fakeoption = "";
       fakeoption += "<li value=''><input class='form-control inputdrop'></li><li><ul class='scroled'>";
    
       $(this).children('select').children("option").each(function(e){
          if($(this).text() != undefined){
              fakeoption += "<li value="+$(this).val()+" class='fo'>"+$(this).text()+"</li>";
          } 
       });
      // $(this).replaceWith("<div>hai</div>");
       fakeoption += "</u><li>";
       $(this).after("<ul class='fakeselect'>"+fakeoption+"</ul>");
        }
      
        $(namaelemet).parent().next().children("li").children(".scroled").children("li").click(function(){
            $(this).closest(".fakeselect").prev().children("select").val($(this).attr('value'));
        });
        
    });

    $(document).on("keyup",".inputdrop",function(e){
        alert("dsasd");
        $(e.target).val($(e.target).val().toUpperCase());
        let value = $(e.target).val();
        $(e.target).parent().parent().children("li").children(".scroled").children(".fo").filter(function() {
           $(this).toggle($(this).text().toLowerCase().indexOf(value.toLowerCase()) > -1)
        });
   
    });

    $(document).on("click",".inputdrop",function(e){
       e.stopPropagation();
    });

  
  
    
}
