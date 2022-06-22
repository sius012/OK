$(document).ready(function(){
    $("#tombolcetak2").attr("disabled","disabled");
    $("#preordersubmitter").submit(function(e){
        e.preventDefault();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                ttd: $("#ttd").val(),
                telepon: $("#telepon").val(),
                us: $("#us").val().replace(/[._]/g,''),
                gm: $("#gm").val(),
                sejumlah: $("#sejumlah").val(),
            },
            type: 'post',
            dataType: "json",
            url: "/tambahpreorder2",
            success: function(data){
                $("#tombolcetak2").removeAttr('disabled'),
                $("#tombolcetak2").attr('id_pre', data['id']);
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
            }
        });
    });

    $(".tombolcetak2").click(function(e){
        
        let id = $(this).attr('id_pre');
        alert(id);
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id: id,
            },
            type: 'post',
            dataType: "json",
            url: "/cetakpreorder",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
            }
        });
    });

    function printpreorder(id_trans){
  
        $.ajax({
            headers: {
                "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
            },
            data: {
                id: id_trans,
            },
            type: 'post',
            dataType: "json",
            url: "/cetakpreorder",
            success: function(data){
                printJS({printable: data['filename'], type: 'pdf', base64: true});
            },
            error: function(err){
            }
        });
    }

    $(".btncetak").click(function(){
        alert($(this).attr("id_pre"));
      //  printpreorder($(this).attr("id_pre"));
    });
});