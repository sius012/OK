


$(document).ready(function(){
    $("#suratjalan").hide();
    $(".jt").hide();
    $(".kunci").hide();
    $("#printbutton").attr("disabled", "disabled");
    $(".td").hide();
    var jenisnota = "pintugarasi";
    $("#gm").val('Pintu Garasi');
    var pg = `
        <label for='ukuranpg readonly'>Ukuran : </label>
        <input required class="form-control readonly mb-3" id="ukuranpg">
        <label for='daunpintupg'>Daun Pintu : </label>
        <input required class="form-control readonly mb-3" id="daunpintupg">
        <label for='arahtikungpg'>Arah Tikung : </label>
        <input required class="form-control readonly mb-3" id="arahtikungpg">
        <label for='pilarpg'>Pilar : </label>
        <input required class="form-control readonly mb-3" id="pilarpg">
        <label for='warnatipepg'>Warna/Tipe : </label>
        <input required class="form-control readonly mb-3" id="warnatipepg">
        <label for='waktupg'>Waktu : </label>
        <textarea type="text-area" required class="form-control readonly" id="waktupg" value="">2 Bulan dari Penerimaan DP 50% dan persetujuan warna, tipe, ukuran lebar dan tinggi lapangan</textarea>
    `;

    var pgadp = `
    <label for='ukurankusenpgadp'>Ukuran : </label>
    <input required class="form-control readonly mb-3" id="ukurankusenpgadp">
    <label for='warnatipepgadp'>Warna/Tipe : </label>
    <input required class="form-control readonly mb-3" id="warnatipepgadp">
    <label for='waktupgadp'>Waktu : </label>
    <textarea type="text-area" required class="form-control readonly" id="waktupgadp" value="">2 Bulan dari Penerimaan DP 50% dan persetujuan warna, tipe, ukuran lebar dan tinggi lapangan</textarea>
`;

var ag = `
<label for='ukuranag'>Ukuran Diperuntukan : </label>
<input required class="form-control readonly mb-3" id="ukuranag">

`;
var upvc = `
<label for='itembarangupvc'>Item Barang : </label>
<input required class="form-control readonly mb-3" id="itembarangupvc">
<label for='warnatipeupvc'>Warna/Tipe : </label>
<input required class="form-control readonly mb-3" id="warnatipeupvc">
`;

var omge = `
<label for='ukuranomge'>Ukuran(Estimasi) : </label>
<input required class="form-control readonly mb-3" id="ukuranomge">

`;


$(document).on('click','.nnt',function(e){
    $("#searcher-nota").val($(e.target).text());
    searchnota($("#searcher-nota").val());
});
$("#notabesar").change(function(){
   jenisnota = $(this).val();
   if($(this).val() == "pintugarasi"){
    $(".opsigrup").html(pg);
    $("#gm").val("Pintu Garasi");
}else if($(this).val() == "pintugadandp"){
    $(".opsigrup").html(pgadp);
    $("#gm").val("Pintu GA & DP");
}else if($(this).val() == 'autog'){
    $(".opsigrup").html(ag);
    $("#gm").val("Auto Gate & Auto Garage");
}else if($(this).val() == 'upvc'){
    $(".opsigrup").html(upvc);
    $("#gm").val("UPVC");
}else{
    $(".opsigrup").html(omge);
    $("#gm").val("OMGE");
}

  
});
$(".opsigrup").html(pg);
$("#trigger").click(function(e){
   
})
 





    $(".td").hide();
    var jmlopsi = 1;
    console.log("{{'lol'}}");

    function callbacking(response){
        jmlopsi = response;
    
    }


    $("#searcher-nota").keyup(function(e){
        searchnota($("#searcher-nota").val());
    });

    $("ul").on("click", "li .cc", function(e){
        $("#notabesar").hide();
        tampilkannb($(e.target).attr('id_nb'));
    });




   


    
   
    $("#addopsi").click(function(){

        jmlopsi += 1
        if(jmlopsi > 4){
            Swal.fire({
                title : "lol"
            });
        }else{
        $(".opsigrup").append(`
        <div class="form-group">
            <input type="text" class="form-control form-control-sm title${jmlopsi}" id="exampleInputPassword1" >
            <input type="text" class="form-control isi${jmlopsi}" id="exampleInputPassword1" >
        </div>
        `);
        }
    });

   
    //ketika tombol submit/bayar tertekan
    $("#preorderform").submit(function(e){
      
        var judulpg = ["Ukuran", "Daun Pintu", "Arah Tikung", "Pilar", "Warna/Tipe", "Waktu"];
        var ospipg = [$("#ukuranpg").val(), $("#daunpintupg").val(), $("#arahtikungpg").val(), $("#pilarpg").val(), $("#warnatipepg").val(), $("#waktupg").val()];

        var judulpgad = ["Ukuran Kusen", "Warna/Tipe", "Waktu"];
        var ospipgagd = [$("#ukurankusenpgadp").val(), $("#warnatipepgadp").val(), $("#waktupgadp").val()];

        var judulag = ["Ukuran Diperuntukan"];
        var ospiag = [$("#ukuranag").val()];

        var judulupvc = ["Item Barang", "Warna/Tipe"];
        var ospiupvc = [$("#itembarangupvc").val(), $("#warnatipeupvc").val()];

        var judulomge = ["Ukuran(Estimasi)"];
        var ospiomge = [$("#ukuranomge").val()];

        var currentjudul = judulpg;
        var currentopsi = ospipg;


        if(jenisnota == "pintugarasi"){
            currentjudul = judulpg;
             currentopsi = ospipg;
        }else if(jenisnota == "pintugadandp"){
         currentjudul = judulpgad;
         currentopsi = ospipgagd;
        }else if(jenisnota == 'autog'){
         currentjudul = judulag;
         currentopsi = ospiag;
        }else if(jenisnota == 'upvc'){
         currentjudul = judulupvc;
         currentopsi = ospiupvc;
        }else{
         currentjudul = judulomge;
         currentopsi = ospiomge;
        }


        let url = $(this).attr("action");
        e.preventDefault();

                                                                                                                                                                           

        var judulopsi = [];
        var ketopsi = [];

        for(var j = 1; j <= jmlopsi; j++){
            judulopsi.push($(".title"+j).val());
            ketopsi.push($(".isi"+j).val());
        }

        var formData = {
            ttd: $("#ttd").val(),
            up: $("#up").val(),
            us: $("#us").val().replace(/[._]/g,''),
            brp: $("#brp").val(),
            gm: $("#gm").val(),
            total: $("#total").val().replace(/[._]/g,'')
            
        }

       // alert(parseInt($("#td2").val()) + parseInt($("#us").val().replace(/[._]/g,'')));

        if(parseInt($("#termin").val()) != 3 || parseInt($("#td2").val()) + parseInt($("#us").val().replace(/[._]/g,'')) >= parseInt($("#total2").val())){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {
                formData: formData,
                jenisnota: jenisnota,
                judulopsi: currentjudul,
                ketopsi: currentopsi,
                id_transaksi: $("#id_trans").val(),
                tanggal: $("#tgl").val(),
                kunci: $("#kunci").val(),
                jt: $("#jt").val()
            },
            type: "POST",
            url: url,
            dataType: "json",
            success: function(data){
                if($("#termin").val() == 2){
                
                    $("#suratjalan").show();
                }
              
                Swal.fire({
                    title: url == "/bayarpreorder" ? "Pembayaran selesai" : "Transaksi Berhasil Ditambahkan" 
                });
             //   $("#preorderform input").val("");
                $("#preorderform").attr("disabled", "disabled");
               
                $("#buttonsubmit").attr("disabled", "disabled");
                $("#buttonsubmit").text("Sudah dibayar");
                $("#buttonsubmit").removeClass("btn-primary");
                $("#buttonsubmit").addClass("btn-success");
                $("#id_trans").val(data["id_nb"]);
                $("#nn").html("No Nota: " + "<a class='nnt' href=#>"+data["no_nota"]+"</a>");
                $("#termin").val(data['termin']);
                $("#searcher-nota").val("");
                $("#printbutton").removeAttr('disabled');
                $("#suratjalan").removeAttr('disabled');
                $("#us").attr("disabled", "disabled");
               
               
               
            
            },
            error: function(err,response){
                Swal.fire("terjadi kesalahan");
                alert(err.responseText);
            }
        });
    }else{
        Swal.fire("Nominal kurang");
    }
       
    });


    $("#printbutton").click(function(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }, 
            data: {
                id_transaksi : $("#id_trans").val()
            },
            url: "/cetaknotabesar",
            type: "post",
            success: function(response){
                printJS({printable: response['filename'], type: 'pdf', base64: true, style: '@page { size: Letter landscape; }'});
            },error: function(err){
                Swal.fire('terjadi kesalahan','','info');
                alert(err.responseText);
            }
        });
    });

    $("#resetbutton").click(function(e){
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN' : $("meta[name='csrf-token'").attr('content')
            },
            url: '/resettrans',
            type: 'POST',
            success: function(){
              
                window.location = $("#resetbutton").attr("href");
            },
            error: function(err){
         
            },
        });
    });

    // $("#suratjalan").click(function(){
    //     $.ajax({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //              }, 
    //         data: {
    //             id_transaksi : $("#id_trans").val()
    //         },
    //         url: "/cetaksjnb",
    //         type: "post",
    //         success: function(response){
    //             printJS({printable: response['filename'], type: 'pdf', base64: true, style: '@page { size: Letter landscape; }'});
    //         },error: function(err){
    //             Swal.fire('terjadi kesalahan','','info');
    //             alert(err.responseText);
    //         }
    //     });
    // });

    $("#sjsubmit").submit(function(e){
        e.preventDefault();
        $.ajax({
           headers: {
               "X-CSRF-TOKEN" : $("meta[name=csrf-token]").attr('content')
           },
           url: "/kirimsj",
           type: "post",
           data: {
               id_trans : $("#id_trans").val(),
               kunci : $("#kunci").val(),
               jt : $("#jt").val(),
           },
           success: function(response){
             if(response['filename']==null){
                 Swal.fire("Surat Jalan Berhasil dibuat");
                 $("#tbsj").text("Cetak")
             }else{
                printJS({printable: response['filename'], type: 'pdf', base64: true, style: '@page { size: Letter landscape; }'});
             }
           },error: function(err){
                alert(err.responseText);
           }
        });
    });
   
    
});