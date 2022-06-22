function tambahItem(ireq){
    $.ajax({
       headers: {
           headers: $("meta[name=csrf-token]").attr('content')
       },
       type: "post",
       dataType: "json",
       data: {
        
       }
       ,
    });
}



